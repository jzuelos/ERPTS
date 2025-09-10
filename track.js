let transactions = [];
let editId = null;
let transactionModal = null;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function () {
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
      document.getElementById('contactInput').value = tx.contact || '';   // NEW
      document.getElementById('transactionInput').value = tx.transaction || '';
      document.getElementById('statusInput').value = tx.status || '';
      editId = id;
    }
  } else {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Add Transaction';
    document.getElementById('transactionID').value = '';
    document.getElementById('nameInput').value = '';
    document.getElementById('contactInput').value = '';                   // NEW
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
  const t_contact = document.getElementById("contactInput").value.trim(); // NEW
  const t_description = document.getElementById("transactionInput").value.trim();
  const t_status = document.getElementById("statusInput").value;

  if (!t_code || !t_name || !t_contact || !t_description || !t_status) {
    alert("Please fill out all fields.");
    return;
  }

  const formData = new FormData();
  formData.append("action", editId ? "updateTransaction" : "saveTransaction");
  formData.append("t_code", t_code);
  formData.append("t_name", t_name);
  formData.append("t_contact", t_contact); // NEW
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
        logActivity(editId
          ? `Updated transaction #${editId} (${t_name})`
          : `Added new transaction (${t_name})`
        );

        alert(editId ? "Transaction updated!" : "Transaction saved!");
        // Reset fields
        document.getElementById("transactionID").value = "";
        document.getElementById("nameInput").value = "";
        document.getElementById("contactInput").value = ""; // NEW
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
        contact: tx.contact_number,       // NEW
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
        <td>${tx.contact || ''}</td> <!-- NEW -->
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


function checkTransaction(transactionId) {
  const checkbox = event.target;

  if (checkbox.checked) {
    console.log("Transaction " + transactionId + " marked as checked ");
    // Example: mark as Completed
    // sendUpdate(transactionId, "Completed");
  } else {
    console.log("Transaction " + transactionId + " unchecked ");
    // Example: mark as In Progress
    // sendUpdate(transactionId, "In Progress");
  }
}

/* Example function to send updates to the server 
function sendUpdate(transactionId, status) {
  fetch("updateTransaction.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "transaction_id=" + transactionId + "&status=" + status
  })
  .then(response => response.text())
  .then(data => {
    console.log("Server response:", data);
  })
  .catch(error => console.error("Error:", error));
}*/

//Confirmation Modals
  function confirmTransaction(transactionId) {
    currentTransactionId = transactionId;
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    confirmModal.show();
  }
 let currentTransactionId = null;
  document.getElementById("confirmBtn").addEventListener("click", function() {
    if (currentTransactionId) {
      console.log("Confirmed transaction:", currentTransactionId);
      // TODO: send AJAX request to PHP to update status in DB
      // Example:
      // fetch('confirm_transaction.php', {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      //   body: 'transaction_id=' + currentTransactionId
      // }).then(() => location.reload());
    }
    bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
  });

  function saveTransaction() {
  const formData = new FormData();
  formData.append("t_code", document.getElementById("transactionID").value);
  formData.append("t_name", document.getElementById("nameInput").value);
  formData.append("t_contact", document.getElementById("contactInput").value);
  formData.append("t_description", document.getElementById("transactionInput").value);
  formData.append("transactionType", document.getElementById("transactionType").value);
  formData.append("t_status", document.getElementById("statusInput").value);

  const file = document.getElementById("fileUpload").files[0];
  if (file) {
    formData.append("t_file", file);
  }

  fetch("save_transaction.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    console.log(data);
    location.reload(); // refresh after saving
  })
  .catch(err => console.error(err));
}

function showRequirements() {
  const transactionType = document.getElementById("transactionType").value;
  const requirementsDiv = document.getElementById("requirementsText");

  let text = "";

  switch (transactionType) {
    case "Simple Transfer of Ownership":
      text = `CHECKLIST : SIMPLE TRANSFER OF OWNERSHIP (LAND/BUILDING/MACHINERIES)
1. DEED OF CONVEYANCES (duly notarized/registered from Registry of Deeds)
   a. Sale
   b. Donations
   c. Extrajudicial Settlement etc..
2. CERTIFICATION OF TAX PAYMENT - Municipal Treasurers Office (MTO) 
3. CERTIFICATION FROM BUREAU OF INTERNAL REVENUE (BIR eCAR)
4. CERTIFICATE OF TRANSFER TAX – Provincial Treasurers Office (PTO)
5. TITLE – Authenticated/Certified true copy/Electronic true copy
   a. Free Patent (DENR/Bureau of Lands)
   b. Original certificate of title (OCT)
   c. Transfer Certificate of Title (TCT)
   d. CLOA – DAR
   e. EP – DAR
6. DAR CLEARANCE (if Agricultural Land)
7. AFFIDAVIT OF PUBLICATION (if Extrajudicial settlement)`;
      break;

    case "New Declaration of Real Property":
      text = `CHECKLIST :  NEW DECLARATION OF REAL PROPERTY
1. LAND
   a. LETTER REQUEST BY OWNER
   b. AFFIDAVIT OF OWNERSHIP/POSSESSION
   c. CERTIFICATION FROM BARANGAY CAPTAIN
   d. CERTIFICATION FROM DENR/PENRO (alienable & disposable)
   e. LIST OF CLAIMANTS (DENR/PENRO)
   f. APPROVED SURVEY PLAN and/or CADASTRAL MAP
   g. INSPECTION REPORT
2. BUILDING
   a. LETTER REQUEST BY OWNER
   b. BUILDING PERMIT
   c. BUILDING FLOOR PLAN
   d. SWORN STATEMENT FOR TRUE FAIR MARKET VALUE
   e. PICTURES
   f. NOTICE OF ASSESSMENT
3. MACHINERIES
   a. LETTER REQUEST
   b. SWORN STATEMENT BY THE OWNER
   c. ACTUAL COST OF MACHINERY
   d. PICTURES
   e. NOTICE OF ASSESSMENT`;
      break;

    case "Revision/Correction":
      text = `CHECKLIST : REVISION/CORRECTION AREA, BOUNDARIES etc….OF REAL PROPERTIES
1. LETTER REQUEST BY OWNER
2. CERTIFICATION FROM DENR/PENRO
3. TITLE (if any) - Authenticated/Certified true copy/Electronic true copy
4. CERTIFICATION OF TAX PAYMENT - Municipal Treasurers Office (MTO)
5. CADASTRAL MAP (DENR/PENRO) if any`;
      break;

    case "Consolidation":
      text = `CHECKLIST :  CONSOLIDATION OF REAL PROPERTIES (TAX DECLARATION)
1. LETTER REQUEST BY OWNER
2. TITLE (if any) - Authenticated/Certified true copy/Electronic true copy
3. CERTIFICATION OF TAX PAYMENT - Municipal Treasurers Office (MTO)
4. APPROVED SUBDIVISION PLAN`;
      break;

    default:
      text = "";
  }

  if (text) {
    requirementsDiv.style.display = "block";
    requirementsDiv.textContent = text;
  } else {
    requirementsDiv.style.display = "none";
  }
}
