//View All Modal
document.addEventListener("DOMContentLoaded", function() {
  const mainTableRows = Array.from(document.querySelectorAll("#dataTable tbody tr"));
  const modalTableBody = document.querySelector("#modalTable tbody");
  const modalSearchInput = document.getElementById("modalSearchInput");

  // When modal opens, populate all rows
  const viewAllModal = document.getElementById("viewAllModal");
  viewAllModal.addEventListener("show.bs.modal", function () {
    modalTableBody.innerHTML = "";
    mainTableRows.forEach(row => {
      const clone = row.cloneNode(true);
      modalTableBody.appendChild(clone);
    });
  });

  // Search inside modal
  modalSearchInput.addEventListener("keyup", function() {
    const query = modalSearchInput.value.toLowerCase();
    Array.from(modalTableBody.querySelectorAll("tr")).forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(query) ? "" : "none";
    });
  });
});

//Search Function in Main Table
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById("searchInput");
  const filterBtn = document.getElementById("filterBtn");
  const table = document.getElementById("dataTable").querySelector("tbody");

  function searchTable() {
    const rows = Array.from(table.querySelectorAll("tr")); // fresh rows
    const query = searchInput.value.toLowerCase().trim();
    let found = false;

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      if (text.includes(query)) {
        row.style.display = "";
        found = true;
      } else {
        row.style.display = "none";
      }
    });

    if (!found) {
      table.innerHTML = `<tr><td colspan="2">No records found.</td></tr>`;
    }
  }

  filterBtn.addEventListener("click", function(e) {
    e.preventDefault();
    searchTable();
  });

  searchInput.addEventListener("keyup", function(e) {
    if (e.key === "Enter") searchTable();
  });
});

//Pagination Function
document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("dataTable").getElementsByTagName("tbody")[0];
  const rows = Array.from(table.getElementsByTagName("tr"));
  const rowsPerPage = 5;  // number of rows per page
  let currentPage = 1;
  const paginationContainer = document.getElementById("paginationControls");

  function renderTable() {
    table.innerHTML = "";
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageRows = rows.slice(start, end);

    if (pageRows.length === 0) {
      table.innerHTML = "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
    } else {
      pageRows.forEach(row => table.appendChild(row));
    }

    renderPagination();
  }

//Resetting Modal when closed
const viewAllModal = document.getElementById('viewAllModal');
viewAllModal.addEventListener('hidden.bs.modal', function () {
    // Reset search input
    const searchInput = document.getElementById('modalSearchInput');
    if (searchInput) searchInput.value = '';

    // Reset the modal table to show all rows
    const modalTable = document.getElementById('modalTable').getElementsByTagName('tbody')[0];
    const originalRows = Array.from(modalTable.querySelectorAll('tr'));
    originalRows.forEach(row => row.style.display = ''); // show all rows
});



  function renderPagination() {
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    paginationContainer.innerHTML = "";

    // Previous Button
    const prevBtn = document.createElement("button");
    prevBtn.textContent = "Previous";
    prevBtn.className = "btn btn-outline-success me-2";
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener("click", () => {
      currentPage--;
      renderTable();
    });
    paginationContainer.appendChild(prevBtn);

    // Page Numbers
    for (let i = 1; i <= totalPages; i++) {
      const pageBtn = document.createElement("button");
      pageBtn.textContent = i;
      pageBtn.className = "btn btn-sm me-1 " + (i === currentPage ? "btn-success" : "btn-outline-success");
      pageBtn.addEventListener("click", () => {
        currentPage = i;
        renderTable();
      });
      paginationContainer.appendChild(pageBtn);
    }

    // Next Button
    const nextBtn = document.createElement("button");
    nextBtn.textContent = "Next";
    nextBtn.className = "btn btn-outline-success ms-2";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener("click", () => {
      currentPage++;
      renderTable();
    });
    paginationContainer.appendChild(nextBtn);
  }

  renderTable();
});