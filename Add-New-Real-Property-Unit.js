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
  const mainForm = document.getElementById('propertyForm');
  const ownerSearchForm = document.getElementById('ownerSearchForm');
  const selectedOwnerDisplay = document.getElementById('selectedOwnerDisplay');

  // Function to update the display of selected owner IDs
  function updateSelectedOwners() {
    const selectedIds = Array.from(document.querySelectorAll('input[name="selected_ids[]"]:checked')).map(cb => cb.value);

    selectedOwnerDisplay.innerHTML = selectedIds.length > 0
      ? `<p>Selected Owner IDs: ${selectedIds.join(', ')}</p>`
      : '<p>No owners selected.</p>';

    document.getElementById('selected_owner_ids').value = selectedIds.join(',');
  }

  // Owner search form submission
  ownerSearchForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(ownerSearchForm);

    try {
      const response = await fetch("http://localhost/ERPTS/func_sOwn.php", {
        method: "POST",
        body: formData
      });

      const result = await response.text();
      document.getElementById("resultsBody").innerHTML = result;
      updateSelectedOwners();

    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while searching.");
    }
  });

  // Event listener for checkboxes to update selected IDs display
  document.addEventListener('change', function (event) {
    if (event.target.matches('input[name="selected_ids[]"]')) {
      updateSelectedOwners();
    }
  });

  // Main form submission
  mainForm.addEventListener('submit', function (event) {
    if (!validateDocumentsForm()) {
      event.preventDefault();
    }
  });
});

function performSearch() {
  alert("Search function executed");
}

function clearOwnerSearchForm() {
  document.getElementById('owner_search').value = '';
}

function clearMainForm() {
  document.getElementById('house_number').value = '';
  document.getElementById('block_number').value = '';
  document.getElementById('house_tag_number').value = '';
  document.getElementById('land_area').value = '';
  document.getElementById('lot_no').value = '';
  document.getElementById('zone_no').value = '';

  document.getElementById('province').selectedIndex = 0;
  document.getElementById('municipality').selectedIndex = 0;
  document.getElementById('district').value = '';
  const barangay = document.getElementById('barangay');
  if (barangay) {
    barangay.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
    barangay.setAttribute('disabled', 'disabled');
    barangay.required = false;
  }

  document.querySelectorAll('input[name="documents[]"]').forEach(checkbox => checkbox.checked = false);
}

function validateDocumentsForm() {
  const checkboxes = document.querySelectorAll('input[name="documents[]"]');
  const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

  if (!isChecked) {
    alert('Please select at least one document.');
    return false;
  } else {
    return true;
  }
}

// =============================================================================
// INITIALIZATION AND OWNER SELECTION
// =============================================================================

document.addEventListener('DOMContentLoaded', function () {
  const mainForm = document.getElementById('propertyForm');
  const ownerSearchForm = document.getElementById('ownerSearchForm');
  const selectedOwnerDisplay = document.getElementById('selectedOwnerDisplay');

  // Function to update the display of selected owner IDs
  function updateSelectedOwners() {
    const selectedIds = Array.from(document.querySelectorAll('input[name="selected_ids[]"]:checked'))
      .map(cb => cb.value);

    selectedOwnerDisplay.innerHTML = selectedIds.length > 0
      ? `<p>Selected Owner IDs: ${selectedIds.join(', ')}</p>`
      : '<p>No owners selected.</p>';

    document.getElementById('selected_owner_ids').value = selectedIds.join(',');
  }

  // Owner search form submission
  ownerSearchForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    const formData = new FormData(ownerSearchForm);

    try {
      const response = await fetch("http://localhost/ERPTS/func_sOwn.php", {
        method: "POST",
        body: formData
      });

      const result = await response.text();
      document.getElementById("resultsBody").innerHTML = result;
      updateSelectedOwners();

    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while searching.");
    }
  });

  // Event listener for checkboxes to update selected IDs display
  document.addEventListener('change', function (event) {
    if (event.target.matches('input[name="selected_ids[]"]')) {
      updateSelectedOwners();
    }
  });

  // Main form submission with validation
  mainForm.addEventListener('submit', function (event) {
    if (!validateDocumentsForm()) {
      event.preventDefault();
    }
  });
});

// =============================================================================
// FORM UTILITY FUNCTIONS
// =============================================================================

function performSearch() {
  alert("Search function executed");
}

function clearOwnerSearchForm() {
  document.getElementById('owner_search').value = '';
}

function clearMainForm() {
  document.getElementById('house_number').value = '';
  document.getElementById('block_number').value = '';
  document.getElementById('house_tag_number').value = '';
  document.getElementById('land_area').value = '';
  document.getElementById('lot_no').value = '';
  document.getElementById('zone_no').value = '';

  document.getElementById('province').selectedIndex = 0;
  document.getElementById('municipality').selectedIndex = 0;
  document.getElementById('district').value = '';

  const barangay = document.getElementById('barangay');
  if (barangay) {
    barangay.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
    barangay.setAttribute('disabled', 'disabled');
    barangay.required = false;
  }

  document.querySelectorAll('input[name="documents[]"]').forEach(checkbox => checkbox.checked = false);
}

function validateDocumentsForm() {
  const checkboxes = document.querySelectorAll('input[name="documents[]"]');
  const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

  if (!isChecked) {
    alert('Please select at least one document.');
    return false;
  }
  return true;
}

// =============================================================================
// MUNICIPALITY AND BARANGAY FILTERING
// =============================================================================

document.addEventListener('DOMContentLoaded', function () {
  const municipalitySelect = document.getElementById('municipality');
  const districtInput = document.getElementById('district');
  const barangaySelect = document.getElementById('barangay');

  if (!municipalitySelect || !barangaySelect) {
    console.warn('Municipality or barangay select not found');
    return;
  }

  // Cache original barangay options with their municipality IDs
  const originalBarangayOptions = Array.from(barangaySelect.options)
    .filter(opt => opt.dataset && opt.dataset.municipalityId)
    .map(opt => ({
      value: opt.value,
      text: opt.textContent.trim(),
      municipalityId: opt.dataset.municipalityId
    }));

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
    // Get the selected municipality option
    const selectedOption = municipalitySelect.options[municipalitySelect.selectedIndex];
    const selectedMunicipalityId = selectedOption ? selectedOption.dataset.municipalityId : null;

    console.log('Municipality changed ->', municipalitySelect.value);
    console.log('Municipality ID ->', selectedMunicipalityId);

    // Clear barangay dropdown and add placeholder
    barangaySelect.innerHTML = '';
    barangaySelect.appendChild(placeholderOption());

    // If no municipality selected, disable barangay
    if (!selectedMunicipalityId) {
      barangaySelect.setAttribute('disabled', 'disabled');
      barangaySelect.required = false;
      return;
    }

    // Filter barangays that match the selected municipality ID
    const matches = originalBarangayOptions.filter(o => o.municipalityId === selectedMunicipalityId);
    console.log('Matched barangays:', matches.length);

    if (matches.length > 0) {
      // Enable dropdown and populate with matching barangays
      barangaySelect.removeAttribute('disabled');
      barangaySelect.required = true;

      matches.forEach(o => {
        const el = document.createElement('option');
        el.value = o.value;
        el.textContent = o.text;
        barangaySelect.appendChild(el);
      });

      // Keep placeholder selected so user must choose
      barangaySelect.querySelector('option[value=""]').selected = true;
    } else {
      // No barangays found for this municipality
      const none = document.createElement('option');
      none.disabled = true;
      none.textContent = 'No barangays available';
      barangaySelect.appendChild(none);
      barangaySelect.setAttribute('disabled', 'disabled');
      barangaySelect.required = false;
    }
  }

  // Municipality change event: update district and filter barangays
  municipalitySelect.addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];

    // Auto-fill district if input exists
    if (districtInput) {
      const districtName = selectedOption ? (selectedOption.getAttribute('data-district') || '') : '';
      districtInput.value = districtName;
    }

    // Filter barangays based on municipality
    filterBarangays();
  });

  // Set initial state on page load
  if (municipalitySelect.value) {
    filterBarangays();
  } else {
    barangaySelect.setAttribute('disabled', 'disabled');
    barangaySelect.required = false;
  }
});