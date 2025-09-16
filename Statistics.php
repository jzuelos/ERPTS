<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="ALog.css">

  <title>Electronic Real Property Tax System</title>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>

  
<main class="container my-5 flex-grow-1 d-flex justify-content-center">
  <div class="col-lg-10 col-md-11 col-sm-12">
    <div class="mb-3">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>

    <h4 class="mb-3 text-center"><i class="fas fa-chart-line me-2"></i> Dashboard Statistics</h4>

    <!-- Dropdown -->
    <div class="d-flex justify-content-center mb-3">
      <select id="chartSelector" class="form-select form-select-sm" style="max-width:300px;">
        <option value="property">Property Statistics</option>
        <option value="user">User Activity</option>
        <option value="audit">Audit Trail</option>
      </select>
    </div>

    <!-- Date filter (always visible now) -->
    <div id="dateFilter" class="d-flex justify-content-center mb-3">
      <input type="date" id="startDate" class="form-control form-control-sm me-2" style="max-width:200px;">
      <input type="date" id="endDate" class="form-control form-control-sm me-2" style="max-width:200px;">
      <button class="btn btn-primary btn-sm" onclick="filterChart()">Filter</button>
    </div>

    <!-- Chart containers -->
    <div class="mb-4" style="max-width: 900px; margin: auto;">
      <div style="height:500px;">
        <canvas id="propertyChart"></canvas>
        <canvas id="userChart" style="display:none;"></canvas>
        <canvas id="auditChart" style="display:none;"></canvas>
      </div>
    </div>

    <div class="d-flex justify-content-end mt-2">
      <button id="exportBtn" class="btn btn-success btn-sm">
        Export as Image
      </button>
    </div>
  </div>
</main>


  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src ="Statistics.js"></script>
   
</body>

</html>
