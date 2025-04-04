document.addEventListener("DOMContentLoaded", function () {
  let selectedForm = "";

 
  document.querySelectorAll(".location-card").forEach((card) => {
    card.addEventListener("click", function (event) {
      event.preventDefault();

      // Get the modal data from the clicked card
      const categoryName = this.getAttribute("data-name");
      selectedForm = this.getAttribute("data-form");

      // Update modal content
      document.getElementById("categoryName").textContent = categoryName;

      // Show the confirmation modal
      $("#confirmationModal").modal("show");
    });
  });

  // When confirm is clicked, open the specific modal
  document.getElementById("confirmBtn").addEventListener("click", function () {
    $("#confirmationModal").modal("hide"); // Hide confirmation modal
    setTimeout(() => {
      $("#" + selectedForm).modal("show"); // Show specific modal
    }, 500); // Small delay for smooth transition
  });
});


document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("cancelBtn").addEventListener("click", function () {
$("#confirmationModal").modal("hide"); // Force-close modal
});

  // Handle Reset Button Click (Resets Form Only)
  document.querySelectorAll(".reset-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const modal = this.closest(".modal");
      const form = modal.querySelector("form");
      if (form) form.reset();
    });
  });

  // Handle Submit Button Click (Validates & Closes Modal)
  document.querySelectorAll(".submit-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const modal = this.closest(".modal");
      const form = modal.querySelector("form");

      if (form && form.checkValidity()) {
        alert("Form submitted: " + form.id);
        $(modal).modal("hide"); // Close the modal
      } else {
        form.reportValidity(); // Show validation errors
      }
    });
  });

  document.querySelectorAll(".close").forEach((button) => {
button.addEventListener("click", function () {
  const modal = this.closest(".modal");
  $(modal).modal("hide"); // Close the modal manually
});
});
});

function changeLocationType(type) {
  document.getElementById("municipalityTable").classList.add("d-none");
  document.getElementById("districtTable").classList.add("d-none");
  document.getElementById("barangayTable").classList.add("d-none");

  if (type === "Municipality") {
    document.getElementById("municipalityTable").classList.remove("d-none");
  } else if (type === "District") {
    document.getElementById("districtTable").classList.remove("d-none");
  } else if (type === "Barangay") {
    document.getElementById("barangayTable").classList.remove("d-none");
  }
}  

// Handle form submission logic for Classification form using AJAX
$('#submitClassificationFormBtn').on('click', function(e) {
  e.preventDefault(); // Prevent default form submission

  // Collect form data
  let formData = {
    c_code: $('#classificationCode').val(),
    c_description: $('#classificationDescription').val(),
    c_uv: $('#unitValue').val(),
    c_status: $('input[name="c_status"]:checked').val()
  };

  // Send data to PHP file for database insertion using AJAX
  $.ajax({
    url: 'propertyFunctions.php', // PHP file to handle database insertion
    type: 'POST',
    data: formData,
    success: function(response) {
      showToastMessage(response); // Display success or error message
      $('#classificationModal').modal('hide'); // Close the modal on success
    },
    error: function(xhr, status, error) {
      console.error("Error:", error); // Log any errors
      showToastMessage("An error occurred. Please try again.");
    }
  });
});

  // Reset form button
  $(".reset-btn").click(function() {
      $("#classificationForm")[0].reset();
  });

//submit actual use form
$(document).ready(function() {
  // Handle Land Use Form Submission
  $("#actUsesModal .submit-btn").click(function(event) {
      event.preventDefault(); // Prevent default form submission

      var formData = {
          report_code: $("#reportCode").val(),
          lu_code: $("#reportCodeValue").val(),
          lu_description: $("#reportDescription").val(),
          lu_al: $("#reportAssessmentLevel").val(),
          lu_status: $("input[name='reportStatus']:checked").val()
      };

      $.ajax({
          url: "propertyFunctions.php",
          type: "POST",
          data: formData,
          success: function(response) {
              console.log("Server Response:", response); // Debugging
              if (response.trim() === "Land Use added successfully!") {
                  alert("Land Use added successfully!");
                  $("#reportForm")[0].reset(); // Reset form
                  $("#actUsesModal").modal("hide"); // Close modal after success
              } else {
                  alert("Error: " + response);
              }
          },
          error: function(xhr, status, error) {
              console.error("AJAX Error:", error);
              alert("AJAX request failed. Check console for details.");
          }
      });
  });

  // Reset form button for Land Use
  $("#actUsesModal .reset-btn").click(function() {
      $("#reportForm")[0].reset();
  });
});