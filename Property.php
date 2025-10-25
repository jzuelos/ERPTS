<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';
$conn = Database::getInstance();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Activity Logging Function (using NOW() for consistency)
function logActivity($user_id, $action)
{
  global $conn;

  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");

  if ($stmt === false) {
    error_log("Failed to prepare log statement: " . $conn->error);
    return false;
  }

  $stmt->bind_param("is", $user_id, $action);

  if ($stmt->execute()) {
    return true;
  } else {
    error_log("Failed to execute log statement: " . $stmt->error);
    return false;
  }
}

// Get user_id from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Handle AJAX updates and form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  // ========================================
  // UPDATE OPERATIONS
  // ========================================
  
  // Update Classification
  if ($action === 'update_classification') {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $assessment = $_POST['assessment'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE classification SET c_description=?, c_uv=?, c_status=? WHERE c_code=?");
    $stmt->bind_param("sdss", $description, $assessment, $status, $code);
    $result = $stmt->execute() ? "success" : "error";

    if ($result === "success") {
      $log_action = "Updated Classification\n";
      $log_action .= "• Code: $code\n";
      $log_action .= "• Description: $description\n";
      $log_action .= "• Assessment Level: $assessment%\n";
      $log_action .= "• Status: $status";
      logActivity($user_id, $log_action);
    }

    echo $result;
    $stmt->close();
    exit;
  }

  // Update Land Use (Actual Uses)
  if ($action === 'update_actual_uses') {
    $reportCode = $_POST['reportCode'];
    $code = $_POST['code'];
    $description = $_POST['description'];
    $assessment = $_POST['assessment'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE land_use SET lu_description=?, lu_al=?, lu_status=? WHERE report_code=? AND lu_code=?");
    $stmt->bind_param("sdsss", $description, $assessment, $status, $reportCode, $code);
    $result = $stmt->execute() ? "success" : "error";

    if ($result === "success") {
      $log_action = "Updated Actual Uses (Land Use)\n";
      $log_action .= "• Report Code: $reportCode\n";
      $log_action .= "• Code: $code\n";
      $log_action .= "• Description: $description\n";
      $log_action .= "• Assessment Level: $assessment%\n";
      $log_action .= "• Status: $status";
      logActivity($user_id, $log_action);
    }

    echo $result;
    $stmt->close();
    exit;
  }

  // Update Sub-Classes
  if ($action === 'update_sub_classes') {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $assessment = $_POST['assessment'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE subclass SET sc_description=?, sc_uv=?, sc_status=? WHERE sc_code=?");
    $stmt->bind_param("sdss", $description, $assessment, $status, $code);
    $result = $stmt->execute() ? "success" : "error";

    if ($result === "success") {
      $log_action = "Updated Sub-Class\n";
      $log_action .= "• Code: $code\n";
      $log_action .= "• Description: $description\n";
      $log_action .= "• Unit Value: ₱" . number_format($assessment, 2) . "\n";
      $log_action .= "• Status: $status";
      logActivity($user_id, $log_action);
    }

    echo $result;
    $stmt->close();
    exit;
  }

  // ========================================
  // ADD OPERATIONS
  // ========================================
  
  // Add Classification
  if ($action === 'add_classification') {
    $code = $_POST['c_code'];
    $description = $_POST['c_description'];
    $uv = $_POST['c_uv'];
    $status = $_POST['c_status'];

    $stmt = $conn->prepare("INSERT INTO classification (c_code, c_description, c_uv, c_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $code, $description, $uv, $status);
    
    if ($stmt->execute()) {
      $log_action = "Added New Classification\n";
      $log_action .= "• Code: $code\n";
      $log_action .= "• Description: $description\n";
      $log_action .= "• Assessment Level: $uv%\n";
      $log_action .= "• Status: $status";
      logActivity($user_id, $log_action);
      echo "success";
    } else {
      echo "error: " . $stmt->error;
    }
    $stmt->close();
    exit;
  }

  // Add Land Use (Actual Uses)
  if ($action === 'add_land_use') {
    $reportCode = $_POST['report_code'];
    $code = $_POST['lu_code'];
    $description = $_POST['lu_description'];
    $al = $_POST['lu_al'];
    $status = $_POST['lu_status'];

    $stmt = $conn->prepare("INSERT INTO land_use (report_code, lu_code, lu_description, lu_al, lu_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $reportCode, $code, $description, $al, $status);
    
    if ($stmt->execute()) {
      $log_action = "Added New Actual Use (Land Use)\n";
      $log_action .= "• Report Code: $reportCode\n";
      $log_action .= "• Code: $code\n";
      $log_action .= "• Description: $description\n";
      $log_action .= "• Assessment Level: $al%\n";
      $log_action .= "• Status: $status";
      logActivity($user_id, $log_action);
      echo "success";
    } else {
      echo "error: " . $stmt->error;
    }
    $stmt->close();
    exit;
  }

  // Add Sub-Class
  if ($action === 'add_subclass') {
    $code = $_POST['sc_code'];
    $description = $_POST['sc_description'];
    $uv = $_POST['sc_uv'];
    $status = $_POST['sc_status'];

    $stmt = $conn->prepare("INSERT INTO subclass (sc_code, sc_description, sc_uv, sc_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $code, $description, $uv, $status);
    
    if ($stmt->execute()) {
      $log_action = "Added New Sub-Class\n";
      $log_action .= "• Code: $code\n";
      $log_action .= "• Description: $description\n";
      $log_action .= "• Unit Value: ₱" . number_format($uv, 2) . "\n";
      $log_action .= "• Status: $status";
      logActivity($user_id, $log_action);
      echo "success";
    } else {
      echo "error: " . $stmt->error;
    }
    $stmt->close();
    exit;
  }

  // ========================================
  // DELETE OPERATIONS
  // ========================================
  
  // Delete Record
  if ($action === 'delete_record') {
    $id = intval($_POST['id']);
    $table = $_POST['table'];
    
    // Map table to primary key column
    $primaryKey = [
      "classification" => "c_id",
      "land_use"       => "lu_id",
      "subclass"       => "sc_id"
    ];

    if (!array_key_exists($table, $primaryKey)) {
      echo "error: Invalid table";
      exit;
    }

    $col = $primaryKey[$table];
    
    // Get record details before deletion for logging
    $details = "";
    if ($table === 'classification') {
      $stmt = $conn->prepare("SELECT c_code, c_description FROM classification WHERE c_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($row = $result->fetch_assoc()) {
        $details = "Classification: {$row['c_code']} - {$row['c_description']}";
      }
      $stmt->close();
    } elseif ($table === 'land_use') {
      $stmt = $conn->prepare("SELECT report_code, lu_code, lu_description FROM land_use WHERE lu_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($row = $result->fetch_assoc()) {
        $details = "Actual Use: {$row['report_code']}-{$row['lu_code']} - {$row['lu_description']}";
      }
      $stmt->close();
    } elseif ($table === 'subclass') {
      $stmt = $conn->prepare("SELECT sc_code, sc_description FROM subclass WHERE sc_id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($row = $result->fetch_assoc()) {
        $details = "Sub-Class: {$row['sc_code']} - {$row['sc_description']}";
      }
      $stmt->close();
    }

    // Delete the record
    $stmt = $conn->prepare("DELETE FROM $table WHERE $col = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
      $log_action = "Deleted Record\n";
      $log_action .= "• Table: $table\n";
      $log_action .= "• Details: $details";
      logActivity($user_id, $log_action);
      echo "success";
    } else {
      echo "error: " . $stmt->error;
    }
    $stmt->close();
    exit;
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Location.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <?php include 'header.php'; ?>

  <main class="container my-5">
    <div class="mb-4 d-flex justify-content-start">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>

    <div class="text-center mb-5">
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Land</h2>
    </div>

    <div class="card border-0 shadow p-4 rounded-3 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="section-title mb-0">Land Category Information</h5>
        <div class="d-flex align-items-center">
          <div class="input-group me-4" style="width: 250px;">
            <input type="text" class="form-control border-start-0" id="tableSearch" placeholder="Search...">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-search"></i>
            </span>
          </div>
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
                <button class='btn btn-sm btn-outline-danger delete-btn' 
                  data-id='{$row['c_id']}' 
                  data-table='classification' 
                  title='Delete'>
                  <i class='fas fa-trash-alt'></i>
                </button>
              </td>
            </tr>";
              }
              ?>
            </tbody>
          </table>

          <!-- Actual Uses Table -->
          <table class="table table-hover align-middle mb-0 d-none text-start" id="actualUsesTable">
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
                <button class='btn btn-sm btn-outline-danger delete-btn' 
                  data-id='{$row['lu_id']}' 
                  data-table='land_use' 
                  title='Delete'>
                  <i class='fas fa-trash-alt'></i>
                </button>
              </td>
            </tr>";
              }
              ?>
            </tbody>
          </table>

          <!-- Sub-Classes Table -->
          <table class="table table-hover align-middle mb-0 d-none text-start" id="subClassesTable">
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
                <button class='btn btn-sm btn-outline-danger delete-btn' 
                  data-id='{$row['sc_id']}' 
                  data-table='subclass' 
                  title='Delete'>
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

    <div class="py-4"></div>
    <div class="text-center mb-5">
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Add Property</h2>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal"
          data-target="#confirmationModal" data-name="Classification" data-form="classificationModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-layer-group icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Classification</h5>
          </div>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal"
          data-target="#confirmationModal" data-name="Actual Uses" data-form="actUsesModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-tag icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Actual Uses</h5>
          </div>
        </a>
      </div>

      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal"
          data-target="#confirmationModal" data-name="Sub-Classes" data-form="subClassesModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-sitemap icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Sub-Classes</h5>
          </div>
        </a>
      </div>
    </div>
  </main>

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
            <input type="text" class="form-control" id="editClassificationDescription" maxlength="100">
          </div>
          <div class="mb-2">
            <label for="editClassificationAssessment" class="form-label">Assessment Level (%)</label>
            <input type="number" class="form-control" id="editClassificationAssessment" min="0" max="100" step="0.01">
          </div>
          <div class="mb-2">
            <label for="editClassificationStatus" class="form-label">Status</label>
            <select class="form-select" id="editClassificationStatus">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
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
            <input type="text" class="form-control" id="editActualUsesDescription" maxlength="100">
          </div>
          <div class="mb-2">
            <label for="editActualUsesAssessment" class="form-label">Assessment Level (%)</label>
            <input type="number" class="form-control" id="editActualUsesAssessment" min="0" max="100" step="0.01">
          </div>
          <div class="mb-2">
            <label for="editActualUsesStatus" class="form-label">Status</label>
            <select class="form-select" id="editActualUsesStatus">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
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
            <input type="text" class="form-control" id="editSubClassesDescription" maxlength="100">
          </div>
          <div class="mb-2">
            <label for="editSubClassesAssessment" class="form-label">Unit Value</label>
            <input type="number" class="form-control" id="editSubClassesAssessment" min="0" step="0.01">
          </div>
          <div class="mb-2">
            <label for="editSubClassesStatus" class="form-label">Status</label>
            <select class="form-select" id="editSubClassesStatus">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveSubClassesChanges">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
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
  <div class="modal fade" id="classificationModal" tabindex="-1" role="dialog"
    aria-labelledby="classificationModalLabel" aria-hidden="true">
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
            <div class="form-group">
              <label for="classificationCode">Code</label>
              <input type="text" class="form-control" id="classificationCode" name="c_code"
                placeholder="Enter Classification Code" maxlength="6" required>
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
                <input type="number" class="form-control" id="unitValue" name="c_uv"
                  placeholder="Enter Assessment Level" min="0" step="0.01" required>
              </div>
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="c_status" id="classificationActive" value="Active"
                  required checked>
                <label class="form-check-label" for="classificationActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="c_status" id="classificationInactive"
                  value="Inactive">
                <label class="form-check-label" for="classificationInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning reset-btn">Reset</button>
          <button type="button" class="btn btn-primary submit-btn" data-form="classification">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Actual Uses Form Modal -->
  <div class="modal fade" id="actUsesModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel"
    aria-hidden="true">
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
            <div class="form-group">
              <label for="reportCode">Report Code</label>
              <select class="form-control" id="reportCode" name="report_code" required>
                <option value="" selected disabled>Select Report Code</option>
                <option value="SC">Scientific (SC)</option>
                <option value="RES">Residential (RES)</option>
                <option value="COM">Commercial (COM)</option>
                <option value="IND">Industrial (IND)</option>
                <option value="AGR">Agricultural (AGR)</option>
              </select>
            </div>
            <div class="form-group">
              <label for="reportCodeValue">Code</label>
              <input type="text" class="form-control" id="reportCodeValue" name="lu_code" placeholder="Enter Code" maxlength="6" required>
            </div>
            <div class="form-group">
              <label for="reportDescription">Description</label>
              <input type="text" class="form-control" id="reportDescription" name="lu_description" placeholder="Enter Description" maxlength="100" required>
            </div>
            <div class="form-group">
              <label for="reportAssessmentLevel">Assessment Level (%)</label>
              <input type="number" class="form-control" id="reportAssessmentLevel" name="lu_al" placeholder="Enter Assessment Level"
                min="0" max="100" step="0.01" required>
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="lu_status" id="reportActive" value="Active"
                  required checked>
                <label class="form-check-label" for="reportActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="lu_status" id="reportInactive" value="Inactive">
                <label class="form-check-label" for="reportInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning reset-btn">Reset</button>
          <button type="button" class="btn btn-primary submit-btn" data-form="land_use">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sub-Classes Modal -->
  <div class="modal fade" id="subClassesModal" tabindex="-1" role="dialog" aria-labelledby="subClassesModalLabel"
    aria-hidden="true">
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
              <input type="text" class="form-control" id="subClassesCode" name="sc_code" placeholder="Enter Code" maxlength="6" required>
            </div>
            <div class="form-group">
              <label for="subClassesDescription">Description</label>
              <input type="text" class="form-control" id="subClassesDescription" name="sc_description" placeholder="Enter Description"
                maxlength="100" required>
            </div>
            <div class="form-group">
              <label for="SunitValue">Unit Value</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">₱</span>
                </div>
                <input type="number" class="form-control" id="SunitValue" name="sc_uv" placeholder="Enter Unit Value"
                  min="0" step="0.01" required>
              </div>
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sc_status" id="subClassesActive"
                  value="Active" required checked>
                <label class="form-check-label" for="subClassesActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sc_status" id="subClassesInactive"
                  value="Inactive">
                <label class="form-check-label" for="subClassesInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning reset-btn">Reset</button>
          <button type="button" class="btn btn-primary submit-btn" data-form="subclass">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let selectedForm = "";

      // Handle location card click
      document.querySelectorAll(".location-card").forEach((card) => {
        card.addEventListener("click", function(event) {
          event.preventDefault();
          const categoryName = this.getAttribute("data-name");
          selectedForm = this.getAttribute("data-form");
          document.getElementById("categoryName").textContent = categoryName;
          $("#confirmationModal").modal("show");
        });
      });

      // Confirm selection
      document.getElementById("confirmBtn").addEventListener("click", function() {
        $("#confirmationModal").modal("hide");
        setTimeout(() => {
          $("#" + selectedForm).modal("show");
        }, 500);
      });

      // Cancel button
      document.getElementById("cancelBtn").addEventListener("click", function() {
        $("#confirmationModal").modal("hide");
      });

      // Reset button
      document.querySelectorAll(".reset-btn").forEach((button) => {
        button.addEventListener("click", function() {
          const modal = this.closest(".modal");
          const form = modal.querySelector("form");
          if (form) form.reset();
        });
      });

      // Submit button for adding new records
      document.querySelectorAll(".submit-btn").forEach((button) => {
        button.addEventListener("click", function() {
          const formType = this.getAttribute("data-form");
          const modal = this.closest(".modal");
          const form = modal.querySelector("form");
          
          if (form && form.checkValidity()) {
            const formData = new FormData(form);
            
            let action = "";
            if (formType === "classification") {
              action = "add_classification";
            } else if (formType === "land_use") {
              action = "add_land_use";
            } else if (formType === "subclass") {
              action = "add_subclass";
            }
            
            formData.append("action", action);
            
            fetch(window.location.href, {
              method: "POST",
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              if (data.trim() === "success") {
                alert("Record added successfully!");
                location.reload();
              } else {
                alert("Error adding record: " + data);
              }
            });
            
            $(modal).modal("hide");
          } else {
            form.reportValidity();
          }
        });
      });

      // Change category dropdown
      window.changeCategoryType = function(type) {
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
      };

      // Edit buttons
      document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
          const table = this.getAttribute('data-table');

          if (table === 'classification') {
            document.getElementById('editClassificationCode').value = this.getAttribute('data-code');
            document.getElementById('editClassificationDescription').value = this.getAttribute('data-description');
            document.getElementById('editClassificationAssessment').value = this.getAttribute('data-assessment');
            document.getElementById('editClassificationStatus').value = this.getAttribute('data-status');
          } else if (table === 'actual_uses') {
            document.getElementById('editReportCode').value = this.getAttribute('data-report-code');
            document.getElementById('editActualUsesCode').value = this.getAttribute('data-code');
            document.getElementById('editActualUsesDescription').value = this.getAttribute('data-description');
            document.getElementById('editActualUsesAssessment').value = this.getAttribute('data-assessment');
            document.getElementById('editActualUsesStatus').value = this.getAttribute('data-status');
          } else if (table === 'sub_classes') {
            document.getElementById('editSubClassesCode').value = this.getAttribute('data-code');
            document.getElementById('editSubClassesDescription').value = this.getAttribute('data-description');
            document.getElementById('editSubClassesAssessment').value = this.getAttribute('data-assessment');
            document.getElementById('editSubClassesStatus').value = this.getAttribute('data-status');
          }
        });
      });

      // Save Classification Changes
      document.getElementById('saveClassificationChanges').addEventListener('click', function() {
        const code = document.getElementById('editClassificationCode').value;
        const description = document.getElementById('editClassificationDescription').value;
        const assessment = document.getElementById('editClassificationAssessment').value;
        const status = document.getElementById('editClassificationStatus').value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (xhr.status === 200 && xhr.responseText.trim() === "success") {
            alert("Classification updated successfully!");
            location.reload();
          } else {
            alert("Error saving Classification changes: " + xhr.responseText);
          }
        };
        xhr.send(`action=update_classification&code=${code}&description=${description}&assessment=${assessment}&status=${status}`);
      });

      // Save Actual Uses Changes
      document.getElementById('saveActualUsesChanges').addEventListener('click', function() {
        const reportCode = document.getElementById('editReportCode').value;
        const code = document.getElementById('editActualUsesCode').value;
        const description = document.getElementById('editActualUsesDescription').value;
        const assessment = document.getElementById('editActualUsesAssessment').value;
        const status = document.getElementById('editActualUsesStatus').value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (xhr.status === 200 && xhr.responseText.trim() === "success") {
            alert("Actual Uses updated successfully!");
            location.reload();
          } else {
            alert("Error saving Actual Uses changes: " + xhr.responseText);
          }
        };
        xhr.send(`action=update_actual_uses&reportCode=${reportCode}&code=${code}&description=${description}&assessment=${assessment}&status=${status}`);
      });

      // Save Sub-Classes Changes
      document.getElementById('saveSubClassesChanges').addEventListener('click', function() {
        const code = document.getElementById('editSubClassesCode').value;
        const description = document.getElementById('editSubClassesDescription').value;
        const assessment = document.getElementById('editSubClassesAssessment').value;
        const status = document.getElementById('editSubClassesStatus').value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
          if (xhr.status === 200 && xhr.responseText.trim() === "success") {
            alert("Sub-Classes updated successfully!");
            location.reload();
          } else {
            alert("Error saving Sub-Classes changes: " + xhr.responseText);
          }
        };
        xhr.send(`action=update_sub_classes&code=${code}&description=${description}&assessment=${assessment}&status=${status}`);
      });

      // Delete functionality
      let deleteId = null;
      let deleteTable = null;

      document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
          deleteId = this.getAttribute("data-id");
          deleteTable = this.getAttribute("data-table");
          let modal = new bootstrap.Modal(document.getElementById("deleteModal"));
          modal.show();
        });
      });

      document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
        if (deleteId && deleteTable) {
          const xhr = new XMLHttpRequest();
          xhr.open("POST", window.location.href, true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText.trim() === "success") {
              alert("Record deleted successfully!");
              location.reload();
            } else {
              alert("Error deleting record: " + xhr.responseText);
            }
          };
          xhr.send(`action=delete_record&id=${deleteId}&table=${deleteTable}`);
        }
      });

      // Pagination for tables
      const tables = ["classificationTable", "actualUsesTable", "subClassesTable"];
      const rowsPerPage = 5;

      tables.forEach(tableId => {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        const pagination = document.createElement("div");
        pagination.classList.add("mt-3", "pagination-container", "d-flex", "justify-content-center", "align-items-center");
        table.parentNode.appendChild(pagination);

        let currentPage = 1;

        function renderPage(page) {
          if (table.classList.contains("d-none")) {
            pagination.style.display = "none";
            return;
          } else {
            pagination.style.display = "flex";
          }

          currentPage = page;
          tbody.innerHTML = "";
          const start = (page - 1) * rowsPerPage;
          const end = start + rowsPerPage;
          rows.slice(start, end).forEach(row => tbody.appendChild(row));

          pagination.innerHTML = "";

          const prevBtn = document.createElement("button");
          prevBtn.innerHTML = "&laquo;";
          prevBtn.classList.add("btn", "btn-sm", "btn-outline-success");
          prevBtn.disabled = page === 1;
          prevBtn.addEventListener("click", () => renderPage(currentPage - 1));
          pagination.appendChild(prevBtn);

          const pageInfo = document.createElement("span");
          pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
          pageInfo.classList.add("mx-2", "fw-semibold");
          pagination.appendChild(pageInfo);

          const nextBtn = document.createElement("button");
          nextBtn.innerHTML = "&raquo;";
          nextBtn.classList.add("btn", "btn-sm", "btn-outline-success");
          nextBtn.disabled = page === totalPages;
          nextBtn.addEventListener("click", () => renderPage(currentPage + 1));
          pagination.appendChild(nextBtn);
        }

        renderPage(1);

        const observer = new MutationObserver(() => renderPage(1));
        observer.observe(table, {
          attributes: true,
          attributeFilter: ["class"]
        });
      });
    });
  </script>
</body>
</html>