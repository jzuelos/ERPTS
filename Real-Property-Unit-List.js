let currentPage = 1;  // To keep track of the current page
const rowsPerPage = 5; // Number of rows to display per page
let filteredRows = []; // Array to store filtered rows
let rows = []; // All rows from the table

// Get table and pagination elements
const table = document.getElementById("propertyTable");
const pageSelect = document.getElementById("pageSelect");
const viewAllButton = document.querySelector(".view-all-container button");

function filterTable() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const selectedBarangay = document.getElementById("barangayDropdown").value.toLowerCase();

  filteredRows = []; // Reset filtered rows

  // Loop through all rows to apply the filter logic
  for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
    const cells = rows[i].getElementsByTagName("td");
    const ownerText = cells[1].textContent.toLowerCase(); // Assuming "Owner" is in the first column
    const locationText = cells[2].textContent.toLowerCase(); // Assuming "Location" is in the third column
    let matchSearch = false;
    let matchBarangay = !selectedBarangay || locationText.includes(selectedBarangay); // Check barangay match

    // Check if the owner matches the search input
    if (ownerText.includes(input)) {
      matchSearch = true;
    }

    // If both search and barangay filters match, add row to filtered rows
    if (matchSearch && matchBarangay) {
      filteredRows.push(rows[i]);
    }
  }

  // Update pagination controls based on the filtered rows
  updatePagination(filteredRows.length);

  // Display the appropriate rows based on the current page
  displayPage(currentPage);
}

function updatePagination(totalRows) {
  const totalPages = Math.ceil(totalRows / rowsPerPage);  // Calculate total number of pages

  // Clear existing options
  pageSelect.innerHTML = "";

  // Create options for each page
  for (let i = 1; i <= totalPages; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = `${i}`;
    pageSelect.appendChild(option);
  }

  // Enable/disable "View All" button based on filtered rows
  if (totalRows > rowsPerPage) {
    viewAllButton.disabled = false;
  } else {
    viewAllButton.disabled = true;
  }

  // Ensure current page is still valid
  if (currentPage > totalPages) {
    currentPage = totalPages; // If we're on a page that no longer exists after filtering, move to last page
  }

  pageSelect.value = currentPage; // Update page select dropdown
}

function displayPage(page) {
  const startIndex = (page - 1) * rowsPerPage;
  const endIndex = startIndex + rowsPerPage;

  // Hide all rows first
  for (let i = 1; i < rows.length; i++) {
    rows[i].style.display = "none";
  }

  // Show the rows for the current page
  const pageRows = filteredRows.slice(startIndex, endIndex);
  pageRows.forEach(row => {
    row.style.display = "";
  });

  // Show a message if no rows match the filter
  const noResultsMessage = document.getElementById("noResultsMessage");
  if (filteredRows.length === 0) {
    if (!noResultsMessage) {
      const messageRow = table.insertRow();
      const messageCell = messageRow.insertCell(0);
      messageCell.colSpan = table.rows[0].cells.length;
      messageCell.id = "noResultsMessage";
      messageCell.textContent = "No records found.";
      messageCell.style.textAlign = "center";
    }
  } else {
    const message = document.getElementById("noResultsMessage");
    if (message) {
      message.remove();
    }
  }
}

function changePage() {
  const page = parseInt(pageSelect.value);
  currentPage = page;
  displayPage(currentPage); // Reapply the display logic when page changes
}

// Optional: Reset pagination and table when the "View All" button is clicked
viewAllButton.addEventListener("click", () => {
  currentPage = 1;  // Reset to the first page
  pageSelect.value = 1;  // Reset dropdown to first page
  filterTable(); // Apply filter again to reset the table
});

// Event listener for when the page is loaded and DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  // Cache rows and initialize table
  rows = Array.from(table.rows); // Store all rows (including the header)

  // Initial filter and page setup
  filterTable(); // Apply filters and display the table

  // Add event listener for page selection change
  pageSelect.addEventListener("change", changePage);

  // Add event listener for the search button click
  document.getElementById("searchButton").addEventListener("click", filterTable);

  // Add event listener for the Enter key to trigger the search
  document.getElementById("searchInput").addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      filterTable();
    }
  });

  // Add event listener to the barangay dropdown (but don't trigger filter here)
  document.getElementById("barangayDropdown").addEventListener("change", () => {
    // Do not call filterTable directly here
    // We will handle this when the user clicks the search button or presses Enter
  });
});
