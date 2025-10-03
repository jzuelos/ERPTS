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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">

  <title>Activity Log</title>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>


<main class="flex-grow-1 d-flex justify-content-center">
  <div class="container my-4" style="max-width: 1100px;">

        <!-- Back Button -->
    <div class="mb-3 d-flex justify-content-start">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>

         <div class="text-center mb-5">
    <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Sheet Modification</h2>
  </div>
<!-- Preview Box -->
<div class="border rounded p-4 mb-4" style="border-radius: 30px;">
  <h6 class="mb-4 text-center">Preview: Tax Declaration</h6>

  <div class="d-flex justify-content-between">
    <!-- Verified By (Left Side) -->
    <div>
      <span style="font-weight:bold;">Verified By:</span>
      <span style="font-weight:bold;"><u>MA. SALOME A. BERTILLO</u></span>
    </div>

    <!-- Approved By (Right Side) -->
    <div style="text-align: center;">
      <div style="text-align: left; font-weight:bold;">Approved By:</div>
      <u>MAXIMO P. MAGANA, JR., REA</u><br>
      <div style="margin-top: 2px; text-align: center;">
        Provincial Assessor
      </div>
    </div>
  </div>
</div>



    <!-- Dropdown + Table Section -->
    <div class="border rounded p-4">
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-4 border-end">
          <p><strong>Provincial Assessor:</strong></p>
          <select class="form-control mb-3">
            <option>Select Assessor</option>
            <option>Assessor 1</option>
            <option>Assessor 2</option>
          </select>

          <p><strong>Verified By:</strong></p>
          <select class="form-control">
            <option>Select Verifier</option>
            <option>Verifier 1</option>
            <option>Verifier 2</option>
          </select>
        </div>

        <!-- Right Column (Table) -->
        <div class="col-md-8">
          <div class="table-responsive rounded">
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
                <tr>
                  <td class="id">001</td>
                  <td class="name">Description Sample No. 1 Test</td>
                  <td class="position">Position?</td>
                  <td class="status">
                    <span class="badge bg-success-subtle text-success">Active</span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary me-1 edit-btn" 
                            title="Edit" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-row-btn" 
                            data-id="001" 
                            title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div id="classificationPagination" class="mt-3 d-flex justify-content-start"></div> <!-- Lagyan ko nalang kapag may php na --> 
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId" name="id">
          
          <div class="mb-3">
            <label for="editName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editName" name="name" required>
          </div>
          
          <div class="mb-3">
            <label for="editPosition" class="form-label">Position</label>
            <input type="text" class="form-control" id="editPosition" name="position">
          </div>
          
          <div class="mb-3">
            <label for="editStatus" class="form-label">Status</label>
            <select class="form-select" id="editStatus" name="status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="deleteMessage">Are you sure you want to delete this record?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>


  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="Admin-Modify.js"></script>
</body>

</html>
