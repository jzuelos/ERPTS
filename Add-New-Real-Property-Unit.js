document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('propertyForm');
    const ownerSearchForm = document.getElementById('ownerSearchForm');
    const selectedOwnerDisplay = document.getElementById('selectedOwnerDisplay'); // Display area for selected owner IDs

    // Function to update the display of selected owner IDs
    function updateSelectedOwners() {
        // Get selected IDs from checkboxes
        const selectedIds = Array.from(document.querySelectorAll('input[name="selected_ids[]"]:checked')).map(cb => cb.value);

        // Display the selected IDs
        selectedOwnerDisplay.innerHTML = selectedIds.length > 0
            ? `<p>Selected Owner IDs: ${selectedIds.join(', ')}</p>`
            : '<p>No owners selected.</p>'; // Message when no owners are selected

        // Update hidden input in main form with selected IDs
        document.getElementById('selected_owner_ids').value = selectedIds.join(','); // Store as a comma-separated string
    }

    // Owner search form submission
    ownerSearchForm.addEventListener("submit", async function (event) {
        event.preventDefault(); // Prevent page reload

        // Collect form data
        const formData = new FormData(ownerSearchForm);

        try {
            // Send data using fetch API
            const response = await fetch("http://localhost/ERPTS/func_sOwn.php", {
                method: "POST",
                body: formData
            });

            const result = await response.text(); // Get the server response

            // Inject the result into the table body for displaying
            document.getElementById("resultsBody").innerHTML = result;

            // Update the display of selected owners after loading new results
            updateSelectedOwners();

        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while searching.");
        }
    });

    // Event listener for checkboxes to update selected IDs display
    document.addEventListener('change', function (event) {
        if (event.target.matches('input[name="selected_ids[]"]')) {
            updateSelectedOwners(); // Update the selected owners display on checkbox change
        }
    });

    // Main form submission
    mainForm.addEventListener('submit', function (event) {
        if (!validateDocumentsForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});




function performSearch() {
    // Define the search functionality here
    alert("Search function executed");
    // Add logic to retrieve and display search results
}

// Function to clear the owner search form
function clearOwnerSearchForm() {
    document.getElementById('owner_search').value = ''; // Clear the owner search input
}

function clearMainForm() {
    document.getElementById('house_number').value = '';
    document.getElementById('block_number').value = '';
    document.getElementById('house_tag_number').value = '';
    document.getElementById('land_area').value = '';
    document.getElementById('lot_no').value = '';
    document.getElementById('zone_no').value = '';

    // Reset selects
    document.getElementById('province').selectedIndex = 0;
    document.getElementById('municipality').selectedIndex = 0; // municipality, not city
    document.getElementById('district').value = '';
    const barangay = document.getElementById('barangay');
    if (barangay) {
      barangay.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
      barangay.setAttribute('disabled', 'disabled');
      barangay.required = false;
    }

    // Uncheck documents checkboxes
    document.querySelectorAll('input[name="documents[]"]').forEach(checkbox => checkbox.checked = false);
}

// Form validation function for the documents form
function validateDocumentsForm() {
    const checkboxes = document.querySelectorAll('input[name="documents[]"]');
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked); // Check if any checkbox is checked

    if (!isChecked) {
        alert('Please select at least one document.'); // Alert user if no checkbox is checked
        return false; // Prevent form submission
    } else {
        return true; // Allow form submission
    }
}

// Function to handle the change event for the municipality select element
// and update the district input field accordingly
document.addEventListener('DOMContentLoaded', function () {
  const municipalitySelect = document.getElementById('municipality');
  const districtInput = document.getElementById('district');
  const barangaySelect = document.getElementById('barangay');

  if (!municipalitySelect || !barangaySelect) {
    console.warn('municipality or barangay select not found');
    return;
  }

  // Cache original barangay options that include data-municipality
  const originalBarangayOptions = Array.from(barangaySelect.options)
    .filter(opt => opt.dataset && opt.dataset.municipality)
    .map(opt => ({ value: opt.value, text: opt.textContent.trim(), m: opt.dataset.municipality }));

  console.log('Cached barangays count:', originalBarangayOptions.length);

  function placeholderOption(text = 'Select Barangay') {
    const p = document.createElement('option');
    p.value = '';
    p.disabled = true;
    p.selected = true;
    p.textContent = text;
    return p;
  }

  function filterBarangays() {
    const selectedM = municipalitySelect.value;
    console.log('Municipality changed ->', selectedM);

    // Clear and add placeholder
    barangaySelect.innerHTML = '';
    barangaySelect.appendChild(placeholderOption());

    if (!selectedM) {
      barangaySelect.setAttribute('disabled', 'disabled');
      barangaySelect.required = false;
      return;
    }

    const matches = originalBarangayOptions.filter(o => o.m === selectedM);
    console.log('Matched barangays:', matches);

    if (matches.length) {
      barangaySelect.removeAttribute('disabled');
      barangaySelect.required = true;
      matches.forEach(o => {
        const el = document.createElement('option');
        el.value = o.value;
        el.textContent = o.text;
        barangaySelect.appendChild(el);
      });
      // keep placeholder selected so user chooses one
      barangaySelect.querySelector('option[value=""]').selected = true;
    } else {
      const none = document.createElement('option');
      none.disabled = true;
      none.textContent = 'No barangays available';
      barangaySelect.appendChild(none);
      barangaySelect.setAttribute('disabled', 'disabled');
      barangaySelect.required = false;
    }
  }

  // municipality -> district autofill
  if (districtInput) {
    municipalitySelect.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const districtName = selectedOption ? (selectedOption.getAttribute('data-district') || '') : '';
      districtInput.value = districtName;
      filterBarangays();
    });
  } else {
    municipalitySelect.addEventListener('change', filterBarangays);
  }

  // set initial state on load (for edit pages)
  if (municipalitySelect.value) {
    filterBarangays();
  } else {
    barangaySelect.setAttribute('disabled', 'disabled');
    barangaySelect.required = false;
  }
});

  