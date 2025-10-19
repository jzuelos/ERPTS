<?php
session_start();

// If already verified, go directly to Track page
if (isset($_SESSION['captcha_verified']) && $_SESSION['captcha_verified'] === true) {
    header("Location: Track.php");
    exit();
}

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $secretKey = "6Ld3Ye8rAAAAAPqsf3GYzP0cuodPvjEn0hu4Phhh"; // Replace with your actual secret key from Google reCAPTCHA
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $response = file_get_contents($url);
    $result = json_decode($response, true);

    if ($result["success"]) {
        $_SESSION['captcha_verified'] = true;
        header("Location: Track.php");
        exit();
    } else {
        $error = "Verification failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Human Verification</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">

<div class="card shadow-lg p-5 text-center" style="max-width: 450px;">
    <h3 class="text-primary mb-3">Verify You're Human</h3>
    <p class="text-muted mb-4">Please complete the CAPTCHA below to continue.</p>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="g-recaptcha d-flex justify-content-center" data-sitekey="6Ld3Ye8rAAAAAIp4F4LzDk1Vx7gEtr-Uz2bDlzpp"></div>
        <button type="submit" class="btn btn-primary mt-4 w-100">Continue to Tracking</button>
    </form>
</div>

</body>
</html>
