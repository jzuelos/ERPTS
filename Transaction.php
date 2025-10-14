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
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Transaction.css">

  <style>
    /* === Enhanced Transaction Card Styles === */
    .feature-card {
      border: none;
      border-radius: 20px;
      background: linear-gradient(145deg, #ffffff, #f2f2f2);
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      background: linear-gradient(145deg, #f8f9fa, #ffffff);
    }

    .feature-card .card-body i {
      transition: transform 0.3s ease, color 0.3s ease;
    }

    .feature-card:hover i {
      transform: scale(1.2);
    }

    h5.fw-bold {
      color: #333;
      font-weight: 600;
    }

    .status-container h3 {
      font-weight: 700;
      letter-spacing: 1px;
      color: #444;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .feature-card {
        padding: 2rem 1rem !important;
      }
      .feature-card i {
        font-size: 1.8rem !important;
      }
      .feature-card h5 {
        font-size: 1rem !important;
      }
    }
  </style>
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

      <div class="row justify-content-center g-4">

        <!-- Permanent Cancellation -->
        <div class="col-md-3 col-sm-6">
          <a href="Real-Property-Unit-List.php" class="text-decoration-none">
            <div class="feature-card p-4 h-100 text-center">
              <div class="card-body">
                <i class="fas fa-ban text-danger mb-3" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mb-0">Permanent Cancellation</h5>
              </div>
            </div>
          </a>
        </div>

        <!-- Track Transaction -->
        <div class="col-md-3 col-sm-6">
          <a href="Track.php" class="text-decoration-none">
            <div class="feature-card p-4 h-100 text-center">
              <div class="card-body">
                <i class="fas fa-route text-info mb-3" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mb-0">Track Transaction</h5>
              </div>
            </div>
          </a>
        </div>

        <!-- Transfer of Ownership -->
        <div class="col-md-3 col-sm-6">
          <a href="Real-Property-Unit-List.php" class="text-decoration-none">
            <div class="feature-card p-4 h-100 text-center">
              <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3">
                  <i class="fas fa-user text-success me-1" style="font-size: 1.5rem;"></i>
                  <i class="fas fa-right-left text-success mx-1" style="font-size: 1rem;"></i>
                  <i class="fas fa-user text-success ms-1" style="font-size: 1.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-0">Transfer of Ownership</h5>
              </div>
            </div>
          </a>
        </div>

        <!-- Declaration of New Property Unit -->
        <div class="col-md-3 col-sm-6">
          <a href="Add-New-Real-Property-Unit.php" class="text-decoration-none">
            <div class="feature-card p-4 h-100 text-center">
              <div class="card-body">
                <i class="fas fa-file-signature text-primary mb-3" style="font-size: 2rem;"></i>
                <h5 class="fw-bold mb-0">Declaration of New Property Unit</h5>
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
