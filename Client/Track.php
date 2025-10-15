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
        // Found in transactions → redirect to TrackResult.php
        header("Location: TrackResult.php?id=" . urlencode($id) . "&source=transactions");
        exit;
    } elseif ($resultReceivedPapers && $resultReceivedPapers->num_rows > 0) {
        //  Found in received_papers → redirect to TrackResult.php
        header("Location: TrackResult.php?id=" . urlencode($id) . "&source=received_papers");
        exit;
    } else {
        //  Not found → show error on same page
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
                <h3 class="text-muted font-weight-light">ERPTS TRANSACTION TRACKING SYSTEM</h3>
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

        <!-- REQUIREMENTS SECTION -->
<div class="info-section rounded shadow-sm p-4 mb-4">
  <h4 class="text-primary-custom mb-4 text-center font-weight-bold">
    <i class="fas fa-list-check me-2"></i>PROVINCIAL ASSESSOR'S OFFICE REQUIREMENTS
    <br>
    <small style="font-size: 1rem;"> MANDATORY MINIMUM SUPPORTING DOCUMENTS FOR COMMON TRANSACTIONS</small>
  </h4>

  <div class="row g-3">
    <!-- Simple Transfer -->
    <div class="col-md-6 col-lg-3">
      <div class="req-card d-flex flex-column justify-content-between h-100 p-4 rounded bg-light-subtle shadow-sm"
           role="button" data-bs-toggle="modal" data-bs-target="#simpleTransferModal">
        <div>
          <h6 class="fw-semibold mb-1 text-dark">Simple Transfer of Ownership</h6>
          <small class="text-muted">Ownership transfer requirements</small>
        </div>
        <div class="d-flex justify-content-end">
          <i class="fas fa-chevron-right text-secondary fs-5"></i>
        </div>
      </div>
    </div>

        <!-- New Declaration -->
        <div class="col-md-6 col-lg-3">
        <div class="req-card d-flex flex-column justify-content-between h-100 p-4 rounded bg-light-subtle shadow-sm"
            role="button" data-bs-toggle="modal" data-bs-target="#newDeclarationModal">
            <div>
            <h6 class="fw-semibold mb-1 text-dark">New Declaration</h6>
            <small class="text-muted">Declare new property</small>
            </div>
            <div class="d-flex justify-content-end">
            <i class="fas fa-chevron-right text-secondary fs-5"></i>
            </div>
        </div>
        </div>

        <!-- Revision / Correction -->
        <div class="col-md-6 col-lg-3">
        <div class="req-card d-flex flex-column justify-content-between h-100 p-4 rounded bg-light-subtle shadow-sm"
            role="button" data-bs-toggle="modal" data-bs-target="#revisionModal">
            <div>
            <h6 class="fw-semibold mb-1 text-dark">Revision / Correction</h6>
            <small class="text-muted">Update or fix entries</small>
            </div>
            <div class="d-flex justify-content-end">
            <i class="fas fa-chevron-right text-secondary fs-5"></i>
            </div>
        </div>
        </div>

        <!-- Consolidation -->
        <div class="col-md-6 col-lg-3">
        <div class="req-card d-flex flex-column justify-content-between h-100 p-4 rounded bg-light-subtle shadow-sm"
            role="button" data-bs-toggle="modal" data-bs-target="#consolidationModal">
            <div>
            <h6 class="fw-semibold mb-1 text-dark">Consolidation</h6>
            <small class="text-muted">Combine multiple lots</small>
            </div>
            <div class="d-flex justify-content-end">
            <i class="fas fa-chevron-right text-secondary fs-5"></i>
            </div>
        </div>
        </div>
    </div>
    </div>       
        </div>
    </div>
</div>


<!-- ===================== MODALS ===================== -->

<!-- 1. Simple Transfer -->
<div class="modal fade" id="simpleTransferModal" tabindex="-1" aria-labelledby="simpleTransferLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="simpleTransferLabel">Simple Transfer of Ownership</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
<pre class="mb-0">
1. DEED OF CONVEYANCES (duly notarized/registered from Registry of Deeds)
   a. Sale
   b. Donations
   c. Extrajudicial Settlement etc..
2. CERTIFICATION OF TAX PAYMENT - Municipal Treasurers Office (MTO)
3. CERTIFICATION FROM BUREAU OF INTERNAL REVENUE (BIR eCAR)
4. CERTIFICATE OF TRANSFER TAX – Provincial Treasurers Office (PTO)
5. TITLE – Authenticated/Certified true copy/Electronic true copy
6. DAR CLEARANCE (if Agricultural Land)
7. AFFIDAVIT OF PUBLICATION (if Extrajudicial settlement)
</pre>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- 2. New Declaration -->
<div class="modal fade" id="newDeclarationModal" tabindex="-1" aria-labelledby="newDeclarationLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="newDeclarationLabel">New Declaration of Real Property</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
<pre class="mb-0">
1. LAND
   a. LETTER REQUEST BY OWNER
   b. AFFIDAVIT OF OWNERSHIP/POSSESSION
   c. CERTIFICATION FROM BARANGAY CAPTAIN
   d. CERTIFICATION FROM DENR/PENRO (alienable & disposable)
   e. LIST OF CLAIMANTS (DENR/PENRO)
   f. APPROVED SURVEY PLAN and/or CADASTRAL MAP
   g. INSPECTION REPORT
2. BUILDING
   a. LETTER REQUEST BY OWNER
   b. BUILDING PERMIT
   c. BUILDING FLOOR PLAN
   d. SWORN STATEMENT FOR TRUE FAIR MARKET VALUE
   e. PICTURES
   f. NOTICE OF ASSESSMENT
3. MACHINERIES
   a. LETTER REQUEST
   b. SWORN STATEMENT BY THE OWNER
   c. ACTUAL COST OF MACHINERY
   d. PICTURES
   e. NOTICE OF ASSESSMENT
</pre>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- 3. Revision / Correction -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="revisionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="revisionLabel">Revision / Correction</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
<pre class="mb-0">
1. LETTER REQUEST BY OWNER
2. CERTIFICATION FROM DENR/PENRO
3. TITLE (if any) - Authenticated/Certified true copy/Electronic true copy
4. CERTIFICATION OF TAX PAYMENT - Municipal Treasurers Office (MTO)
5. CADASTRAL MAP (DENR/PENRO) if any
</pre>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- 4. Consolidation -->
<div class="modal fade" id="consolidationModal" tabindex="-1" aria-labelledby="consolidationLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="consolidationLabel">Consolidation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
<pre class="mb-0">
1. LETTER REQUEST BY OWNER
2. TITLE (if any) - Authenticated/Certified true copy/Electronic true copy
3. CERTIFICATION OF TAX PAYMENT - Municipal Treasurers Office (MTO)
4. APPROVED SUBDIVISION PLAN
</pre>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Spacer before footer -->
<div style="height: 80px;"></div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Optional JavaScript -->
 <!-- Bootstrap 5.3 JS Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
