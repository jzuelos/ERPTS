
// Edit Button
document.querySelectorAll(".edit-btn").forEach(button => {
  button.addEventListener("click", function () {
    let row = this.closest("tr");

    // Extract current row values
    let id = row.querySelector(".id").textContent.trim();
    let name = row.querySelector(".name").textContent.trim();
    let position = row.querySelector(".position").textContent.trim();
    let status = row.querySelector(".status span").textContent.trim();

    // Populate modal form fields with row data
    document.getElementById("editId").value = id;
    document.getElementById("editName").value = name;
    document.getElementById("editPosition").value = position;
    document.getElementById("editStatus").value = status;
  });
});

// Save Edit
document.getElementById("editForm").addEventListener("submit", function (e) {
  e.preventDefault();

  // Get updated values from modal inputs
  let id = document.getElementById("editId").value;
  let name = document.getElementById("editName").value;
  let position = document.getElementById("editPosition").value;
  let status = document.getElementById("editStatus").value;

  // Find row with matching ID
  let row = [...document.querySelectorAll("#classificationTable tbody tr")]
              .find(r => r.querySelector(".id").textContent.trim() === id);

  if (row) {
    // Update table row values
    row.querySelector(".name").textContent = name;
    row.querySelector(".position").textContent = position;
    row.querySelector(".status span").textContent = status;

    // Update badge styling depending on status
    let badge = row.querySelector(".status span");
    badge.className = status === "Active" 
      ? "badge bg-success-subtle text-success"
      : "badge bg-danger-subtle text-danger";
  }

  // Close the edit modal
  let modal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
  modal.hide();
});

// Delete Button
let rowToDelete = null; // store the row to delete globally

document.querySelectorAll(".delete-row-btn").forEach(button => {
  button.addEventListener("click", function () {
    rowToDelete = this.closest("tr");
    let id = this.getAttribute("data-id");

    // Update delete modal message
    document.getElementById("deleteMessage").textContent =
      `Are you sure you want to delete record ID ${id}?`;

    // Show delete modal
    let deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    deleteModal.show();
  });
});

// Confirm Delete
document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
  if (rowToDelete) {
    rowToDelete.remove();
    rowToDelete = null;
  }

  // Close delete modal
  let modalEl = document.getElementById("deleteModal");
  let modalInstance = bootstrap.Modal.getInstance(modalEl);
  modalInstance.hide();
});

