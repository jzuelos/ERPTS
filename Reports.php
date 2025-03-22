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
  <link rel="stylesheet" href="Reports.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <section class="container mt-4">
    <div class="card p-4">
      <form>
        <!-- Filter by Classification -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="classificationCheck">
          <label class="form-check-label font-weight-bold" for="classificationCheck">Filter by: Classification</label>
        </div>
        <div class="form-group">
          <label for="classificationSelect">Classification</label>
          <select class="form-control w-25" id="classificationSelect">
            <option>Residential</option>
            <option>Credential</option>
            <option>Industrial</option>
            <option>Agricultural</option>
            <option>Timberland</option>
            <option>Mineral Lands</option>
            <option>Special Property</option>
          </select>
        </div>
        <hr>

        <!-- Filter by Location -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="locationCheck">
          <label class="form-check-label font-weight-bold" for="locationCheck">Filter by: Location</label>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="provinceSelect">Province</label>
            <select class="form-control" id="provinceSelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="citySelect">Municipality/City</label>
            <select class="form-control" id="citySelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="districtSelect">District</label>
            <select class="form-control" id="districtSelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="barangaySelect">Barangay</label>
            <select class="form-control" id="barangaySelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
        </div>
        <hr>

        <!-- Filter by Date -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="dateCheck">
          <label class="form-check-label font-weight-bold" for="dateCheck">Filter by: Date Created</label>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="fromDate">From:</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="form-group col-md-6">
            <label for="toDate">To:</label>
            <input type="date" class="form-control" id="toDate">
          </div>
        </div>

        <!-- Print All Checkbox -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="printAllCheck">
          <label class="form-check-label font-weight-bold" for="printAllCheck">Print ALL (No Filtering)</label>
        </div>

        <!-- Submit Button -->
        <div class="text-right">
          <a href="#" class="btn btn-primary" target="_blank">PRINT</a>
        </div>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-light text-center text-lg-start mt-4">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
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