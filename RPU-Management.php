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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="RPU-Management.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top"
        alt="">
      Electronic Real Property Tax System
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto"> <!-- Use ml-auto to align items to the right -->
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration-List.php">Tax Declaration</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="Track.php">Track Paper</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Transaction.php">Transaction</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Reports.php">Reports</a>
        </li>
        <li class="nav-item" style = "margin-left: 20px">
          <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
  <section class="u-clearfix u-section-1" id="sec-9208" style="margin: 50px;">
    <div class="u-clearfix u-sheet u-sheet-1">
      <h2 class="u-custom-font u-heading-font u-text u-text-default u-text-1" style="font-size: 1.5rem;">Real Property
        Unit Management</h2>
      <div class="row" style="margin-top: 100px">
        <div class="col-md-3 mb-4">
          <div class="card u-custom-color-4 h-100 text-center rounded-3">
            <a href="Real-Property-Unit-List.php" class="text-decoration-none text-dark">
              <img src="images/1504969.png" class="card-img-top" alt="Real Property Units List"
                style="max-height: 90px; object-fit: contain;">
              <div class="card-body">
                <h5 class="card-title" style="font-size: 1rem;">Real Property Units List</h5>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card u-custom-color-4 h-100 text-center rounded-3">
            <a href="FAAS.php" class="text-decoration-none text-dark">
              <img src="images/2991113.png" class="card-img-top" alt="Field Appraisal and Assessment Sheets"
                style="max-height: 90px; object-fit: contain;">
              <div class="card-body">
                <h5 class="card-title" style="font-size: 1rem;">Field Appraisal<br>and Assessment Sheets</h5>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card u-custom-color-4 h-100 text-center rounded-3">
            <a href="Tax-Declaration-List.php" class="text-decoration-none text-dark">
              <img src="images/1026130.png" class="card-img-top" alt="Tax Declaration List"
                style="max-height: 90px; object-fit: contain;">
              <div class="card-body">
                <h5 class="card-title" style="font-size: 1rem;">Tax Declaration List</h5>
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card u-custom-color-4 h-100 text-center rounded-3">
            <a href="Track.php" class="text-decoration-none text-dark">
              <img src="images/5977954.png" class="card-img-top" alt="Paper Progress"
                style="max-height: 90px; object-fit: contain;">
              <div class="card-body">
                <h5 class="card-title" style="font-size: 1rem;">Paper Progress</h5>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
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
</body>

</html>