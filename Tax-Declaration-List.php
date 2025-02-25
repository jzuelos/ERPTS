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
  <link rel="stylesheet" href="Tax-Declaration-List.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <section class="container mt-4">
    <!-- Search Bar and Filters -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex">
        <!-- Search Bar -->
        <input type="text" class="form-control mr-2" placeholder="Search..." style="width: 200px;">
        <button class="btn btn-secondary" onclick="filterTable()">Search</button>
      </div>

      <div class="d-flex align-items-center">
        <!-- Filter by Property Type -->
        <label class="mb-0 mr-2" for="propertyType">Filter by Property Type:</label>
        <select id="propertyType" class="form-control mr-2" style="width: 150px;">
          <option value="">Select Type</option>
          <option value="Land">Land</option>
          <option value="Building">Building</option>
          <option value="Vehicle">Vehicle</option>
        </select>
        <button class="btn btn-primary">Go</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-striped modern-table text-center">
        <thead class="thead-dark">
          <tr>
            <th>TD ID</th>
            <th>OWNER<br><span class="owner-subtext">(person) (company/group)</span></th>
            <th>TD NUMBER</th>
            <th>PROPERTY VALUE</th>
            <th>YEAR</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <!-- Sample rows for table -->
          <tr>
            <td>123</td>
            <td>John Doe</td>
            <td>TD-2023-0001</td>
            <td>$150,000</td>
            <td>2023</td>
            <td>
              <button class="btn btn-outline-primary btn-sm">Edit</button>
            </td>
          </tr>
          <tr>
            <td>124</td>
            <td>Jane Smith</td>
            <td>TD-2023-0002</td>
            <td>$200,000</td>
            <td>2023</td>
            <td>
              <button class="btn btn-outline-primary btn-sm">Edit</button>
            </td>
          </tr>
          <tr>
            <td>123</td>
            <td>John Doe</td>
            <td>TD-2023-0001</td>
            <td>$150,000</td>
            <td>2023</td>
            <td>
              <button class="btn btn-outline-primary btn-sm">Edit</button>
            </td>
          </tr>
          <tr>
            <td>124</td>
            <td>Jane Smith</td>
            <td>TD-2023-0002</td>
            <td>$200,000</td>
            <td>2023</td>
            <td>
              <button class="btn btn-outline-primary btn-sm">Edit</button>
            </td>
          </tr>
          <tr>
            <td>123</td>
            <td>John Doe</td>
            <td>TD-2023-0001</td>
            <td>$150,000</td>
            <td>2023</td>
            <td>
              <button class="btn btn-outline-primary btn-sm">Edit</button>
            </td>
          </tr>
          <tr>
            <td>124</td>
            <td>Jane Smith</td>
            <td>TD-2023-0002</td>
            <td>$200,000</td>
            <td>2023</td>
            <td>
              <button class="btn btn-outline-primary btn-sm">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination Controls -->
    <div class="d-flex justify-content-between mt-3">
      <div class="d-flex align-items-center">
        <p class="mb-0 mr-2">Page:</p>
        <select class="form-control mr-2" style="width: 80px;">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
        <button class="btn btn-custom">Go</button>
      </div>
      <a href="#" class="mt-1 fs-6">View All</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/Tax-Declaration-List.js"></script>
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