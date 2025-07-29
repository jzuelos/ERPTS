function filterTable() {
    const input = document.querySelector('input[type="text"]').value.toLowerCase();
    const table = document.querySelector('.modern-table');
    const rows = table.getElementsByTagName('tr');
  
    // Loop through all table rows, excluding the header
    for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      let match = false;
  
      // Check each cell, excluding the "Property Value" (index 3) and "Actions" (index 5)
      for (let j = 0; j < cells.length; j++) {
        if (j === 3 || j === 5) continue; // Skip "Property Value" and "Actions" columns
  
        if (cells[j].innerText.toLowerCase().includes(input)) {
          match = true;
          break;
        }
      }
  
      // Show or hide the row based on whether a match was found
      rows[i].style.display = match ? '' : 'none';
    }
  }