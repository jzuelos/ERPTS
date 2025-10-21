
document.addEventListener("DOMContentLoaded", () => {
  // single authoritative variable
  let rowToDelete = null;

  // =========================
  // ADD RECORD
  // =========================
  const addForm = document.getElementById("addForm");
  if (addForm) {
    addForm.addEventListener("submit", function (e) {
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
          alert("Error adding record.");
        });
    });
  }

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

      const status = statusText.toLowerCase();
      document.getElementById("editId").value = id;
      document.getElementById("editName").value = name;
      document.getElementById("editPosition").value = position;

      const radioButton = document.querySelector(`#editForm input[value="${status}"]`);
      if (radioButton) radioButton.checked = true;

      const editModalEl = document.getElementById("editModal");
      if (editModalEl) {
        const editModal = new bootstrap.Modal(editModalEl);
        editModal.show();
      }
    });
  });

  const editForm = document.getElementById("editForm");
if (editForm) {
  editForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const id = document.getElementById("editId").value;
    const name = document.getElementById("editName").value.trim();
    const position = document.getElementById("editPosition").value.trim();
    const status = document.querySelector('#editForm input[name="editStatus"]:checked').value;

    // 🛑 Prevent inactivating Provincial Assessor
    if ((name.toLowerCase() === "provincial assessor" || position.toLowerCase() === "provincial assessor") && status.toLowerCase() !== "active") {
      alert("⚠️The Provincial Assessor cannot be set to inactive.");
      return; // stop here, do not submit
    }

    // Proceed normally for others
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
}


  // =========================
  // DELETE RECORD
  // single handler only — prevents modal for Provincial Assessor
  // =========================
  document.querySelectorAll(".delete-row-btn").forEach(button => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      // decide which row we'll attempt to delete
      rowToDelete = this.closest("tr");
      const id = this.dataset.id || rowToDelete.querySelector(".id")?.textContent.trim();
      const name = rowToDelete.querySelector(".name")?.textContent.trim().toLowerCase() || "";
      const position = rowToDelete.querySelector(".position")?.textContent.trim().toLowerCase() || "";

      // If Provincial Assessor -> block and show immediate message; do NOT show modal
      if (name === "provincial assessor" || position === "provincial assessor") {
        // Small timeout helps avoid UI flicker if some other UI event is queued
        setTimeout(() => {
          alert("You cannot delete the Provincial Assessor record.");
        }, 50);
        // do not set rowToDelete (or keep it but ensure confirm won't delete) — just return
        rowToDelete = null;
        return;
      }

      // normal path: show confirmation modal
      const messageEl = document.getElementById("deleteMessage");
      if (messageEl) {
        messageEl.textContent = `Are you sure you want to delete record ID ${id}?`;
      }

      const deleteModalEl = document.getElementById("deleteModal");
      if (deleteModalEl) {
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        deleteModal.show();
      }
    });
  });

  // Confirm delete: defensive check (re-verify Provincial Assessor before proceeding)
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", function () {
      if (!rowToDelete) {
        // nothing to delete (either cancelled earlier or blocked)
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
        if (modalInstance) modalInstance.hide();
        return;
      }

      const id = rowToDelete.querySelector(".id")?.textContent.trim();
      const name = rowToDelete.querySelector(".name")?.textContent.trim().toLowerCase() || "";
      const position = rowToDelete.querySelector(".position")?.textContent.trim().toLowerCase() || "";

      // Defensive check: block deletion if it's changed to Provincial Assessor
      if (name === "provincial assessor" || position === "provincial assessor") {
        alert("The Provincial Assessor record cannot be deleted.");
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
        if (modalInstance) modalInstance.hide();
        rowToDelete = null;
        return;
      }

      // Proceed with delete request
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
          const modalInstance = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
          if (modalInstance) modalInstance.hide();
          rowToDelete = null;
        });
    });
  }

  // =========================
  // SEARCH FUNCTIONALITY
  // =========================
  const searchInput = document.getElementById("searchInput");
  const searchBtn = document.getElementById("searchBtn");

  function performSearch() {
    const searchTerm = (searchInput?.value || "").toLowerCase().trim();
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

  if (searchBtn) searchBtn.addEventListener("click", performSearch);
  if (searchInput) searchInput.addEventListener("keyup", e => {
    if (e.key === "Enter") performSearch();
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

