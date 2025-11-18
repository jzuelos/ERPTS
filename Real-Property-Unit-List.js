// Function to handle Enter key press (for both modal and main table)
function handleEnter(event) {
  if (event.key === "Enter") {
    event.preventDefault(); // Prevent form submission or other default behaviors

    // Check if the search is within the modal
    const modalInput = document.getElementById("modalSearchInput"); // Modal search input field
    const mainInput = document.getElementById("searchInput"); // Main table search input field

    // If modal search input is focused, trigger the modal search function
    if (document.activeElement === modalInput) {
      viewAllSearch(); // Trigger modal search
    }
    // If main table search input is focused, trigger the main table search function
    else if (document.activeElement === mainInput) {
      filterTable(); // Trigger main table search
    }
  }
}


// Function to filter the main table based on the search input and barangay selection
function filterTable() {
  const input = document.getElementById("searchInput"); // Get the search input field for the main table
  const filter = input.value.toLowerCase(); // Convert to lowercase for case-insensitive comparison

  const dropdown = document.getElementById("barangayDropdown"); // Get the barangay dropdown
  const selectedBarangay = dropdown.value.toLowerCase(); // Get selected barangay value

  const table = document.getElementById("propertyTable"); // Get the property table
  const tr = table.getElementsByTagName("tr"); // Get all rows in the table

  // Loop through each row in the table
  for (let i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
    const td = tr[i].getElementsByTagName("td"); // Get all td (table data) elements for the row
    const locationText = td[2].textContent.toLowerCase(); // Get the location text (barangay, city, etc.)

    let matchSearch = false; // To track if the search term matches any row
    let matchBarangay = selectedBarangay === "" || locationText.includes(selectedBarangay); // Check if barangay matches or "All Barangay" is selected

    for (let j = 0; j < td.length; j++) {

      // Include normal cell text
      let txtValue = td[j].textContent.toLowerCase();

      // Also include hidden block_no + house_no
      const extra = td[j].querySelector(".search-extra");
      if (extra) {
        txtValue += " " + extra.textContent.toLowerCase();
      }

      if (txtValue.indexOf(filter) > -1) {
        matchSearch = true;
        break;
      }
    }


    // Show or hide row based on the search and barangay match
    tr[i].style.display = matchSearch && matchBarangay ? "" : "none";
  }
}
