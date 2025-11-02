<?php
  session_start();

  // Check if the user is logged in by verifying if 'user_id' exists in the session
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if user is not logged in
    exit; // Stop further execution after redirection
  }

  // Prevent the browser from caching this page
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // Instruct the browser not to store or cache the page
  header("Cache-Control: post-check=0, pre-check=0", false); // Additional caching rules to prevent the page from being reloaded from cache
  header("Pragma: no-cache"); // Older cache control header for HTTP/1.0 compatibility
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <style>
/* ==========================
   RPU Management Page Styles
   ========================== */

:root {
  --primary-color: #459577;
  --primary-dark: #357561;
  --primary-light: #5eb392;
  --success-color: #10b981;
  --bg-gradient: linear-gradient(135deg, #459577 0%, #357561 100%);
  --card-shadow: 0 8px 25px rgba(69, 149, 119, 0.15);
  --card-hover-shadow: 0 12px 35px rgba(69, 149, 119, 0.25);
}

/* Global Page Styling */
body {
  background: linear-gradient(135deg, #f0f9f6 0%, #e6f4ef 100%);
  min-height: 100vh;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  display: flex;
  flex-direction: column;
}

/* Hero Section */
.hero-section {
  background: var(--bg-gradient);
  padding: 2.5rem 0;
  margin-bottom: 3rem;
  box-shadow: 0 8px 25px rgba(69, 149, 119, 0.2);
}

/* Status Badge */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  padding: 0.625rem 1.25rem;
  border-radius: 50px;
  color: white;
  font-weight: 500;
  margin-bottom: 0.75rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
  font-size: 0.95rem;
}

.status-dot {
  width: 8px;
  height: 8px;
  background: var(--success-color);
  border-radius: 50%;
  animation: pulse 2s infinite;
  box-shadow: 0 0 10px rgba(16, 185, 129, 0.8);
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.6; transform: scale(0.95); }
}

/* Page Title */
.hero-title {
  color: var(--primary-dark);
  font-size: 2.25rem;
  font-weight: 700;
  margin: 0;
  text-align: center;
  text-shadow: none;
}

/* Card Container Layout */
main.container {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.cards-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  text-align: center;
}

.cards-wrapper {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
  align-items: center;
  justify-content: center;
}

.cards-row {
  display: flex;
  gap: 2rem;
  justify-content: center;
  width: 100%;
}

.cards-row.top-row {
  max-width: 100%;
}

.cards-row.bottom-row {
  max-width: 880px;
}

/* Feature Cards */
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

/* Card Content */
.card-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  position: relative;
  z-index: 1;
  width: 100%;
}

/* Icon Wrapper */
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

.feature-card,
.feature-card:hover,
.feature-card:focus {
  text-decoration: none !important;
}

/* Card Title */
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

/* Footer */
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

/* Responsive Breakpoints */
@media (max-width: 1200px) {
  .cards-row {
    flex-wrap: wrap;
  }

  .cards-row.bottom-row {
    max-width: 100%;
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
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.5rem;
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

  </style>
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

<!-- RPU Management Section -->
<main class="container pb-5">
  <div class="cards-container">
    <div class="text-center mb-5">
      <div class="status-badge">
      </div>
      <h1 class="hero-title">Real Property Unit Management</h1>
    </div>

    <div class="cards-wrapper">
      <div class="cards-row top-row">
        <a href="Real-Property-Unit-List.php" class="feature-card">
          <div class="card-content">
            <div class="icon-wrapper">
              <i class="fas fa-building"></i>
            </div>
            <h5 class="card-title">Real Property Units List</h5>
          </div>
        </a>

        <a href="Field-Appraisal.php" class="feature-card">
          <div class="card-content">
            <div class="icon-wrapper">
              <i class="fas fa-file-signature"></i>
            </div>
            <h5 class="card-title">Field Appraisal<br>and Assessment Sheets</h5>
          </div>
        </a>

        <a href="Tax-Declaration-List.php" class="feature-card">
          <div class="card-content">
            <div class="icon-wrapper">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h5 class="card-title">Tax Declaration List</h5>
          </div>
        </a>

        <a href="Track.php" class="feature-card">
          <div class="card-content">
            <div class="icon-wrapper">
              <i class="fas fa-route"></i>
            </div>
            <h5 class="card-title">Paper Progress</h5>
          </div>
        </a>
      </div>
    </div>
  </div>
</main>




  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
  </footer>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
</body>

</html>