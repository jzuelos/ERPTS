<?php
session_start();

// Check if the user is logged in by verifying if 'user_id' exists in the session
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php"); // Redirect to login page if user is not logged in
  exit; // Stop further execution after redirection
}

$user_role = $_SESSION['user_type'] ?? 'user'; // Default to 'user' if role is not set

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
  <link rel="stylesheet" href="Transaction.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <div class="container my-5">
    <h2 class="text-center">Transaction</h2>

    <div class="table-responsive">
      <table class="table modern-table table-hover">
        <thead class="thead-light">
          <tr>
            <th>ID</th>
            <th>Owner</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>John Doe</td>
            <td>2024-10-15</td>
            <td><span class="badge badge-warning">Pending</span></td>
            <td><a href="Track.html" class="btn btn-info btn-sm">Track</a></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Jane Smith</td>
            <td>2024-10-18</td>
            <td><span class="badge badge-success">Approved</span></td>
            <td><a href="#" class="btn btn-info btn-sm">Track</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-light text-center py-3">
    <div class="container">
      <p class="mb-0">&copy; 2024 Electronic Real Property Tax System</p>
    </div>
  </footer>

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
</body>

</html>