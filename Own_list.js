let currentPage = 1; // Global current page variable
const rowsPerPage = 5; // Rows displayed per page

// Function to handle Enter key press (for both modal and main table)
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

function filterTable() {
  const input = document.getElementById("searchInput").value.toLowerCase(); 
  const table = document.getElementById("propertyTable");
  const tr = Array.from(table.getElementsByTagName("tr")).slice(1); 

  let filteredRows = []; 

  console.log("Starting filterTable function with search term:", input);


  if (input === "") {
    for (let row of tr) {
      row.style.display = ""; 
    }
    return; 
  }

  for (let row of tr) {
    const td = row.getElementsByTagName("td");

   
    let matchSearch = false;
    for (let i = 0; i < td.length - 1; i++) { 
      const cellText = td[i].textContent.toLowerCase(); 
      if (cellText.includes(input)) { 
        matchSearch = true;
        break; 
      }
    }

    if (matchSearch) {
      filteredRows.push(row);
      row.style.display = "";
      console.log(`Row matches search: ${row.innerHTML}`);
    } else {
      row.style.display = "none";
    }
  }

  updatePagination(filteredRows); // Update pagination for the filtered rows
  displayPage(1, filteredRows); // Reset to the first page for filtered results
  console.log("Filtered Rows:", filteredRows.length);
}

function updatePagination(filteredRows) {
  const pageSelect = document.getElementById("pageSelect");
  pageSelect.innerHTML = ""; // Clear current options

  const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
  console.log(`Total pages calculated: ${totalPages} for ${filteredRows.length} filtered rows`);

  for (let i = 1; i <= totalPages; i++) {
    const option = document.createElement("option");
    option.value = i;
    option.textContent = i;
    pageSelect.appendChild(option);
  }

  currentPage = Math.min(currentPage, totalPages);
  pageSelect.value = currentPage;
  togglePaginationButtons(filteredRows);
}

function displayPage(page, filteredRows) {
  const startIndex = (page - 1) * rowsPerPage;
  const endIndex = startIndex + rowsPerPage;

  console.log(`Displaying page ${page}: Showing rows from index ${startIndex} to ${endIndex - 1}`);
  filteredRows.forEach(row => row.style.display = "none");
  filteredRows.slice(startIndex, endIndex).forEach(row => row.style.display = "");

  currentPage = page; // Update global currentPage
  togglePaginationButtons(filteredRows);
}

function togglePaginationButtons(filteredRows) {
  const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
  document.getElementById("nextBtn").disabled = currentPage >= totalPages;
  document.getElementById("backBtn").disabled = currentPage <= 1;
}

// Event listener for the page dropdown
document.getElementById("pageSelect").addEventListener("change", (event) => {
  const selectedPage = parseInt(event.target.value);
  const filteredRows = Array.from(document.getElementById("propertyTable").getElementsByTagName("tr")).slice(1);
  displayPage(selectedPage, filteredRows);
});

// Event listener for the "Next" button
document.getElementById("nextBtn").addEventListener("click", () => {
  const filteredRows = Array.from(document.getElementById("propertyTable").getElementsByTagName("tr")).slice(1);
  if (currentPage < Math.ceil(filteredRows.length / rowsPerPage)) {
    displayPage(currentPage + 1, filteredRows);
  }
});

// Event listener for the "Back" button
document.getElementById("backBtn").addEventListener("click", () => {
  const filteredRows = Array.from(document.getElementById("propertyTable").getElementsByTagName("tr")).slice(1);
  if (currentPage > 1) {
    displayPage(currentPage - 1, filteredRows);
  }
});

// Initial setup - display the first page of all rows
document.addEventListener("DOMContentLoaded", () => {
  filterTable();
});
