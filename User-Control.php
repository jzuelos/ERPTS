<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <title>User Control</title>
  <link rel="stylesheet" href="User-Control.css">
</head>
<body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">

<?php
    // Example PHP logic (if needed)
    $serverStatus = "Online";
    $userType = "Admin"; // Replace with your dynamic data
?>

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
<section class="section-user-management">
    <div class="container py-4">
        <h4 class="text-success">Server Status: Online</h4>
        <div class="alert alert-info text-center" role="alert">
            Logged in as Admin
        </div>

        <h3 class="mb-4">Users</h3>
        <div class="button-group mb-4">
            <button class="btn btn-outline-primary">Add User</button>
            <button class="btn btn-outline-primary">Show Disabled User</button>
            <button class="btn btn-outline-primary">Hide Disabled User</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th class="text-center">Update Status</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Data Rows -->
                    <?php for($i = 0; $i < 10; $i++): ?>
                    <tr>
                        <td>15</td>
                        <td><a href="#">eren.sena</a></td>
                        <td>Sena, Eren L.</td>
                        <td>Signatory</td>
                        <td>enabled</td>
                        <td class="text-center"><input type="checkbox" checked disabled></td>
                        <td class="text-center">
                            <a href="edit-user.php">
                                <img src="images/pencil.png" alt="Edit" class="edit-icon">
                            </a>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Your original JS files -->
<script src="jquery.js" defer></script>
<script src="nicepage.js" defer></script>
</body>
</html>
