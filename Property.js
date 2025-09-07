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

// ==================== AJAX FORM SUBMISSIONS ====================

$(document).ready(function () {
  // Submit Classification Form
  $("#classificationModal .submit-btn").click(function (event) {
    event.preventDefault();

    var formData = {
      c_code: $("#classificationCode").val(),
      c_description: $("#classificationDescription").val(),
      c_uv: $("#unitValue").val(),
      c_status: $("input[name='c_status']:checked").val()
    };

    $.ajax({
      url: "propertyFunctions.php",
      type: "POST",
      data: formData,
      success: function (response) {
        console.log("Server Response:", response); // Debugging
        if (response.trim() === "Classification details added successfully!") {
          alert("Classification added successfully!");
          $("#classificationForm")[0].reset();
          $("#classificationModal").modal("hide");
          location.reload(); // refresh table
        } else {
          alert("Error: " + response);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("AJAX request failed. Check console for details.");
      }
    });
  });

  // Reset Classification Form
  $("#classificationModal .reset-btn").click(function () {
    $("#classificationForm")[0].reset();
  });
});

// Submit Actual Use Form
$(document).ready(function () {
  $("#actUsesModal .submit-btn").click(function (event) {
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
      success: function (response) {
        console.log("Server Response:", response); // Debugging
        if (response.trim() === "Land Use added successfully!") {
          alert("Land Use added successfully!");
          $("#reportForm")[0].reset(); // Reset form
          $("#actUsesModal").modal("hide"); // Close modal after success
          location.reload(); // refresh table
        } else {
          alert("Error: " + response);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("AJAX request failed. Check console for details.");
      }
    });
  });

  // Reset form button for Land Use
  $("#actUsesModal .reset-btn").click(function () {
    $("#reportForm")[0].reset();
  });
});

// Submit Sub-Classes Form
$(document).ready(function () {
  $("#subClassesModal .submit-btn").click(function (event) {
    event.preventDefault();

    var formData = {
      sc_code: $("#subClassesCode").val(),
      sc_description: $("#subClassesDescription").val(),
      sc_uv: $("#SunitValue").val(),
      sc_status: $("input[name='subClassesStatus']:checked").val()
    };

    $.ajax({
      url: "propertyFunctions.php",
      type: "POST",
      data: formData,
      success: function (response) {
        console.log("Server Response:", response);
        if (response.trim() === "Sub-Class added successfully!") {
          alert("Sub-Class added successfully!");
          $("#subClassesForm")[0].reset();
          $("#subClassesModal").modal("hide");
          location.reload(); // refresh table
        } else {
          alert("Error: " + response);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        alert("AJAX request failed. Check console for details.");
      }
    });
  });

  // Reset Sub-Classes Form
  $("#subClassesModal .reset-btn").click(function () {
    $("#subClassesForm")[0].reset();
  });
});

//Delete Functionality
$(document).ready(function () {
  $(".delete-btn").click(function () {
    var id = $(this).data("id");
    var table = $(this).data("table");

    if (confirm("Are you sure you want to delete this record?")) {
      $.ajax({
        url: "propertyFunctions.php",
        type: "POST",
        data: { action: "delete", id: id, table: table },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            alert("Record deleted successfully!");
            location.reload(); // refresh the table
          } else {
            alert("Error: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("AJAX request failed. Check console for details.");
        }
      });
    }
  });
});


