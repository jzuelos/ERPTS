<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';
$conn = Database::getInstance();

// ========================================
// SIMPLE SECURITY SETTINGS
// ========================================
define('MAX_ATTEMPTS', 3);        // Lock after 3 failed attempts
define('LOCKOUT_MINUTES', 3);     // Lock for 3 minutes
define('PERMANENT_ATTEMPTS', 6);  // Permanent lock after 6 attempts

// ========================================
// INITIALIZE SESSION VARIABLES
// ========================================
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lock_until'])) {
    $_SESSION['lock_until'] = 0;
}
if (!isset($_SESSION['is_permanent_lock'])) {
    $_SESSION['is_permanent_lock'] = false;
}

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
// CHECK LOCKOUT STATUS
// ========================================
$isLocked = false;
$lockMessage = '';
$remainingSeconds = 0;

// Check permanent lock
if ($_SESSION['is_permanent_lock']) {
    $isLocked = true;
    $lockMessage = "Account permanently locked. Contact administrator.";
    $lockType = 'permanent';
}
// Check temporary lock
elseif (time() < $_SESSION['lock_until']) {
    $isLocked = true;
    $remainingSeconds = $_SESSION['lock_until'] - time();
    $remainingMinutes = ceil($remainingSeconds / 60);
    $lockMessage = "Too many failed attempts. Try again in {$remainingMinutes} minute(s).";
    $lockType = 'temporary';
}
// Lock expired - reset attempts
elseif ($_SESSION['lock_until'] > 0 && time() >= $_SESSION['lock_until']) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_until'] = 0;
}

// ========================================
// HANDLE LOGIN
// ========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isLocked) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $clientIP = getClientIP();

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and Password are required";
        $_SESSION['login_attempts']++;
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($user['status'] == 1) {
                if (password_verify($password, $user['password'])) {
                    // ✅ SUCCESS - Reset everything
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['lock_until'] = 0;
                    $_SESSION['is_permanent_lock'] = false;
                    
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
                    $_SESSION['login_attempts']++;
                    $remaining = MAX_ATTEMPTS - $_SESSION['login_attempts'];
                    $_SESSION['error'] = "Incorrect password. {$remaining} attempt(s) remaining.";
                    logActivity($conn, $user['user_id'], "Failed login from IP: {$clientIP} - Wrong password");
                }
            } else {
                // ❌ Inactive account
                $_SESSION['login_attempts']++;
                $_SESSION['error'] = "Account is inactive. Contact administrator.";
                logActivity($conn, $user['user_id'], "Failed login from IP: {$clientIP} - Inactive account");
            }
        } else {
            // ❌ Username not found
            $_SESSION['login_attempts']++;
            $remaining = MAX_ATTEMPTS - $_SESSION['login_attempts'];
            $_SESSION['error'] = "Username not found. {$remaining} attempt(s) remaining.";
            logActivity($conn, 0, "Failed login from IP: {$clientIP} - Username '{$username}' not found");
        }
        $stmt->close();
    }

    // ========================================
    // APPLY LOCKOUT IF NEEDED
    // ========================================
    if ($_SESSION['login_attempts'] >= PERMANENT_ATTEMPTS) {
        // Permanent lock
        $_SESSION['is_permanent_lock'] = true;
        $_SESSION['error'] = "Account permanently locked. Contact administrator.";
        logActivity($conn, 0, "Permanent lock activated from IP: {$clientIP}");
    } elseif ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
        // Temporary lock
        $_SESSION['lock_until'] = time() + (LOCKOUT_MINUTES * 60);
        $_SESSION['error'] = "Too many failed attempts. Locked for " . LOCKOUT_MINUTES . " minutes.";
        logActivity($conn, 0, "Temporary lock activated from IP: {$clientIP}");
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

      <div class="welcome-box">
        <h4 class="text-center mt-4">Welcome to ERPTS</h4>
        <p>From the Assessor's Module you can:</p>
        <ul>
          <li>Search for information in Owner's Declaration (OD), Assessor's Field Sheet/FAAS, Tax Declaration (TD), or RPTOP.</li>
          <li>Encode new real property information.</li>
        </ul>
      </div>
    </div>
  </div>

  <?php if ($isLocked && $lockType === 'temporary'): ?>
    <script>
      const lockUntil = <?php echo $_SESSION['lock_until']; ?>;
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