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

  // Handle form submission logic for Barangay (can be expanded for other forms)
  $('#submitFormBtn').on('click', function(e) {
    e.preventDefault();
    alert('Barangay form submitted!');
    // Add your form submission logic here (e.g., AJAX request, form validation, etc.)
  });

  // Handle form reset
  $('#resetFormBtn').on('click', function() {
    $('#barangayForm')[0].reset();
  });