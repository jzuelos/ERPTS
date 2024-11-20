<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set the number of records per page
$records_per_page = 5;

// Get the current page number (if not set, default to page 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Fetch the total number of records
$total_sql = "SELECT COUNT(*) AS total FROM propertyowner";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// Modify the original query to fetch paginated records
$sql = "SELECT 
  po.pO_id, 
  po.property_id, 
  po.owner_id, 
  o.own_id, 
  o.own_fname, 
  o.own_mname, 
  o.own_surname,
  p.barangay,
  p.city,
  p.district,
  p.province
FROM 
  propertyowner po
JOIN 
  owners_tb o ON po.owner_id = o.own_id
JOIN 
  p_info p ON po.property_id = p.p_id
ORDER BY o.own_surname ASC, o.own_fname ASC
LIMIT $start_from, $records_per_page";

// Fetch data for the current page
$owners = [];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $owners[] = $row;
  }
}

/*
// Handle merging logic if the form is submitted
if (isset($_POST['mergeOwners'])) {
  if (isset($_POST['personChoice']) && count($_POST['personChoice']) > 1) {
    $selected_owners = $_POST['personChoice'];
    $main_owner_id = $selected_owners[0]; // First selected owner as the main owner
    $other_owners = array_slice($selected_owners, 1); // All other owners to merge

    // Step 1: Update the `propertyowner` table to reassign properties
    foreach ($other_owners as $owner_id) {
      $update_sql = "UPDATE propertyowner SET owner_id = ? WHERE owner_id = ?";
      $stmt = $conn->prepare($update_sql);
      $stmt->bind_param("ii", $main_owner_id, $owner_id);
      $stmt->execute();
    }

    // Step 2: Update the `owners_tb` table to consolidate information
    foreach ($other_owners as $owner_id) {
      // Retrieve redundant owner's details
      $owner_sql = "SELECT * FROM owners_tb WHERE own_id = ?";
      $stmt = $conn->prepare($owner_sql);
      $stmt->bind_param("i", $owner_id);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $redundant_owner = $result->fetch_assoc();

        // Fetch main owner's existing details
        $main_owner_sql = "SELECT * FROM owners_tb WHERE own_id = ?";
        $stmt2 = $conn->prepare($main_owner_sql);
        $stmt2->bind_param("i", $main_owner_id);
        $stmt2->execute();
        $main_owner_result = $stmt2->get_result();
        $main_owner = $main_owner_result->fetch_assoc();

        // Update the main owner's details if fields are missing
        $updated_fname = empty($main_owner['own_fname']) ? $redundant_owner['own_fname'] : $main_owner['own_fname'];
        $updated_mname = empty($main_owner['own_mname']) ? $redundant_owner['own_mname'] : $main_owner['own_mname'];
        $updated_surname = empty($main_owner['own_surname']) ? $redundant_owner['own_surname'] : $main_owner['own_surname'];
        $updated_address = empty($main_owner['house_no']) ? $redundant_owner['house_no'] : $main_owner['house_no'];

        // Update the main owner's record in `owners_tb`
        $update_main_owner_sql = "UPDATE owners_tb SET 
                  own_fname = ?, 
                  own_mname = ?, 
                  own_surname = ?, 
                  house_no = ?, 
                  street = ?, 
                  barangay = ?, 
                  district = ?, 
                  city = ?, 
                  province = ?, 
                  own_info = ?
              WHERE own_id = ?";
        $stmt3 = $conn->prepare($update_main_owner_sql);
        $stmt3->bind_param(
          "ssssssssssi",
          $updated_fname,
          $updated_mname,
          $updated_surname,
          $updated_address,
          $redundant_owner['street'],
          $redundant_owner['barangay'],
          $redundant_owner['district'],
          $redundant_owner['city'],
          $redundant_owner['province'],
          $redundant_owner['own_info'],
          $main_owner_id
        );
        $stmt3->execute();
      }
    }

    echo "<div class='alert alert-success'>Merging completed successfully. Owners have been updated.</div>";
  } else {
    echo "<div class='alert alert-warning'>Please select at least two owners to merge.</div>";
  }
}*/

// Fetch the total count of pO_id from the propertyowner table
$total_sql = "SELECT COUNT(pO_id) AS total_pO FROM propertyowner";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pO_count = $total_row['total_pO'];
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Merge_Owners.css">
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

  <!--Main Body-->

  <!-- Form -->
  <form method="post" action="Merge_Owners.php">
    <section class="container mt-5 table-container">
      <div class="table-title">Merge Owners</div>
      <div class="table-responsive">
        <!-- Row for Search Input on the Left -->
        <div class="d-flex justify-content-between mb-2">
          <!-- Search Bar (on the left side) -->
          <div>
            <label for="searchInput" class="sr-only">Search</label>
            <div class="input-group">
              <input type="text" class="form-control w-50" id="searchInput" placeholder="Search">
              <div class="input-group-append">
                <button type="button" class="btn btn-success btn-hover" onclick="filterTable()">Search</button>
              </div>
            </div>
          </div>

          <!-- Total Count (on the right side) -->
          <div class="total-count">
            <strong>Total Count:</strong> <?= $total_pO_count ?>
          </div>
        </div>
      </div>

      <table class="table table-striped table-hover text-center">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Person ID</th>
            <th scope="col" class="center-input">Choose Person</th>
            <th scope="col">Last Name</th>
            <th scope="col">First Name</th>
            <th scope="col">Middle Name</th>
            <th scope="col" style="width: 300px;">Property Address</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // When the form is submitted, store the selected owner IDs in the session
          if (isset($_POST['personChoice'])) {
            $_SESSION['selected_owners'] = $_POST['personChoice'];
          }

          // Retrieve the selected owner IDs from the session if they exist
          $selectedOwners = isset($_SESSION['selected_owners']) ? $_SESSION['selected_owners'] : [];
          ?>
          <!-- Inside the HTML table, check if an owner is selected from the session -->
        <tbody>
          <?php foreach ($owners as $owner): ?>
            <?php
            // Check if the owner_id is in the session (which means it's selected)
            $checked = in_array($owner['owner_id'], $selectedOwners) ? 'checked' : '';
            ?>
            <tr>
              <td><?= $owner['pO_id']; ?></td>
              <td class="center-input">
                <input type="checkbox" name="personChoice[]" value="<?= $owner['owner_id']; ?>" <?= in_array($owner['owner_id'], $selectedOwners) ? 'checked' : ''; ?>>
              </td>
              <td><?= $owner['own_surname']; ?></td>
              <td><?= $owner['own_fname']; ?></td>
              <td><?= $owner['own_mname']; ?></td>
              <td><?= $owner['barangay'] . ' ' . $owner['city'] . ', ' . $owner['district'] . ', ' . $owner['province']; ?></td>
            </tr>

          <?php endforeach; ?>
        </tbody>

      </table>
      </div>

      <div class="pagination">
        <!-- Pagination controls -->
        <?php if ($page > 1): ?>
          <a href="Merge_Owners.php?page=1">First</a>&nbsp;&nbsp;
          <a href="Merge_Owners.php?page=<?= $page - 1 ?>">
            << </a>&nbsp;&nbsp;
            <?php endif; ?>
            <span>Page <?= $page ?> of <?= $total_pages ?></span>&nbsp;&nbsp;
            <?php if ($page < $total_pages): ?>
              <a href="Merge_Owners.php?page=<?= $page + 1 ?>"> >> </a>&nbsp;&nbsp;
              <a href="Merge_Owners.php?page=<?= $total_pages ?>">Last</a>
            <?php endif; ?>
      </div>

      <div class="btn-container">
        <button type="button" id="mergeOwnersButton" class="btn btn-custom">Merge Owners</button>
      </div>
    </section>
  </form>

  <!-- Modal -->
  <div class="modal fade" id="mergeOwnersModal" tabindex="-1" role="dialog" aria-labelledby="mergeOwnersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mergeOwnersModalLabel">Retain Owner</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="Merge_Owners.php">
            <section class="container table-container">
              <div class="table-title">Merge Owners</div>
              <div class="table-responsive">
                <table class="table table-striped table-hover text-center">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col" class="center-input">Choose Person</th>
                      <th scope="col" class="center-input" style="width:50px;">Show/Hide Properties</th>
                      <th scope="col">Person ID</th>
                      <th scope="col">Last Name</th>
                      <th scope="col">First Name</th>
                      <th scope="col">Middle Name</th>
                      <th scope="col" style="width: 300px;">Property Address</th>
                    </tr>
                  </thead>
                  <tbody id="modalBody">
                    <!-- Dynamic content will be populated here via JavaScript -->
                  </tbody>
                </table>
              </div>
            </section>
            <div class="modal-footer">
              <button type="submit" name="mergeOwners" class="btn btn-custom">Retain Owners</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/main_layout.js"></script>
  <script src="http://localhost/ERPTS/Merge_Owners.js"></script>
  <script src="http://localhost/ERPTS/Own_list.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>