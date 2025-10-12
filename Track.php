<?php
session_start();
require_once "database.php";
$conn = Database::getInstance();

// Check if the user is logged in by verifying if 'user_id' exists in the session
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php"); // Redirect to login page if user is not logged in
  exit; // Stop further execution after redirection
}

// Prevent the browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Pagination Setup
$limit = 5; // rows
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total records
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM transactions");
$totalCount = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalCount / $limit);

// Get counts by status
$inProgressResult = $conn->query("SELECT COUNT(*) AS total FROM transactions WHERE status='In Progress'");
$inProgressCount = $inProgressResult->fetch_assoc()['total'];

$completedResult = $conn->query("SELECT COUNT(*) AS total FROM transactions WHERE status='Completed'");
$completedCount = $completedResult->fetch_assoc()['total'];

// Fetch paginated transactions (include transaction_type now)
$sql = "SELECT transaction_id, transaction_code, name, contact_number, description, transaction_type, status 
        FROM transactions ORDER BY transaction_id DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Table Rows
$transactionRows = "";
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $statusClass = strtolower(str_replace(' ', '-', $row['status'])); // e.g., "In Progress" → "in-progress"
    $transaction_type = htmlspecialchars($row['transaction_type'] ?? '', ENT_QUOTES);

    $transactionRows .= "
<tr>
  <td>{$row['transaction_code']}</td>
  <td>{$row['name']}</td>
  <td>{$row['contact_number']}</td>
  <td>{$row['description']}</td>
  <td>{$transaction_type}</td>
  <td><span class='status-badge status-{$statusClass}'>{$row['status']}</span></td>
<td>
  <button class='btn btn-sm btn-primary me-1 mb-1' style='padding:8px 12px; font-size:12px;' onclick='openModal(" . $row['transaction_id'] . ")'>
    <i class='fas fa-edit'></i> Edit
  </button>

  <button class='btn btn-sm btn-danger me-1 mb-1' style='padding:8px 12px; font-size:12px;' onclick='deleteTransaction(" . $row['transaction_id'] . ")'>
    <i class='fas fa-trash'></i> Delete
  </button>

  <button class='btn btn-sm btn-dark me-1 mb-1' style='padding:8px 12px; font-size:12px;' onclick='showDocuments(" . $row['transaction_id'] . ")'>
    <i class='fas fa-file-alt'></i> Documents
  </button>
</td>
<td>
  <button class='btn btn-sm " . ($row['status'] === 'Completed' ? 'btn-success' : 'btn-secondary') . " mb-1' style='padding:8px 12px; font-size:12px;' onclick='confirmTransaction(" . $row['transaction_id'] . ")' " . ($row['status'] !== 'Completed' ? 'disabled' : '') . ">
    <i class='fas fa-check'></i>
  </button>
</td>


</tr>";
  }
} else {
  $transactionRows = "<tr><td colspan='8' class='text-center'>No transactions found</td></tr>";
}

// Fetch received papers
$sql = "SELECT 
            transaction_code,
            client_name,
            contact_number,
            transaction_type,
            DATE(received_date) AS received_date,
            notes,
            received_by
        FROM received_papers
        ORDER BY received_date DESC";

$result = $conn->query($sql);

// Build table rows
$receivedRows = "";
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $receivedRows .= "
      <tr>
        <td>" . htmlspecialchars($row['transaction_code']) . "</td>
        <td>" . htmlspecialchars($row['client_name']) . "</td>
        <td>" . htmlspecialchars($row['contact_number']) . "</td>
        <td>" . htmlspecialchars($row['transaction_type']) . "</td>
        <td>" . htmlspecialchars($row['received_date']) . "</td>
        <td>" . htmlspecialchars($row['notes']) . "</td>
        <td>" . htmlspecialchars($row['received_by']) . "</td>
      </tr>";
  }
} else {
  $receivedRows = "<tr><td colspan='7' class='text-center'>No received papers found</td></tr>";
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
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Track.css">

  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!--Main Content-->
  <div class="container">
    <h1><i class="fas fa-exchange-alt"></i> Transaction Dashboard</h1>

    <!-- Back Button -->
    <div class="mb-3">
      <a href="Transaction.php" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>

    <div class="dashboard">
      <div class="card">
        <div>Total Transactions</div>
        <div id="totalCount"><?= $totalCount ?></div>
      </div>
      <div class="card">
        <div>In Progress</div>
        <div id="inProgressCount"><?= $inProgressCount ?></div>
      </div>
      <div class="card">
        <div>Completed</div>
        <div id="completedCount"><?= $completedCount ?></div>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Add Transaction Button -->
      <button class="btn btn-add" onclick="openModal()">
        <i class="fas fa-plus"></i> Add Transaction
      </button>

      <!-- Toggle Button -->
      <button id="toggleBtn" class="btn btn-primary" onclick="toggleTables()">
        <i class="fas fa-exchange-alt"></i> Show Received Table
      </button>
    </div>


    <!-- Transaction Table -->
    <div id="transactionSection">
      <table class="table table-borderless table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Transaction Code</th>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Description</th>
            <th>Transaction Type</th>
            <th>Status</th>
            <th>Actions</th>
            <th>Confirm</th>
          </tr>
        </thead>
        <tbody>
          <?= $transactionRows ?>
        </tbody>
      </table>
      <div class="d-flex justify-content-center mt-3 mb-5">
        <ul class="pagination justify-content-center my-3">

          <!-- Previous Button -->
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page - 1 ?>">&lt;</a>
            </li>
          <?php else: ?>
            <li class="page-item disabled">
              <span class="page-link">&lt;</span>
            </li>
          <?php endif; ?>

          <!-- Page Info -->
          <li class="page-item disabled">
            <span class="page-link">
              Page <?= $page ?> of <?= $totalPages ?>
            </span>
          </li>

          <!-- Next Button -->
          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page + 1 ?>">&gt;</a>
            </li>
          <?php else: ?>
            <li class="page-item disabled">
              <span class="page-link">&gt;</span>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </div>

    <!-- Received Table -->
    <div id="receivedSection" class="d-none">
      <table class="table table-borderless table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Transaction Code</th>
            <th>Client Name</th>
            <th>Contact Number</th>
            <th>Transaction Type</th>
            <th>Received Date</th>
            <th>Notes</th>
            <th>User</th>
          </tr>
        </thead>
        <tbody id="receivedTable">
          <?php echo $receivedRows; ?>
        </tbody>
      </table>

      <!-- Received Pagination -->
      <div class="d-flex justify-content-center mt-3 mb-5">
        <ul id="receivedPagination" class="pagination justify-content-center my-3"></ul>
      </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="recent-activity">
      <h3><i class="fas fa-history"></i> Transaction Log</h3>

      <!-- Filters -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Date Picker -->
        <div>
          <label for="dateFilter" class="form-label me-2">Filter by Date:</label>
          <select id="dateFilter" class="form-select">
            <option value="">All Dates</option>
          </select>
        </div>

        <!-- Search -->
        <div>
          <input type="text" id="searchInput" class="form-control" placeholder="Search transaction code...">
        </div>
      </div>

      <!-- Activity Table -->
      <div id="activityLog">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th scope="col">Date/Time</th>
              <th scope="col">Transaction Code</th>
              <th scope="col">Action</th>
              <th scope="col">Details</th>
              <th scope="col">Current User</th>
            </tr>
          </thead>
          <tbody id="activityTableBody">
            <tr id="activityLoadingRow">
              <td colspan="5" class="text-center">Loading recent activity…</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination container -->
      <div id="pagination" class="d-flex justify-content-center align-items-center my-3"></div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
          <h3 class="modal-title" id="modalTitle">
            <i class="fas fa-exchange-alt"></i> Add Transaction
          </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <input type="text" id="transactionID" name="t_code" class="form-control mb-2" placeholder="Transaction Code">

          <input type="text" id="nameInput" name="t_name" class="form-control mb-2" placeholder="Name">

          <input type="tel" id="contactInput" class="form-control" placeholder="Enter mobile number" maxlength="13"
            value="+63">

          <input type="text" id="transactionInput" name="t_description" class="form-control mb-2"
            placeholder="Transaction Description">

          <!-- Transaction Type Select - Add the onchange event -->
          <select id="transactionType" name="transactionType" class="form-select mb-2" required
            onchange="handleTransactionTypeChange(); showRequirements();">
            <option value="" disabled selected hidden>Select Transaction</option>
            <option value="Simple Transfer of Ownership">Simple Transfer of Ownership</option>
            <option value="New Declaration of Real Property">New Declaration of Real Property</option>
            <option value="Revision/Correction">Revision/Correction of Real Properties</option>
            <option value="Consolidation">Consolidation of Real Properties</option>
          </select>

          <!-- Checklist container -->
          <div id="requirementsText" class="alert alert-info mt-2" style="display:none; white-space:pre-line;"></div>

          <!-- Status Input - Add the onchange event -->
          <select id="statusInput" name="t_status" class="form-select mb-2" required onchange="handleStatusChange()">
            <option value="" disabled selected hidden>Select Status</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
          </select>

          <!-- Upload Image Input -->
          <div class="mb-2 d-flex align-items-center">
            <div class="flex-grow-1">
              <label for="fileUpload" class="form-label">Upload Image</label>
              <input type="file" class="form-control" id="fileUpload" name="t_file[]" multiple>
            </div>
            <button type="button" class="btn btn-info ms-2 mt-4" id="generateQrBtn"
              title="Generate QR for phone upload">
              <i class="fas fa-qrcode"></i>
            </button>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <div class="d-flex w-100 gap-2">
            <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">
              <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-success w-50" onclick="saveTransaction()">
              <i class="fas fa-save"></i> Save
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal for Images -->
  <div class="modal fade" id="documentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-file-image"></i> Transaction Documents</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="documentsList">
          <!-- images will load here -->
        </div>
      </div>
    </div>
  </div>

  <!-- Document Viewer Modal -->
  <div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="documentPreviewModalLabel" class="modal-title">Preview</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <!-- Preview content inserted dynamically -->
        </div>
      </div>
    </div>
  </div>

  <!-- Confirm Modal -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-success" id="confirmModalLabel">Confirm Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          Are you sure you want to confirm this transaction?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success" id="confirmBtn">Yes, Confirm</button>
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
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="track.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script for Transaction Table Pagination -->
  <script>
    function initReceivedPagination() {
      const rowsPerPage = 10;
      const table = document.getElementById("receivedTable");
      const pagination = document.getElementById("receivedPagination");

      if (!table || !pagination) {
        console.error("Received table or pagination element not found");
        return;
      }

      // Get all tr elements, but exclude any with colspan (like "no data" rows)
      const rows = Array.from(table.querySelectorAll("tr")).filter(row => {
        const firstCell = row.querySelector("td");
        return firstCell && !firstCell.hasAttribute("colspan");
      });

      const totalRows = rows.length;

      // If no rows or only one page, hide pagination
      if (totalRows === 0) {
        pagination.innerHTML = "";
        return;
      }

      const totalPages = Math.ceil(totalRows / rowsPerPage);

      let currentPage = 1;

      function renderTable() {
        rows.forEach((row, index) => {
          row.style.display =
            index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage
              ? ""
              : "none";
        });
      }

      function renderPagination() {
        pagination.innerHTML = "";

        // Previous Button
        const prev = document.createElement("li");
        prev.className = "page-item " + (currentPage === 1 ? "disabled" : "");
        prev.innerHTML = `<a class="page-link" href="#">&lt;</a>`;
        prev.addEventListener("click", (e) => {
          e.preventDefault();
          if (currentPage > 1) {
            currentPage--;
            update();
          }
        });
        pagination.appendChild(prev);

        // Page Info
        const info = document.createElement("li");
        info.className = "page-item disabled";
        info.innerHTML = `<span class="page-link">Page ${currentPage} of ${totalPages}</span>`;
        pagination.appendChild(info);

        // Next Button
        const next = document.createElement("li");
        next.className = "page-item " + (currentPage === totalPages ? "disabled" : "");
        next.innerHTML = `<a class="page-link" href="#">&gt;</a>`;
        next.addEventListener("click", (e) => {
          e.preventDefault();
          if (currentPage < totalPages) {
            currentPage++;
            update();
          }
        });
        pagination.appendChild(next);
      }

      function update() {
        renderTable();
        renderPagination();
      }

      update();
    }
  </script>

  <script>
    // Toggle between Transaction and Received tables
    function toggleTables() {
      const transactionSection = document.getElementById("transactionSection");
      const receivedSection = document.getElementById("receivedSection");
      const toggleBtn = document.getElementById("toggleBtn");
      const addBtn = document.querySelector(".btn-add");

      // Toggle table visibility
      transactionSection.classList.toggle("d-none");
      receivedSection.classList.toggle("d-none");

      // Update button visibility and state
      if (receivedSection.classList.contains("d-none")) {
        toggleBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Show Received Table';
        addBtn.classList.remove("invisible-btn"); // make visible again
        addBtn.disabled = false; // re-enable
      } else {
        toggleBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Show Transaction Table';
        addBtn.classList.add("invisible-btn"); // hide visually but keep space
        addBtn.disabled = true; // disable click
        initReceivedPagination();
      }
    }


  </script>
</body>

</html>