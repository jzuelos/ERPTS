<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Clear session if user clicks Back to Login
if (isset($_GET['clear'])) {
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }
  session_destroy();

  header("Location: index.php");
  exit();
}

require_once 'database.php';

// ✅ Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to send verification code
function sendVerificationCode($userEmail, $code) {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'zereljm09@gmail.com'; // Gmail
    $mail->Password = 'uext teee qekk pwsg';   // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('zereljm09@gmail.com', 'ERPTS');
    $mail->addAddress($userEmail);

    $mail->isHTML(true);
    $mail->Subject = 'Your Password Reset Code';
    $mail->Body = "
      <div style='font-family: Arial, sans-serif; font-size:14px; color:#333;'>
        <p>Dear {$userEmail},</p>

        <p>We received a request to reset the password for your <strong>Electronic Real Property Tax System (ERPTS)</strong> account.</p>

        <p>Your verification code is:</p>

        <h2 style='text-align:center; background:#f4f4f4; padding:10px; border-radius:5px; display:inline-block;'>
          {$code}
        </h2>

        <p>This code will expire in <strong>3 minutes</strong>. If you did not request a password reset, please ignore this email.  
        For your security, do not share this code with anyone.</p>

        <br>
        <p>Best regards,<br>
        <strong>ERPTS Support Team</strong></p>
      </div>
    ";

    $mail->send();
  } catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
  }
}

// ========== STEP 1: EMAIL SUBMISSION ==========
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  if (!empty($email)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = 1 LIMIT 1");
    if ($stmt) {
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['reset_email'] = $user['email'];

        // ✅ Generate verification code
        $code = random_int(100000, 999999);
        $_SESSION['verification_code'] = $code;
        $_SESSION['verification_expires'] = time() + (3 * 60); // 3 minutes

        sendVerificationCode($user['email'], $code);

        $_SESSION['step'] = 2;
      } else {
        $_SESSION['invalid_email'] = true;
      }
      $stmt->close();
    }
  }
}

// ========== RESEND CODE ==========
if (isset($_POST['resend'])) {
  if (!empty($_SESSION['reset_email'])) {
    $code = random_int(100000, 999999);
    $_SESSION['verification_code'] = $code;
    $_SESSION['verification_expires'] = time() + (3 * 60); // reset timer

    sendVerificationCode($_SESSION['reset_email'], $code);
    $_SESSION['step'] = 2;
    $_SESSION['resent'] = true;
  }
}

// ========== STEP 2: CODE VERIFICATION ==========
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['code'])) {
  $enteredCode = trim($_POST['code']);
  if (!empty($_SESSION['verification_code'])) {
    if (time() >= $_SESSION['verification_expires']) {
      $_SESSION['code_error'] = "expired";
      $_SESSION['step'] = 2;
    } elseif ($enteredCode == $_SESSION['verification_code']) {
      $_SESSION['step'] = 3; // unlock password form
    } else {
      $_SESSION['code_error'] = "invalid";
      $_SESSION['step'] = 2;
    }
  }
}

// ========== STEP 3: PASSWORD RESET ==========
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newPassword'], $_POST['confirmPassword'])) {
  $newPassword = $_POST['newPassword'];
  $confirmPassword = $_POST['confirmPassword'];

  if ($newPassword === $confirmPassword && !empty($_SESSION['reset_email'])) {
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    if ($stmt) {
      $stmt->bind_param("ss", $hashedPassword, $_SESSION['reset_email']);
      if ($stmt->execute()) {
        // ✅ Success: clear session and redirect
        session_unset();
        session_destroy();
        header("Location: index.php?reset=success");
        exit();
      }
      $stmt->close();
    }
  } else {
    $_SESSION['password_error'] = true;
    $_SESSION['step'] = 3;
  }
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
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
        <h2 class="text-center">Reset Password</h2>

        <!-- Step Indicator -->
        <div class="d-flex justify-content-center mb-4">
          <div class="d-flex flex-column align-items-center mx-3" style="width:100px;">
            <span class="badge badge-pill <?php echo (empty($_SESSION['step']) || $_SESSION['step'] == 1) ? 'badge-dark' : 'badge-secondary'; ?>">1</span>
            <small class="text-center">Email</small>
          </div>
          <div class="d-flex flex-column align-items-center mx-3" style="width:100px;">
            <span class="badge badge-pill <?php echo (!empty($_SESSION['step']) && $_SESSION['step'] == 2) ? 'badge-dark' : 'badge-secondary'; ?>">2</span>
            <small class="text-center">Verify Code</small>
          </div>
          <div class="d-flex flex-column align-items-center mx-3" style="width:100px;">
            <span class="badge badge-pill <?php echo (!empty($_SESSION['step']) && $_SESSION['step'] == 3) ? 'badge-dark' : 'badge-secondary'; ?>">3</span>
            <small class="text-center">New Password</small>
          </div>
        </div>

        <!-- Step 1: Enter Email -->
        <form id="emailForm" method="POST" action="" style="<?php echo (empty($_SESSION['step']) || $_SESSION['step'] == 1) ? '' : 'display:none;'; ?>">
          <div class="form-group">
            <label for="email">Enter your Email</label>
            <input type="email" id="email" name="email"
              class="form-control rounded-pill <?php echo !empty($_SESSION['invalid_email']) ? 'is-invalid' : ''; ?>"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <div class="invalid-feedback">Please enter a valid active email.</div>
          </div>
          <button type="submit" class="btn btn-dark w-100 mt-3">Send Code</button>
        </form>

        <!-- Step 2: Enter Code -->
        <form id="codeForm" method="POST" action="" style="<?php echo (!empty($_SESSION['step']) && $_SESSION['step'] == 2) ? '' : 'display:none;'; ?>">
          <div class="form-group">
            <label for="code">Enter Verification Code</label>
            <input type="text" id="code" name="code"
              class="form-control rounded-pill <?php echo !empty($_SESSION['code_error']) ? 'is-invalid' : ''; ?>" required>
            <div class="invalid-feedback">
              <?php 
                if (!empty($_SESSION['code_error']) && $_SESSION['code_error'] === "expired") {
                  echo "Verification code has expired. Please request a new one.";
                } else {
                  echo "Invalid verification code.";
                }
              ?>
            </div>
          </div>
          <button type="submit" class="btn btn-dark w-100 mt-4">Enter Code</button>
        </form>

        <!-- Countdown + Resend -->
        <?php if (!empty($_SESSION['step']) && $_SESSION['step'] == 2): ?>
          <div class="text-center mt-3">
            <p id="countdown" class="text-danger font-weight-bold"></p>
          </div>
          <form method="POST" action="" class="text-center mt-2">
            <input type="hidden" name="resend" value="1">
            <button type="submit" class="btn btn-link mb-3">Resend Code</button>
          </form>
          <?php if (!empty($_SESSION['resent'])): ?>
            <p class="text-success text-center">A new code has been sent to your email.</p>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Step 3: New Password -->
        <form id="passwordForm" method="POST" action="" style="<?php echo (!empty($_SESSION['step']) && $_SESSION['step'] == 3) ? '' : 'display:none;'; ?>">
          <div class="form-group">
            <label for="newPassword">New Password</label>
            <div class="input-group">
              <input type="password" id="newPassword" name="newPassword" class="form-control rounded-pill" required>
              <div class="input-group-append">
                <span class="input-group-text bg-white border-0">
                  <i class="fas fa-eye togglePassword" data-target="newPassword" style="cursor:pointer;"></i>
                </span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <div class="input-group">
              <input type="password" id="confirmPassword" name="confirmPassword" class="form-control rounded-pill" required>
              <div class="input-group-append">
                <span class="input-group-text bg-white border-0">
                  <i class="fas fa-eye togglePassword" data-target="confirmPassword" style="cursor:pointer;"></i>
                </span>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-dark w-100 mt-4">Set Password</button>
        </form>

        <div class="text-center mt-2">
          <a href="ResetPassword.php?clear=1">Cancel</a>
        </div>
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

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Toggle password visibility
      document.querySelectorAll(".togglePassword").forEach(icon => {
        icon.addEventListener("click", function () {
          const targetId = this.getAttribute("data-target");
          const input = document.getElementById(targetId);
          if (input.type === "password") {
            input.type = "text";
            this.classList.replace("fa-eye", "fa-eye-slash");
          } else {
            input.type = "password";
            this.classList.replace("fa-eye-slash", "fa-eye");
          }
        });
      });

      // Countdown timer
      <?php if (!empty($_SESSION['verification_expires']) && !empty($_SESSION['step']) && $_SESSION['step'] == 2): ?>
        var expirationTime = <?php echo $_SESSION['verification_expires'] - time(); ?>; // seconds left
        var countdownElem = document.getElementById("countdown");

        function updateCountdown() {
          if (expirationTime <= 0) {
            countdownElem.textContent = "⏳ Code expired! Please request a new one.";
            return;
          }
          var minutes = Math.floor(expirationTime / 60);
          var seconds = expirationTime % 60;
          countdownElem.textContent = "Code expires in " + minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);
          expirationTime--;
          setTimeout(updateCountdown, 1000);
        }
        updateCountdown();
      <?php endif; ?>
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>
<?php
// cleanup temporary flags
unset($_SESSION['invalid_email']);
unset($_SESSION['code_error']);
unset($_SESSION['password_error']);
unset($_SESSION['resent']);
?>
