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

  loadTransactions();
  loadActivity();

  const contactInput = document.getElementById('contactInput');

  if (contactInput) {
    contactInput.addEventListener('input', function (e) {
      let val = e.target.value;

      // Always start with +63
      if (!val.startsWith('+63')) {
        val = '+63' + val.replace(/\D/g, ''); // remove non-digits
      } else {
        // keep only digits after +63
        val = '+63' + val.slice(3).replace(/\D/g, '');
      }

      // limit to 13 chars total (+63 + 10 digits)
      if (val.length > 13) val = val.slice(0, 13);

      e.target.value = val;
    });

    // optional: place cursor after +63 when focusing
    contactInput.addEventListener('focus', function () {
      if (contactInput.value === '+63') {
        contactInput.setSelectionRange(3, 3);
      }
    });
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
      document.getElementById('transactionID').disabled = true;
      document.getElementById('nameInput').value = tx.name || '';
      document.getElementById('contactInput').value = tx.contact || '';
      document.getElementById('transactionInput').value = tx.transaction || '';
      document.getElementById('transactionInput').disabled = false;
      document.getElementById('transactionType').value = tx.transaction_type || '';

      const statusSelect = document.getElementById('statusInput');
      statusSelect.disabled = false; // ✅ Re-enable dropdown in edit mode
      statusSelect.innerHTML = `
      <option value="" disabled selected>Select Status</option>
      <option value="In Progress">In Progress</option>
      <option value="Completed">Completed</option>
    `;
      statusSelect.value = tx.status || '';

      editId = id;
    }
  }
  else {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Add Transaction';

    // Fetch next transaction code from backend
    fetch('trackFunctions.php?action=getNextTransactionCode')
      .then(res => res.json())
      .then(data => {
        document.getElementById('transactionID').value = data.next_code;
        document.getElementById('transactionID').disabled = true; // prevent editing
      })
      .catch(err => console.error('Error fetching next transaction code:', err));

    document.getElementById('nameInput').value = '';
    document.getElementById('contactInput').value = '';
    document.getElementById('transactionInput').value = '';
    document.getElementById('transactionInput').disabled = true; // Disable initially for add mode
    document.getElementById('transactionType').selectedIndex = 0;

    const statusSelect = document.getElementById('statusInput');
    statusSelect.innerHTML = `
  <option value="Pending" selected>Pending</option>
`;
    statusSelect.selectedIndex = 0;
    statusSelect.disabled = true; // ✅ keep this

    editId = null;
  }

  transactionModal.show();
}

// Generate auto-message based on transaction type and status
function generateAutoMessage() {
  const statusSelect = document.getElementById('statusInput');
  const transactionInput = document.getElementById('transactionInput');
  const transactionCode = document.getElementById('transactionID').value;
  const transactionType = document.getElementById('transactionType').value;
  const selectedStatus = statusSelect.value;

  if (!selectedStatus || !transactionType) return;

  const now = new Date();
  const dateString = now.toLocaleDateString('en-US', {
    month: '2-digit',
    day: '2-digit',
    year: 'numeric'
  });

  // Shortened transaction type names for SMS
  const typeAbbrev = {
    'Simple Transfer of Ownership': 'Transfer of Ownership',
    'New Declaration of Real Property': 'New Property Declaration',
    'Revision/Correction': 'Property Revision',
    'Consolidation': 'Property Consolidation'
  };

  const shortType = typeAbbrev[transactionType] || transactionType;
  let autoMessage = '';

  switch (selectedStatus) {
    case 'Pending':
      autoMessage = `${shortType} request #${transactionCode} received ${dateString}. Your application is now pending review. For more info. visit https://erptstrack.erpts.online`;
      break;
    case 'In Progress':
      autoMessage = `${shortType} #${transactionCode} is being processed. Documents under review as of ${dateString}. For more info. visit https://erptstrack.erpts.online`;
      break;
    case 'Completed':
      autoMessage = `${shortType} #${transactionCode} completed ${dateString}. Ready for pickup at our office. For more info. visit https://erptstrack.erpts.online`;
      break;
  }

  // Ensure message stays under 160 characters for GSM-7
  if (autoMessage.length > 160) {
    autoMessage = autoMessage.substring(0, 157) + '...';
  }

  transactionInput.value = autoMessage;
  transactionInput.disabled = false; // Enable so user can modify if needed
}

// Handle status change to auto-generate description
function handleStatusChange() {
  generateAutoMessage();
}

// Handle transaction type change to auto-generate description
function handleTransactionTypeChange() {
  generateAutoMessage();
}

// Add event listener for status change
document.addEventListener('DOMContentLoaded', function () {
  const statusSelect = document.getElementById('statusInput');
  if (statusSelect) {
    statusSelect.addEventListener('change', handleStatusChange);
  }
});

function closeModal() {
  if (transactionModal) {
    transactionModal.hide();
  }
}

function saveTransaction() {
  const t_code = document.getElementById("transactionID").value.trim();
  const t_name = document.getElementById("nameInput").value.trim();
  const t_contact = document.getElementById("contactInput").value.trim();
  const t_description = document.getElementById("transactionInput").value.trim();
  const transactionType = document.getElementById("transactionType").value;
  const t_status = document.getElementById("statusInput").value;

  if (!t_code || !t_name || !t_contact || !t_description || !t_status || !transactionType) {
    alert("Please fill out all fields.");
    return;
  }

  const formData = new FormData();
  formData.append("action", editId ? "updateTransaction" : "saveTransaction");
  formData.append("t_code", t_code);
  formData.append("t_name", t_name);
  formData.append("t_contact", t_contact);
  formData.append("t_description", t_description);
  formData.append("transactionType", transactionType);
  formData.append("t_status", t_status);

  const files = document.getElementById("fileUpload").files;
  if (files.length > 0) {
    for (let i = 0; i < files.length; i++) {
      formData.append("t_file[]", files[i]); // send all files
    }
  }

  if (editId) {
    formData.append("transaction_id", editId);
  }

  fetch("trackFunctions.php", {  // keep consistent backend
    method: "POST",
    body: formData
  })
    .then(async (response) => {
      const text = await response.text();
      console.log("RAW server response:", text);
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
        if (transactionModal) transactionModal.hide();
        if (typeof loadTransactions === "function") loadTransactions();
        if (typeof loadActivity === "function") loadActivity(); // refresh activity
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
        transaction_type: tx.transaction_type,
        status: tx.status
      }));
      updateTable();
    })
    .catch(error => console.error("Error loading transactions:", error));
}

// --- Load recent activity from backend ---
function loadActivity() {
  const tbody = document.getElementById("activityTableBody");
  if (!tbody) return;

  // show loading row
  tbody.innerHTML = `<tr><td colspan="5" class="text-center">Loading recent activity…</td></tr>`;

  fetch("trackFunctions.php?action=getActivity")
    .then(res => res.json())
    .then(data => {
      tbody.innerHTML = "";

      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center">No recent activity.</td></tr>`;
        populateDateFilter([]); // clear filter
        return;
      }

      // Build rows and collect dates
      const datesSet = new Set();

      data.forEach(item => {
        const createdAtRaw = item.created_at || ""; // e.g. "2024-05-12 14:23:45"
        const dateOnly = createdAtRaw ? createdAtRaw.split(" ")[0] : ""; // "YYYY-MM-DD"
        if (dateOnly) datesSet.add(dateOnly);

        const tcode = item.t_code || ("#" + (item.transaction_id || ""));
        const actionRaw = item.action || "";
        const detailsRaw = item.details || "";
        const user = item.user || "";

        const details = detailsRaw.length > 120 ? detailsRaw.slice(0, 117) + "..." : detailsRaw;
        const prettyDate = formatTimestamp(createdAtRaw);

        // create row element and set first cell's data-date attribute
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td data-date="${escapeHtml(createdAtRaw)}" style="white-space:nowrap">${escapeHtml(prettyDate)}</td>
          <td>${escapeHtml(tcode)}</td>
          <td style="max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
              title="${escapeHtml(actionRaw)}">${escapeHtml(actionRaw)}</td>
          <td style="max-width:400px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
              title="${escapeHtml(detailsRaw)}">${escapeHtml(details)}</td>
          <td>${escapeHtml(user)}</td>
        `;
        tbody.appendChild(tr);
      });

      // Populate date filter with sorted dates (newest first)
      populateDateFilter(Array.from(datesSet));

      initActivityTable();
    })
    .catch(err => {
      console.error("Error loading activity:", err);
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Failed to load activity.</td></tr>`;
      populateDateFilter([]); // clear filter on error
    });

  // Populate date filter dropdown (accepts array of date strings "YYYY-MM-DD")
  function populateDateFilter(datesArr) {
    const dateFilter = document.getElementById("dateFilter");
    if (!dateFilter) return;

    // Remove duplicates already handled; sort descending (newest first)
    const sorted = (datesArr || []).slice().sort((a, b) => b.localeCompare(a));

    // clear and add "All"
    dateFilter.innerHTML = `<option value="">All Dates</option>`;

    sorted.forEach(date => {
      // show YYYY-MM-DD but you can format label if you want
      const opt = document.createElement("option");
      opt.value = date; // value used for filtering
      opt.textContent = date; // you can use a prettier format here if desired
      dateFilter.appendChild(opt);
    });
  }
}

// small helper: try to format 'YYYY-MM-DD HH:MM:SS' or ISO strings, else return input
function formatTimestamp(s) {
  if (!s) return "";
  // convert "YYYY-MM-DD HH:MM:SS" -> "YYYY-MM-DDTHH:MM:SS" then try Date()
  let iso = s.replace(' ', 'T');
  // if string lacks seconds or timezone, don't break – try Date parse and fallback
  const d = new Date(iso);
  if (!isNaN(d.getTime())) {
    return d.toLocaleString();
  }
  return s;
}

// basic escape to avoid injecting HTML
function escapeHtml(str) {
  if (typeof str !== 'string') return str;
  return str.replace(/[&<>"'`=\/]/g, function (s) {
    return ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;',
      '`': '&#x60;',
      '=': '&#x3D;'
    })[s];
  });
}


function deleteTransaction(id) {
  if (!confirm('Are you sure you want to delete this transaction?')) return;

  let formData = new FormData();
  formData.append("action", "deleteTransaction");
  formData.append("transaction_id", id);

  fetch("trackFunctions.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.message); // Optional: show deletion success
        loadTransactions(); // Reload your transaction table
      } else {
        alert("Failed: " + data.message);
      }
    })
    .catch(err => console.error("Error deleting transaction:", err));
}


function updateTable() {
  const table = document.getElementById('transactionTable');
  table.innerHTML = '';

  transactions.forEach(tx => {
    const row = document.createElement('tr');
    const statusClass = tx.status === 'Completed' ? 'status-completed' : 'status-in-progress';

    // disable confirm button unless Completed
    const confirmDisabled = tx.status !== 'Completed';
    const confirmBtnClass = confirmDisabled ? 'btn-secondary' : 'btn-success';
    const confirmAttr = confirmDisabled ? 'disabled' : '';

    row.innerHTML = `
      <td>${tx.t_code || '#' + tx.id}</td>
      <td>${tx.name}</td>
      <td>${tx.contact || ''}</td>
      <td>${tx.transaction}</td>
      <td>${tx.transaction_type || ''}</td> <!-- 🔹 show transaction type -->
      <td><span class="status-badge ${statusClass}">${tx.status}</span></td>
      <td>
        <button class="btn btn-edit btn-sm" onclick="openModal(${tx.id})">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-dark btn-sm" onclick="showDocuments(${tx.id})">
          <i class="fas fa-file-image"></i> Documents
        </button>
        <button class="btn btn-delete btn-sm" onclick="deleteTransaction(${tx.id})">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
      <td>
        <button class="btn ${confirmBtnClass} btn-sm" onclick="confirmTransaction(${tx.id})" ${confirmAttr}>
          <i class="fas fa-check"></i>
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

function checkTransaction(transactionId) {
  const checkbox = event.target;

  if (checkbox.checked) {
    console.log("Transaction " + transactionId + " marked as checked ");
  } else {
    console.log("Transaction " + transactionId + " unchecked ");
  }
}

// Updated JavaScript functions for received papers functionality

//Confirmation Modals
function confirmTransaction(transactionId) {
  // Find the transaction to check if it's completed
  const transaction = transactions.find(t => t.id === transactionId);

  if (!transaction) {
    alert('Transaction not found');
    return;
  }

  if (transaction.status !== 'Completed') {
    alert('Only completed transactions can be confirmed for receipt');
    return;
  }

  currentTransactionId = transactionId;

  // Update modal content to show transaction details
  const modalBody = document.querySelector('#confirmModal .modal-body');
  modalBody.innerHTML = `
    <div class="alert alert-info">
      <h6><i class="fas fa-info-circle"></i> Transaction Details</h6>
      <strong>Code:</strong> ${transaction.t_code}<br>
      <strong>Client:</strong> ${transaction.name}<br>
      <strong>Contact:</strong> ${transaction.contact}<br>
      <strong>Type:</strong> ${transaction.transaction_type}<br>
      <strong>Status:</strong> <span class="badge bg-success">${transaction.status}</span>
    </div>
    <p><strong>Are you sure you want to confirm that the papers have been received by the client?</strong></p>
    <p class="text-muted small">This action will mark the transaction as "Papers Received" and create a permanent record in the system.</p>
    <div class="mb-3">
      <label for="confirmNotes" class="form-label">Additional Notes (Optional):</label>
      <textarea id="confirmNotes" class="form-control" rows="3" placeholder="Enter any additional notes about the receipt (e.g., received by, special instructions, etc.)..."></textarea>
    </div>
  `;

  let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
  confirmModal.show();
}

// Confirm Transaction Functionality

let currentTransactionId = null;

// Show confirmation modal
function confirmTransaction(transactionId) {
  const transaction = transactions.find(t => t.id === transactionId);

  if (!transaction) {
    alert('Transaction not found');
    return;
  }

  if (transaction.status !== 'Completed') {
    alert('Only completed transactions can be confirmed for receipt');
    return;
  }

  currentTransactionId = transactionId;

  const modalBody = document.querySelector('#confirmModal .modal-body');
  modalBody.innerHTML = `
    <div class="alert alert-info">
      <h6><i class="fas fa-info-circle"></i> Transaction Details</h6>
      <strong>Code:</strong> ${transaction.t_code}<br>
      <strong>Client:</strong> ${transaction.name}<br>
      <strong>Contact:</strong> ${transaction.contact}<br>
      <strong>Type:</strong> ${transaction.transaction_type}<br>
      <strong>Status:</strong> <span class="badge bg-success">${transaction.status}</span>
    </div>
    <p><strong>Are you sure you want to confirm that the papers have been received by the client?</strong></p>
    <p class="text-muted small">This action will mark the transaction as "Papers Received" and create a permanent record in the system.</p>
    <div class="mb-3">
      <label for="confirmNotes" class="form-label">Additional Notes (Optional):</label>
      <textarea id="confirmNotes" class="form-control" rows="3" placeholder="Enter any additional notes..."></textarea>
    </div>
  `;

  new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

// Confirm button listener
document.addEventListener('DOMContentLoaded', function () {
  const confirmBtn = document.getElementById("confirmBtn");
  if (confirmBtn) {
    confirmBtn.addEventListener("click", function () {
      if (!currentTransactionId) return;

      const notes = document.getElementById('confirmNotes')?.value || '';

      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming...';

      const formData = new FormData();
      formData.append('action', 'confirmTransaction');
      formData.append('transaction_id', currentTransactionId);
      formData.append('notes', notes);

      fetch('receivedPapers.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert(data.message + '\n\nReceived on: ' + (data.received_date || 'Now'));
            if (typeof loadTransactions === 'function') loadTransactions();
            if (typeof loadActivity === 'function') loadActivity();
            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
          } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
          }
        })
        .catch(err => {
          console.error('Error confirming transaction:', err);
          alert('An error occurred while confirming the transaction. Please try again.');
        })
        .finally(() => {
          confirmBtn.disabled = false;
          confirmBtn.innerHTML = '<i class="fas fa-check"></i> Yes, Confirm';
          currentTransactionId = null;
        });
    });
  }
});

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

// Reset modal inputs when closed
document.addEventListener("DOMContentLoaded", () => {
  const modalEl = document.getElementById("transactionModal");
  if (!modalEl) return;

  modalEl.addEventListener("hidden.bs.modal", () => {
    // Reset all form fields inside modal
    modalEl.querySelectorAll("input, select, textarea").forEach(el => {
      if (el.type === "file") {
        el.value = ""; // clear file input
      } else {
        el.value = "";
      }
    });

    // Also reset requirements text
    const requirementsText = document.getElementById("requirementsText");
    if (requirementsText) {
      requirementsText.style.display = "none";
      requirementsText.innerText = "";
    }
  });
});

//Show Documents
function showDocuments(transactionId) {
  fetch(`trackFunctions.php?action=getDocuments&transaction_id=${transactionId}`)
    .then(res => res.json())
    .then(files => {
      const container = document.getElementById("documentsList");
      container.innerHTML = "";

      if (!Array.isArray(files) || files.length === 0) {
        container.innerHTML = "<p>No documents uploaded.</p>";
      } else {
        files.forEach(file => {
          const wrapper = document.createElement("div");
          wrapper.className = "doc-item mb-3 d-flex align-items-center justify-content-between";

          // Extract the file name from the path
          const fileName = file.original_name || file.file_path.split("/").pop();

          // Truncate file name (max 70 chars)
          let displayName = fileName.length > 70
            ? fileName.substring(0, 22) + "..."
            : fileName;

          wrapper.innerHTML = `
  <div class="d-flex align-items-center justify-content-between w-100">
    <!-- Left: Image -->
    <img src="${file.file_path}" class="img-fluid" style="max-height:100px; width:auto;">

    <!-- Middle: File name + date -->
    <div class="d-flex flex-column align-items-center justify-content-center flex-grow-1 mx-3" style="max-width: 300px; overflow: hidden;">
      <span class="doc-name text-truncate text-center w-100" title="${fileName}">
        ${displayName}
      </span>
      <small class="text-muted text-center">
        ${file.uploaded_at}
      </small>
    </div>

    <!-- Right: Delete button -->
    <button class="btn btn-sm btn-danger" onclick="deleteDocument(${file.file_id}, ${transactionId})">
      <i class="fas fa-trash"></i> Delete
    </button>
  </div>
`;
          container.appendChild(wrapper);
        });
      }

      new bootstrap.Modal(document.getElementById("documentsModal")).show();
    })
    .catch(err => console.error("Error loading documents:", err));
}

// Delete Document
function deleteDocument(fileId, transactionId) {
  if (!confirm("Delete this document?")) return;

  const formData = new FormData();
  formData.append("action", "deleteDocument");
  formData.append("file_id", fileId);

  fetch("trackFunctions.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Document deleted!");
        showDocuments(transactionId); // reload list
        if (typeof loadActivity === "function") loadActivity();

        // 🔹 Fix stuck backdrop
        const backdrops = document.querySelectorAll(".modal-backdrop");
        backdrops.forEach(bd => bd.remove());
        document.body.classList.remove("modal-open");
        document.body.style.paddingRight = "";
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(err => console.error("Error deleting document:", err));
}

function initActivityTable() {
  const rowsPerPage = 5;
  const tableBody = document.getElementById("activityTableBody");
  const pagination = document.getElementById("pagination");
  const searchInput = document.getElementById("searchInput");
  const dateFilter = document.getElementById("dateFilter");

  let rows = Array.from(tableBody.querySelectorAll("tr"));
  let currentPage = 1;
  let visibleRows = [...rows]; // keep a persistent array of visible rows

  function filterRows() {
    const searchText = searchInput.value.toLowerCase();
    const filterDate = dateFilter.value;

    visibleRows = rows.filter(row => {
      const rowText = row.innerText.toLowerCase();
      const rowDate = row.cells[0]?.getAttribute("data-date")?.split(" ")[0];
      const matchesSearch = rowText.includes(searchText);
      const matchesDate = !filterDate || rowDate === filterDate;
      return matchesSearch && matchesDate;
    });

    currentPage = 1; // reset page when filter changes
    showPage(currentPage);
    renderPagination();
  }

  function showPage(page) {
    visibleRows.forEach((row, index) => {
      row.style.display =
        index >= (page - 1) * rowsPerPage && index < page * rowsPerPage
          ? ""
          : "none";
    });
  }

  function renderPagination() {
    const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
    pagination.innerHTML = "";

    const ul = document.createElement("ul");
    ul.classList.add("pagination", "justify-content-center");

    // Previous
    const prevLi = document.createElement("li");
    prevLi.classList.add("page-item");
    if (currentPage === 1) prevLi.classList.add("disabled");
    prevLi.innerHTML = `<a class="page-link" href="#">&laquo; Previous</a>`;
    prevLi.addEventListener("click", e => {
      e.preventDefault();
      if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
        renderPagination();
      }
    });
    ul.appendChild(prevLi);

    // Page indicator
    const pageLi = document.createElement("li");
    pageLi.classList.add("page-item", "disabled");
    pageLi.innerHTML = `<span class="page-link">Page ${currentPage} of ${totalPages}</span>`;
    ul.appendChild(pageLi);

    // Next
    const nextLi = document.createElement("li");
    nextLi.classList.add("page-item");
    if (currentPage === totalPages) nextLi.classList.add("disabled");
    nextLi.innerHTML = `<a class="page-link" href="#">Next &raquo;</a>`;
    nextLi.addEventListener("click", e => {
      e.preventDefault();
      if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
        renderPagination();
      }
    });
    ul.appendChild(nextLi);

    pagination.appendChild(ul);
  }

  // Event listeners
  searchInput.oninput = filterRows;
  dateFilter.onchange = filterRows;

  // Initial render
  showPage(currentPage);
  renderPagination();
}

function generateQrFromModal() {
  const t_code = document.getElementById("transactionID").value.trim();
  if (!t_code) {
    alert("Please save the transaction first.");
    return;
  }

  const domain = "https://responsively-interfulgent-thad.ngrok-free.dev/erpts";// Replace with your actual domain
  const uploadUrl = `${domain}/mobile_upload.php?t_code=${encodeURIComponent(t_code)}`;

  //Using api.qrserver.com (stable public QR generator)
  const qrImage = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(uploadUrl)}`;

  //Calculate centered popup position
  const popupWidth = 400;
  const popupHeight = 450;
  const left = (window.screen.width / 2) - (popupWidth / 2);
  const top = (window.screen.height / 2) - (popupHeight / 2);

  //Open a centered popup window
  const qrWindow = window.open(
    '',
    'QR Upload',
    `width=${popupWidth},height=${popupHeight},left=${left},top=${top},resizable=no,scrollbars=no,status=no`
  );

  // ✅ Build HTML content safely (no document.write)
  const htmlContent = `
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8">
        <title>QR Upload</title>
        <style>
          body { 
            text-align:center; 
            font-family:Arial, sans-serif; 
            padding:20px; 
          }
          img { 
            width:250px; 
            height:250px; 
            margin:10px 0; 
            border:1px solid #ccc; 
          }
          h3 { margin-bottom:8px; }
          p { font-size:14px; }
        </style>
      </head>
      <body>
        <h3>Scan to Upload</h3>
        <img src="${qrImage}" alt="QR Code">
        <p>Scan this QR to upload documents for <b>${t_code}</b></p>
      </body>
    </html>
  `;

  // ✅ Write the content using DOM methods
  qrWindow.document.open();
  qrWindow.document.write(htmlContent);
  qrWindow.document.close();
}

document.addEventListener('DOMContentLoaded', () => {
  const qrBtn = document.getElementById('generateQrBtn');
  if (qrBtn) qrBtn.addEventListener('click', generateQrFromModal);
});

