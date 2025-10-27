<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ✅ Improved logActivity function that works even if user_id is NULL
function logActivity($conn, $user_id, $action)
{
  if ($user_id === 0 || $user_id === null) {
    // Logs for failed or unknown users
    $stmtLog = $conn->prepare("INSERT INTO activity_log (action, log_time) VALUES (?, NOW())");
    if ($stmtLog) {
      $stmtLog->bind_param("s", $action);
      $stmtLog->execute();
      $stmtLog->close();
    }
  } else {
    // Logs for valid users
    $stmtLog = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
    if ($stmtLog) {
      $stmtLog->bind_param("is", $user_id, $action);
      $stmtLog->execute();
      $stmtLog->close();
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Failed login attempt <br>• Username or Password cannot be empty";
    logActivity($conn, 0, "Failed login attempt \n• Error occurred (Empty Fields)");
  } else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");

    if ($stmt) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['status'] == 1) {
          if (password_verify($password, $user['password'])) {
            // ✅ Successful login
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['logged_in'] = true;

            logActivity($conn, $user['user_id'], "User logged in successfully");

            header("Location: Home.php");
            exit();
          } else {
            // ❌ Incorrect password
            $_SESSION['error'] = "Incorrect Password";
            logActivity($conn, $user['user_id'], "Failed login attempt \n• Incorrect Password");
          }
        } else {
          // ❌ Inactive account
          $_SESSION['error'] = "Account is Inactive. Please contact the administrator.";
          logActivity($conn, $user['user_id'], "Failed login attempt \n• Inactive Account");
        }
      } else {
        // ❌ Username not found
        $_SESSION['error'] = "Username does not exist";
        logActivity($conn, 0, "Failed login attempt \n• Username '{$username}' does not exist in Database");
      }
      $stmt->close();
    } else {
      $_SESSION['error'] = "System Error.";
      logActivity($conn, 0, "Failed login attempt \n• Error preparing statement: " . $conn->error);
    }
  }
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger text-center fade show" role="alert" id="errorAlert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <form method="POST">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control rounded-pill" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <div class="position-relative">
              <input type="password" id="password" name="password" class="form-control rounded-pill" required>
              <button type="button" id="togglePassword" class="btn btn-link"
                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #000000; cursor: pointer;">
                <i class="fa fa-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>
          <a href="ResetPassword.php">Forgot Password?</a>
          <button type="submit" class="btn btn-dark w-100 mt-4">Log In</button>
        </form>
      </div>

      <div class="welcome-box">
        <h4 class="text-center mt-4">Welcome to ERPTS</h4>
        <p>From the Assessor’s Module you can:</p>
        <ul>
          <li>Search for information in Owner’s Declaration (OD), Assessor’s Field Sheet/FAAS, Tax Declaration (TD), or RPTOP.</li>
          <li>Encode new real property information.</li>
        </ul>
        <p>To begin:</p>
        <ul>
          <li>Encode Property Information in the OD</li>
          <li>Select or encode Owner Information</li>
          <li>Encode Real Property Information in the AFS/FAAS</li>
          <li>Encode Tax-related Information in the TD</li>
          <li>Generate the RPTOP</li>
        </ul>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/js/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
  <script src="http://localhost/ERPTS/index.js"></script>

  <script>
    // ✅ Automatically hide error message after 4 seconds
    document.addEventListener('DOMContentLoaded', function () {
      const alertBox = document.getElementById('errorAlert');
      if (alertBox) {
        setTimeout(() => {
          alertBox.classList.remove('show');
          alertBox.classList.add('fade');
          setTimeout(() => alertBox.remove(), 500); // Remove element after fade animation
        }, 4000); // 4 seconds
      }
    });
  </script>
</body>

</html>
