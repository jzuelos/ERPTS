
function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const dropdown = document.getElementById("barangayDropdown");
    const selectedBarangay = dropdown.value.toLowerCase();
    const table = document.getElementById("propertyTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
      const td = tr[i].getElementsByTagName("td");
      const locationText = td[2].textContent.toLowerCase();
      let matchSearch = false;
      let matchBarangay = selectedBarangay === "" || locationText.includes(selectedBarangay);

      // Check if any of the search fields match
      for (let j = 0; j < td.length - 2; j++) { // Exclude the edit and land area
        if (td[j]) {
          const txtValue = td[j].textContent || td[j].innerText;
          if (txtValue.toLowerCase().indexOf(filter) > -1) {
            matchSearch = true;
            break;
          }
        }
      }

      tr[i].style.display = matchSearch && matchBarangay ? "" : "none"; 
    }
  }