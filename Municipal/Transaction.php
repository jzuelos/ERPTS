<?php
session_start();

// ✅ Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

// ✅ Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Electronic Real Property Tax System</title>

  <!-- ✅ Bootstrap 5 & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Transaction.css">
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Header -->
  <?php include 'header.php'; ?>

 <!-- Main Menu -->
<main class="container d-flex flex-column justify-content-center align-items-center my-5 flex-grow-1">
  <section class="w-100" style="max-width: 1100px;">
    <div class="status-container mb-5 text-center">
      <h3 class="text-secondary" style="font-size: 2rem;">Transaction</h3>
    </div>

    <div class="row justify-content-center">
      <!-- Permanent Cancellation -->
      <div class="col-md-3 mb-4">
        <a href="Real-Property-Unit-List.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center text-center">
              <i class="fas fa-ban me-3" style="font-size: 2rem; color: red;"></i>
              <h5 class="fw-bold mb-0" style="font-size: 1.25rem;">Permanent Cancellation</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- Track Transaction -->
      <div class="col-md-3 mb-4">
        <a href="Track.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center text-center">
              <i class="fas fa-route me-3" style="font-size: 2rem;"></i>
              <h5 class="fw-bold mb-0" style="font-size: 1.25rem;">Track Transaction</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- Approved Transactions -->
      <div class="col-md-3 mb-4">
        <a href="Approved.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center text-center">
              <i class="fas fa-check-circle me-3 text-success" style="font-size: 2rem;"></i>
              <h5 class="fw-bold mb-0" style="font-size: 1.25rem;">Approved</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- Pending Transactions -->
      <div class="col-md-3 mb-4">
        <a href="Pending.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center text-center">
              <i class="fas fa-clock me-3 text-warning" style="font-size: 2rem;"></i>
              <h5 class="fw-bold mb-0" style="font-size: 1.25rem;">Pending</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>
</main>

  <!-- Footer -->
  <footer class="bg-light text-center py-3 mt-auto">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
  </footer>

  <!-- ✅ Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
