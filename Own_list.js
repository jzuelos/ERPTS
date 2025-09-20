let currentPage = 1;
const rowsPerPage = 5;
let currentFilteredRows = [];
let viewAllMode = false; // ✅ new flag

// Handle Enter key on search
function handleEnter(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    filterTable();
  }
}

// Function to handle Enter key press in the modal search
function handleModalSearch(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    viewAllSearch();
  }
}

// Function to search the modal table
function viewAllSearch() {
  const input = document.getElementById("modalSearchInput").value.toLowerCase();
  const rows = document
    .getElementById("modalTable")
    .getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName("td");
    let match = false;

    for (let j = 0; j < cells.length; j++) {
      if (cells[j].textContent.toLowerCase().includes(input)) {
        match = true;
        break;
      }
    }

    rows[i].style.display = match ? "" : "none";
  }
}

// Main search filter
function filterTable() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const allRows = Array.from(
    document.querySelectorAll("#propertyTable tbody tr")
  );

  currentFilteredRows = allRows.filter(row =>
    Array.from(row.getElementsByTagName("td")).some(cell =>
      cell.textContent.toLowerCase().includes(input)
    )
  );

  allRows.forEach(row => (row.style.display = "none"));

  if (viewAllMode) {
    // ✅ If in View All mode, show all filtered
    currentFilteredRows.forEach(row => (row.style.display = ""));
  } else {
    updatePagination(currentFilteredRows);
    displayPage(1);
  }
}

// Build pagination dropdown
function updatePagination(filteredRows) {
  const pageSelect = document.getElementById("pageSelect");
  pageSelect.innerHTML = "";

  const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;

  for (let i = 1; i <= totalPages; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = i;
    pageSelect.appendChild(option);
  }

  if (currentPage > totalPages) currentPage = totalPages;
  if (currentPage < 1) currentPage = 1;

  pageSelect.value = currentPage;
  togglePaginationButtons(totalPages);
}

// Display rows for current page
function displayPage(page) {
  if (viewAllMode) return; // ✅ Skip pagination in View All mode

  const startIndex = (page - 1) * rowsPerPage;
  const endIndex = startIndex + rowsPerPage;

  currentFilteredRows.forEach(row => (row.style.display = "none"));
  currentFilteredRows.slice(startIndex, endIndex).forEach(row => {
    row.style.display = "";
  });

  currentPage = page;
  document.getElementById("pageSelect").value = page;
  togglePaginationButtons(Math.ceil(currentFilteredRows.length / rowsPerPage));
}

// Enable/disable back/next buttons
function togglePaginationButtons(totalPages) {
  document
    .getElementById("nextBtn")
    .classList.toggle("disabled", currentPage >= totalPages || viewAllMode);
  document
    .getElementById("backBtn")
    .classList.toggle("disabled", currentPage <= 1 || viewAllMode);
}

// ✅ Handle View All toggle
function toggleViewAll() {
  viewAllMode = !viewAllMode;

  if (viewAllMode) {
    // Show all
    currentFilteredRows.forEach(row => (row.style.display = ""));
    document.getElementById("pageSelect").disabled = true;
    togglePaginationButtons(0);
  } else {
    // Back to paginated view
    document.getElementById("pageSelect").disabled = false;
    updatePagination(currentFilteredRows);
    displayPage(1);
  }
}

// Initial Setup
document.addEventListener("DOMContentLoaded", () => {
  currentFilteredRows = Array.from(
    document.querySelectorAll("#propertyTable tbody tr")
  );

  updatePagination(currentFilteredRows);
  displayPage(1);

  // Pagination events
  document.getElementById("pageSelect").addEventListener("change", e => {
    displayPage(parseInt(e.target.value));
  });
  document.getElementById("nextBtn").addEventListener("click", () => {
    const totalPages = Math.ceil(currentFilteredRows.length / rowsPerPage);
    if (currentPage < totalPages) displayPage(currentPage + 1);
  });
  document.getElementById("backBtn").addEventListener("click", () => {
    if (currentPage > 1) displayPage(currentPage - 1);
  });

  // ✅ View All button
  document
    .querySelector(".btn-info[data-bs-target='#viewAllModal']")
    .addEventListener("click", toggleViewAll);

  // Reset modal search on open
  $('#viewAllModal').on("show.bs.modal", () => {
    document.getElementById("modalSearchInput").value = "";
    viewAllSearch();
  });
});
