<?php
session_start();
$user_role = $_SESSION['user_type'] ?? 'user';
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../main_layout.css">
  <link rel="stylesheet" href="../FAAS.css">
  <link rel="stylesheet" href="../header.css">
    <title>Electronic Real Property Tax System</title>
</head>

<body>

    <!-- Header Navigation -->
  <?php include '../header.php'; ?>
 <!--Plant and Trees--> 
<section class="container my-5" id="plants-trees-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">
            <a href="../FAAS.php" class="text-decoration-none me-2" title="Back">
                <i class="fas fa-arrow-left"></i>
            </a>
            PLANTS AND TREES
        </h4>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="showPnTModal()">Edit</button>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow p-4 rounded-3 bg-light">
      <!-- Show/Hide Section -->
      <div class="form-group mb-4">
        <div class="d-flex align-items-center">
          <label for="showHide" class="form-check-label me-2">Show/Hide</label>
          <input type="checkbox" id="showHide" class="form-check-input">
        </div>
      </div>

      <!-- Market Value and Assessed Value Section -->
      <div class="row mb-4">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="marketValue" class="form-label">Market Value</label>
            <input type="text" class="form-control" id="marketValue" placeholder="Enter market value">
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="assessedValue" class="form-label">Assessed Value</label>
            <input type="text" class="form-control" id="assessedValue" placeholder="Enter assessed value">
          </div>
        </div>
      </div>

      <!-- Add and Print Buttons Inside the Form -->
      <div class="d-flex justify-content-between mb-3">
        <!-- Enable "Add Plants/Trees" button -->
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="togglePlantsSection()">Add
          Plants/Trees</button>
        <!-- Enable Print button -->
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openPrintPage()">Print</button>
      </div>

      <!-- Remove Button -->
      <div class="form-group mt-3">
        <!-- Enable Remove button -->
        <button type="button" class="btn btn-outline-danger btn-sm" id="removeButton"
          style="margin-left: 0.5rem;">Remove</button>
      </div>

      <!-- Hidden Plants/Trees Section (Initially Hidden) -->
      <div id="plantsSection" style="display: none;">
        <p>Details for Plants/Trees will appear here.</p>
      </div>
    </div>
  </section>
  
  <!-- Modal for Editing Plants and Trees -->
  <div class="modal fade" id="editPlantsTreesModal" tabindex="-1" aria-labelledby="editPlantsTreesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPlantsTreesModalLabel">Edit Plants and Trees Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form inside Modal -->
          <form id="editPlantsTreesForm">
            <div class="mb-3">
              <label for="marketValueModal" class="form-label">Market Value</label>
              <input type="text" class="form-control" id="marketValueModal" placeholder="Enter market value">
            </div>
            <div class="mb-3">
              <label for="assessedValueModal" class="form-label">Assessed Value</label>
              <input type="text" class="form-control" id="assessedValueModal" placeholder="Enter assessed value">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savePlantsTreesData()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

    
    <!-- Footer -->
<footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
</footer>

    <script src="http://localhost/ERPTS/Add-New-Real-Property-Unit.js"></script>

    <script>
    function resetForm() {
      // Target all forms inside modals
      const modals = document.querySelectorAll('.modal');

      modals.forEach(modal => {
        // Find all forms in the modal
        const forms = modal.querySelectorAll('form');
        forms.forEach(form => {
          // Reset the form to its default state
          form.reset();

          // Clear additional fields if reset does not handle them
          form.querySelectorAll("input, select, textarea").forEach(field => {
            if (field.type === "text" || field.type === "textarea" || field.type === "email" || field.type === "date") {
              field.value = ""; // Clear text, email, textarea, and date inputs
            } else if (field.type === "checkbox" || field.type === "radio") {
              field.checked = field.defaultChecked; // Reset checkboxes and radio buttons
            } else if (field.tagName === "SELECT") {
              field.selectedIndex = 0; // Reset select dropdowns to the first option
            }
          });
        });
      });

      // Ensure manual clearing for LAND modal if it's outside a form
      const landModal = document.getElementById("editLandModal");
      if (landModal) {
        const inputs = landModal.querySelectorAll("input, select, textarea");
        inputs.forEach(input => {
          if (input.type === "text" || input.type === "textarea" || input.type === "email" || input.type === "date") {
            input.value = ""; // Clear the value
          } else if (input.type === "checkbox" || input.type === "radio") {
            input.checked = input.defaultChecked; // Reset to default checked state
          } else if (input.tagName === "SELECT") {
            input.selectedIndex = 0; // Reset select to the first option
          }
        });
      }
    }

    function DRPprint() {
      const printWindow = window.open('DRP.html', '_blank'); // '_blank' ensures the content opens in a new tab
      printWindow.onload = function () {

        printWindow.print();
      };
    }
  </script>
  <script>
    function toggleEdit() {
      const editButton = document.getElementById('editRPUButton');
      const inputs = document.querySelectorAll('#rpu-identification-section input, #rpu-identification-section select');
      const isEditMode = editButton.textContent === 'Edit';

      if (isEditMode) {
        // Change button text to "Save"
        editButton.textContent = 'Save';

        // Enable all inputs
        inputs.forEach(input => {
          input.disabled = false;
        });
      } else {
        // Save data
        saveRPUData();

        // Change button text back to "Edit"
        editButton.textContent = 'Edit';

        // Disable all inputs
        inputs.forEach(input => {
          input.disabled = true;
        });
      }
    }

    let arpData = {}; // Object to store data

    function saveRPUData() {
      // Get Property ID (`pro_id`) from the URL
      const propertyId = new URLSearchParams(window.location.search).get('id');

      // Find the FAAS ID from the page (assuming it is inside a <div> or similar element)
      const faasIdText = document.body.innerHTML.match(/Faas ID:\s*(\d+)/);
      const faasId = faasIdText ? faasIdText[1] : null; // Extract FAAS ID

      if (!faasId) {
        alert("Error: FAAS ID not found on the page.");
        return;
      }

      // Get input values
      const arpNumber = document.getElementById('arpNumber').value;
      const propertyNumber = document.getElementById('propertyNumber').value;
      const taxability = document.getElementById('taxability').value;
      const effectivity = document.getElementById('effectivity').value;

      // Store data including FAAS ID
      arpData = {
        faasId: faasId, // Correct FAAS ID extracted from page
        arpNumber: arpNumber,
        propertyNumber: propertyNumber,
        taxability: taxability,
        effectivity: effectivity
      };

      // Send data to FAASrpuID.php
      fetch('FAASrpuID.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(arpData)
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Success');
          } else {
            alert('Failed to insert data: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while inserting the data.');
        });
    }

  </script>
  <script src="http://localhost/ERPTS/FAAS.js"></script>
  <script src="http://localhost/ERPTS/print-layout.js"></script>
  <script src="http://localhost/ERPTS/printdata.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>