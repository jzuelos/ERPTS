// Function to open the confirmation modal and update the question
function openConfirmationModal(event) {
  var button = $(event.relatedTarget);
  var locationName = button.data('name'); // Get the location name (e.g., Barangay)

  // Update the confirmation question in the modal
  $('#confirmationModal .modal-body #confirmationQuestion').text('Will you encode the ' + locationName + ' details?');

  // When the user clicks Confirm in the Confirmation Modal, open the specific form modal
  $('#confirmBtn').off('click').on('click', function() {
    // Close the Confirmation Modal
    $('#confirmationModal').modal('hide');

    // Open the corresponding form modal based on the location name
    openLocationFormModal(locationName);
  });
}

function openLocationFormModal(locationName) {
  if (locationName === "Barangay") {
    $('#barangayModal').modal('show');
  }
  // Add similar conditionals for other locations like "Municipality" or "District"
  else if (locationName === "Municipality") {
    $('#municipalityModal').modal('show');
  }
  else if (locationName === "District") {
    $('#districtModal').modal('show');
  }
}

// Bind the function to the modal show event for the confirmation modal
document.querySelectorAll('.openConfirmationBtn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent page jump

    const locationName = this.dataset.name; // Get the data-name attribute

    // Update confirmation modal text
    document.getElementById('confirmationQuestion').textContent =
      `Will you encode the ${locationName} details?`;

    // Show confirmation modal
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    confirmationModal.show();

    // Set confirm button behavior
    document.getElementById('confirmBtn').onclick = function () {
      confirmationModal.hide();
      openLocationFormModal(locationName); // Open respective form modal
    };
  });
});


$(document).ready(function () {
  // Fetch municipalities when the modal is shown
  $('#barangayModal').on('show.bs.modal', function () {
    $.ajax({
      url: 'loc_getMunicipalitiesforBrgy.php', // PHP script to fetch municipalities
      method: 'GET',
      success: function (data) {
        $('#locationDropdown').html(data); // Populate the location dropdown with fetched data
      },
      error: function () {
        $('#locationDropdown').html('<option value="">Failed to load data</option>');
      }
    });
  });
});

// Handle form submission logic for Barangay form using AJAX
$('#submitBarangayFormBtn').on('click', function(e) {
  e.preventDefault(); // Prevent default form submission

  // Collect form data
let formData = {
    m_id: $('#locationDropdown').val(),
    brgy_code: $('#barangayCode').val(),
    brgy_name: $('#barangayName').val(),
    status: $('input[name="statusBarangay"]:checked').val() // updated name
};


  // Send data to PHP file for database insertion using AJAX
  $.ajax({
    url: 'loc_submit_barangay.php', // PHP file to handle the database insertion
    type: 'POST',
    data: formData,
    success: function(response) {
      showToastMessage(response); // Display success or error message
      $('#barangayModal').modal('hide'); // Close the modal on success
    },
    error: function(xhr, status, error) {
      console.error("Error:", error); // Log any errors
      showToastMessage("An error occurred. Please try again.");
    }
  });
});

// Handle form reset for Barangay
$('#resetFormBtn').on('click', function() {
  $('#barangayForm')[0].reset();
});

// Handle form submission logic for District form using AJAX
// JavaScript function to insert district data
$(document).ready(function () {
  // Fetch municipalities when the modal is opened (if not already populated)
  $('#districtModal').on('show.bs.modal', function () {
    $.ajax({
      url: 'getMunicipalities.php', // PHP script to fetch municipalities
      method: 'GET',
      success: function (data) {
        $('#municipality').html(data); // Populate the municipality dropdown
      }
    });
  });

  // Submit the district form
  $('#submitDistrictFormBtn').click(function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Get the form data
    const districtCode = $('#districtCode').val();
    const districtDescription = $('#districtDescription').val();
    const status = $("input[name='status']:checked").val(); // Get selected status
    const municipalityId = $('#municipality').val(); // Get selected municipality ID

    // Validate inputs
    if (!districtCode || !districtDescription || !status || !municipalityId) {
      alert('Please fill all fields.');
      return;
    }

    // Send the data to the server using AJAX
    $.ajax({
      url: 'loc_submit_district.php', // PHP script to handle insertion
      method: 'POST',
      data: {
        district_code: districtCode,
        description: districtDescription,
        status: status,
        m_id: municipalityId
      },
      success: function (response) {
        if (response === 'success') {
          alert('District added successfully!');
          $('#districtForm')[0].reset(); // Reset the form
          $('#districtModal').modal('hide'); // Close the modal
        } else {
          alert('Failed to add district. Please try again.');
        }
      },
      error: function () {
        alert('An error occurred while processing your request.');
      }
    });
  });
});

// Separate reset function for District form
$('#districtModal #resetFormBtn').on('click', function() {
  $('#districtForm')[0].reset();
});

// script to populate region drop down options
document.addEventListener("DOMContentLoaded", function() {
  // Fetch regions
  fetch("loc_getRegions.php")
    .then(response => response.json())
    .then(data => {
      const regionSelect = document.getElementById("region");

      // Clear any existing options
      regionSelect.innerHTML = '';

      // Populate options with r_no
      data.forEach(region => {
        const option = document.createElement("option");
        option.value = region.r_id;           // Set r_id as the value
        option.textContent = region.r_no;      // Display r_no as the visible text
        regionSelect.appendChild(option);
      });
    })
    .catch(error => console.error("Error fetching regions:", error));

  // Fetch municipalities and populate the dropdown
  fetch("loc_getMunicipalities.php")
   .then(response => response.json())
   .then(data => {
       const municipalitySelect = document.getElementById("municipality");

       // Clear existing options and add a default "Select Municipality"
       municipalitySelect.innerHTML = '<option value="" selected disabled>Select Municipality</option>';

       // Populate the dropdown
       if (data.length > 0) {
           data.forEach(municipality => {
               const option = document.createElement("option");
               option.value = municipality.m_id;  // m_id is used as the value
               option.textContent = municipality.m_description;  // m_description is displayed
               municipalitySelect.appendChild(option);
           });
       } else {
           const noDataOption = document.createElement("option");
           noDataOption.textContent = "No municipalities available";
           municipalitySelect.appendChild(noDataOption);
       }
   })
   .catch(error => {
       console.error("Error fetching municipalities:", error);
   });
});

// Handle form submission logic for Municipality form using AJAX
$('#submitMunicipalityFormBtn').on('click', function(e) {
  e.preventDefault(); // Prevent default form submission

  // Collect form data
  let regionId = $('#region').val();  // Get the region value
  if (!regionId) {
    showToastMessage("Please select a region");  // Show an error if no region is selected
    return;  // Stop the form submission
  }

let formData = {
    region_id: $('#region').val(),
    municipality_code: $('#municipalityCode').val(),
    municipality_description: $('#municipalityDescription').val(),
    status: $('input[name="statusMunicipality"]:checked').val() // updated name
};


  // Send data to PHP file for database insertion using AJAX
  $.ajax({
    url: 'loc_submit_municipality.php', // PHP file to handle the database insertion
    type: 'POST',
    data: formData,
    success: function(response) {
      showToastMessage(response); // Display success or error message
      $('#municipalityModal').modal('hide'); // Close the modal on success
      $('#municipalityForm')[0].reset(); // Reset the form
    },
    error: function(xhr, status, error) {
      console.error("Error:", error); // Log any errors
      showToastMessage("An error occurred. Please try again.");
    }
  });
});

// Input validation for Barangay Code field (limit to 3 digits, numeric only)
$('#barangayCode').on('input', function() {
  var value = this.value;
  // Allow only numeric input and limit to 3 digits
  value = value.replace(/\D/g, '');  // Remove non-digit characters
  if (value.length > 10) {
    value = value.slice(0, 10); // Limit input to 3 digits
  }
  this.value = value;
});

// Capitalize first letter of Barangay Name input
$('#barangayName').on('input', function() {
  var value = this.value;
  // Capitalize first letter of each word
  value = value.replace(/\b\w/g, function(char) {
    return char.toUpperCase();
  });
  this.value = value;
});

// Input validation for District Code - Numbers only (no digit limit)
$('#districtCode').on('input', function() {
  var value = this.value;
  // Allow only numbers, no digit limit
  value = value.replace(/\D/g, '');  // Remove non-digit characters
  this.value = value;
});

// Input validation for District Description - Capitalize the first letter
$('#districtDescription').on('input', function() {
  var value = this.value;
  // Capitalize the first letter and keep the rest lowercase
  value = value.charAt(0).toUpperCase() + value.slice(1);
  this.value = value;
});

// Input validation for Municipality Code - Numbers only (no digit limit)
$('#municipalityCode').on('input', function() {
  var value = this.value;
  // Allow only numbers, no digit limit
  value = value.replace(/\D/g, '');  // Remove non-digit characters
  this.value = value;
});

// Input validation for Municipality Description - Capitalize the first letter
$('#municipalityDescription').on('input', function() {
  var value = this.value;
  // Capitalize the first letter and keep the rest lowercase
  value = value.charAt(0).toUpperCase() + value.slice(1);
  this.value = value;
});

// Prevent form submission on Enter key press for all forms
$('#barangayForm, #districtForm, #municipalityForm').on('keypress', function(e) {
  if (e.which == 13) {
    e.preventDefault(); // Prevent the form from submitting
  }
});

// Function to display toast messages (success/error)
function showToastMessage(message) {
  // You can replace this with any toast or notification library you prefer
  alert(message); // For simplicity, using alert here
}

// Location.js

// Function to handle switching between location types
function changeLocationType(type) {
  // Update the dropdown button label
  const dropdownButton = document.getElementById('locationTypeDropdown');
  if (dropdownButton) {
    dropdownButton.innerText = type;
  }

  // Get the table elements
  const municipalityTable = document.getElementById('municipalityTable');
  const districtTable = document.getElementById('districtTable');
  const barangayTable = document.getElementById('barangayTable');

  // Hide all tables
  if (municipalityTable) municipalityTable.classList.add('d-none');
  if (districtTable) districtTable.classList.add('d-none');
  if (barangayTable) barangayTable.classList.add('d-none');

  // Show the selected table
  switch (type) {
    case 'Municipality':
      if (municipalityTable) municipalityTable.classList.remove('d-none');
      break;
    case 'District':
      if (districtTable) districtTable.classList.remove('d-none');
      break;
    case 'Barangay':
      if (barangayTable) barangayTable.classList.remove('d-none');
      break;
  }
}

//Delete Function for Main Table
document.addEventListener("DOMContentLoaded", () => {
  let rowToDelete = null;

  // Delete button click
  document.querySelectorAll(".btn-outline-danger").forEach(btn => {
    btn.addEventListener("click", function () {
      rowToDelete = this.closest("tr"); // get the row
    });
  });

  // Confirm delete
  document.getElementById("confirmDelete").addEventListener("click", () => {
    if (rowToDelete) {
      rowToDelete.remove(); // remove row from table
      rowToDelete = null;
    }
    const modal = bootstrap.Modal.getInstance(document.getElementById("GlobalDeleteModal"));
    modal.hide();
  });
});