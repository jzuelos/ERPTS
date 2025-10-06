<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';
$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ================================
// BACKEND ACTION HANDLERS (CRUD)
// ================================

// ADD new certification
if (isset($_POST['action']) && $_POST['action'] === 'add') {
  $name = trim($_POST['name'] ?? '');
  $position = trim($_POST['position'] ?? '');
  $status = trim($_POST['status'] ?? 'Active');

  $stmt = $conn->prepare("INSERT INTO admin_certification (name, position, status) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $position, $status);
  $success = $stmt->execute();
  echo json_encode(['success' => $success]);
  exit;
}

// UPDATE existing certification
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
  $id = (int) $_POST['id'];
  $name = trim($_POST['name'] ?? '');
  $position = trim($_POST['position'] ?? '');
  $status = trim($_POST['status'] ?? '');

  $stmt = $conn->prepare("UPDATE admin_certification SET name=?, position=?, status=? WHERE id=?");
  $stmt->bind_param("sssi", $name, $position, $status, $id);
  $success = $stmt->execute();
  echo json_encode(['success' => $success]);
  exit;
}

// DELETE certification
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
  $id = (int) $_POST['id'];
  $stmt = $conn->prepare("DELETE FROM admin_certification WHERE id=?");
  $stmt->bind_param("i", $id);
  $success = $stmt->execute();
  echo json_encode(['success' => $success]);
  exit;
}

// FETCH all certifications
$result = $conn->query("SELECT * FROM admin_certification ORDER BY id ASC");
$certifications = $result->fetch_all(MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Certification | Electronic Real Property Tax System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="certi.css">
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
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Certification</h2>
    </div>

    <div class="card border-0 shadow p-4 rounded-3 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="section-title mb-0">Land Category Information</h5>
        <div class="d-flex align-items-center custom-spacing">
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Add
          </button>
          <div class="input-group" style="width: 250px;">
            <input type="text" class="form-control" id="tableSearch" placeholder="Search...">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="px-3">
        <div class="table-responsive rounded">
          <table class="table table-hover align-middle mb-0" id="classificationTable">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($certifications as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['id']); ?></td>
                  <td><?= htmlspecialchars($row['name']); ?></td>
                  <td><?= htmlspecialchars($row['position']); ?></td>
                  <td><span
                      class="badge bg-<?= strtolower($row['status']) === 'active' ? 'success' : 'secondary'; ?>"><?= htmlspecialchars($row['status']); ?></span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary me-1 edit-btn" data-id="<?= $row['id']; ?>"
                      data-name="<?= htmlspecialchars($row['name']); ?>"
                      data-position="<?= htmlspecialchars($row['position']); ?>"
                      data-status="<?= htmlspecialchars($row['status']); ?>" data-bs-toggle="modal"
                      data-bs-target="#editModal">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $row['id']; ?>">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- Pagination container -->
  <div id="classificationPagination" class="mt-3 text-center"></div>
      </div>
    </div>
  </main>

  <!-- Add Modal -->
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog" style="max-width: 30%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addForm">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Position</label>
              <input type="text" class="form-control" name="position">
            </div>
            <div class="mb-3">
              <label class="form-label d-block">Status</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="addActive" value="Active" checked>
                <label class="form-check-label" for="addActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="addInactive" value="Inactive">
                <label class="form-check-label" for="addInactive">Inactive</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog" style="max-width: 30%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="editForm">
          <div class="modal-body">
            <input type="hidden" name="id" id="editId">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" id="editName" name="name">
            </div>
            <div class="mb-3">
              <label class="form-label">Position</label>
              <input type="text" class="form-control" id="editPosition" name="position">
            </div>
            <div class="mb-3">
              <label class="form-label d-block">Status</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="editActive" value="Active">
                <label class="form-check-label" for="editActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="editInactive" value="Inactive">
                <label class="form-check-label" for="editInactive">Inactive</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(function () {
      // ADD
      $('#addForm').submit(function (e) {
        e.preventDefault();
        $.post('Certification.php', $(this).serialize() + '&action=add', function (resp) {
          if (resp.success) location.reload();
        }, 'json');
      });

      // EDIT - load data into modal
      $('.edit-btn').click(function () {
        $('#editId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
        $('#editPosition').val($(this).data('position'));

        // Normalize status (trim + lowercase)
        const status = ($(this).data('status') || '').trim().toLowerCase();

        // Reset both radios first
        $('#editActive, #editInactive').prop('checked', false);

        // Select the correct one
        if (status === 'active') {
          $('#editActive').prop('checked', true);
        } else if (status === 'inactive') {
          $('#editInactive').prop('checked', true);
        }
      });

      // SAVE EDIT
      $('#editForm').submit(function (e) {
        e.preventDefault();
        $.post('Certification.php', $(this).serialize() + '&action=edit', function (resp) {
          if (resp.success) location.reload();
        }, 'json');
      });

      // DELETE
      $('.delete-btn').click(function () {
        if (!confirm('Are you sure you want to delete this record?')) return;
        $.post('Certification.php', { action: 'delete', id: $(this).data('id') }, function (resp) {
          if (resp.success) location.reload();
        }, 'json');
      });
    });

    // SEARCH FUNCTION
    $('#tableSearch').on('keyup', function () {
      const value = $(this).val().toLowerCase();
      $('#classificationTable tbody tr').filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });

  </script>

<script>
function paginateTable(tableId, paginationId, rowsPerPage = 10) {
  const table = document.getElementById(tableId);
  const tbody = table.querySelector("tbody");
  const rows = Array.from(tbody.querySelectorAll("tr"));
  const pagination = document.getElementById(paginationId);

  let currentPage = 1;
  const totalPages = Math.ceil(rows.length / rowsPerPage);

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

    const prevBtn = document.createElement("button");
    prevBtn.className = "btn btn-sm btn-outline-success me-2";
    prevBtn.innerHTML = "&laquo; Prev";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => {
      if (currentPage > 1) {
        currentPage--;
        renderTable();
        renderPagination();
      }
    };

    const pageIndicator = document.createElement("span");
    pageIndicator.className = "mx-2";
    pageIndicator.innerText = `Page ${currentPage} of ${totalPages}`;

    const nextBtn = document.createElement("button");
    nextBtn.className = "btn btn-sm btn-outline-success ms-2";
    nextBtn.innerHTML = "Next &raquo;";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => {
      if (currentPage < totalPages) {
        currentPage++;
        renderTable();
        renderPagination();
      }
    };

    pagination.appendChild(prevBtn);
    pagination.appendChild(pageIndicator);
    pagination.appendChild(nextBtn);
  }

  renderTable();
  renderPagination();
}

// Initialize after DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  paginateTable("classificationTable", "classificationPagination", 5);
});
</script>

</body>

</html>