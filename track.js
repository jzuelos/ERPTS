let transactions = [];
let editId = null;
let transactionModal = null;


// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the Bootstrap modal
    const modalElement = document.getElementById('transactionModal');
    if (modalElement) {
        transactionModal = new bootstrap.Modal(modalElement);
    }
});

function openModal(id = null) {
    if (!transactionModal) {
        const modalElement = document.getElementById('transactionModal');
        if (modalElement) {
            transactionModal = new bootstrap.Modal(modalElement);
        } else {
            console.error('Modal element not found');
            return;
        }
    }

    if (id !== null) {
        const tx = transactions.find(t => t.id === id);
        if (tx) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Transaction';
            document.getElementById('transactionID').value = tx.t_code || '';
            document.getElementById('nameInput').value = tx.name || '';
            document.getElementById('transactionInput').value = tx.transaction || '';
            document.getElementById('statusInput').value = tx.status || '';
            editId = id;
        }
    } else {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Add Transaction';
        document.getElementById('transactionID').value = '';
        document.getElementById('nameInput').value = '';
        document.getElementById('transactionInput').value = '';
        document.getElementById('statusInput').selectedIndex = 0;
        editId = null;
    }

    transactionModal.show();
}

function closeModal() {
    if (transactionModal) {
        transactionModal.hide();
    }
}

function saveTransaction() {
  const t_code = document.getElementById("transactionID").value.trim();
  const t_name = document.getElementById("nameInput").value.trim();
  const t_description = document.getElementById("transactionInput").value.trim();
  const t_status = document.getElementById("statusInput").value;

  if (!t_code || !t_name || !t_description || !t_status) {
    alert("Please fill out all fields.");
    return;
  }

  const formData = new FormData();
  formData.append("action", editId ? "updateTransaction" : "saveTransaction");
  formData.append("t_code", t_code);
  formData.append("t_name", t_name);
  formData.append("t_description", t_description);
  formData.append("t_status", t_status);

  // IMPORTANT: backend expects "transaction_id" (not "id")
  if (editId) {
    formData.append("transaction_id", editId);
  }

  fetch("trackFunctions.php", { method: "POST", body: formData })
    .then(async (response) => {
      const text = await response.text();
      console.log("RAW server response:", text); // <- check this in DevTools if anything goes wrong
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        throw new Error("Server did not return JSON. Raw: " + text);
      }
      return data;
    })
    .then((data) => {
      if (data.success) {
        alert(editId ? "Transaction updated!" : "Transaction saved!");
        // Reset fields
        document.getElementById("transactionID").value = "";
        document.getElementById("nameInput").value = "";
        document.getElementById("transactionInput").value = "";
        document.getElementById("statusInput").selectedIndex = 0;

        if (transactionModal) transactionModal.hide();

        if (typeof loadTransactions === "function") loadTransactions();
      } else {
        alert("Error: " + (data.message || "Unknown error"));
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Something went wrong while saving.");
    });
}

function loadTransactions() {
    fetch("trackFunctions.php?action=getTransactions")
    .then(response => response.json())
    .then(data => {
        transactions = data.map(tx => ({
            id: parseInt(tx.transaction_id),   // backend ID
            t_code: tx.transaction_code,
            name: tx.name,
            transaction: tx.description,
            status: tx.status
        }));
        updateTable();
    })
    .catch(error => console.error("Error loading transactions:", error));
}

function deleteTransaction(id) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        let formData = new FormData();
        formData.append("action", "deleteTransaction");
        formData.append("transaction_id", id);

        fetch("trackFunctions.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Transaction deleted successfully!");
                loadTransactions(); // refresh from DB
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error deleting transaction:", error));
    }
}

function updateTable() {
  const table = document.getElementById('transactionTable');
  table.innerHTML = '';

  transactions.forEach(tx => {
    const row = document.createElement('tr');
    const statusClass = tx.status === 'Completed' ? 'status-completed' : 'status-in-progress';
    
      row.innerHTML = `
        <td>${tx.t_code || '#' + tx.id}</td>
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
window.onload = function() {
    loadTransactions();
};