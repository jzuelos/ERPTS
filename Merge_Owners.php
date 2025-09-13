<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Prevent caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'database.php';

// Establish database connection
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
 WHERE 
   po.is_retained = 1  -- Only show retained properties
 ORDER BY 
   o.own_surname ASC, o.own_fname ASC
 LIMIT $start_from, $records_per_page";

// Fetch data for the current page
$owners = [];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $owners[] = $row;
  }
}

$conn->begin_transaction();
try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['retainOwnersButton'])) {
    // Validate the retained person ID (pO_id)
    if (!isset($_POST['personChoiceModal'])) {
        echo 'Error: No person selected to retain ownership.<br>';
        exit;
    }
    $selectedPersonID = $_POST['personChoiceModal'];  // The pO_id (primary key) of the retained owner
    $selectedProperties = $_POST['showProperties'] ?? [];  // The properties to transfer

    if (empty($selectedProperties)) {
        echo 'Error: No properties selected for transfer.<br>';
        exit;
    }

    // Step 1: Set is_retained = 0 for all other selected property owners
    foreach ($selectedProperties as $pO_id) {
        $isRetained = ($pO_id == $selectedPersonID) ? 1 : 0;  // Retained person gets 1, others get 0
        $stmtUpdate = $conn->prepare("UPDATE propertyowner SET is_retained = ? WHERE pO_id = ?");
        if ($stmtUpdate === false) {
            echo "Error preparing UPDATE query for is_retained: " . $conn->error . '<br>';
            continue;
        }
        $stmtUpdate->bind_param("ii", $isRetained, $pO_id);
        if (!$stmtUpdate->execute()) {
            echo "Error executing UPDATE query for pO_id $pO_id: " . $stmtUpdate->error . '<br>';
            continue;
        }
        echo "Set is_retained = $isRetained for property with pO_id: $pO_id<br>";
    }

    // Commit the transaction after all updates
    echo "Merge process completed successfully.";
    $conn->commit();  // Commit the transaction

    // Redirect to the same page to refresh the data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;  // Ensure no further code is executed after the redirect
  }
} catch (Exception $e) {
    $conn->rollback();  // Rollback transaction on error
    echo "Transaction failed: " . $e->getMessage();
}

// Fetch the total count of retained pO_id from the propertyowner table
$total_sql = "SELECT COUNT(pO_id) AS total_pO FROM propertyowner WHERE is_retained = 1";
$total_result = $conn->query($total_sql);

if ($total_result && $total_result->num_rows > 0) {
    $total_row = $total_result->fetch_assoc();
    $total_pO_count = $total_row['total_pO'];
} else {
    $total_pO_count = 0; // Default to 0 if no rows found
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Merge_Owners.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
     <?php include 'header.php'; ?>
<!-- Main Body -->
<form method="post" action="Merge_Owners.php">
  <section class="container mt-5">
    <div class="header-container d-flex justify-content-between align-items-center">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
      <div class="table-title">Merge Owners</div>
    </div>

    <!-- Search Bar and Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- Search input and button container -->
      <div class="search-container d-flex align-items-center">
        <input
          type="text"
          class="form-control search-input"
          id="searchInput"
          placeholder="Search Owners"
          aria-label="Search"
          onkeyup="handleSearch(event)"
        />
        <button
          type="button"
          class="btn btn-primary ml-2"
          onclick="searchTable()">
          Search
        </button>
      </div>
      <div class="total-count text-right">
        <strong>Total Count:</strong> <?= $total_pO_count ?>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-striped table-hover modern-table text-start">
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
        <tbody id="ownerTableBody">
          <?php
          // When the form is submitted, store the selected owner IDs in the session
          if (isset($_POST['personChoice'])) {
            $_SESSION['selected_owners'] = $_POST['personChoice'];
          }

          // Retrieve the selected owner IDs from the session if they exist
          $selectedOwners = isset($_SESSION['selected_owners']) ? $_SESSION['selected_owners'] : [];
          ?>
          <!-- Inside the HTML table, check if an owner is selected from the session -->
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

    <!-- Pagination -->
    <div class="pagination mt-3">
      <?php if ($page > 1): ?>
        <a href="Merge_Owners.php?page=1" class="pagination-link">First</a>&nbsp;&nbsp;
        <a href="Merge_Owners.php?page=<?= $page - 1 ?>" class="pagination-link"><<</a>&nbsp;&nbsp;
      <?php endif; ?>
      <span>Page <?= $page ?> of <?= $total_pages ?></span>&nbsp;&nbsp;
      <?php if ($page < $total_pages): ?>
        <a href="Merge_Owners.php?page=<?= $page + 1 ?>" class="pagination-link">>></a>&nbsp;&nbsp;
        <a href="Merge_Owners.php?page=<?= $total_pages ?>" class="pagination-link">Last</a>
      <?php endif; ?>
    </div>

    <!-- Merge Button -->
    <div class="btn-container text-right mt-4">
      <button type="submit" id="mergeOwnersButton" class="btn btn-custom">Merge Owners</button>
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
    <div class="table-title">Choose Owner to Retain</div>
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

  <!-- Hidden Fields for selectedPersonID and selectedProperties -->
  <input type="hidden" name="selectedPersonID" id="selectedPersonID">
  <input type="hidden" name="selectedProperties" id="selectedProperties">

  <div class="modal-footer">
    <button type="submit" name="retainOwnersButton" class="btn btn-custom">Retain Owners</button>
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
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/main_layout.js"></script>
  <script src="http://localhost/ERPTS/Merge_Owners.js"></script>
  <script src="http://localhost/ERPTS/Own_list.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>