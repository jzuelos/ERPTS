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
      document.getElementById('transactionType').value = tx.transaction_type || '';
      document.getElementById('statusInput').value = tx.status || '';
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
    document.getElementById('statusInput').selectedIndex = 0;
    document.getElementById('transactionType').selectedIndex = 0;
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
        return;
      }

      data.forEach(item => {
        // fields returned by backend: created_at, t_code, action, details, user, transaction_id
        const createdAtRaw = item.created_at || "";
        const tcode = item.t_code || ("#" + (item.transaction_id || ""));
        const action = item.action || "";
        const detailsRaw = item.details || "";
        const user = item.user || "";

        // truncate long details to 120 chars
        const details = (detailsRaw.length > 120) ? (detailsRaw.slice(0, 117) + '...') : detailsRaw;

        // Attempt a friendly date/time. If parse fails, fall back to raw string.
        const prettyDate = formatTimestamp(createdAtRaw);

        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td style="white-space:nowrap">${prettyDate}</td>
          <td>${escapeHtml(tcode)}</td>
          <td>${escapeHtml(action)}</td>
          <td title="${escapeHtml(detailsRaw)}">${escapeHtml(details)}</td>
          <td>${escapeHtml(user)}</td>
        `;
        tbody.appendChild(tr);
      });
    })
    .catch(err => {
      console.error("Error loading activity:", err);
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Failed to load activity.</td></tr>`;
    });
}

// small helper: try to format 'YYYY-MM-DD HH:MM:SS' or ISO strings, else return input
function formatTimestamp(s) {
  if (!s) return "";
  // convert "YYYY-MM-DD HH:MM:SS" -> "YYYY-MM-DDTHH:MM:SS" then try Date()
  let iso = s.replace(' ', 'T');
  // if string lacks seconds or timezone, don't break — try Date parse and fallback
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

//Confirmation Modals
function confirmTransaction(transactionId) {
  currentTransactionId = transactionId;
  let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
  confirmModal.show();
}
let currentTransactionId = null;
document.getElementById("confirmBtn").addEventListener("click", function () {
  if (currentTransactionId) {
    console.log("Confirmed transaction:", currentTransactionId);
    // TODO: send AJAX request to PHP to update status in DB
  }
  bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
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
          const fileName = file.file_path.split("/").pop();

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
