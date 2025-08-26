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
  SELECT r.*, f.faas_id, f.pro_id AS p_id
  FROM rpu_dec r
  LEFT JOIN faas f ON r.faas_id = f.faas_id
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
      <!-- Left Column: Stats + Table -->
      <div class="col-lg-8">
        <!-- Stats Cards Container -->
        <div class="modern-card shadow-lg p-4 rounded-lg mb-4">
          <div class="row g-4">
            <!-- Property Listing -->
            <div class="col-md-4">
              <div class="stats-card h-100">
                <h4 class="font-weight-bold">Property Listings</h4>
                <div class="d-flex align-items-center mt-3">
                  <i class="fas fa-building fa-3x text-warning me-3"></i>
                  <div>
                    <h2 class="mb-0"><?php echo $total_properties; ?></h2>
                    <small class="text-muted">Total Properties</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Owner Statistics -->
            <div class="col-md-4">
              <div class="stats-card h-100">
                <h4 class="font-weight-bold">Owner Statistics</h4>
                <div class="d-flex align-items-center mt-3">
                  <i class="fas fa-users fa-3x text-warning me-3"></i>
                  <div>
                    <h2 class="mb-0"><?php echo $total_owners; ?></h2>
                    <small class="text-muted">Total Owners</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Parcel -->
            <div class="col-md-4">
              <div class="stats-card h-100">
                <h4 class="font-weight-bold">PARCEL</h4>
                <div class="row mt-3">
                  <div class="col-4 text-center">
                    <i class="fas fa-map fa-2x text-warning mb-2"></i>
                    <div class="parcel-count"><?php echo $land_count; ?></div>
                    <h6>LAND</h6>
                  </div>
                  <div class="col-4 text-center">
                    <i class="fas fa-building fa-2x text-warning mb-2"></i>
                    <div class="parcel-count"><?php echo $building_count; ?></div>
                    <h6>BUILDING</h6>
                  </div>
                  <div class="col-4 text-center">
                    <i class="fas fa-tree fa-2x text-warning mb-2"></i>
                    <div class="parcel-count"><?php echo $plant_count; ?></div>
                    <h6>PLANT/TREES</h6>
                  </div>
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
              <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $dec_id = $row['dec_id'];
                    $faas_id = $row['faas_id'];
                    $p_id = $row['p_id'];
                    $owner_names = '';

                    echo "<!-- DEBUG: dec_id={$dec_id}, faas_id={$faas_id}, p_id={$p_id} -->";

                    if (!empty($faas_id)) {
                      $faas_stmt = $conn->prepare("SELECT propertyowner_id FROM faas WHERE faas_id = ?");
                      $faas_stmt->bind_param("i", $faas_id);
                      $faas_stmt->execute();
                      $faas_result = $faas_stmt->get_result();

                      if ($faas_result && $faas_row = $faas_result->fetch_assoc()) {
                        echo "<!-- DEBUG: faas_row fetched successfully -->";
                        $po_ids_json = $faas_row['propertyowner_id'];
                        echo "<!-- DEBUG: raw JSON = {$po_ids_json} -->";

                        $po_ids = json_decode($po_ids_json, true);

                        if (json_last_error() !== JSON_ERROR_NONE) {
                          echo "<!-- DEBUG: JSON Decode Error: " . json_last_error_msg() . " -->";
                        }

                        if (is_array($po_ids) && count($po_ids) > 0) {
                          // Get owner_ids from propertyowner table
                          $placeholders = implode(',', array_fill(0, count($po_ids), '?'));
                          $types = str_repeat('i', count($po_ids));
                          $stmt_po = $conn->prepare("SELECT owner_id FROM propertyowner WHERE pO_id IN ($placeholders)");
                          $stmt_po->bind_param($types, ...$po_ids);
                          $stmt_po->execute();
                          $result_po = $stmt_po->get_result();

                          $owner_ids = [];
                          while ($row_po = $result_po->fetch_assoc()) {
                            $owner_ids[] = $row_po['owner_id'];
                          }

                          echo "<!-- DEBUG: owner_ids resolved = " . implode(',', $owner_ids) . " -->";

                          if (count($owner_ids) > 0) {
                            // Get owner names from owners_tb
                            $placeholders2 = implode(',', array_fill(0, count($owner_ids), '?'));
                            $types2 = str_repeat('i', count($owner_ids));
                            $stmt_owners = $conn->prepare("SELECT own_fname, own_mname, own_surname FROM owners_tb WHERE own_id IN ($placeholders2)");
                            $stmt_owners->bind_param($types2, ...$owner_ids);
                            $stmt_owners->execute();
                            $result_owners = $stmt_owners->get_result();

                            $owner_fullnames = [];
                            while ($owner = $result_owners->fetch_assoc()) {
                              $full_name = trim($owner['own_fname'] . ' ' . $owner['own_mname'] . ' ' . $owner['own_surname']);
                              $owner_fullnames[] = $full_name;
                            }

                            $owner_names = implode(', ', $owner_fullnames);
                            echo "<!-- DEBUG: Owners fetched = " . count($owner_fullnames) . " -->";
                          } else {
                            echo "<!-- DEBUG: No owner_ids found from propertyowner -->";
                          }
                        } else {
                          echo "<!-- DEBUG: po_ids is not a valid array or empty -->";
                        }
                      } else {
                        echo "<!-- DEBUG: faas_row not found -->";
                      }
                    } else {
                      echo "<!-- DEBUG: faas_id is empty -->";
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($dec_id ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($owner_names) . "</td>";
                    echo "<td>" . htmlspecialchars($row['arp_no'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['total_property_value'] ?? '') . "</td>";
                    echo "<td>" . (!empty($row['tax_year']) ? htmlspecialchars(date('Y', strtotime($row['tax_year']))) : '') . "</td>";
                  }
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
</body>

</html>