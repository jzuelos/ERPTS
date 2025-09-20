let currentPage = 1;
const rowsPerPage = 5;
let currentFilteredRows = [];

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

// Function to search the modal table based on the input
function viewAllSearch() {
  const input = document.getElementById("modalSearchInput").value.toLowerCase(); 
  const table = document.getElementById("modalTable");
  const rows = table.getElementsByTagName("tr"); 
  const modalTableBody = document.getElementById("modalTableBody"); 

  if (input === "") {
    for (let row of rows) {
      row.style.display = ""; 
    }
    return; 
  }

  for (let i = 1; i < rows.length; i++) { 
    const cells = rows[i].getElementsByTagName("td");
    let matchSearch = false;

    for (let j = 0; j < cells.length; j++) { 
      const cellText = cells[j].textContent.toLowerCase(); 
      if (cellText.includes(input)) { 
        matchSearch = true;
        break; 
      }
    }

    rows[i].style.display = matchSearch ? "" : "none"; 
  }
}


// Main search filter
function filterTable() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const table = document.getElementById("propertyTable");
  const allRows = Array.from(table.querySelectorAll("tbody tr"));

  currentFilteredRows = allRows.filter(row => {
    const cells = row.getElementsByTagName("td");
    return Array.from(cells).some(cell =>
      cell.textContent.toLowerCase().includes(input)
    );
  });

  // Hide all initially
  allRows.forEach(row => (row.style.display = "none"));

  // Update pagination and reset to page 1
  updatePagination(currentFilteredRows);
  displayPage(1);
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
    .classList.toggle("disabled", currentPage >= totalPages);
  document
    .getElementById("backBtn")
    .classList.toggle("disabled", currentPage <= 1);
}

// Initial Setup
document.addEventListener("DOMContentLoaded", () => {
  const allRows = Array.from(
    document.getElementById("propertyTable").querySelectorAll("tbody tr")
  );
  currentFilteredRows = allRows;

  updatePagination(currentFilteredRows);
  displayPage(1);

  // Dropdown change
  document.getElementById("pageSelect").addEventListener("change", e => {
    displayPage(parseInt(e.target.value));
  });

  // Next button
  document.getElementById("nextBtn").addEventListener("click", () => {
    const totalPages = Math.ceil(currentFilteredRows.length / rowsPerPage);
    if (currentPage < totalPages) {
      displayPage(currentPage + 1);
    }
  });

  // Back button
  document.getElementById("backBtn").addEventListener("click", () => {
    if (currentPage > 1) {
      displayPage(currentPage - 1);
    }
  });

  // Reset modal search on open
  $('#viewAllModal').on('show.bs.modal', function () {
    document.getElementById("modalSearchInput").value = "";
    viewAllSearch(); // Reset rows when opening modal
  });
});


