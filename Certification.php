<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; // Include your database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">  
  <link rel="stylesheet" href="certi.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
   <?php include 'header.php'; ?>

  <!-- Main Body -->
  <main class="container my-5">
  <!-- Back Button (Positioned to the top left) -->
  <div class="mb-4 d-flex justify-content-start">
    <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>

  <!-- Location Title -->
  <div class="text-center mb-5">
    <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Certification</h2>
  </div>

  <!-- Property Categories Table Section -->
  <div class="card border-0 shadow p-4 rounded-3 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="section-title mb-0">Land Category Information</h5>
  <div class="d-flex align-items-center custom-spacing"> <!-- Added custom class -->
    
    <!-- Add Button -->
    <button class="btn btn-sm btn-primary" onclick="prepareAddModal()" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="fas fa-plus"></i> Add
    </button>

    <!-- Search Bar -->
    <div class="input-group" style="width: 250px;">
      <input type="text" class="form-control border-start-0" id="tableSearch" placeholder="Search...">
      <span class="input-group-text bg-transparent border-end-0">
        <i class="fas fa-search"></i>
      </span>
    </div>
  </div>
</div>



    <div class="px-3">
      <div class="table-responsive rounded">
        <!-- Classification Table -->
        <table class="table table-hover align-middle mb-0" id="classificationTable">
          <thead class="table-light">
            <tr>
              <th style="width: 15%">ID</th>
              <th style="width: 40%">Name</th>
              <th style="width: 15%">Position</th>
              <th style="width: 15%">Status</th>
              <th style="width: 15%">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data -->
            <tr>
            <td class="id">001</td>
            <td class="name">Description Sample No. 1 Test </td>
            <td class="position">Position?</td>
            <td class="status">
            <span class="badge bg-success-subtle text-success">Active</span>
            </td>
            <td>
            <button class="btn btn-sm btn-outline-primary me-1 edit-btn" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="fas fa-edit"></i>
            </button>
        <button class="btn btn-sm btn-outline-danger delete-btn" 
                data-id="001" title="Delete">
          <i class="fas fa-trash-alt"></i>
        </button>
            </td>
        </tr>
          </tbody>
        </table>
         <div id="classificationPagination" class="mt-3 d-flex justify-content-start"></div>
      </div>
    </div>
  </div>

<!--Modal Section--> 
<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="max-width: 30%; width: 30%; height: 30%; max-height: 30%;"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Add New Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
        <!-- Form inside the Modal -->
        <form id="addForm">
          <div class="mb-3">
            <label for="inputID" class="form-label">ID</label>
            <input type="text" class="form-control" id="inputID" placeholder="Enter ID">
          </div>
          
          <div class="mb-3">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" placeholder="Enter Name">
          </div>

          <div class="mb-3">
            <label for="inputPosition" class="form-label">Position</label>
            <input type="text" class="form-control" id="inputPosition" placeholder="Enter Position">
          </div>

          <div class="mb-3">
            <label for="inputStatus" class="form-label" style="font-size: 14px; font-weight: bold; color: #495057;">Status</label>
            <select class="form-control" id="inputStatus" style="padding: 8px 12px; font-size: 14px; border-radius: 5px; border: 1px solid #ced4da; background-color: #f8f9fa; color: #495057;">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 30%; width: 30%; height: 30%; max-height: 30%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="max-height: 75vh; overflow-y: auto;"> 
        <form id="editForm" style="padding: 5%;">
          <div class="mb-3">
            <label for="editName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editName" placeholder="Enter name">
          </div>
          <div class="mb-3">
            <label for="editPosition" class="form-label">Position</label>
            <input type="text" class="form-control" id="editPosition" placeholder="Enter position">
          </div>
                <div class="mb-3">
        <label for="inputStatus" class="form-label" style="font-size: 14px; font-weight: bold; color: #495057;">Status</label>
        <select class="form-control" id="inputStatus" style="padding: 8px 12px; font-size: 14px; border-radius: 5px; border: 1px solid #ced4da; background-color: #f8f9fa; color: #495057;">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveEditEntry()">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this record? <strong>This action cannot be undone.</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

</main>

  
  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="Property.js"></script>

  <script>
// Add event listener to each edit button
document.querySelectorAll('.edit-btn').forEach(button => {
  button.addEventListener('click', function() {
    // Get the row that the button is in
    const row = this.closest('tr');
    
    // Get the data from the row
    const id = row.querySelector('.id').textContent;
    const name = row.querySelector('.name').textContent;
    const position = row.querySelector('.position').textContent;
    const status = row.querySelector('.status').textContent.trim();

    // Call the function to populate the modal with the extracted data
    loadEditModalData(id, name, position, status);
  });
});

// Function to load the data into the modal form
function loadEditModalData(id, name, position, status) {
  // Set the form values based on the data passed from the row
  document.getElementById('editName').value = name;
  document.getElementById('editPosition').value = position;
  document.getElementById('inputStatus').value = status;
  
  // Store the ID in the form to identify the record
  document.getElementById('editForm').setAttribute('data-id', id);
}

// Function to handle saving the edited entry
function saveEditEntry() {
  const id = document.getElementById('editForm').getAttribute('data-id');
  const name = document.getElementById('editName').value;
  const position = document.getElementById('editPosition').value;
  const status = document.getElementById('inputStatus').value;

  // Handle saving the data (you can send it to the server or update the table here)
  console.log('Saving changes for ID:', id);
  console.log('Name:', name, 'Position:', position, 'Status:', status);

  // Close the modal after saving
  $('#editModal').modal('hide');
}
  </script>

  <script>
    //Delete Confirmation
document.addEventListener("DOMContentLoaded", () => {
  let activeRow = null;
  const modalEl = document.getElementById("deleteModal");
  const modal = new bootstrap.Modal(modalEl);

  // On Click
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".delete-btn");
    if (!btn) return;
    activeRow = btn.closest("tr");
    modal.show();
  });

  // Confirm Delete
  document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    if (activeRow) {
      activeRow.remove(); 
      modal.hide();

    }
  });
});
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
  paginateTable("classificationTable", "classificationPagination", 5); 
});

function paginateTable(tableId, paginationId, rowsPerPage = 10) {
  const table = document.getElementById(tableId);
  const tbody = table.querySelector("tbody");
  const rows = Array.from(tbody.querySelectorAll("tr"));
  const pagination = document.getElementById(paginationId);

  let currentPage = 1;
  const totalPages = Math.ceil(rows.length / rowsPerPage);

  function renderTable() {
    rows.forEach((row, index) => {
      row.style.display = "none";
      if (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) {
        row.style.display = "";
      }
    });
  }

  function renderPagination() {
    pagination.innerHTML = "";

    // Previous button
    const prevBtn = document.createElement("button");
    prevBtn.innerHTML = "&laquo;";
    prevBtn.className = "btn btn-sm btn-outline-success me-2";
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        renderTable();
        renderPagination();
      }
    });
    pagination.appendChild(prevBtn);

    // Page indicator
    const pageIndicator = document.createElement("span");
    pageIndicator.className = "fw-semibold";
    pageIndicator.innerText = `Page ${currentPage} of ${totalPages}`;
    pagination.appendChild(pageIndicator);

    // Next button
    const nextBtn = document.createElement("button");
    nextBtn.innerHTML = "&raquo;";
    nextBtn.className = "btn btn-sm btn-outline-success ms-2";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener("click", () => {
      if (currentPage < totalPages) {
        currentPage++;
        renderTable();
        renderPagination();
      }
    });
    pagination.appendChild(nextBtn);
  }

  // Initialize
  renderTable();
  renderPagination();
}

</script>
</body>

</html>