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
$sql = "SELECT 
    r.dec_id,
    r.arp_no,
    r.total_property_value,
    r.tax_year,
    f.faas_id,
    f.pro_id AS p_id,
    GROUP_CONCAT(DISTINCT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname) SEPARATOR ', ') AS owner_names
  FROM rpu_dec r
  LEFT JOIN faas f ON r.faas_id = f.faas_id
  LEFT JOIN propertyowner po  
    ON po.property_id = f.pro_id 
   AND po.is_retained = 1
  LEFT JOIN owners_tb o ON o.own_id = po.owner_id
  GROUP BY r.dec_id, f.faas_id, f.pro_id
  ORDER BY r.dec_id DESC
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
      <div class="d-flex align-items-stretch" style="width: 250px;">
        <input type="text" id="searchInput" placeholder="Search..." class="form-control" style="width: 400px;">
        <button type="button" id="filterBtn" class="btn btn-success ms-1">Search</button>
      </div>
      <div class="d-flex align-items-center">
        <label class="mb-0 me-2" for="propertyType">Filter by Property Type:</label>
        <select id="propertyType" class="form-control me-2" style="width: 150px;">
          <option value="">Select Type</option>
          <option value="Land">Land</option>
          <option value="Building">Building</option>
          <option value="Vehicle">Vehicle</option>
        </select>
        <button type="button" class="btn btn-success">Go</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-striped modern-table" id="dataTable">
        <thead class="table-dark">
          <tr>
            <th class="text-center">TD ID</th>
            <th class="text-center">OWNER<br><span class="owner-subtext">(person) (company/group)</span></th>
            <th class="text-center">TD NUMBER</th>
            <th class="text-center">PROPERTY VALUE</th>
            <th class="text-center">YEAR</th>
            <th class="text-center">ACTIONS</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $dec_id = $row['dec_id'];
              $faas_id = $row['faas_id'];
              $p_id = $row['p_id'];
              $owner_names = $row['owner_names'] ?? '';

              echo "<tr>";
              echo "<td>" . htmlspecialchars($dec_id) . "</td>";
              echo "<td>" . htmlspecialchars($owner_names) . "</td>";
              echo "<td>" . htmlspecialchars($row['arp_no']) . "</td>";
              echo "<td>₱ " . number_format($row['total_property_value'], 2) . "</td>";
              echo "<td>" . htmlspecialchars($row['tax_year']) . "</td>";
              if (!empty($p_id)) {
                echo "<td class='text-center'><a href='FAAS.php?id={$p_id}' class='btn btn-success btn-sm'><i class='fas fa-edit'></i></a </td>";
              } else {
                echo "<td><span class='text-muted'>No Property</span></td>";
              }
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
      <div class="d-flex justify-content-between mt-3">
        <div class="d-flex justify-content-center align-items-center mt-3" id="paginationControls"></div>
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#viewAllModal">View All</button>
      </div>
  </section>

  <!--View All Modal-->
  <div class="modal fade" id="viewAllModal" tabindex="-1" aria-labelledby="viewAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewAllModalLabel">All Tax Declarations</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-12 col-md-4">
              <input type="text" id="modalSearchInput" class="form-control form-control-sm" placeholder="Search...">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-striped modern-table" id="modalTable">
              <thead class="table-dark">
                <tr>
                  <th>TD ID</th>
                  <th>Owner</th>
                  <th>TD Number</th>
                  <th>Property Value</th>
                  <th>Year</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <!-- Rows will be here from the main table -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="Tax-Declaration-List.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>