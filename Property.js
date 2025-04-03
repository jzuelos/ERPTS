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

$(document).ready(function() {
  $(".submit-btn").click(function() {
      var formData = $("#classificationForm").serialize(); // Collect form data

      $.ajax({
          url: "propertyFunctions.php", // Updated PHP script path
          type: "POST",
          data: formData,
          success: function(response) {
              console.log("Server Response:", response); // Debugging
              if (response.trim() === "Classification details added successfully!") {
                  alert("Classification added successfully!");
                  $("#classificationForm")[0].reset(); // Reset form
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

  // Reset form button
  $(".reset-btn").click(function() {
      $("#classificationForm")[0].reset();
  });
});