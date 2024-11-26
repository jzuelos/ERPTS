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
$('#confirmationModal').on('show.bs.modal', openConfirmationModal);

// Handle form submission logic for Barangay form using AJAX
$('#submitBarangayFormBtn').on('click', function(e) {
  e.preventDefault(); // Prevent default form submission

  // Collect form data
  let formData = {
    brgy_code: $('#barangayCode').val(),
    brgy_name: $('#barangayName').val(),
    status: $('input[name="status"]:checked').val()
  };

  // Send data to PHP file for database insertion using AJAX
  $.ajax({
    url: 'loc_submit_barangay.php', // PHP file to handle the database insertion
    type: 'POST',
    data: formData,
    success: function(response) {
      alert(response); // Display success or error message
      $('#barangayModal').modal('hide'); // Close the modal on success
    },
    error: function(xhr, status, error) {
      console.error("Error:", error); // Log any errors
    }
  });
});

// Handle form reset
$('#resetFormBtn').on('click', function() {
  $('#barangayForm')[0].reset();
});

// Handle form submission logic for District form using AJAX
$('#submitDistrictFormBtn').on('click', function(e) {
  e.preventDefault(); // Prevent default form submission

  // Collect form data
  let formData = {
    code: $('#districtCode').val(),
    description: $('#districtDescription').val(),
    status: $('input[name="status"]:checked').val()
  };

  // Send data to PHP file for database insertion using AJAX
  $.ajax({
    url: 'loc_submit_district.php', // PHP file to handle the database insertion
    type: 'POST',
    data: formData,
    success: function(response) {
      alert(response); // Display success or error message
      $('#districtModal').modal('hide'); // Close the modal on success
      $('#districtForm')[0].reset(); // Reset the form
    },
    error: function(xhr, status, error) {
      console.error("Error:", error); // Log any errors
      alert("An error occurred. Please try again.");
    }
  });
});

// Separate reset function for District form
$('#districtModal #resetFormBtn').on('click', function() {
  $('#districtForm')[0].reset();
});

document.addEventListener("DOMContentLoaded", function() {
  fetch("loc_getRegions.php")
      .then(response => response.json())
      .then(data => {
          const regionSelect = document.getElementById("region");

          // Clear any existing options
          regionSelect.innerHTML = '<option value="">Select Region</option>';

          // Populate options with r_no
          data.forEach(region => {
              const option = document.createElement("option");
              option.value = region.r_id;           // Set r_id as the value
              option.textContent = region.r_no;      // Display r_no as the visible text
              regionSelect.appendChild(option);
          });
      })
      .catch(error => console.error("Error fetching regions:", error));
});

// Input validation for Barangay Code field (limit to 3 digits, numeric only)
$('#barangayCode').on('input', function() {
  var value = this.value;
  // Allow only numeric input and limit to 3 digits
  value = value.replace(/\D/g, '');  // Remove non-digit characters
  if (value.length > 3) {
    value = value.slice(0, 3); // Limit input to 3 digits
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
  value = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
  this.value = value;
});