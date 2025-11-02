<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';
$conn = Database::getInstance();

// ========================================
// SECURITY SETTINGS
// ========================================
define('MAX_ATTEMPTS', 3);        // Lock after 3 failed attempts
define('LOCKOUT_MINUTES', 3);     // Lock for 3 minutes
define('PERMANENT_ATTEMPTS', 6);  // Permanent lock after 6 attempts

// ========================================
// HELPER FUNCTIONS
// ========================================
function logActivity($conn, $user_id, $action) {
    $action = mysqli_real_escape_string($conn, $action);
    if ($user_id === 0 || $user_id === null) {
        $sql = "INSERT INTO activity_log (user_id, action, log_time) VALUES (NULL, '$action', NOW())";
    } else {
        $sql = "INSERT INTO activity_log (user_id, action, log_time) VALUES ($user_id, '$action', NOW())";
    }
    $conn->query($sql);
}

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

// ========================================
// IP LOCKOUT FUNCTIONS
// ========================================
function getIPLockoutStatus($conn, $ip) {
    $stmt = $conn->prepare("SELECT * FROM ip_lockout WHERE ip_address = ? LIMIT 1");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $status = $result->fetch_assoc();
    $stmt->close();
    return $status;
}

function createOrUpdateIPRecord($conn, $ip, $attempts, $lockUntil = 0, $isPermanent = 0) {
    $stmt = $conn->prepare("INSERT INTO ip_lockout (ip_address, attempts, lock_until, is_permanent, last_attempt) 
                            VALUES (?, ?, ?, ?, NOW()) 
                            ON DUPLICATE KEY UPDATE 
                            attempts = VALUES(attempts), 
                            lock_until = VALUES(lock_until), 
                            is_permanent = VALUES(is_permanent),
                            last_attempt = NOW()");
    $stmt->bind_param("siii", $ip, $attempts, $lockUntil, $isPermanent);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function resetIPAttempts($conn, $ip) {
    $stmt = $conn->prepare("UPDATE ip_lockout SET attempts = 0, lock_until = 0, is_permanent = 0 WHERE ip_address = ?");
    $stmt->bind_param("s", $ip);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function unlockTemporaryIP($conn, $ip) {
    // Only clear lock_until, keep attempts accumulating
    $stmt = $conn->prepare("UPDATE ip_lockout SET lock_until = 0 WHERE ip_address = ?");
    $stmt->bind_param("s", $ip);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function incrementIPAttempts($conn, $ip, $currentAttempts) {
    $newAttempts = $currentAttempts + 1;
    $lockUntil = 0;
    $isPermanent = 0;

    // Check if should apply permanent lock
    if ($newAttempts >= PERMANENT_ATTEMPTS) {
        $isPermanent = 1;
        logActivity($conn, null, "Permanent lock activated for IP: {$ip}");
    }
    // Check if should apply temporary lock (but keep accumulating attempts)
    elseif ($newAttempts >= MAX_ATTEMPTS && ($newAttempts - MAX_ATTEMPTS) % MAX_ATTEMPTS == 0) {
        // Lock temporarily every 3 attempts: 3, 6 (but 6 is permanent)
        $lockUntil = time() + (LOCKOUT_MINUTES * 60);
        logActivity($conn, null, "Temporary lock activated for IP: {$ip}");
    }

    return createOrUpdateIPRecord($conn, $ip, $newAttempts, $lockUntil, $isPermanent);
}

// ========================================
// CHECK IP LOCKOUT STATUS
// ========================================
$clientIP = getClientIP();
$ipStatus = getIPLockoutStatus($conn, $clientIP);

$isLocked = false;
$lockMessage = '';
$remainingSeconds = 0;
$lockType = '';

if ($ipStatus) {
    // Check permanent lock
    if ($ipStatus['is_permanent'] == 1) {
        $isLocked = true;
        $lockMessage = "This IP address has been permanently blocked due to multiple failed login attempts. Contact administrator.";
        $lockType = 'permanent';
    }
    // Check temporary lock
    elseif (time() < $ipStatus['lock_until']) {
        $isLocked = true;
        $remainingSeconds = $ipStatus['lock_until'] - time();
        $remainingMinutes = ceil($remainingSeconds / 60);
        $lockMessage = "Too many failed attempts from this IP. Try again in {$remainingMinutes} minute(s).";
        $lockType = 'temporary';
    }
    // Lock expired - reset attempts
    elseif ($ipStatus['lock_until'] > 0 && time() >= $ipStatus['lock_until']) {
        unlockTemporaryIP($conn, $clientIP);
        // Keep attempts count but clear the lock
        $ipStatus['lock_until'] = 0;
    }
}

// ========================================
// HANDLE LOGIN
// ========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isLocked) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($username) || empty($password)) {
        $currentAttempts = $ipStatus ? $ipStatus['attempts'] : 0;
        $newAttempts = $currentAttempts + 1;
        incrementIPAttempts($conn, $clientIP, $currentAttempts);
        
        $totalRemaining = PERMANENT_ATTEMPTS - $newAttempts;
        
        // Show warning only on attempts 2, 5, etc. (one before lockout)
        if ($newAttempts >= (MAX_ATTEMPTS - 1) && ($newAttempts % MAX_ATTEMPTS == MAX_ATTEMPTS - 1)) {
            $_SESSION['error'] = "Username and Password are required. ({$totalRemaining} attempts remaining until IP block)";
        } else {
            $_SESSION['error'] = "Username and Password are required.";
        }
        
        // Only log on significant events
        if ($newAttempts % MAX_ATTEMPTS == 0 || $newAttempts >= PERMANENT_ATTEMPTS) {
            logActivity($conn, null, "Failed login from IP: {$clientIP} - Empty credentials");
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($user['status'] == 1) {
                if (password_verify($password, $user['password'])) {
                    // ✅ SUCCESS - Reset IP attempts
                    resetIPAttempts($conn, $clientIP);
                    
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['middle_name'] = $user['middle_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['logged_in'] = true;

                    logActivity($conn, $user['user_id'], "Logged in from IP: {$clientIP}");
                    header("Location: Home.php");
                    exit();
                } else {
                    // ❌ Wrong password
                    $currentAttempts = $ipStatus ? $ipStatus['attempts'] : 0;
                    $newAttempts = $currentAttempts + 1;
                    incrementIPAttempts($conn, $clientIP, $currentAttempts);
                    
                    // Calculate remaining attempts dynamically
                    $remaining = MAX_ATTEMPTS - ($newAttempts % MAX_ATTEMPTS == 0 ? MAX_ATTEMPTS : $newAttempts % MAX_ATTEMPTS);
                    if ($remaining == 0) $remaining = MAX_ATTEMPTS;
                    
                    $totalRemaining = PERMANENT_ATTEMPTS - $newAttempts;
                    
                    if ($newAttempts >= PERMANENT_ATTEMPTS) {
                        $_SESSION['error'] = "IP permanently blocked due to multiple failed attempts.";
                    } elseif ($newAttempts >= MAX_ATTEMPTS) {
                        $_SESSION['error'] = "Incorrect password. Account locked for " . LOCKOUT_MINUTES . " minutes. ({$totalRemaining} more failed attempts = permanent block)";
                    } elseif ($newAttempts >= (MAX_ATTEMPTS - 1)) {
                        // Show warning only on attempt 2, 5, etc. (one before lockout)
                        $_SESSION['error'] = "Incorrect password. {$remaining} attempt remaining before temporary lock. ({$totalRemaining} until permanent block)";
                    } else {
                        $_SESSION['error'] = "Incorrect password.";
                    }
                    
                    // Only log on significant events (every 3rd attempt or permanent lock)
                    if ($newAttempts % MAX_ATTEMPTS == 0 || $newAttempts >= PERMANENT_ATTEMPTS) {
                        logActivity($conn, $user['user_id'], "Failed login from IP: {$clientIP} - Wrong password");
                    }
                }
            } else {
                // ❌ Inactive account
                $currentAttempts = $ipStatus ? $ipStatus['attempts'] : 0;
                $newAttempts = $currentAttempts + 1;
                incrementIPAttempts($conn, $clientIP, $currentAttempts);
                
                $totalRemaining = PERMANENT_ATTEMPTS - $newAttempts;
                
                // Show warning only on attempts 2, 5, etc. (one before lockout)
                if ($newAttempts >= (MAX_ATTEMPTS - 1) && ($newAttempts % MAX_ATTEMPTS == MAX_ATTEMPTS - 1)) {
                    $_SESSION['error'] = "Account is inactive. Contact administrator. ({$totalRemaining} attempts remaining until IP block)";
                } else {
                    $_SESSION['error'] = "Account is inactive. Contact administrator.";
                }
                
                // Only log on significant events
                if ($newAttempts % MAX_ATTEMPTS == 0 || $newAttempts >= PERMANENT_ATTEMPTS) {
                    logActivity($conn, $user['user_id'], "Failed login from IP: {$clientIP} - Inactive account");
                }
            }
        } else {
            // ❌ Username not found
            $currentAttempts = $ipStatus ? $ipStatus['attempts'] : 0;
            $newAttempts = $currentAttempts + 1;
            incrementIPAttempts($conn, $clientIP, $currentAttempts);
            
            $totalRemaining = PERMANENT_ATTEMPTS - $newAttempts;
            
            // Show warning only on attempts 2, 5, etc. (one before lockout)
            if ($newAttempts >= (MAX_ATTEMPTS - 1) && ($newAttempts % MAX_ATTEMPTS == MAX_ATTEMPTS - 1)) {
                $_SESSION['error'] = "Username not found. ({$totalRemaining} attempts remaining until IP block)";
            } else {
                $_SESSION['error'] = "Username not found.";
            }
            
            // Only log on significant events
            if ($newAttempts % MAX_ATTEMPTS == 0 || $newAttempts >= PERMANENT_ATTEMPTS) {
                logActivity($conn, null, "Failed login from IP: {$clientIP} - Username not found");
            }
        }
        $stmt->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="index.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card-container d-flex flex-row">
      <div class="card login-card">
        <div class="logo-container">
          <img src="images/coconut_.__1_-removebg-preview1.png" alt="ERPTS Logo" class="logo">
        </div>
        <h2 class="text-center">LOG IN</h2>

        <?php if (isset($_SESSION['error']) || $isLocked): ?>
          <div class="alert alert-danger text-center" role="alert" id="errorAlert">
            <?php echo $isLocked ? $lockMessage : $_SESSION['error']; ?>
          </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control rounded-pill" 
                   <?php echo $isLocked ? 'disabled' : 'required'; ?>>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <div class="position-relative">
              <input type="password" id="password" name="password" class="form-control rounded-pill" 
                     <?php echo $isLocked ? 'disabled' : 'required'; ?>>
              <button type="button" id="togglePassword" class="btn btn-link"
                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #000000; cursor: pointer;">
                <i class="fa fa-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>
          <a href="ResetPassword.php">Forgot Password?</a>
          <button type="submit" class="btn btn-dark w-100 mt-4" id="loginBtn" 
                  <?php echo $isLocked ? 'disabled' : ''; ?>>
            Log In
          </button>
        </form>
      </div>

      <!-- Welcome Box -->
    <div class="welcome-box">
      <h4 class="text-center mt-4">Welcome to ERPTS</h4>
      <p>From the Assessor’s Module you can:</p>
      <ul>
        <li>Search for information in Owner’s Declaration (OD), Assessor’s Field Sheet/FAAS, Tax Declaration (TD), or RPTOP.</li>
        <li>Encode new real property information.</li>
        <li>Manage document handling such as uploading, viewing, and organizing property-related files.</li>
        <li>Track transactions and monitor updates or changes made within the system.</li>
        <li>Modify and manage data sheets for assessment and property details efficiently.</li>
      </ul>
      <p>To begin:</p>
      <ul>
        <li>Encode Property Information in the OD</li>
        <li>Select or Encode Owner Information</li>
        <li>Encode Real Property Information in the AFS/FAAS</li>
        <li>Encode Tax-related Information in the TD</li>
      </ul>
    </div>
    </div>
  </div>

  <?php if ($isLocked && $lockType === 'temporary'): ?>
    <script>
      const remainingSeconds = <?php echo $remainingSeconds; ?>;
    </script>
  <?php endif; ?>

  <?php if ($isLocked && $lockType === 'permanent'): ?>
    <script>
      const isPermanentLock = true;
    </script>
  <?php endif; ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
  <script src="index.js"></script>

  <?php unset($_SESSION['error']); ?>
</body>
</html>