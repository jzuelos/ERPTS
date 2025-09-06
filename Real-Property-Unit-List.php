<?php
session_start();  //Start session at the top to access session variables

// Check if the user is logged in by verifying if 'user_id' exists in the session
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php"); // Redirect to login page if user is not logged in
  exit; // Stop further execution after redirection
}

$user_role = $_SESSION['user_type'] ?? 'user'; // Default to 'user' if role is not set

// Prevent the browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // Prevent caching
header("Cache-Control: post-check=0, pre-check=0", false); // Additional no-cache headers
header("Pragma: no-cache"); // Older cache control header for HTTP/1.0 compatibility

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch property units along with their owners, sorted by latest ID first
// Logic used:
// p_info.p_id -> faas.pro_id
// faas.propertyowner_id is a JSON array of pO_id values (e.g. [62,63])
// propertyowner.pO_id matches those values and contains owner_id -> owners_tb.own_id
$sql = "
SELECT 
  p.p_id,
  p.house_no,
  p.block_no,
  p.barangay,
  p.province,
  p.city,
  p.district,
  p.land_area,
  p.is_active,
  GROUP_CONCAT(DISTINCT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname) SEPARATOR ' / ') AS owner
FROM p_info p
LEFT JOIN faas f ON f.pro_id = p.p_id
LEFT JOIN propertyowner po 
  ON f.propertyowner_id IS NOT NULL
     AND f.propertyowner_id <> '[]'
     AND FIND_IN_SET(
           CAST(po.pO_id AS CHAR),
           REPLACE(REPLACE(REPLACE(REPLACE(f.propertyowner_id, '[', ''), ']', ''), '\"', ''), ' ', '')
         ) > 0
LEFT JOIN owners_tb o ON o.own_id = po.owner_id
GROUP BY p.p_id
ORDER BY p.p_id DESC
";

$propertyUnits = [];
$result = $conn->query($sql);

if ($result) {
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Ensure owner key exists (so your existing HTML that uses $unit['owner'] works)
      $row['owner'] = isset($row['owner']) ? $row['owner'] : '';
      $propertyUnits[] = $row;
    }
  } else {
    // No rows found in the SELECT — return an empty array (don't echo raw text into page)
    $propertyUnits = [];
  }
} else {
  // SQL error — helpful for debugging but you can remove or log instead in production
  echo "SQL error: " . $conn->error;
  $propertyUnits = [];
}

// Fetch barangay options for the dropdown
$barangayOptions = '';
$barangayQuery = "SELECT brgy_id, brgy_name FROM brgy";
$barangayResult = $conn->query($barangayQuery);

if ($barangayResult && $barangayResult->num_rows > 0) {
  while ($barangayRow = $barangayResult->fetch_assoc()) {
    $barangayOptions .= '<option value="' . $barangayRow['brgy_id'] . '">' . $barangayRow['brgy_name'] . '</option>';
  }
} else {
  $barangayOptions = '<option value="">No Barangays Available</option>';
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Real-Property-Unit-List.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <section class="container mt-5">
    <div class="mb-4 d-flex justify-content-start">
      <a href="Home.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
    <div class="card p-4">
      <h3 class="mb-4">Real Property Units List</h3>
      <div class="form-row mb-4">
        <div class="row mb-4 align-items-center">
          <!-- search input: medium width on md+, full width on xs -->
          <div class="col-12 col-md-6 col-lg-5 mb-2 mb-md-0">
            <input type="text" class="form-control" id="searchInput" placeholder="Search" onkeyup="filterTable()">
          </div>

          <!-- barangay dropdown -->
          <div class="col-8 col-sm-6 col-md-3 col-lg-2 mb-2 mb-md-0">
            <select class="form-select" id="barangayDropdown" name="barangay">
              <option selected value="">All Barangay</option>
              <?php echo $barangayOptions; ?>
            </select>
          </div>

          <!-- action buttons -->
          <div class="col-12 col-md-3 col-lg-5 text-md-end">
            <button type="button" class="btn btn-success me-2" onclick="filterTable()">Search</button>
            <a href="Add-New-Real-Property-Unit.php" class="btn btn-success">Add new RPU</a>
          </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-bordered text-center modern-table" id="propertyTable"> <!-- Responsive table -->
            <thead>
              <tr>
                <th>OD ID</th>
                <th>Owner</th>
                <th>Location <br><small>(Street, Barangay, City, Province)</small></th>
                <th>Land Area</th>
                <th>Edit</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              <?php foreach ($propertyUnits as $unit):
                $ownerRaw = isset($unit['owner']) ? $unit['owner'] : '';
                $owner = trim((string) $ownerRaw) !== '' ? $ownerRaw : 'None';
                $rowClass = ($unit['is_active'] == 0) ? 'table-secondary' : ''; // highlight inactive
                ?>
                <tr class="<?= $rowClass ?>">
                  <td><?= htmlspecialchars($unit['p_id']) ?></td>
                  <td><?= htmlspecialchars($owner) ?></td>
                  <td>
                    <?= htmlspecialchars("{$unit['house_no']}, {$unit['barangay']}, {$unit['city']}, {$unit['province']}") ?>
                  </td>
                  <td><?= htmlspecialchars($unit['land_area']) ?></td>
                  <td><a href="FAAS.php?id=<?= htmlspecialchars($unit['p_id']) ?>" class="btn btn-primary">EDIT</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination Controls -->
        <div class="pagination-controls mt-3">
          <label for="pageSelect">Page: </label>
          <select id="pageSelect" onchange="changePage()"></select>
        </div>

        <!-- View All Button -->
        <div class="view-all-container d-flex mt-3">
          <div class="ml-auto">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewAllModal">View
              All</button>
          </div>
        </div>
      </div>
  </section>

  <!-- View All Modal -->
  <div class="modal fade" id="viewAllModal" tabindex="-1" aria-labelledby="viewAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewAllModalLabel">All Properties</h5>
        </div>
        <div class="modal-body">
          <!-- Search Bar inside the modal -->
          <div class="mb-3 d-flex">
            <input type="text" class="form-control" id="modalSearchInput" placeholder="Search properties..."
              style="width: 50%; margin-right: 10px;" onkeyup="handleEnter(event)">
            <button class="btn btn-primary" onclick="viewAllSearch()">Search</button>
          </div>

          <!-- Modal Table -->
          <div class="table-responsive">
            <table class="table table-bordered text-center modern-table" id="modalPropertyTable">
              <thead>
                <tr>
                  <th>OD ID</th>
                  <th>Owner</th>
                  <th>Location <br><small>(Barangay, City, Province)</small></th>
                  <th>Land Area</th>
                </tr>
              </thead>
              <tbody id="modalTableBody">
                <?php
                // Display the fetched data in modal table rows
                foreach ($propertyUnits as $unit) {
                  $ownerRaw = isset($unit['owner']) ? $unit['owner'] : '';
                  $owner = trim((string) $ownerRaw) !== '' ? $ownerRaw : 'None';

                  echo "<tr>
                    <td>" . htmlspecialchars($unit['p_id']) . "</td>
                    <td>" . htmlspecialchars($owner) . "</td>
                    <td>" . htmlspecialchars($unit['house_no'] . ', ' . $unit['barangay'] . ', ' . $unit['city'] . ', ' . $unit['province']) . "</td>
                    <td>" . htmlspecialchars($unit['land_area']) . "</td>
                  </tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetModal()">Close</button>
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
  <script>
    let currentPage = 1;
    const rowsPerPage = 5;

    // Function to initialize the table and pagination
    function initializeTable() {
      const tableRows = document.querySelectorAll("#tableBody tr");
      const totalRows = tableRows.length;

      const totalPages = Math.ceil(totalRows / rowsPerPage);


      const pageSelect = document.getElementById("pageSelect");
      pageSelect.innerHTML = '';
      for (let i = 1; i <= totalPages; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        pageSelect.appendChild(option);
      }

      displayRowsForPage(currentPage);

      pageSelect.value = currentPage;
    }


    function displayRowsForPage(page) {
      const tableRows = document.querySelectorAll("#tableBody tr");
      const totalRows = tableRows.length;
      const startIndex = (page - 1) * rowsPerPage;
      const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

      tableRows.forEach((row, index) => {
        row.style.display = "none";
      });


      for (let i = startIndex; i < endIndex; i++) {
        tableRows[i].style.display = "";
      }
    }

    function changePage() {
      const pageSelect = document.getElementById("pageSelect");
      currentPage = parseInt(pageSelect.value);
      displayRowsForPage(currentPage);
    }

    document.addEventListener("DOMContentLoaded", initializeTable);

  </script>
  <script>
    // Function to reset the modal when the close button is clicked
    function resetModal() {
      document.getElementById("modalSearchInput").value = "";
      resetTable();
    }


    function resetTable() {
      var tableBody = document.getElementById("modalTableBody");

      tableBody.innerHTML = '';
      var propertyUnits = <?php echo json_encode($propertyUnits); ?>;

      propertyUnits.forEach(function (unit) {
        var row = `<tr>
                <td>${unit.p_id}</td>
                <td>${unit.owner}</td>
                <td>${unit.house_no}, ${unit.barangay}, ${unit.city}, ${unit.province}</td>
                <td>${unit.land_area}</td>
               </tr>`;
        tableBody.innerHTML += row;
      });

    }


    function viewAllSearch() {
      var searchQuery = document.getElementById("modalSearchInput").value.toLowerCase();
      var tableRows = document.getElementById("modalTableBody").getElementsByTagName("tr");

      Array.from(tableRows).forEach(function (row) {
        var cells = row.getElementsByTagName("td");
        var matchFound = false;

        Array.from(cells).forEach(function (cell) {
          if (cell.innerText.toLowerCase().includes(searchQuery)) {
            matchFound = true;
          }
        });
        row.style.display = matchFound ? "" : "none";
      });
    }

  </script>
  <script src="http://localhost/ERPTS/Real-Property-Unit-List.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>