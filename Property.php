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
  <link rel="stylesheet" href="Location.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top" alt="">
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
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <!-- Dropdown menu -->
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="Real-Property-Unit-List.php">FAAS</a>
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
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Land</h2>
    </div>

    <!-- Property Categories Table Section -->
    <div class="card border-0 shadow p-4 rounded-3 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="section-title mb-0">Land Category Information</h5>
        <div class="d-flex align-items-center">
          <!-- Search Bar -->
          <div class="input-group me-4" style="width: 250px;">
            <input type="text" class="form-control border-start-0" id="tableSearch" placeholder="Search...">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-search"></i>
            </span>
          </div>

          <!-- Dropdown -->
          <div class="dropdown ml-5">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="categoryTypeDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Classification
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoryTypeDropdown">
              <li><a class="dropdown-item" href="#" onclick="changeCategoryType('Classification')">Classification</a></li>
              <li><a class="dropdown-item" href="#" onclick="changeCategoryType('ActualUses')">Actual Uses</a></li>
              <li><a class="dropdown-item" href="#" onclick="changeCategoryType('SubClasses')">Sub-Classes</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="px-3">
        <div class="table-responsive rounded">
          <!-- Classification Table -->
          <table class="table table-hover align-middle mb-0" id="classificationTable">
            <thead class="table-light">
              <tr>
                <th style="width: 15%">Code</th>
                <th style="width: 40%">Description</th>
                <th style="width: 15%">Assessment Level</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM classification";
              $result = mysqli_query($conn, $query);

              while ($row = mysqli_fetch_assoc($result)) {
                $statusBadge = ($row['c_status'] === 'Active')
                  ? '<span class="badge bg-success-subtle text-success">Active</span>'
                  : '<span class="badge bg-danger-subtle text-danger">Inactive</span>';

                echo "<tr>
              <td>{$row['c_code']}</td>
              <td>{$row['c_description']}</td>
              <td>{$row['c_uv']}%</td>
              <td>{$statusBadge}</td>
              <td>
                <button class='btn btn-sm btn-outline-primary me-1 edit-btn'
                data-table='classification'
                data-code='{$row['c_code']}'
                data-description='{$row['c_description']}'
                data-assessment='{$row['c_uv']}'
                data-status='{$row['c_status']}'
                data-bs-toggle='modal'
                data-bs-target='#editClassificationModal' title='Edit'>
                  <i class='fas fa-edit'></i>
                </button>
                <button class='btn btn-sm btn-outline-danger' title='Delete'>
                  <i class='fas fa-trash-alt'></i>
                </button>
              </td>
            </tr>";
              }
              ?>
            </tbody>

          </table>

          <!-- Actual Uses Table -->
          <table class="table table-hover align-middle mb-0 d-none" id="actualUsesTable">
            <thead class="table-light">
              <tr>
                <th style="width: 15%">Report Code</th>
                <th style="width: 15%">Code</th>
                <th style="width: 30%">Description</th>
                <th style="width: 15%">Assessment</th>
                <th style="width: 15%">Status</th>
                <th style="width: 10%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM land_use";
              $result = mysqli_query($conn, $query);

              while ($row = mysqli_fetch_assoc($result)) {
                $statusBadge = ($row['lu_status'] === 'Active')
                  ? '<span class="badge bg-success-subtle text-success">Active</span>'
                  : '<span class="badge bg-danger-subtle text-danger">Inactive</span>';

                echo "<tr>
              <td>{$row['report_code']}</td>
              <td>{$row['lu_code']}</td>
              <td>{$row['lu_description']}</td>
              <td>{$row['lu_al']}%</td>
              <td>{$statusBadge}</td>
              <td>
                <button class='btn btn-sm btn-outline-primary me-1 edit-btn'
                data-table='actual_uses'
                data-report-code='{$row['report_code']}'
                data-code='{$row['lu_code']}'
                data-description='{$row['lu_description']}'
                data-assessment='{$row['lu_al']}'
                data-status='{$row['lu_status']}'
                data-bs-toggle='modal'
                data-bs-target='#editActualUsesModal' title='Edit'>
                  <i class='fas fa-edit'></i>
                </button>
                <button class='btn btn-sm btn-outline-danger' title='Delete'>
                  <i class='fas fa-trash-alt'></i>
                </button>
              </td>
            </tr>";
              }
              ?>
            </tbody>
          </table>

          <!-- Sub-Classes Table -->
          <table class="table table-hover align-middle mb-0 d-none" id="subClassesTable">
            <thead class="table-light">
              <tr>
                <th style="width: 15%">Code</th>
                <th style="width: 40%">Description</th>
                <th style="width: 15%">Unit Value</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM subclass";
              $result = mysqli_query($conn, $query);

              while ($row = mysqli_fetch_assoc($result)) {
                $statusBadge = ($row['sc_status'] === 'Active')
                  ? '<span class="badge bg-success-subtle text-success">Active</span>'
                  : '<span class="badge bg-danger-subtle text-danger">Inactive</span>';

                echo "<tr>
              <td>{$row['sc_code']}</td>
              <td>{$row['sc_description']}</td>
              <td>₱" . number_format($row['sc_uv'], 2) . "</td>
              <td>{$statusBadge}</td>
              <td>
                <button class='btn btn-sm btn-outline-primary me-1 edit-btn'
                data-table='sub_classes'
                data-code='{$row['sc_code']}'
                data-description='{$row['sc_description']}'
                data-assessment='{$row['sc_uv']}'
                data-status='{$row['sc_status']}'
                data-bs-toggle='modal'
                data-bs-target='#editSubClassesModal' title='Edit'>
                  <i class='fas fa-edit'></i>
                </button>
                <button class='btn btn-sm btn-outline-danger' title='Delete'>
                  <i class='fas fa-trash-alt'></i>
                </button>
              </td>
            </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add Table -->
    <div class="py-4"></div>
    <div class="text-center mb-5">
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Add Property</h2>
    </div>

    <!-- Property Selection Options -->
    <div class="row justify-content-center">
      <!-- Classification -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Classification" data-form="classificationModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-layer-group icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Classification</h5>
          </div>
        </a>
      </div>

      <!-- Actual Uses -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Actual Uses" data-form="actUsesModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-tag icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Actual Uses</h5>
          </div>
        </a>
      </div>

      <!-- Sub-Classes -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Sub-Classes" data-form="subClassesModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-sitemap icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Sub-Classes</h5>
          </div>
        </a>
      </div>
    </div>
  </main>

  <!--Modal Section-->
<!-- Table Modal Section --> 
<!-- Edit Classification Modal -->
<div class="modal fade" id="editClassificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Classification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label for="editClassificationCode" class="form-label">Code</label>
          <input type="text" class="form-control" id="editClassificationCode" readonly>
        </div>
        <div class="mb-2">
          <label for="editClassificationDescription" class="form-label">Description</label>
          <input type="text" class="form-control" id="editClassificationDescription">
        </div>
        <div class="mb-2">
          <label for="editClassificationAssessment" class="form-label">Assessment Level (%)</label>
          <input type="number" class="form-control" id="editClassificationAssessment" min="0" max="100" step="0.01">
        </div>
        <div class="mb-2">
      <label for="editClassificationStatus" class="form-label">Status</label>
      <div class="dropdown">
        <select class="form-select" id="editClassificationStatus" aria-label="Select status">
          <option value="Active" class="status-active">
            <i class="bi bi-check-circle me-2"></i>Active
          </option>
          <option value="Inactive" class="status-inactive">
            <i class="bi bi-x-circle me-2"></i>Inactive
          </option>
        </select>
      </div>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveClassificationChanges">Save Changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Edit Actual Uses Modal -->
<div class="modal fade" id="editActualUsesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Actual Uses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label for="editReportCode" class="form-label">Report Code</label>
          <input type="text" class="form-control" id="editReportCode" readonly>
        </div>
        <div class="mb-2">
          <label for="editActualUsesCode" class="form-label">Code</label>
          <input type="text" class="form-control" id="editActualUsesCode" readonly>
        </div>
        <div class="mb-2">
          <label for="editActualUsesDescription" class="form-label">Description</label>
          <input type="text" class="form-control" id="editActualUsesDescription">
        </div>
        <div class="mb-2">
          <label for="editActualUsesAssessment" class="form-label">Assessment Level (%)</label>
          <input type="number" class="form-control" id="editActualUsesAssessment" min="0" max="100" step="0.01">
        </div>
        <div class="mb-2">
        <label for="editActualUsesStatus" class="form-label">Status</label>
        <div class="dropdown">
          <select class="form-select" id="editActualUsesStatus" aria-label="Select status">
            <option value="Active" class="status-active">
              <i class="bi bi-check-circle me-2"></i>Active
            </option>
            <option value="Inactive" class="status-inactive">
              <i class="bi bi-x-circle me-2"></i>Inactive
            </option>
          </select>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveActualUsesChanges">Save Changes</button>
      </div>
    </div>
  </div>
</div>



    <!-- Edit Sub-Classes Modal -->
<div class="modal fade" id="editSubClassesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Sub-Classes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label for="editSubClassesCode" class="form-label">Code</label>
          <input type="text" class="form-control" id="editSubClassesCode" readonly>
        </div>
        <div class="mb-2">
          <label for="editSubClassesDescription" class="form-label">Description</label>
          <input type="text" class="form-control" id="editSubClassesDescription">
        </div>
        <div class="mb-2">
          <label for="editSubClassesAssessment" class="form-label">Unit Value</label>
          <input type="number" class="form-control" id="editSubClassesAssessment" min="0" step="0.01">
        </div>
        <div class="mb-2">
        <label for="editSubClassesStatus" class="form-label">Status</label>
        <div class="dropdown">
          <select class="form-select" id="editSubClassesStatus" aria-label="Select status">
            <option value="Active" class="status-active">
              <i class="bi bi-check-circle me-2"></i>Active
            </option>
            <option value="Inactive" class="status-inactive">
              <i class="bi bi-x-circle me-2"></i>Inactive
            </option>
          </select>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveSubClassesChanges">Save Changes</button>
      </div>
    </div>
  </div>
</div>


    


<!-- Add Property Category Modal -->
  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Confirm Property Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="confirmationQuestion">Will you encode the <span id="categoryName"></span> details?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Classification Form Modal -->
  <div class="modal fade" id="classificationModal" tabindex="-1" role="dialog" aria-labelledby="classificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="classificationModalLabel">Enter Classification Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="classificationForm">
            <input type="hidden" name="form_type" value="classification">
            <div class="form-group">
              <label for="classificationCode">Code</label>
              <input type="text" class="form-control" id="classificationCode" name="c_code" placeholder="Enter Classification Code" required>
            </div>

            <div class="form-group">
              <label for="classificationDescription">Description</label>
              <select class="form-control" id="classificationDescription" name="c_description" required>
                <option value="" selected disabled>Select Classification</option>
                <option value="Residential">Residential</option>
                <option value="Agricultural">Agricultural</option>
                <option value="Commercial">Commercial</option>
                <option value="Industrial">Industrial</option>
                <option value="Mineral">Mineral</option>
                <option value="Special">Special</option>
              </select>
            </div>

            <div class="form-group">
              <label for="unitValue">Assessment Level</label>
              <div class="input-group">
                <div class="input-group-append">
                  <span class="input-group-text">%</span>
                </div>
                <input type="number" class="form-control" id="unitValue" name="c_uv" placeholder="Enter Assessment Level" min="0" step="0.01" required>
              </div>
            </div>

            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="c_status" id="classificationActive" value="Active" required checked>
                <label class="form-check-label" for="classificationActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="c_status" id="classificationInactive" value="Inactive">
                <label class="form-check-label" for="classificationInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning reset-btn">Reset</button>
          <button type="button" class="btn btn-primary submit-btn">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Actual Uses Form Modal -->
  <div class="modal fade" id="actUsesModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportModalLabel">Actual Uses</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="reportForm">
            <input type="hidden" name="form_type" value="land_use">
            <div class="form-group">
              <label for="reportCode">Report Code</label>
              <select class="form-control" id="reportCode" required>
                <option value="" selected disabled>Select Report Code</option>
                <option value="SC">Scientific (SC)</option>
                <!-- Add more report codes here if needed -->
              </select>
            </div>
            <div class="form-group">
              <label for="reportCodeValue">Code</label>
              <input type="text" class="form-control" id="reportCodeValue" placeholder="Enter Code" required>
            </div>
            <div class="form-group">
              <label for="reportDescription">Description</label>
              <input type="text" class="form-control" id="reportDescription" placeholder="Enter Description" required>
            </div>
            <div class="form-group">
              <label for="reportAssessmentLevel">Assessment Level (%)</label>
              <input type="number" class="form-control" id="reportAssessmentLevel"
                placeholder="Enter Assessment Level" min="0" max="100" step="0.01" required>
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportStatus" id="reportActive" value="Active" required checked>
                <label class="form-check-label" for="reportActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportStatus" id="reportInactive" value="Inactive">
                <label class="form-check-label" for="reportInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning reset-btn">Reset</button>
          <button type="button" class="btn btn-primary submit-btn">Submit</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Sub-Classes Modal -->
  <div class="modal fade" id="subClassesModal" tabindex="-1" role="dialog" aria-labelledby="subClassesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="subClassesModalLabel">Sub-Classes Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="subClassesForm">
            <div class="form-group">
              <label for="subClassesCode">Code</label>
              <input type="text" class="form-control" id="subClassesCode" placeholder="Enter Code" required>
            </div>
            <div class="form-group">
              <label for="subClassesDescription">Description</label>
              <input type="text" class="form-control" id="subClassesDescription" placeholder="Enter Description" required>
            </div>
            <div class="form-group">
              <label for="unitValue">Unit Value</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">₱</span>
                </div>
                <input type="number" class="form-control" id="SunitValue" name="sc_uv" placeholder="Enter Unit Value" min="0" step="0.01" required>
              </div>
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="subClassesStatus" id="subClassesActive" value="Active" required checked>
                <label class="form-check-label" for="subClassesActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="subClassesStatus" id="subClassesInactive" value="Inactive">
                <label class="form-check-label" for="subClassesInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning reset-btn">Reset</button>
          <button type="button" class="btn btn-primary submit-btn">Submit</button>
        </div>
      </div>
    </div>
  </div>



  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="Property.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let selectedForm = "";


      document.querySelectorAll(".location-card").forEach((card) => {
        card.addEventListener("click", function(event) {
          event.preventDefault();


          const categoryName = this.getAttribute("data-name");
          selectedForm = this.getAttribute("data-form");


          document.getElementById("categoryName").textContent = categoryName;


          $("#confirmationModal").modal("show");
        });
      });


      document.getElementById("confirmBtn").addEventListener("click", function() {
        $("#confirmationModal").modal("hide");
        setTimeout(() => {
          $("#" + selectedForm).modal("show");
        }, 500);
      });
    });


    document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("cancelBtn").addEventListener("click", function() {
        $("#confirmationModal").modal("hide");
      });


      document.querySelectorAll(".reset-btn").forEach((button) => {
        button.addEventListener("click", function() {
          const modal = this.closest(".modal");
          const form = modal.querySelector("form");
          if (form) form.reset();
        });
      });

      document.querySelectorAll(".submit-btn").forEach((button) => {
        button.addEventListener("click", function() {
          const modal = this.closest(".modal");
          const form = modal.querySelector("form");

          if (form && form.checkValidity()) {
            alert("Form submitted: " + form.id);
            $(modal).modal("hide");
          } else {
            form.reportValidity();
          }
        });
      });

      document.querySelectorAll(".close").forEach((button) => {
        button.addEventListener("click", function() {
          const modal = this.closest(".modal");
          $(modal).modal("hide");
        });
      });
    });

    function changeCategoryType(type) {

      document.getElementById("classificationTable").classList.add("d-none");
      document.getElementById("actualUsesTable").classList.add("d-none");
      document.getElementById("subClassesTable").classList.add("d-none");


      document.getElementById("categoryTypeDropdown").textContent = type;


      if (type === "Classification") {
        document.getElementById("classificationTable").classList.remove("d-none");
      } else if (type === "ActualUses") {
        document.getElementById("actualUsesTable").classList.remove("d-none");
      } else if (type === "SubClasses") {
        document.getElementById("subClassesTable").classList.remove("d-none");
      }
    }

    //Table Edit Functionality
// Handle edit button clicks for all modals
document.querySelectorAll('.edit-btn').forEach(button => {
  button.addEventListener('click', function () {
    const table = this.getAttribute('data-table'); // Which table's modal we're working with

    if (table === 'classification') {
      // Edit Classification Modal
      document.getElementById('editClassificationCode').value = this.getAttribute('data-code');
      document.getElementById('editClassificationDescription').value = this.getAttribute('data-description');
      document.getElementById('editClassificationAssessment').value = this.getAttribute('data-assessment');
      document.getElementById('editClassificationStatus').value = this.getAttribute('data-status');
    } else if (table === 'actual_uses') {
      // Edit Actual Uses Modal
      document.getElementById('editReportCode').value = this.getAttribute('data-reportcode');
      document.getElementById('editActualUsesCode').value = this.getAttribute('data-code');
      document.getElementById('editActualUsesDescription').value = this.getAttribute('data-description');
      document.getElementById('editActualUsesAssessment').value = this.getAttribute('data-assessment');
      document.getElementById('editActualUsesStatus').value = this.getAttribute('data-status');
    } else if (table === 'sub_classes') {
      // Edit Sub-Classes Modal
      document.getElementById('editSubClassesCode').value = this.getAttribute('data-code');
      document.getElementById('editSubClassesDescription').value = this.getAttribute('data-description');
      document.getElementById('editSubClassesAssessment').value = this.getAttribute('data-unitvalue');
      document.getElementById('editSubClassesStatus').value = this.getAttribute('data-status');
    }
  });
});

// Save Changes for Classification Table
document.getElementById('saveClassificationChanges').addEventListener('click', function () {
  const code = document.getElementById('editClassificationCode').value;
  const description = document.getElementById('editClassificationDescription').value;
  const assessment = document.getElementById('editClassificationAssessment').value;
  const status = document.getElementById('editClassificationStatus').value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_classification.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      alert("Classification updated successfully!");
      // Optionally, reload the table or update the row dynamically
    } else {
      alert("Error saving Classification changes.");
    }
  };
  xhr.send(`code=${code}&description=${description}&assessment=${assessment}&status=${status}`);
});

// Save Changes for Actual Uses Table
document.getElementById('saveActualUsesChanges').addEventListener('click', function () {
  const reportCode = document.getElementById('editReportCode').value;
  const actualUsesCode = document.getElementById('editActualUsesCode').value;
  const description = document.getElementById('editActualUsesDescription').value;
  const assessment = document.getElementById('editActualUsesAssessment').value;
  const status = document.getElementById('editActualUsesStatus').value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_actual_uses.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      alert("Actual Uses updated successfully!");
      // Optionally, reload the table or update the row dynamically
    } else {
      alert("Error saving Actual Uses changes.");
    }
  };
  xhr.send(`report_code=${reportCode}&actual_uses_code=${actualUsesCode}&description=${description}&assessment=${assessment}&status=${status}`);
});

// Save Changes for Sub-Classes Table
document.getElementById('saveSubClassesChanges').addEventListener('click', function () {
  const code = document.getElementById('editSubClassesCode').value;
  const description = document.getElementById('editSubClassesDescription').value;
  const unitValue = document.getElementById('editSubClassesAssessment').value;
  const status = document.getElementById('editSubClassesStatus').value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_sub_classes.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      alert("Sub-Class updated successfully!");
      // Optionally, reload the table or update the row dynamically
    } else {
      alert("Error saving Sub-Class changes.");
    }
  };
  xhr.send(`code=${code}&description=${description}&unit_value=${unitValue}&status=${status}`);
});


// Function to handle the "Edit" button click for all modals
document.querySelectorAll('.edit-btn').forEach(button => {
  button.addEventListener('click', function () {
    // Get the table name (classification, actual_uses, or sub_classes) from the button's data-table attribute
    const table = this.getAttribute('data-table');
    
    // Check populate the modal fields based on the table type
    if (table === 'classification') {
      // For Classification Table
      document.getElementById('editClassificationCode').value = this.getAttribute('data-code');
      document.getElementById('editClassificationDescription').value = this.getAttribute('data-description');
      document.getElementById('editClassificationAssessment').value = this.getAttribute('data-assessment');
      document.getElementById('editClassificationStatus').value = this.getAttribute('data-status');
    } else if (table === 'actual_uses') {
      // For Actual Uses Table
      document.getElementById('editReportCode').value = this.getAttribute('data-reportcode');
      document.getElementById('editActualUsesCode').value = this.getAttribute('data-code');
      document.getElementById('editActualUsesDescription').value = this.getAttribute('data-description');
      document.getElementById('editActualUsesAssessment').value = this.getAttribute('data-assessment');
      document.getElementById('editActualUsesStatus').value = this.getAttribute('data-status');
    } else if (table === 'sub_classes') {
      // For Sub-Classes Table
      document.getElementById('editSubClassesCode').value = this.getAttribute('data-code');
      document.getElementById('editSubClassesDescription').value = this.getAttribute('data-description');
      document.getElementById('editSubClassesAssessment').value = this.getAttribute('data-unitvalue');
      document.getElementById('editSubClassesStatus').value = this.getAttribute('data-status');
    }
  });
});

// Function to save changes for the Classification Table
document.getElementById('saveClassificationChanges').addEventListener('click', function () {
  // Get data from classification table
  const code = document.getElementById('editClassificationCode').value;
  const description = document.getElementById('editClassificationDescription').value;
  const assessment = document.getElementById('editClassificationAssessment').value;
  const status = document.getElementById('editClassificationStatus').value;

  // Display a success message
  alert("Successfully saved changes to Classification.");
});

// Function to save changes for the Actual Uses Table
document.getElementById('saveActualUsesChanges').addEventListener('click', function () {
  // Get the data from actual uses table
  const reportCode = document.getElementById('editReportCode').value;
  const actualUsesCode = document.getElementById('editActualUsesCode').value;
  const description = document.getElementById('editActualUsesDescription').value;
  const assessment = document.getElementById('editActualUsesAssessment').value;
  const status = document.getElementById('editActualUsesStatus').value;

  // Display a success message
  alert("Successfully saved changes to Actual Uses.");
});

// Function to save changes for the Sub-Classes Table
document.getElementById('saveSubClassesChanges').addEventListener('click', function () {
  // Get the data from sub-classes table
  const code = document.getElementById('editSubClassesCode').value;
  const description = document.getElementById('editSubClassesDescription').value;
  const unitValue = document.getElementById('editSubClassesAssessment').value;
  const status = document.getElementById('editSubClassesStatus').value;

  // Display a success message
  alert("Successfully saved changes to Sub-Classes.");
});

  </script>
</body>

</html>