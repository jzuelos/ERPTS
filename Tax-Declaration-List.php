<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$user_role = $_SESSION['user_type'] ?? 'user';

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Electronic Real Property Tax System</title>

  <!-- Bootstrap & Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Tax-Declaration-List.css">
</head>

<body>
  <?php include 'header.php'; ?>

  <section class="container mt-4">
    <div class="mb-4 d-flex justify-content-start">
      <a href="Home.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex">
        <input type="text" class="form-control mr-2" placeholder="Search..." style="width: 200px;">
        <button class="btn btn-secondary" onclick="filterTable()">Search</button>
      </div>
      <div class="d-flex align-items-center">
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
              echo "<td>" . htmlspecialchars($row['tax_year'] ?? '') . "</td>";

              if (!empty($p_id)) {
                echo "<td><a href='FAAS.php?id={$p_id}' class='btn btn-primary'>EDIT</a></td>";
              } else {
                echo "<td><span class='text-muted'>No Property</span></td>";
              }
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='6'>No records found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

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

  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="http://localhost/ERPTS/Tax-Declaration-List.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>