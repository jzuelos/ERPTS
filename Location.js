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
    code: $('#code').val(),
    description: $('#description').val(),
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