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
    :root {
      --primary-color: #459577;
      --primary-dark: #357561;
      --primary-light: #5eb392;
      --success-color: #10b981;
      --bg-gradient: linear-gradient(135deg, #459577 0%, #357561 100%);
      --card-shadow: 0 8px 25px rgba(69, 149, 119, 0.15);
      --card-hover-shadow: 0 12px 35px rgba(69, 149, 119, 0.25);
    }

    body {
      background: linear-gradient(135deg, #f0f9f6 0%, #e6f4ef 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      flex-direction: column;
    }

    .hero-section {
      background: var(--bg-gradient);
      padding: 2.5rem 0;
      margin-bottom: 3rem;
      box-shadow: 0 8px 25px rgba(69, 149, 119, 0.2);
    }

    .hero-title {
      color: white;
      font-size: 2.25rem;
      font-weight: 700;
      margin: 0;
      text-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
    }

    main {
      flex-grow: 1;
    }

    .cards-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    .cards-wrapper {
      display: flex;
      flex-direction: column;
      gap: 2.5rem;
      align-items: center;
    }

    .cards-row {
      display: flex;
      gap: 2rem;
      justify-content: center;
      width: 100%;
    }

    .feature-card {
      background: white;
      border-radius: 16px;
      padding: 2rem 1.5rem;
      box-shadow: var(--card-shadow);
      transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      text-decoration: none;
      color: inherit;
      display: flex;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
      width: 260px;
      height: 200px;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--bg-gradient);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 0;
    }

    .feature-card:hover::before {
      opacity: 0.06;
    }

    .feature-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--card-hover-shadow);
      border-color: var(--primary-color);
    }

    .card-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative;
      z-index: 1;
      width: 100%;
    }

    .icon-wrapper {
      width: 70px;
      height: 70px;
      background: var(--bg-gradient);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.25rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(69, 149, 119, 0.25);
    }

    .feature-card:hover .icon-wrapper {
      transform: scale(1.08) rotate(3deg);
      box-shadow: 0 6px 20px rgba(69, 149, 119, 0.4);
    }

    .icon-wrapper i {
      font-size: 1.75rem;
      color: white;
    }

    .icon-wrapper.transfer-icons {
      background: var(--primary-color);
      box-shadow: 0 4px 15px rgba(69, 149, 119, 0.3);
    }

.feature-card:hover .icon-wrapper.transfer-icons {
  background: var(--primary-dark);
  border-color: var(--primary-light);
}

   .transfer-icons i {
  color: white;
  font-size: 1.2rem;
    }

    .card-title {
      font-size: 1.125rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0;
      transition: color 0.3s ease;
      line-height: 1.4;
    }

    .feature-card:hover .card-title {
      color: var(--primary-color);
    }

    footer {
      margin-top: 4rem;
      background: white;
      box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.05);
    }

    .footer-content {
      padding: 1.75rem;
      text-align: center;
      color: #64748b;
      font-size: 0.9rem;
    }

    @media (max-width: 1200px) {
      .cards-row {
        flex-wrap: wrap;
      }
    }

    @media (max-width: 768px) {
      .hero-title {
        font-size: 1.875rem;
      }
      
      .cards-container {
        padding: 0 1rem;
      }
      
      .cards-row {
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
      }

      .feature-card {
        width: 100%;
        max-width: 400px;
        height: 180px;
      }

      .icon-wrapper {
        width: 60px;
        height: 60px;
      }

      .icon-wrapper i {
        font-size: 1.5rem;
      }

      .card-title {
        font-size: 1rem;
      }
    }

    @media (max-width: 480px) {
      .hero-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>

  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Hero Section -->
  <div class="hero-section">
    <div class="container text-center">
      <h1 class="hero-title">Transaction</h1>
    </div>
  </div>

  <!-- Main Content -->
  <main class="container pb-5">
    <div class="cards-container">
      <div class="cards-wrapper">
        <!-- Single Row: 4 Cards -->
        <div class="cards-row">
          <a href="Real-Property-Unit-List.php" class="feature-card">
            <div class="card-content">
              <div class="icon-wrapper">
                <i class="fas fa-ban"></i>
              </div>
              <h5 class="card-title">Permanent Cancellation</h5>
            </div>
          </a>

          <a href="Track.php" class="feature-card">
            <div class="card-content">
              <div class="icon-wrapper">
                <i class="fas fa-route"></i>
              </div>
              <h5 class="card-title">Track Transaction</h5>
            </div>
          </a>

          <a href="Real-Property-Unit-List.php" class="feature-card">
            <div class="card-content">
              <div class="icon-wrapper transfer-icons">
                <div class="d-flex align-items-center gap-1">
                  <i class="fas fa-user"></i>
                  <i class="fas fa-right-left" style="font-size: 0.9rem;"></i>
                  <i class="fas fa-user"></i>
                </div>
              </div>
              <h5 class="card-title">Transfer of Ownership</h5>
            </div>
          </a>

          <a href="Add-New-Real-Property-Unit.php" class="feature-card">
            <div class="card-content">
              <div class="icon-wrapper">
                <i class="fas fa-file-signature"></i>
              </div>
              <h5 class="card-title">Declaration of New Property Unit</h5>
            </div>
          </a>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-light text-center py-3 mt-auto">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
  </footer>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
