<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once 'database.php';
$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get p_id from URL
$p_id = isset($_GET['p_id']) ? (int)$_GET['p_id'] : 0;

// Fetch faas_id using p_id
$faas_id = 0;
$query = $conn->prepare("SELECT faas_id FROM faas WHERE pro_id = ?");
$query->bind_param("i", $p_id);
$query->execute();
$query->bind_result($faas_id);
$query->fetch();
$query->close();

// Ensure we found a valid faas_id
if ($faas_id == 0) {
  die("Error: No FAAS record found for this property.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $oct_no = isset($_POST['oct_no']) ? (int)$_POST['oct_no'] : 0;
  $unit_value = isset($_POST['unit_value']) ? (int)$_POST['unit_value'] : 0;
  $market_value = isset($_POST['market_value']) ? (int)$_POST['market_value'] : 0;

  // Collect all other input fields
  $survey_no = $_POST['survey_no'] ?? '';
  $boundaries = $_POST['boundaries'] ?? '';
  $boun_desc = $_POST['boun_desc'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $first_name = $_POST['first_name'] ?? '';
  $middle_name = $_POST['middle_name'] ?? '';
  $contact_no = $_POST['contact_no'] ?? '';
  $email = $_POST['email'] ?? '';
  $house_street = $_POST['house_street'] ?? '';
  $barangay = $_POST['barangay'] ?? '';
  $district = $_POST['district'] ?? '';
  $municipality = $_POST['municipality'] ?? '';
  $province = $_POST['province'] ?? '';
  $land_desc = $_POST['land_desc'] ?? '';
  $classification = $_POST['classification'] ?? '';
  $sub_class = $_POST['sub_class'] ?? '';
  $area = $_POST['area'] ?? '';
  $actual_use = $_POST['actual_use'] ?? '';

  // Prepare and execute the insert query
  $stmt = $conn->prepare("INSERT INTO land (faas_id, oct_no, survey_no, boundaries, boun_desc, last_name, first_name, middle_name, contact_no, email, house_street, barangay, district, municipality, province, land_desc, classification, sub_class, area, actual_use, unit_value, market_value)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param(
    "iissssssssssssssssssii",
    $faas_id,       // Foreign Key faas_id
    $oct_no,
    $survey_no,
    $boundaries,
    $boun_desc,
    $last_name,
    $first_name,
    $middle_name,
    $contact_no,
    $email,
    $house_street,
    $barangay,
    $district,
    $municipality,
    $province,
    $land_desc,
    $classification,
    $sub_class,
    $area,
    $actual_use,
    $unit_value,
    $market_value
  );

  if ($stmt->execute()) {
    echo "Land record added successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}
$conn->close();
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="FAAS.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>

  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand" href="#">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top"
        alt="">
      Electronic Real Property Tax System
    </a>
  </nav>

  <!-- LAND Section -->
  <section class="container my-5" id="land-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">
        <?php
        // Retrieve p_id from the current page's URL (assuming it's passed as p_id)
        $p_id = isset($_GET['p_id']) ? htmlspecialchars($_GET['p_id']) : '';
        ?>
        <a href="FAAS.php?id=<?= $p_id; ?>" class="text-decoration-none me-2">
          <i class="fas fa-arrow-left"></i>
        </a>
        Land
      </h4>
      <div>

        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
          data-bs-target="#editLandModal">Edit</button>
      </div>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Land Details Section -->
      <h5 class="section-title">Land Details</h5>

      <!-- Identification Numbers -->
      <h6 class="section-subtitle mt-4">Identification Numbers</h6>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="octTctNumber" class="form-label">OCT/TCT Number</label>
            <input type="text" id="octTctNumber" class="form-control" placeholder="Enter OCT/TCT Number" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="surveyNumber" class="form-label">Survey Number</label>
            <input type="text" id="surveyNumber" class="form-control" placeholder="Enter Survey Number" disabled>
          </div>
        </div>
      </div>

      <!-- Boundaries -->
      <h6 class="section-subtitle mt-4">Boundaries</h6>
      <div class="row">
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="north" class="form-label">North</label>
            <input type="text" id="north" class="form-control" placeholder="Enter North Boundary" disabled>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="south" class="form-label">South</label>
            <input type="text" id="south" class="form-control" placeholder="Enter South Boundary" disabled>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="east" class="form-label">East</label>
            <input type="text" id="east" class="form-control" placeholder="Enter East Boundary" disabled>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="west" class="form-label">West</label>
            <input type="text" id="west" class="form-control" placeholder="Enter West Boundary" disabled>
          </div>
        </div>
      </div>

      <!-- Boundary Description -->
      <h6 class="section-subtitle mt-4">Boundary Description</h6>
      <textarea class="form-control mb-4" id="boundaryDescriptionModal" rows="2"
        placeholder="Enter boundary description" disabled></textarea>

      <!-- Administrator Information Section -->
      <h5 class="section-title mt-5">Administrator Information</h5>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adminLastName" class="form-label">Last Name</label>
            <input type="text" id="adminLastName" class="form-control" placeholder="Enter last name" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adminFirstName" class="form-label">First Name</label>
            <input type="text" id="adminFirstName" class="form-control" placeholder="Enter first name" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adminMiddleName" class="form-label">Middle Name</label>
            <input type="text" id="adminMiddleName" class="form-control" placeholder="Enter middle name" disabled>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="adminContact" class="form-label">Contact Number</label>
            <input type="text" id="adminContact" class="form-control" placeholder="Enter contact number" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="adminEmail" class="form-label">Email</label>
            <input type="email" id="adminEmail" class="form-control" placeholder="Enter email" disabled>
          </div>
        </div>
      </div>

      <!-- Address Information -->
      <h6 class="section-subtitle mt-4">Address</h6>
      <div class="row">
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="adminAddressNumber" class="form-label">House Number</label>
            <input type="text" id="adminAddressNumber" class="form-control" placeholder="Enter house number" disabled>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="adminAddressStreet" class="form-label">Street</label>
            <input type="text" id="adminAddressStreet" class="form-control" placeholder="Enter street" disabled>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="adminAddressBarangay" class="form-label">Barangay</label>
            <input type="text" id="adminAddressBarangay" class="form-control" placeholder="Enter barangay" disabled>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="mb-3">
            <label for="adminAddressDistrict" class="form-label">District</label>
            <input type="text" id="adminAddressDistrict" class="form-control" placeholder="Enter district" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="adminAddressMunicipality" class="form-label">Municipality/City</label>
            <input type="text" id="adminAddressMunicipality" class="form-control"
              placeholder="Enter municipality or city" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="adminAddressProvince" class="form-label">Province</label>
            <input type="text" id="adminAddressProvince" class="form-control" placeholder="Enter province" disabled>
          </div>
        </div>
      </div>

      <!-- Land Appraisal Section -->
      <h5 class="section-title mt-5">Land Appraisal</h5>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" id="description" class="form-control" placeholder="Enter description" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="classification" class="form-label">Classification</label>
            <input type="text" id="classification" class="form-control" placeholder="Enter classification" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="subClass" class="form-label">Sub-Class</label>
            <input type="text" id="subClass" class="form-control" placeholder="Enter sub-class" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="area" class="form-label">Area (sq m)</label>
            <input type="text" id="area" class="form-control" placeholder="Enter area" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="actualUse" class="form-label">Actual Use</label>
            <input type="text" id="actualUse" class="form-control" placeholder="Enter actual use" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="unitValue" class="form-label">Unit Value</label>
            <input type="text" id="unitValue" class="form-control" placeholder="Enter unit value" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="marketValue" class="form-label">Market Value</label>
            <input type="text" id="marketValue" class="form-control" placeholder="Enter market value" disabled>
          </div>
        </div>
      </div>
      <!-- Print Button at Bottom Right -->
      <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-outline-secondary py-2 px-4" onclick="openPrintPage()"
          style="font-size: 1.1rem;">
          <i class="fas fa-print me-2"></i>Print
        </button>
      </div>
    </div>
    </div>
  </section>

  <!-- Modal for Editing Land Details -->
  <div class="modal fade" id="editLandModal" tabindex="-1" aria-labelledby="editLandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="POST" action=""> <!-- Form starts here -->
          <div class="modal-header">
            <h5 class="modal-title" id="editLandModalLabel">Edit Land Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <!-- Identification Numbers -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="octTctNumberModal" class="form-label">OCT/TCT Number</label>
                <input type="text" id="octTctNumberModal" name="oct_no" class="form-control" placeholder="Enter OCT/TCT Number">
              </div>
              <div class="col-md-6 mb-4">
                <label for="surveyNumberModal" class="form-label">Survey Number</label>
                <input type="text" id="surveyNumberModal" name="survey_no" class="form-control" placeholder="Enter Survey Number">
              </div>
            </div>

            <!-- Boundaries -->
            <div class="row">
              <div class="col-md-3 mb-4">
                <label for="northModal" class="form-label">North Boundary</label>
                <input type="text" id="northModal" name="north_boundary" class="form-control" placeholder="Enter North Boundary">
              </div>
              <div class="col-md-3 mb-4">
                <label for="southModal" class="form-label">South Boundary</label>
                <input type="text" id="southModal" name="south_boundary" class="form-control" placeholder="Enter South Boundary">
              </div>
              <div class="col-md-3 mb-4">
                <label for="eastModal" class="form-label">East Boundary</label>
                <input type="text" id="eastModal" name="east_boundary" class="form-control" placeholder="Enter East Boundary">
              </div>
              <div class="col-md-3 mb-4">
                <label for="westModal" class="form-label">West Boundary</label>
                <input type="text" id="westModal" name="west_boundary" class="form-control" placeholder="Enter West Boundary">
              </div>
            </div>

            <!-- Boundary Description -->
            <div class="mb-4">
              <label for="boundaryDescriptionModal" class="form-label">Boundary Description</label>
              <textarea class="form-control" id="boundaryDescriptionModal" name="boun_desc" rows="2" placeholder="Enter boundary description"></textarea>
            </div>

            <!-- Administrator Information -->
            <h5 class="section-title mt-5">Administrator Information</h5>
            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="adminLastNameModal" class="form-label">Last Name</label>
                <input type="text" id="adminLastNameModal" name="last_name" class="form-control" placeholder="Enter last name">
              </div>
              <div class="col-md-4 mb-4">
                <label for="adminFirstNameModal" class="form-label">First Name</label>
                <input type="text" id="adminFirstNameModal" name="first_name" class="form-control" placeholder="Enter first name">
              </div>
              <div class="col-md-4 mb-4">
                <label for="adminMiddleNameModal" class="form-label">Middle Name</label>
                <input type="text" id="adminMiddleNameModal" name="middle_name" class="form-control" placeholder="Enter middle name">
              </div>
            </div>

            <!-- Contact Information -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="adminContactModal" class="form-label">Contact Number</label>
                <input type="text" id="adminContactModal" name="contact_no" class="form-control" placeholder="Enter contact number">
              </div>
              <div class="col-md-6 mb-4">
                <label for="adminEmailModal" class="form-label">Email</label>
                <input type="email" id="adminEmailModal" name="email" class="form-control" placeholder="Enter email">
              </div>
            </div>

            <!-- Address Information -->
            <h6 class="section-subtitle mt-4">Address</h6>
            <div class="row">
              <div class="col-md-3 mb-4">
                <label for="adminAddressNumberModal" class="form-label">House Number</label>
                <input type="text" id="adminAddressNumberModal" name="house_street" class="form-control" placeholder="Enter house number">
              </div>
              <div class="col-md-3 mb-4">
                <label for="adminAddressStreetModal" class="form-label">Street</label>
                <input type="text" id="adminAddressStreetModal" name="barangay" class="form-control" placeholder="Enter street">
              </div>
              <div class="col-md-3 mb-4">
                <label for="adminAddressMunicipalityModal" class="form-label">Municipality</label>
                <input type="text" id="adminAddressMunicipalityModal" name="municipality" class="form-control" placeholder="Enter municipality">
              </div>
              <div class="col-md-3 mb-4">
                <label for="adminAddressProvinceModal" class="form-label">Province</label>
                <input type="text" id="adminAddressProvinceModal" name="province" class="form-control" placeholder="Enter province">
              </div>
            </div>

            <!-- Land Appraisal Section -->
            <h5 class="section-title mt-5">Land Appraisal</h5>
            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="landDescModal" class="form-label">Land Description</label>
                <input type="text" id="landDescModal" name="land_desc" class="form-control" placeholder="Enter land description">
              </div>
              <div class="col-md-4 mb-4">
                <label for="classificationModal" class="form-label">Classification</label>
                <input type="text" id="classificationModal" name="classification" class="form-control" placeholder="Enter classification">
              </div>
              <div class="col-md-4 mb-4">
                <label for="subClassModal" class="form-label">Sub-Class</label>
                <input type="text" id="subClassModal" name="sub_class" class="form-control" placeholder="Enter sub-class">
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="areaModal" class="form-label">Area (sq m)</label>
                <input type="text" id="areaModal" name="area" class="form-control" placeholder="Enter area">
              </div>
              <div class="col-md-4 mb-4">
                <label for="actualUseModal" class="form-label">Actual Use</label>
                <input type="text" id="actualUseModal" name="actual_use" class="form-control" placeholder="Enter actual use">
              </div>
              <div class="col-md-4 mb-4">
                <label for="unitValueModal" class="form-label">Unit Value</label>
                <input type="number" id="unitValueModal" name="unit_value" class="form-control" placeholder="Enter unit value">
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="marketValueModal" class="form-label">Market Value</label>
                <input type="number" id="marketValueModal" name="market_value" class="form-control" placeholder="Enter market value">
              </div>
            </div>

            <!-- Commented Sections for Later Use -->
            <!-- Property Assessment Section -->
            <!-- Certification Section -->
            <!-- Miscellaneous Section -->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary" name="save_changes">Save Changes</button>
          </div>
        </form> <!-- Form ends here -->
      </div>
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
      printWindow.onload = function() {

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
  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/FAAS.js"></script>
  <script src="http://localhost/ERPTS/print-layout.js"></script>
  <script src="http://localhost/ERPTS/printdata.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>