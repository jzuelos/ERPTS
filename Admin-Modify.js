
document.addEventListener("DOMContentLoaded", () => {
  let rowToDelete = null;

  // =========================
  // ADD RECORD
  // =========================
  document.getElementById("addForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const name = document.getElementById("addName").value.trim();
    const position = document.getElementById("addPosition").value.trim();
    const status = document.querySelector('#addForm input[name="status"]:checked').value;

    fetch("", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ action: "add", name, position, status })
    })
      .then(res => res.json())
      .then(data => {  // ✅ use 'data' here
        if (data.success) {
          alert('Saved successfully!');
          location.reload();
        } else if (data.error) {
          alert(data.error);
        } else {
          alert('An error occurred.');
        }
      })
      .catch(err => {
        console.error(err);
        alert("Error adding record.");
      });
  });

  // =========================
  // EDIT RECORD
  // =========================
  document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      const id = row.querySelector(".id").textContent.trim();
      const name = row.querySelector(".name").textContent.trim();
      const position = row.querySelector(".position").textContent.trim();
      const statusText = row.querySelector(".status span").textContent.trim();

      // Convert "Active" display text to "active" database value
      const status = statusText.toLowerCase();

      document.getElementById("editId").value = id;
      document.getElementById("editName").value = name;
      document.getElementById("editPosition").value = position;

      // Check the correct radio button
      const radioButton = document.querySelector(`#editForm input[value="${status}"]`);
      if (radioButton) {
        radioButton.checked = true;
      }

      // Show the modal
      const editModal = new bootstrap.Modal(document.getElementById("editModal"));
      editModal.show();
    });
  });

  document.getElementById("editForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const id = document.getElementById("editId").value;
    const name = document.getElementById("editName").value.trim();
    const position = document.getElementById("editPosition").value.trim();
    const status = document.querySelector('#editForm input[name="editStatus"]:checked').value;

    fetch("", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ action: "edit", id, name, position, status })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Saved successfully!');
          location.reload();
        } else if (data.error) {
          alert(data.error);
        } else {
          alert('An error occurred.');
        }
      })
      .catch(err => {
        console.error(err);
        alert("Error updating record.");
      });
  });

  // =========================
  // DELETE RECORD
  // =========================
  document.querySelectorAll(".delete-row-btn").forEach(button => {
    button.addEventListener("click", function () {
      rowToDelete = this.closest("tr");
      const id = this.dataset.id;
      document.getElementById("deleteMessage").textContent =
        `Are you sure you want to delete record ID ${id}?`;

      const deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
      deleteModal.show();
    });
  });

  document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
    if (!rowToDelete) return;
    const id = rowToDelete.querySelector(".id").textContent.trim();

    fetch("", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ action: "delete", id })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("🗑️ Record deleted successfully!");
          location.reload();
        } else {
          alert("Delete failed.");
        }
      })
      .catch(err => {
        console.error(err);
        alert("Error deleting record.");
      })
      .finally(() => {
        const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
        if (modal) modal.hide();
      });
  });

  // =========================
  // SEARCH FUNCTIONALITY
  // =========================
  const searchInput = document.getElementById("searchInput");
  const searchBtn = document.getElementById("searchBtn");

  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const rows = document.querySelectorAll("#classificationTable tbody tr");

    rows.forEach(row => {
      const id = row.querySelector(".id")?.textContent.toLowerCase() || "";
      const name = row.querySelector(".name")?.textContent.toLowerCase() || "";
      const position = row.querySelector(".position")?.textContent.toLowerCase() || "";
      const status = row.querySelector(".status")?.textContent.toLowerCase() || "";

      if (id.includes(searchTerm) || name.includes(searchTerm) ||
        position.includes(searchTerm) || status.includes(searchTerm)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  }

  if (searchBtn) {
    searchBtn.addEventListener("click", performSearch);
  }

  if (searchInput) {
    searchInput.addEventListener("keyup", function (e) {
      if (e.key === "Enter") {
        performSearch();
      }
    });
  }
});

document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
  if (!rowToDelete) return;
  const id = rowToDelete.querySelector(".id").textContent.trim();

  fetch("", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ action: "delete", id })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        rowToDelete.remove();
        alert("Record deleted successfully!");
      } else {
        alert("Delete failed.");
      }

      const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
      modal.hide();
    });
});


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

