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
  <link rel="stylesheet" href="Admin-Page-2.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body class="d-flex flex-column min-vh-100">
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
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home</a>
        </li>
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item active" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration.php">Tax Declaration</a>
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
        <li class="nav-item ml-3">
          <button type="button" class="btn btn-danger">Log Out</button>
        </li>
      </ul>
    </div>
  </nav>

 <!-- Main Content -->
 <main class="container my-5 d-flex justify-content-center align-items-center flex-column">
  <section class="w-100" style="max-width: 1100px;">
    <div class="status-container mb-4 text-center">
      <h5 class="text-muted" style="font-size: 1.25rem;">Server Status: <span class="text-success font-weight-bold">Online</span></h5>
      <h3 class="text-secondary" style="font-size: 2rem;">Admin</h3>
    </div>

    <div class="control-panel card border-0 shadow rounded-lg mb-5 p-5">
      <div class="card-body">
        <h4 class="card-title mb-4 text-center" style="font-size: 1.75rem;">Control Panel</h4>
        <div class="row">
          <div class="col-lg-6 mb-4">
            <a href="User-Control.html" class="text-decoration-none">
              <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5">
                <div class="card-body d-flex align-items-center">
                  <img src="images/AdIc1.png" alt="User Control Icon" class="mr-4" width="60">
                  <h5 class="font-weight-bold mb-0" style="font-size: 1.5rem;">User Control</h5>
                </div>
              </div>
            </a>
          </div>
          <div class="col-lg-6 mb-4">
            <a href="Admin-Modify.html" class="text-decoration-none">
              <div class="feature-card bg-light text-dark rounded-lg shadow-sm p-5">
                <div class="card-body d-flex align-items-center">
                  <img src="images/AdIc2.png" alt="Sheet Modification Icon" class="mr-4" width="60">
                  <h5 class="font-weight-bold mb-0" style="font-size: 1.5rem;">Sheet Modification</h5>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>


 <!-- Footer -->
 <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/Real-Property-Unit-List.js"></script>
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