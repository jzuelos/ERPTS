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

<section class="container mt-5">
  <!-- Back Button -->
  <div class="mb-4 d-flex justify-content-start">
    <a href="Home.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>

  <!-- Card Container -->
  <div class="card p-4">
    <h3 class="mb-4">Tax Declaration List</h3>

    <!-- Search + Filter Row -->
    <div class="form-row mb-4">
      <div class="row mb-4 align-items-center">
        <!-- Search Input -->
        <div class="col-12 col-md-6 col-lg-3 mb-2 mb-md-0">
          <input type="text" class="form-control" id="searchInput" placeholder="Search" onkeyup="filterTable()">
        </div>

        <!-- Dropdown + Search Button -->
        <div class="col-10 col-sm-6 col-md-4 col-lg-4 mb-2 mb-md-0">
          <div class="d-flex">
            <select class="form-select me-3 w-50" id="propertyType" name="propertyType">
              <option value="" disabled selected hidden>Select Type</option>
              <option value="Land">Land</option>
              <option value="Building">Building</option>
              <option value="Machinery">Machinery</option>
              <option value="Other">Other</option>
            </select>
            <button type="button" class="btn btn-success" onclick="filterTable()">Search</button>
          </div>
        </div>


      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered text-start modern-table" id="dataTable">
          <thead>
            <tr>
              <th>TD ID</th>
              <th>Owner<br><small>(Person / Company)</small></th>
              <th>TD Number</th>
              <th>Property Value</th>
              <th>Year</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $dec_id = $row['dec_id'];
                $faas_id = $row['faas_id'];
                $p_id = $row['p_id'];
                $owner_names = $row['owner_names'] ?? '';
                $owner_display = trim($owner_names) !== '' ? $owner_names : 'None';
                $rowClass = empty($p_id) ? 'table-secondary' : ''; // gray if no property

                echo "<tr class='{$rowClass}'>";
                echo "<td>" . htmlspecialchars($dec_id) . "</td>";
                echo "<td>" . htmlspecialchars($owner_display) . "</td>";
                echo "<td>" . htmlspecialchars($row['arp_no']) . "</td>";
                echo "<td>₱ " . number_format($row['total_property_value'], 2) . "</td>";
                echo "<td>" . htmlspecialchars($row['tax_year']) . "</td>";
                if (!empty($p_id)) {
                  echo "<td><a href='FAAS.php?id={$p_id}' class='btn btn-primary btn-sm'>EDIT</a></td>";
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
      </div>

      <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="pagination-controls d-flex align-items-center">
        <button class="btn btn-outline-success me-2" id="prevPage">Previous</button>
        <button class="btn btn-success me-2 active" id="currentPage">1</button>
        <button class="btn btn-outline-success" id="nextPage">Next</button>
      </div>

      <!-- View All Button -->
      <div class="view-all-container d-flex mt-3">
        <div class="ml-auto">
          <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewAllModal">
            View All
          </button>
        </div>
      </div>
    </div>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>