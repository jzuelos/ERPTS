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

// Main query to join rpu_dec with faas
$sql = "
  SELECT 
    r.dec_id,
    r.arp_no,
    r.total_property_value,
    r.tax_year,
    f.faas_id,
    f.pro_id AS p_id,
    GROUP_CONCAT(DISTINCT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname) SEPARATOR ', ') AS owner_names
  FROM rpu_dec r
  LEFT JOIN faas f ON r.faas_id = f.faas_id
  LEFT JOIN propertyowner po ON po.property_id = f.pro_id
  LEFT JOIN owners_tb o ON o.own_id = po.owner_id
  GROUP BY r.dec_id, f.faas_id, f.pro_id
  ORDER BY r.dec_id DESC
";

$result = $conn->query($sql);
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
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Home.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <div class="container-fluid p-0 main-content" style="margin-top: 20px;">
    <div class="row px-4">
      <h2 class="fw-bold fst-italic text-black">
        Welcome, <?php echo htmlspecialchars($first_name); ?>!
      </h2>
      <div class="row mt-4">
        <!-- Left Column -->
        <div class="col-lg-8">
          <!-- Stats + Line Graph Card -->
          <div class="modern-card shadow-lg p-4 rounded-lg mb-4">
            <div class="row g-3">
              <!-- Stats Cards stacked vertically on the right -->
              <div class="col-md-4 d-flex flex-column gap-3">
                <!-- Property Listing -->
                <a href="Real-Property-Unit-List.php" class="text-decoration-none text-dark">
                  <div class="stats-card text-center p-3 shadow-sm">
                    <h6 class="fw-bold">Property Listings</h6>
                    <i class="fas fa-building fa-2x text-warning my-2"></i>
                    <h3 class="mb-0"><?php echo $total_properties; ?></h3>
                    <small class="text-muted">Total Properties</small>
                  </div>
                </a>

                <!-- Owner Statistics -->
                <a href="Tax-Declaration-List.php" class="text-decoration-none text-dark">
                  <div class="stats-card text-center p-3 shadow-sm">
                    <h6 class="fw-bold">Owner Statistics</h6>
                    <i class="fas fa-users fa-2x text-warning my-2"></i>
                    <h3 class="mb-0"><?php echo $total_owners; ?></h3>
                    <small class="text-muted">Total Owners</small>
                  </div>
                </a>

                <!-- Parcel -->
                <a href="Track.php" class="text-decoration-none text-dark">
                  <div class="stats-card text-center p-3 shadow-sm">
                    <h6 class="fw-bold">Parcel</h6>
                    <div class="d-flex justify-content-around mt-2">
                      <div>
                        <i class="fas fa-map fa-lg text-warning mb-1"></i>
                        <div class="parcel-count"><?php echo $land_count; ?></div>
                        <small>Land</small>
                      </div>
                      <div>
                        <i class="fas fa-building fa-lg text-warning mb-1"></i>
                        <div class="parcel-count"><?php echo $building_count; ?></div>
                        <small>Building</small>
                      </div>
                      <div>
                        <i class="fas fa-tree fa-lg text-warning mb-1"></i>
                        <div class="parcel-count"><?php echo $plant_count; ?></div>
                        <small>Plant/Trees</small>
                      </div>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Line Graph on the left -->
              <div class="col-md-8 d-flex justify-content-center align-items-center">
                <div class="w-100" style="max-width: 600px;">
                  <h6 class="mb-2 text-center"><i class="fas fa-chart-line me-2"></i> Property Statistics</h6>
                  <div style="height:300px;">
                    <canvas id="dashboardChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- Tax Declaration Table -->
          <div class="modern-card shadow-lg p-4 rounded-lg">
            <h4 class="font-weight-bold custom-text-color mb-4 d-flex align-items-center justify-content-between">
              TAX DECLARATION
              <a href="tax-declaration-list.php" class="btn btn-sm btn-outline-primary">View All</a>
            </h4>
            <div class="table-responsive">
              <table class="table table-hover table-custom">
                <thead class="thead-light">
                  <tr>
                    <th>TD ID</th>
                    <th>OWNER</th>
                    <th>TD NUMBER</th>
                    <th>PROPERTY VALUE</th>
                    <th>YEAR</th>
                  </tr>
                </thead>
                <?php
                $sql = "
  SELECT 
    r.dec_id,
    r.arp_no,
    r.total_property_value,
    r.tax_year,
    f.faas_id,
    f.pro_id AS p_id,
    GROUP_CONCAT(DISTINCT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname) SEPARATOR ', ') AS owner_names
  FROM rpu_dec r
  LEFT JOIN faas f ON r.faas_id = f.faas_id
  LEFT JOIN propertyowner po ON po.property_id = f.pro_id
  LEFT JOIN owners_tb o ON o.own_id = po.owner_id
  GROUP BY r.dec_id, f.faas_id, f.pro_id
  ORDER BY r.dec_id DESC
";

                $result = $conn->query($sql);
                ?>

                <tbody>
                  <?php
                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $dec_id = $row['dec_id'];
                      $faas_id = $row['faas_id'];
                      $p_id = $row['p_id'];
                      $owner_names = $row['owner_names'] ?? '';

                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($dec_id ?? '') . "</td>";
                      echo "<td>" . htmlspecialchars($owner_names) . "</td>";
                      echo "<td>" . htmlspecialchars($row['arp_no'] ?? '') . "</td>";
                      echo "<td>" . htmlspecialchars($row['total_property_value'] ?? '') . "</td>";
                      echo "<td>" . (!empty($row['tax_year']) ? htmlspecialchars(date('Y', strtotime($row['tax_year']))) : '') . "</td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='5'>No records found.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Right Section: Main Content -->
        <div class="col-lg-4">
          <div class="modern-card shadow-lg p-4 rounded-lg" style="height: 100%;">
            <h3 class="font-weight-bold custom-text-color">CITIZEN'S CHARTER OFFICE OF THE PROVINCIAL ASSESSOR</h3>
            <h5 class="text-secondary mb-4 custom-text-color">Capitol, Daet, Camarines Norte</h5>
            <p class="lead custom-text-color">The Office of the Provincial Assessor is a key entity in the Provincial
              Government, operating under Republic Act No. 7160, also known as the Local Government Code of 1991.</p>
            <p class="custom-text-color">Its primary goal is to perform duties related to real property taxation, adhering
              to fundamental principles such as:</p>
            <ul class="list-group list-group-flush">
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Appraising
                real property at its current and fair market value.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i>
                Classification of property for assessment based on actual use.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring
                uniform assessment classification within the local government unit.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Restricting
                private persons from performing appraisal and assessment tasks.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring
                equitable property appraisal and assessment.</li>
            </ul>
            <p class="mt-3 custom-text-color">Under Sec. 472, par (b) of the Code, the Office has the following key
              responsibilities:</p>
            <ol class="ml-3">
              <li class="custom-text-color">Enforcing laws and policies regarding property appraisal and taxation.</li>
              <li class="custom-text-color">Reviewing and recommending improvements to policies and practices in property
                valuation and assessment.</li>
              <li class="custom-text-color">Establishing efficient property assessment systems and maintaining accurate
                property records.</li>
              <li class="custom-text-color">Ensuring proper tax mapping and conducting frequent surveys for verification
                of listed properties.</li>
              <li class="custom-text-color">Coordinating with municipal assessors for better data management and tax
                mapping operations.</li>
            </ol>
            <p class="mt-4 custom-text-color">The office operates under the supervision of the Governor, with technical
              oversight from the Department of Finance and the Bureau of Local Government Finance.</p>
            <p class="font-weight-bold custom-text-color mt-4">Currently, the Office is composed of three divisions:
              Assessment and Appraisal, Tax Mapping and Records, and Administrative.</p>
          </div>
        </div>
      </div>
    </div>


    <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
      </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="DashboardGraph.js"></script>
</body>

</html>