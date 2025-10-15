<?php
session_start(); // Start session at the top

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: index.php");
  exit;
}

$first_name = $_SESSION['first_name'] ?? 'Guest';

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; // Include your database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Fetch total number of owners
$query_owners = "SELECT COUNT(*) AS total_owners FROM owners_tb";
$result_owners = $conn->query($query_owners);
$total_owners = $result_owners->fetch_assoc()['total_owners'];

// Fetch total number of properties
$query_properties = "SELECT COUNT(*) AS total_properties FROM p_info";
$result_properties = $conn->query($query_properties);
$total_properties = $result_properties->fetch_assoc()['total_properties'];

// Fetch counts for land
$query_land = "SELECT COUNT(*) AS total_land FROM land";
$result_land = $conn->query($query_land);
$land_count = $result_land->fetch_assoc()['total_land'];

// Dummy data
$building_count = 843;
$plant_count = 327;

// --- Fetch activity logs ---
$query_activity = "SELECT user_id, action, log_time FROM activity_log ORDER BY log_time ASC";
$result_activity = $conn->query($query_activity);

// --- Fetch total users ---
$query_users = "SELECT COUNT(*) AS user_count FROM users";
$result_users = $conn->query($query_users);
$user_count = ($result_users && $result_users->num_rows > 0)
  ? $result_users->fetch_assoc()['user_count']
  : 0;

// --- Default counts ---
$loginCount = 0;
$transactionLogs = 0;
$transactionsDone = 0;

// --- Analyze activity logs ---
if ($result_activity && $result_activity->num_rows > 0) {
  while ($row = $result_activity->fetch_assoc()) {
    $action = strtolower($row['action']);

    if (strpos($action, 'logged in') !== false) {
      $loginCount++;
    } elseif (
      strpos($action, 'added') !== false ||
      strpos($action, 'updated') !== false ||
      strpos($action, 'created') !== false
    ) {
      $transactionLogs++;
    } elseif (strpos($action, 'transaction done') !== false) {
      $transactionsDone++;
    }
  }
}

// --- Prepare labels and data ---
$labels = ['Users', 'Transaction Logs', 'Login Counts', 'Transactions Done'];
$data_counts = [
  $user_count,
  $transactionLogs > 0 ? $transactionLogs : 20,  // dummy fallback if 0
  $loginCount > 0 ? $loginCount : 10,
  $transactionsDone > 0 ? $transactionsDone : 5
];

// --- Pass data to JS ---
$js_labels = json_encode($labels);
$js_data = json_encode($data_counts);
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


  <script>
  const totalLand = <?= $land_count ?>;
  const totalBuilding = <?= $building_count ?>;
  const totalPlant = <?= $plant_count ?>;
  const totalOwners = <?= $total_owners ?>;
  const totalProperties = <?= $total_properties ?>;
</script>

  <script>
      const activityLabels = <?= $js_labels ?>;
      const activityData = <?= $js_data ?>;
  </script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src ="Statistics.js"></script>
   
</body>

</html>
