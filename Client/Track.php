<?php
require_once "../database.php"; // adjust path

$conn = Database::getInstance();
$error = null;

// Handle search
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $conn->real_escape_string($_GET['id']);

    // Query for transaction_code in both tables
    $sqlTransactions = "SELECT * FROM transactions WHERE transaction_code = '$id' LIMIT 1";
    $resultTransactions = $conn->query($sqlTransactions);

    $sqlReceivedPapers = "SELECT * FROM received_papers WHERE transaction_code = '$id' LIMIT 1";
    $resultReceivedPapers = $conn->query($sqlReceivedPapers);

    if ($resultTransactions && $resultTransactions->num_rows > 0) {
        // ✅ Found in transactions → redirect to TrackResult.php
        header("Location: TrackResult.php?id=" . urlencode($id) . "&source=transactions");
        exit;
    } elseif ($resultReceivedPapers && $resultReceivedPapers->num_rows > 0) {
        // ✅ Found in received_papers → redirect to TrackResult.php
        header("Location: TrackResult.php?id=" . urlencode($id) . "&source=received_papers");
        exit;
    } else {
        // ❌ Not found → show error on same page
        $error = "No transaction found!";
    }
}
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
    <link rel="stylesheet" href="track.css">
    <title>Electronic Real Property Tax System</title>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 font-weight-bold text-primary-custom mb-3">Track and Trace</h1>
                <h3 class="text-muted font-weight-light">ERPT'S TRANSACTION TRACKING SYSTEM</h3>
                <div class="divider mx-auto bg-primary-custom" style="height: 3px; width: 80px; margin-top: 1.5rem;"></div>
            </div>
            
            <!-- Search Form -->
            <div class="card card-custom mb-5">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0 text-white"><i class="fas fa-search mr-2"></i>Search Transaction</h5>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Error Alert -->
                    <?php if ($error): ?>
                        <div id="errorAlert" class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="get">
                        <div class="form-group row align-items-center">
                            <label for="transactionId" class="col-md-3 col-form-label text-md-right text-primary-custom font-weight-bold">Transaction ID</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control form-control-custom" id="transactionId" name="id" placeholder="Enter your tracking number" required
                                    value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary-custom btn-block py-2">
                                    <i class="fas fa-paper-plane mr-2"></i>Track
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Information Section -->
            <div class="info-section rounded">
                <h4 class="text-primary-custom mb-4 font-weight-bold"><i class="fas fa-info-circle mr-2"></i>About Track and Trace</h4>
                <p class="lead">
                    Our system provides complete transparency for all your transactions.
                </p>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="mr-3 text-primary-custom">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold">Real-time Monitoring</h5>
                                <p>Track your transaction status with live updates</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="mr-3 text-primary-custom">
                                <i class="fas fa-bell fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold">Instant Notifications</h5>
                                <p>Get alerts for every status change</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="mr-3 text-primary-custom">
                                <i class="fas fa-shield-alt fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold">Secure Verification</h5>
                                <p>Authenticate your transaction records</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-light mt-4 border-left-primary-custom" style="border-left: 4px solid var(--primary);">
                    <p class="mb-0">
                        We are committed to providing a secure and reliable platform for all your transactions.
                        If you encounter any issues, please contact our <a href="#" class="font-weight-bold text-primary-custom">support team</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Spacer before footer -->
<div style="height: 80px;"></div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>

<script>
// Auto-hide error message after 4 seconds
document.addEventListener("DOMContentLoaded", function () {
    let errorAlert = document.getElementById("errorAlert");
    if (errorAlert) {
        setTimeout(() => {
            errorAlert.classList.add("fade");
            errorAlert.style.transition = "opacity 0.5s ease";
            errorAlert.style.opacity = "0";
            setTimeout(() => errorAlert.remove(), 500); // remove after fade
        }, 2000); // 2 seconds
    }
});
</script>

</body>
</html>
