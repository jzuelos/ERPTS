let transactions = [];
let editId = null;

function openModal(id = null) {
  document.getElementById('transactionModal').style.display = 'flex';
  if (id !== null) {
    const tx = transactions.find(t => t.id === id);
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Transaction';
    document.getElementById('nameInput').value = tx.name;
    document.getElementById('transactionInput').value = tx.transaction;
    document.getElementById('statusInput').value = tx.status;
    editId = id;
  } else {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Add Transaction';
    document.getElementById('nameInput').value = '';
    document.getElementById('transactionInput').value = '';
    document.getElementById('statusInput').value = 'stats';
    editId = null;
  }
}

function closeModal() {
  document.getElementById('transactionModal').style.display = 'none';
}

function saveTransaction() {
  // Get input values
  let t_code = document.getElementById("transactionID").value.trim();
  let t_name = document.getElementById("nameInput").value.trim();
  let t_description = document.getElementById("transactionInput").value.trim();
  let t_status = document.getElementById("statusInput").value;

  // Basic validation
  if (!t_code || !t_name || !t_description || !t_status) {
    alert("Please fill out all fields.");
    return;
  }

  // Prepare data
  let formData = new FormData();
  formData.append("action", "saveTransaction"); // action identifier
  formData.append("t_code", t_code);
  formData.append("t_name", t_name);
  formData.append("t_description", t_description);
  formData.append("t_status", t_status);

  // Send request
  fetch("trackFunctions.php", {
    method: "POST",
    body: formData
  })
    .then(response => {
      // Make sure we parse valid JSON
      return response.json().catch(() => {
        throw new Error("Invalid JSON response from server");
      });
    })
    .then(data => {
      if (data.success) {
        alert("Transaction saved successfully!");

        // Reset form fields
        document.getElementById("transactionID").value = "";
        document.getElementById("nameInput").value = "";
        document.getElementById("transactionInput").value = "";
        document.getElementById("statusInput").selectedIndex = 0;

        // Close modal (Bootstrap 5 requires creating instance if not exists)
        let modalEl = document.getElementById('transactionModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) {
          modal = new bootstrap.Modal(modalEl);
        }
        modal.hide();

        // Optionally reload transaction list
        if (typeof loadTransactions === "function") {
          loadTransactions();
        }
      } else {
        alert("Error: " + (data.message || "Unknown error"));
      }
    })
    .catch(error => {
      console.error("Error:", error);
      alert("Something went wrong while saving.");
    });
}

function deleteTransaction(id) {
  if (confirm('Are you sure you want to delete this transaction?')) {
    transactions = transactions.filter(t => t.id !== id);
    logActivity(`Deleted transaction #${id}`);
    updateTable();
  }
}

function updateTable() {
  const table = document.getElementById('transactionTable');
  table.innerHTML = '';

  transactions.forEach(tx => {
    const row = document.createElement('tr');
    const statusClass = tx.status === 'Completed' ? 'status-completed' : 'status-in-progress';

    row.innerHTML = `
      <td>#${tx.id}</td>
      <td>${tx.name}</td>
      <td>${tx.transaction}</td>
      <td><span class="status-badge ${statusClass}">${tx.status}</span></td>
      <td>
        <button class="btn btn-edit" onclick="openModal(${tx.id})">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-delete" onclick="deleteTransaction(${tx.id})">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
    `;
    table.appendChild(row);
  });

  updateCounts();
}

function updateCounts() {
  document.getElementById('totalCount').innerText = transactions.length;
  document.getElementById('inProgressCount').innerText =
    transactions.filter(t => t.status === 'In Progress').length;
  document.getElementById('completedCount').innerText =
    transactions.filter(t => t.status === 'Completed').length;
}

function logActivity(message) {
  const log = document.getElementById('activityLog');
  const item = document.createElement('div');
  item.className = 'activity-item';
  item.innerHTML = `
    <i class="fas fa-circle"></i>
    <span>${new Date().toLocaleString()}: ${message}</span>
  `;

  // Limit to 10 most recent activities
  if (log.children.length >= 10) {
    log.removeChild(log.lastChild);
  }
  log.prepend(item);
}

// Initialize with some sample data if needed
window.onload = function () {
  // Sample data (optional)
  /*
  transactions = [
    { id: 1001, name: 'John Doe', transaction: 'Website Development', status: 'In Progress' },
    { id: 1002, name: 'Jane Smith', transaction: 'Mobile App Design', status: 'Completed' },
    { id: 1003, name: 'Acme Corp', transaction: 'SEO Services', status: 'In Progress' }
  ];
  updateTable();
  */
};