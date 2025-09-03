<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; // Include your database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Capture and sanitize form data
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // Check if username and password are not empty
  if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Username or password cannot be empty!";
  } else {
    // Query the database to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");

    if ($stmt) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // First check if account is active
        if ($user['status'] == 1) {
          // Active account → check password
          if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['first_name'] = $user['first_name'];  // ✅ Store first_name in session
            $_SESSION['logged_in'] = true;

            header("Location: Home.php");
            exit();
          } else {
            $_SESSION['error'] = "Incorrect password!";
          }
        } else {
          // Inactive account
          $_SESSION['error'] = "Your account is inactive. Please contact the administrator.";
        }
      } else {
        $_SESSION['error'] = "Username does not exist!";
      }
      $stmt->close();
    } else {
      $_SESSION['error'] = "Error preparing statement: " . $conn->error;
    }

  }
}
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="index.css"> <!-- Custom CSS -->
  <title>Electronic Real Property Tax System</title>
</head>

<body>
<!-- Main Content -->
<div class="container d-flex justify-content-center align-items-center vh-100">
  <!-- Reset Password Card -->
  <div class="card-container d-flex flex-row">
    <div class="card login-card">
      <div class="logo-container">
        <img src="images/coconut_.__1_-removebg-preview1.png" alt="ERPTS Logo" class="logo">
      </div>
      <h2 class="text-center">Reset Password</h2>

      <!-- Step 1: Enter Email -->
      <form id="emailForm" onsubmit="return false;">
        <div class="form-group">
          <label for="email">Enter your Email</label>
          <input type="email" id="email" name="email" class="form-control rounded-pill">
        </div>
        <button type="button" class="btn btn-dark w-100 mt-4" id="sendCodeBtn">Send Code</button>
      </form>

      <!-- Step 2: Enter Code -->
      <form id="codeForm" style="display: none;" onsubmit="return false;">
        <div class="form-group">
          <label for="code">Enter Verification Code</label>
          <input type="text" id="code" name="code" class="form-control rounded-pill" required>
        </div>
        <button type="button" class="btn btn-dark w-100 mt-4" id="enterCodeBtn">Enter Code</button>
      </form>

      <!-- Step 3: New Password -->
      <form id="passwordForm" style="display: none;" onsubmit="return false;">
        <div class="form-group">
          <label for="newPassword">New Password</label>
          <div class="position-relative">
            <input type="password" id="newPassword" name="newPassword" class="form-control rounded-pill" required>
            <button type="button" class="btn btn-link togglePassword" data-target="newPassword"
              style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #000; cursor: pointer;">
              <i class="fa fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <div class="position-relative">
            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control rounded-pill" required>
            <button type="button" class="btn btn-link togglePassword" data-target="confirmPassword"
              style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #000; cursor: pointer;">
              <i class="fa fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="button" class="btn btn-dark w-100 mt-4" id="setPasswordBtn">Set Password</button>
      </form>

      <!-- Back to Login -->
      <div class="text-center mt-3">
        <a href="index.php">Back to Login</a>
      </div>
    </div>



      <!-- Welcome Box -->
      <div class="welcome-box">
        <h4 class="text-center mt-4">Welcome to ERPTS</h4>
        <p>From the Assessor’s Module you can:</p>
        <ul>
          <li>Search for information in Owner’s Declaration (OD), Assessor’s Field Sheet/FAAS, Tax Declaration (TD), or
            RPTOP.</li>
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

  <script>
   document.addEventListener("DOMContentLoaded", function () {
    const emailForm = document.getElementById("emailForm");
    const codeForm = document.getElementById("codeForm");
    const passwordForm = document.getElementById("passwordForm");

    document.getElementById("sendCodeBtn").addEventListener("click", function (e) {
      e.preventDefault();
      emailForm.style.display = "none";
      codeForm.style.display = "block";
    });

    document.getElementById("enterCodeBtn").addEventListener("click", function (e) {
      e.preventDefault();
      codeForm.style.display = "none";
      passwordForm.style.display = "block";
    });

    document.getElementById("setPasswordBtn").addEventListener("click", function (e) {
      e.preventDefault();
      // redirect after setting password
      window.location.href = "index.php";
    });

    // Toggle password visibility
    document.querySelectorAll(".togglePassword").forEach(button => {
      button.addEventListener("click", function () {
        const targetId = this.getAttribute("data-target");
        const input = document.getElementById(targetId);
        if (input.type === "password") {
          input.type = "text";
          this.querySelector("i").classList.remove("fa-eye");
          this.querySelector("i").classList.add("fa-eye-slash");
        } else {
          input.type = "password";
          this.querySelector("i").classList.remove("fa-eye-slash");
          this.querySelector("i").classList.add("fa-eye");
        }
      });
    });
  });
</script>

  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/js/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="http://localhost/ERPTS/index.js"></script>
</body>

</html>