<?php
session_start();  // Start session at the top to access session variables

// Check if the user is logged in by verifying if 'user_id' exists in the session
/*if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if user is not logged in
    exit; // Stop further execution after redirection
}*/

// Prevent the browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // Prevent caching
header("Cache-Control: post-check=0, pre-check=0", false); // Additional no-cache headers
header("Pragma: no-cache"); // Older cache control header for HTTP/1.0 compatibility

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  echo "Connected";
}

// Fetch property units along with their owners, sorted by latest ID first
$sql = "SELECT p.p_id, p.house_no, p.block_no, p.barangay, p.province, p.city, p.district, p.land_area,
               CONCAT(o.own_fname, ', ', o.own_mname, ' ', o.own_surname) AS owner
        FROM p_info p
        LEFT JOIN owners_tb o ON p.ownId_Fk = o.own_id
        ORDER BY p.p_id DESC"; // Sort by latest p_id first

$propertyUnits = [];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $propertyUnits[] = $row;
  }
} else {
  echo "No records found";
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
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Real-Property-Unit-List.css">
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
          <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
  <section class="container mt-5">
    <div class="card p-4">
      <h3 class="mb-4">Real Property Units List</h3>
      <div class="form-row mb-4">
        <div class="col-auto">
          <label for="searchInput" class="sr-only">Search</label>
          <div class="input-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Search"> <!-- Search input -->
            <select class="custom-select" id="barangayDropdown"> <!-- Dropdown for barangay selection -->
              <option selected value="">All Barangay</option> <!-- Default option -->
              <option value="Kalamunding">Kalamunding</option>
              <option value="Bautista">Bautista</option>
              <option value="Barangay 3">Barangay 3</option>
            </select>
          </div>
        </div>

        <div class="col-auto">
          <button type="button" class="btn btn-success btn-hover" onclick="filterTable()">Search</button>
          <a href="Add-New-Real-Property-Unit.php" class="btn btn-success btn-hover">Add new RPU</a>
        </div>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered text-center modern-table" id="propertyTable"> <!-- Responsive table -->
          <thead>
            <tr>
              <th>OD ID</th>
              <th>Owner</th>
              <th>Location <br><small>(Barangay, City, Province)</small></th>
              <th>Land Area</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php
            // Display the fetched data in table rows
            foreach ($propertyUnits as $unit) {
              echo "<tr>
                    <td>{$unit['p_id']}</td>
                    <td>{$unit['owner']}</td>
                    <td>{$unit['house_no']}, {$unit['barangay']}, {$unit['city']}, {$unit['province']}</td>
                    <td>{$unit['land_area']}</td>
                    <td><a href='FAAS.php?id={$unit['p_id']}' class='btn btn-primary'>EDIT</a></td>
                  </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div class="pagination-controls mta-3">
        <label for="pageSelect">Page: </label>
        <select id="pageSelect" onchange="changePage()"></select>
      </div>

      <!-- View All Button -->
      <div class="view-all-container d-flex mt-3">
        <div class="ml-auto">
          <button type="button" class="btn btn-info">View All</button>
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