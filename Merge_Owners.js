document.addEventListener('DOMContentLoaded', function () {
    // Get references to elements
    const mergeOwnersButton = document.getElementById('mergeOwnersButton');
    const modalBody = document.getElementById('modalBody');
    const mergeOwnersModal = $('#mergeOwnersModal'); // jQuery reference to the modal
    const checkboxes = document.querySelectorAll('input[name="personChoice[]"]');

    // Retrieve the selected checkboxes from localStorage on page load
    const selectedOwners = JSON.parse(localStorage.getItem('selectedOwners')) || [];

    // Add event listener to checkboxes to update localStorage when they are checked/unchecked
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // If the checkbox is checked, add its value to selectedOwners
            if (checkbox.checked) {
                selectedOwners.push(checkbox.value);
            } else {
                // If unchecked, remove its value from selectedOwners
                const index = selectedOwners.indexOf(checkbox.value);
                if (index !== -1) {
                    selectedOwners.splice(index, 1);
                }
            }
            // Save the updated selectedOwners array in localStorage
            localStorage.setItem('selectedOwners', JSON.stringify(selectedOwners));
        });
    });

    // When the "Merge Owners" button is clicked
    mergeOwnersButton.addEventListener('click', function () {
        // Get all the selected checkboxes
        const selectedCheckboxes = document.querySelectorAll('input[name="personChoice[]"]:checked');

        // If fewer than two checkboxes are selected, show an alert and don't open the modal
        if (selectedCheckboxes.length < 2) {
            alert("Please select at least two owners to merge.");
            return; // Exit without opening the modal
        }

        // Prepare the modal body container
        modalBody.innerHTML = '';  // Clear any existing rows

        let selectedPersonID = null; // To store the retained person ID
        let selectedProperties = [];

        selectedCheckboxes.forEach(function (checkbox) {
            const row = checkbox.closest('tr');
            const personID = row.cells[0].textContent; // Person ID (owner_id)
            const lastName = row.cells[2].textContent;
            const firstName = row.cells[3].textContent;
            const middleName = row.cells[4].textContent;
            const address = row.cells[5].textContent;

            // Create a new row for the modal
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="center-input"><input type="radio" name="personChoiceModal" value="${personID}" checked></td>
                <td class="center-input"><input type="checkbox" name="showProperties[]" value="${personID}"></td>
                <td>${personID}</td>
                <td>${lastName}</td>
                <td>${firstName}</td>
                <td>${middleName}</td>
                <td>${address}</td>
            `;
            modalBody.appendChild(newRow);

            // Collect selected personID and properties
            if (selectedPersonID === null) {
                selectedPersonID = personID; // Set the first selected person as the one to retain
            }
            selectedProperties.push(personID); // Add each selected property
        });

        // Populate the hidden input fields with the selected values
        document.getElementById('selectedPersonID').value = selectedPersonID;
        document.getElementById('selectedProperties').value = JSON.stringify(selectedProperties); // Convert array to string

        // Show the modal using jQuery
        mergeOwnersModal.modal('show');
    });
});

// Function to search and filter the table
function searchTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("ownerTableBody");
    const rows = table.getElementsByTagName("tr");
  
    for (let i = 0; i < rows.length; i++) {
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
  