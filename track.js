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
    document.getElementById('statusInput').value = 'In Progress';
    editId = null;
  }
}

function closeModal() {
  document.getElementById('transactionModal').style.display = 'none';
}

function saveTransaction() {
  const name = document.getElementById('nameInput').value;
  const transaction = document.getElementById('transactionInput').value;
  const status = document.getElementById('statusInput').value;

  if (!name || !transaction) {
    alert('Please fill all fields.');
    return;
  }

  if (editId !== null) {
    const tx = transactions.find(t => t.id === editId);
    tx.name = name;
    tx.transaction = transaction;
    tx.status = status;
    logActivity(`Updated transaction #${tx.id}`);
  } else {
    const id = Date.now();
    transactions.push({ id, name, transaction, status });
    logActivity(`Added new transaction #${id}`);
  }

  closeModal();
  updateTable();
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
window.onload = function() {
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