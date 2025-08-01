<?php
  session_start();

  // Check if the user is logged in by verifying if 'user_id' exists in the session
  if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if user is not logged in
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
  <link rel="stylesheet" href="../main_layout.css">
  <link rel="stylesheet" href="../header.css">
  <link rel="stylesheet" href="RPU-Management.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include '../header.php'; ?>

<!-- RPU Management Section -->
<main class="container my-5 d-flex justify-content-center align-items-center flex-column">
  <section class="w-100" style="max-width: 1100px;">
    <div class="status-container mb-4 text-center">
      <h3 class="text-secondary" style="font-size: 2rem;">RPU Management</h3>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-3 mb-4">
        <a href="Real-Property-Unit-List.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
              <i class="fas fa-building mb-3" style="font-size: 2.5rem;"></i>
              <h5 class="font-weight-bold mb-0" style="font-size: 1.2rem;">Real Property Units List</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 mb-4">
        <a href="Real-Property-Unit-List.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
              <i class="fas fa-file-signature mb-3" style="font-size: 2.5rem;"></i>
              <h5 class="font-weight-bold mb-0" style="font-size: 1.2rem;">Field Appraisal<br>and Assessment Sheets</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 mb-4">
        <a href="Tax-Declaration-List.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
              <i class="fas fa-file-invoice-dollar mb-3" style="font-size: 2.5rem;"></i>
              <h5 class="font-weight-bold mb-0" style="font-size: 1.2rem;">Tax Declaration List</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 mb-4">
        <a href="Track.php" class="text-decoration-none">
          <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5 h-100">
            <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
              <i class="fas fa-route mb-3" style="font-size: 2.5rem;"></i>
              <h5 class="font-weight-bold mb-0" style="font-size: 1.2rem;">Paper Progress</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>
</main>



  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
  </footer>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
</body>

</html>