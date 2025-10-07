<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; // Include your database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ================================
// FETCH CURRENT ACTIVE ASSIGNMENTS
// ================================
$currentAssessor = null;
$currentVerifier = null;

// Fetch current Provincial Assessor
$assessorQuery = $conn->query("
  SELECT name 
  FROM admin_certification 
  WHERE role = 'provincial_assessor' AND status = 'active'
  LIMIT 1
");
if ($assessorQuery && $assessorQuery->num_rows > 0) {
  $currentAssessor = $assessorQuery->fetch_assoc()['name'];
  $assessorQuery->free();
}

// Fetch current Verifier
$verifierQuery = $conn->query("
  SELECT name 
  FROM admin_certification 
  WHERE role = 'verifier' AND status = 'active'
  LIMIT 1
");
if ($verifierQuery && $verifierQuery->num_rows > 0) {
  $currentVerifier = $verifierQuery->fetch_assoc()['name'];
  $verifierQuery->free();
}

// ================================
// BACKEND ACTION HANDLERS (CRUD)
// ================================

// 🟢 APPLY (Update selected Provincial Assessor and Verifier)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['action'])) {
  $provincial_assessor = isset($_POST['provincial_assessor']) ? (int) $_POST['provincial_assessor'] : 0;
  $verified_by = isset($_POST['verified_by']) ? (int) $_POST['verified_by'] : 0;

  // Optional: Reset previous roles before setting new ones
  $conn->query("UPDATE admin_certification SET role = 'none' WHERE role IN ('provincial_assessor', 'verifier')");

  // Update selected Provincial Assessor
  if ($provincial_assessor > 0) {
    $stmt = $conn->prepare("UPDATE admin_certification SET role = 'provincial_assessor' WHERE id = ?");
    $stmt->bind_param("i", $provincial_assessor);
    $stmt->execute();
    $stmt->close();
  }

  // Update selected Verifier
  if ($verified_by > 0) {
    $stmt = $conn->prepare("UPDATE admin_certification SET role = 'verifier' WHERE id = ?");
    $stmt->bind_param("i", $verified_by);
    $stmt->execute();
    $stmt->close();
  }

  echo "<script>alert('Roles updated successfully!'); window.location.href = window.location.href;</script>";
  exit;
}

// ADD new certification
if (isset($_POST['action']) && $_POST['action'] === 'add') {
  header('Content-Type: application/json');
  $name = trim($_POST['name'] ?? '');
  $position = trim($_POST['position'] ?? '');
  $status = strtolower(trim($_POST['status'] ?? 'active')); // Convert to lowercase

  // 🛑 Prevent duplicate names
  $checkName = $conn->prepare("SELECT COUNT(*) AS total FROM admin_certification WHERE LOWER(name) = LOWER(?)");
  $checkName->bind_param("s", $name);
  $checkName->execute();
  $nameResult = $checkName->get_result()->fetch_assoc();
  $checkName->close();

  if ($nameResult['total'] > 0) {
    echo json_encode(['success' => false, 'error' => 'This name already exists in the list.']);
    exit;
  }

  // 🛑 Prevent adding another Provincial Assessor
  if (strcasecmp($position, 'Provincial Assessor') === 0) {
    $check = $conn->query("SELECT COUNT(*) AS total FROM admin_certification WHERE position = 'Provincial Assessor'");
    $row = $check->fetch_assoc();
    if ($row['total'] > 0) {
      echo json_encode(['success' => false, 'error' => 'A Provincial Assessor already exists.']);
      exit;
    }
  }

  $stmt = $conn->prepare("INSERT INTO admin_certification (name, position, status) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $position, $status);
  $success = $stmt->execute();
  $stmt->close();

  echo json_encode(['success' => $success]);
  exit;
}


// UPDATE existing certification
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
  header('Content-Type: application/json');
  $id = (int) $_POST['id'];
  $name = trim($_POST['name'] ?? '');
  $position = trim($_POST['position'] ?? '');
  $status = strtolower(trim($_POST['status'] ?? 'active'));

  // Get current position
  $currentStmt = $conn->prepare("SELECT position FROM admin_certification WHERE id = ?");
  $currentStmt->bind_param("i", $id);
  $currentStmt->execute();
  $currentRecord = $currentStmt->get_result()->fetch_assoc();
  $currentStmt->close();

  $currentPosition = $currentRecord['position'] ?? '';

  // 🛑 Prevent adding another Provincial Assessor
  if (strcasecmp($position, 'Provincial Assessor') === 0) {
    $check = $conn->prepare("SELECT COUNT(*) AS total FROM admin_certification WHERE position = 'Provincial Assessor' AND id != ?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    $check->close();

    if ($result['total'] > 0) {
      echo json_encode(['success' => false, 'error' => 'Cannot assign another Provincial Assessor. One already exists.']);
      exit;
    }
  }

  // ✅ Clear role if changing FROM Provincial Assessor to another position
  if (strcasecmp($currentPosition, 'Provincial Assessor') === 0 && strcasecmp($position, 'Provincial Assessor') !== 0) {
    // Clear the role to 'none'
    $stmt = $conn->prepare("UPDATE admin_certification SET name=?, position=?, status=?, role='none' WHERE id=?");
    $stmt->bind_param("sssi", $name, $position, $status, $id);
  } else {
    // Normal update without changing role
    $stmt = $conn->prepare("UPDATE admin_certification SET name=?, position=?, status=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $position, $status, $id);
  }

  $success = $stmt->execute();
  $stmt->close();
  echo json_encode(['success' => $success]);
  exit;
}

// DELETE certification
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
  header('Content-Type: application/json');
  $id = (int) $_POST['id'];
  $stmt = $conn->prepare("DELETE FROM admin_certification WHERE id=?");
  $stmt->bind_param("i", $id);
  $success = $stmt->execute();
  $stmt->close();
  echo json_encode(['success' => $success]);
  exit;
}

// APPLY Provincial Assessor and Verifier selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
  header('Content-Type: application/json');

  $provincial_assessor = (int) ($_POST['provincial_assessor'] ?? 0);
  $verified_by = (int) ($_POST['verified_by'] ?? 0);

  $success = false;

  // Reset current roles (ensure only one of each)
  $conn->query("UPDATE admin_certification SET role = 'none' WHERE role IN ('provincial_assessor', 'verifier')");

  // Assign new Provincial Assessor
  if ($provincial_assessor > 0) {
    $stmt = $conn->prepare("UPDATE admin_certification SET role = 'provincial_assessor' WHERE id = ?");
    $stmt->bind_param("i", $provincial_assessor);
    $stmt->execute();
    $stmt->close();
  }

  // Assign new Verifier
  if ($verified_by > 0) {
    $stmt = $conn->prepare("UPDATE admin_certification SET role = 'verifier' WHERE id = ?");
    $stmt->bind_param("i", $verified_by);
    $stmt->execute();
    $stmt->close();
  }

  $success = true;
  echo json_encode(['success' => $success]);
  exit;
}


// FETCH all certifications
$certifications = [];
$result = $conn->query("SELECT id, name, position, status, role FROM admin_certification ORDER BY id ASC");
if ($result) {
  $certifications = $result->fetch_all(MYSQLI_ASSOC);
  $result->free();
}

// FETCH Provincial Assessor (Active only)
$assessors = [];
$assessorQuery = $conn->query("
  SELECT id, name 
  FROM admin_certification 
  WHERE status = 'active' AND (role = 'provincial_assessor' OR position = 'Provincial Assessor')
");
if ($assessorQuery) {
  $assessors = $assessorQuery->fetch_all(MYSQLI_ASSOC);
  $assessorQuery->free();
}

// FETCH all Active Verifiers
$verifiers = [];
$verifierQuery = $conn->query("
  SELECT id, name 
  FROM admin_certification 
  WHERE status = 'active' AND (role = 'verifier' OR position != 'Provincial Assessor')
");
if ($verifierQuery) {
  $verifiers = $verifierQuery->fetch_all(MYSQLI_ASSOC);
  $verifierQuery->free();
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

  <title>Sheet Modification</title>
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
            <span style="font-weight:bold;">
              <?php
              $isNotAssigned = empty($currentVerifier);
              ?>
              <u style="<?= $isNotAssigned ? 'color: red;' : '' ?>">
                <?= htmlspecialchars($currentVerifier ?? 'Not Assigned') ?>
              </u>
            </span>
          </div>

          <!-- Approved By (Right Side) -->
          <div style="text-align: center;">
            <div style="text-align: left; font-weight:bold;">Approved By:</div>
            <?php
            $isNotAssigned = empty($currentAssessor);
            ?>
            <u style="<?= $isNotAssigned ? 'color: red;' : '' ?>">
              <?= htmlspecialchars($currentAssessor ?? 'Not Assigned') ?>
            </u>
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

            <!-- Form for Assessor and Verifier -->
            <?php
            // Fetch only Active Provincial Assessors
            $assessors = [];
            $assessorQuery = $conn->query("
  SELECT id, name 
  FROM admin_certification 
  WHERE position = 'Provincial Assessor' 
  AND status = 'Active'
");
            if ($assessorQuery) {
              $assessors = $assessorQuery->fetch_all(MYSQLI_ASSOC);
              $assessorQuery->free();
            }

            // Fetch all Active verifiers EXCEPT Provincial Assessors
            $verifiers = [];
            $verifierQuery = $conn->query("
  SELECT id, name 
  FROM admin_certification 
  WHERE status = 'Active' 
  AND position != 'Provincial Assessor'
");
            if ($verifierQuery) {
              $verifiers = $verifierQuery->fetch_all(MYSQLI_ASSOC);
              $verifierQuery->free();
            }

            // 🟢 Get currently assigned roles
            $currentAssessorId = 0;
            $currentVerifierId = 0;

            $roleResult = $conn->query("SELECT id, role FROM admin_certification WHERE role IN ('provincial_assessor', 'verifier')");
            if ($roleResult) {
              while ($r = $roleResult->fetch_assoc()) {
                if ($r['role'] === 'provincial_assessor') {
                  $currentAssessorId = (int) $r['id'];
                } elseif ($r['role'] === 'verifier') {
                  $currentVerifierId = (int) $r['id'];
                }
              }
              $roleResult->free();
            }
            ?>

            <form id="assessorForm" method="POST" action="">
              <p><strong>Provincial Assessor:</strong></p>
              <select class="form-control mb-3" name="provincial_assessor" id="provincial_assessor" required>
                <option value="">Select Assessor</option>
                <?php foreach ($assessors as $a): ?>
                  <option value="<?= htmlspecialchars($a['id']); ?>" <?= ($a['id'] == $currentAssessorId ? 'selected' : '') ?>>
                    <?= htmlspecialchars($a['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>

              <p><strong>Verified By:</strong></p>
              <select class="form-control mb-4" name="verified_by" id="verified_by" required>
                <option value="">Select Verifier</option>
                <?php foreach ($verifiers as $v): ?>
                  <option value="<?= htmlspecialchars($v['id']); ?>" <?= ($v['id'] == $currentVerifierId ? 'selected' : '') ?>>
                    <?= htmlspecialchars($v['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>

              <!-- Apply Button -->
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success px-4">
                  <i class="fas fa-check-circle me-1"></i> Apply
                </button>
              </div>
            </form>
          </div>

          <!-- Right Column (Table) -->
          <div class="col-md-8">
            <div class="table-responsive rounded">

              <!-- Top Bar: Add + Search -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Classification List</h5>

                <div class="d-flex gap-2">
                  <!-- Add Button -->
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus"></i> Add
                  </button>

                  <!-- Search Box -->
                  <div class="input-group" style="width: 280px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                    <button class="btn btn-outline-primary" type="button" id="searchBtn">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Table -->
              <table class="table table-hover align-middle mb-0" id="classificationTable">
                <thead class="table-light">
                  <tr>
                    <th style="width: 10%">ID</th>
                    <th style="width: 35%">Name</th>
                    <th style="width: 25%">Position</th>
                    <th style="width: 15%">Status</th>
                    <th style="width: 15%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($certifications) && count($certifications) > 0): ?>
                    <?php foreach ($certifications as $row): ?>
                      <tr>
                        <td class="id"><?= htmlspecialchars($row['id']) ?></td>
                        <td class="name"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="position"><?= htmlspecialchars($row['position'] ?? '') ?></td>
                        <td class="status">
                          <?php if (strtolower($row['status']) === 'active'): ?>
                            <span class="badge bg-success-subtle text-success">Active</span>
                          <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger">Inactive</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $row['id'] ?>">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-danger delete-row-btn" data-id="<?= $row['id'] ?>">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        No records found. Click "Add" to create a new record.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <div id="classificationPagination" class="mt-3 d-flex justify-content-start"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Add Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="addForm">
          <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Add New Record</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="addName" class="form-label">Name</label>
              <input type="text" class="form-control" id="addName" required>
            </div>
            <div class="mb-3">
              <label for="addPosition" class="form-label">Position</label>
              <input type="text" class="form-control" id="addPosition" required>
            </div>
            <div class="mb-3">
              <label class="form-label d-block">Status</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="addActive" value="active" checked>
                <label class="form-check-label" for="addActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="addInactive" value="inactive">
                <label class="form-check-label" for="addInactive">Inactive</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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
              <input type="text" class="form-control" id="editPosition" name="position" required>
            </div>

            <div class="mb-3">
              <label class="form-label d-block">Status</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="editStatus" id="editActive" value="active" checked>
                <label class="form-check-label" for="editActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="editStatus" id="editInactive" value="inactive">
                <label class="form-check-label" for="editInactive">Inactive</label>
              </div>
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
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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