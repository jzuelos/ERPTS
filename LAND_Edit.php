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
$p_id = isset($_GET['p_id']) ? (int) $_GET['p_id'] : 0;

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

// Fetch land data using faas_id
$land_data = [];
$land_query = $conn->prepare("SELECT * FROM land WHERE faas_id = ?");
$land_query->bind_param("i", $faas_id);
$land_query->execute();
$land_result = $land_query->get_result();

if ($land_result->num_rows > 0) {
  $land_data = $land_result->fetch_assoc();
} else {
  die("Error: No land record found for this FAAS.");
}

$land_query->close();

// Fetch certification data using the land_id
$cert_data = [];
if (isset($land_data['land_id'])) {
  $cert_query = $conn->prepare("SELECT * FROM certification WHERE land_id = ?");
  $cert_query->bind_param("i", $land_data['land_id']);
  $cert_query->execute();
  $cert_result = $cert_query->get_result();

  if ($cert_result->num_rows > 0) {
    $cert_data = $cert_result->fetch_assoc();
  }
  $cert_query->close();
}

$conn->close();

// Output both arrays to the browser console
echo "<script>
        console.log('Land Data:', " . json_encode($land_data) . ");
        console.log('Certification Data:', " . json_encode($cert_data) . ");
      </script>";
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
  <section class="container my-5" id="rpu-identification-section">
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

        <button type="button" id="editRPUButton" class="btn btn-outline-primary btn-sm"
          onclick="toggleEdit()">Edit</button>
      </div>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Land Details Section -->
      <h5 class="section-title">Land Details</h5>

      <form action="" method="post">
        <!-- Identification Numbers -->
        <h6 class="section-subtitle mt-4">Identification Numbers</h6>
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="octTctNumber" class="form-label">OCT/TCT Number</label>
              <input type="text" id="octTctNumber" class="form-control" placeholder="Enter OCT/TCT Number" disabled
                value="<?php echo htmlspecialchars($land_data['oct_no']); ?>">
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="surveyNumber" class="form-label">Survey Number</label>
              <input type="text" id="surveyNumber" class="form-control" placeholder="Enter Survey Number" disabled
                value="<?php echo htmlspecialchars($land_data['survey_no']); ?>">
            </div>
          </div>
        </div>

        <!-- Boundaries -->
        <h6 class="section-subtitle mt-4">Boundaries</h6>
        <div class="row">
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="north" class="form-label">North</label>
              <input type="text" id="north" class="form-control" placeholder="Enter North Boundary" disabled
                value="<?php echo htmlspecialchars($land_data['north']); ?>">
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="south" class="form-label">South</label>
              <input type="text" id="south" class="form-control" placeholder="Enter South Boundary" disabled
                value="<?php echo htmlspecialchars($land_data['south']); ?>">
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="east" class="form-label">East</label>
              <input type="text" id="east" class="form-control" placeholder="Enter East Boundary" disabled
                value="<?php echo htmlspecialchars($land_data['east']); ?>">
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="west" class="form-label">West</label>
              <input type="text" id="west" class="form-control" placeholder="Enter West Boundary" disabled
                value="<?php echo htmlspecialchars($land_data['west']); ?>">
            </div>
          </div>
        </div>

        <!-- Boundary Description -->
        <h6 class="section-subtitle mt-4">Boundary Description</h6>
        <textarea class="form-control mb-4" id="boundaryDescriptionModal" rows="2"
          placeholder="Enter boundary description"
          disabled><?php echo htmlspecialchars($land_data['boun_desc']); ?></textarea>

        <!-- Administrator Information Section -->
        <h5 class="section-title mt-5">Administrator Information</h5>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="adminLastName" class="form-label">Last Name</label>
              <input type="text" id="adminLastName" class="form-control" placeholder="Enter last name" disabled
                value="<?php echo htmlspecialchars($land_data['last_name']); ?>">
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="adminFirstName" class="form-label">First Name</label>
              <input type="text" id="adminFirstName" class="form-control" placeholder="Enter first name" disabled
                value="<?php echo htmlspecialchars($land_data['first_name']); ?>">
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="adminMiddleName" class="form-label">Middle Name</label>
              <input type="text" id="adminMiddleName" class="form-control" placeholder="Enter middle name" disabled
                value="<?php echo htmlspecialchars($land_data['middle_name']); ?>">
            </div>
          </div>
        </div>

        <!-- Contact Information -->
        <h6 class="section-subtitle mt-4">Contact Information</h6>
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="adminContact" class="form-label">Contact Number</label>
              <input type="text" id="adminContact" class="form-control" placeholder="Enter contact number" disabled
                value="<?php echo htmlspecialchars($land_data['contact_no']); ?>">
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="adminEmail" class="form-label">Email</label>
              <input type="email" id="adminEmail" class="form-control" placeholder="Enter email" disabled
                value="<?php echo htmlspecialchars($land_data['email']); ?>">
            </div>
          </div>
        </div>

        <!-- Address Information -->
        <h6 class="section-subtitle mt-4">Address</h6>
        <div class="row">
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="adminAddressNumber" class="form-label">House Number</label>
              <input type="text" id="adminAddressNumber" class="form-control" placeholder="Enter house number" disabled
                value="<?php echo htmlspecialchars($land_data['house_street']); ?>">
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="adminAddressStreet" class="form-label">Street</label>
              <input type="text" id="adminAddressStreet" class="form-control" placeholder="Enter street" disabled
                value="<?php echo htmlspecialchars($land_data['house_street']); ?>">
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="adminAddressBarangay" class="form-label">Barangay</label>
              <input type="text" id="adminAddressBarangay" class="form-control" placeholder="Enter barangay" disabled
                value="<?php echo htmlspecialchars($land_data['barangay']); ?>">
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="mb-3">
              <label for="adminAddressDistrict" class="form-label">District</label>
              <input type="text" id="adminAddressDistrict" class="form-control" placeholder="Enter district" disabled
                value="<?php echo htmlspecialchars($land_data['district']); ?>">
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="adminAddressMunicipality" class="form-label">Municipality/City</label>
              <input type="text" id="adminAddressMunicipality" class="form-control"
                placeholder="Enter municipality or city" disabled
                value="<?php echo htmlspecialchars($land_data['municipality']); ?>">
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="adminAddressProvince" class="form-label">Province</label>
              <input type="text" id="adminAddressProvince" class="form-control" placeholder="Enter province" disabled
                value="<?php echo htmlspecialchars($land_data['province']); ?>">
            </div>
          </div>
        </div>

        <!-- Land Appraisal Section -->
        <h5 class="section-title mt-5">Land Appraisal</h5>

        <div class="row">
          <div class="col-md-6 col-12 mb-4">
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <input type="text" id="description" class="form-control" placeholder="Enter description" disabled
                value="<?php echo htmlspecialchars($land_data['land_desc']); ?>">
            </div>
          </div>
          <div class="col-md-6 col-12 mb-4">
            <div class="mb-3">
              <label for="classification" class="form-label">Classification</label>
              <input type="text" id="classification" class="form-control" placeholder="Enter classification" disabled
                value="<?php echo htmlspecialchars($land_data['classification']); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-12 mb-4">
            <div class="mb-3">
              <label for="actualUse" class="form-label">Actual Use</label>
              <input type="text" id="actualUse" class="form-control" placeholder="Enter actual use" disabled
                value="<?php echo htmlspecialchars($land_data['actual_use']); ?>">
            </div>
          </div>
          <div class="col-md-6 col-12 mb-4">
            <div class="mb-3">
              <label for="subClass" class="form-label">Sub-Class</label>
              <input type="text" id="subClass" class="form-control" placeholder="Enter sub-class" disabled
                value="<?php echo htmlspecialchars($land_data['sub_class']); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="area" class="form-label">Area</label>
              <div class="input-group">
                <input type="text" id="area" class="form-control" placeholder="Enter area in sq m" name="area" disabled
                  value="<?php echo htmlspecialchars($land_data['area']); ?>">
                <div class="input-group-text">
                  <label><input type="radio" id="sqm" name="areaUnit" value="sqm" checked> Sq m</label>
                  <label class="ms-2"><input type="radio" id="hectare" name="areaUnit" value="hectare" disabled>
                    Ha</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="unitValue" class="form-label">Unit Value</label>
              <input type="text" id="unitValue" class="form-control" placeholder="Enter unit value" name="unitValue"
                disabled value="<?php echo htmlspecialchars($land_data['unit_value']); ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="marketValue" class="form-label">Market Value</label>
              <input type="text" id="marketValue" class="form-control" placeholder="Enter market value"
                name="marketValue" disabled value="<?php echo htmlspecialchars($land_data['market_value']); ?>">
            </div>
          </div>
        </div>


        <!-- Value Adjustment Factor Section -->
        <h5 class="section-title mt-5">Value Adjustment Factor</h5>

        <div class="row">
          <div class="col-md-12 mb-4">
            <label for="adjustmentFactorModal" class="form-label">Adjustment Factor</label>
            <textarea id="adjustmentFactorModal" name="adjustment_factor" class="form-control" rows="3"
              placeholder="Enter adjustment factor"
              disabled><?php echo htmlspecialchars($land_data['adjust_factor']); ?></textarea>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="percentAdjustment" class="form-label">Adjustment Factor (%)</label>
              <input type="text" id="percentAdjustment" class="form-control" placeholder="Enter adjustment factor"
                disabled value="<?php echo htmlspecialchars($land_data['adjust_factor']); ?>">
            </div>
          </div>

          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="valueAdjustment" class="form-label">Value Adjustment</label>
              <input type="text" id="valueAdjustment" class="form-control" placeholder="Enter value adjustment" disabled
                value="<?php echo htmlspecialchars($land_data['adjust_value']); ?>">
            </div>
          </div>

          <div class="col-md-4 mb-4">
            <div class="mb-3">
              <label for="adjustedMarketValue" class="form-label">Adjusted Market Value</label>
              <input type="text" id="adjustedMarketValue" class="form-control" placeholder="Enter adjusted market value"
                disabled value="<?php echo htmlspecialchars($land_data['adjust_mv']); ?>">
            </div>
          </div>

        </div>

        <!-- Property Assessment Section -->
        <h5 class="section-title mt-5">Property Assessment</h5>
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="assessmentLevel" class="form-label">Assessment Level</label>
              <input type="text" id="assessmentLevel" class="form-control" placeholder="Enter assessment level" disabled
                value="<?php echo htmlspecialchars($land_data['assess_lvl']); ?>">
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="recommendedAssessmentLevel" class="form-label">% Recommended Assessment Level</label>
              <input type="text" id="recommendedAssessmentLevel" class="form-control"
                placeholder="Enter recommended assessment level" disabled
                value="<?php echo htmlspecialchars($land_data['assess_lvl']); ?>">
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="assessedValue" class="form-label">Assessed Value</label>
              <input type="text" id="assessedValue" class="form-control" placeholder="Enter assessed value" disabled
                value="<?php echo htmlspecialchars($land_data['assess_value']); ?>">
            </div>
          </div>
        </div>

        <!-- Certification Section -->
        <div class="section-wrap px-4 mb-5">
          <h5 class="section-title mt-4">Certification</h5>
          <div class="row gx-4">
            <div class="col-md-12">
              <!-- Verified By -->
              <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 me-2" style="width: 140px;">Verified By</label>
                <select class="form-select me-2" style="width: 30%;" name="verified_by" disabled>
                  <option disabled <?= empty($cert_data['verified']) ? 'selected' : '' ?>>Select verifier</option>
                  <option <?= ($cert_data['verified'] ?? '') === 'Malapajo, Antonio Menorca' ? 'selected' : '' ?>>
                    Malapajo, Antonio Menorca
                  </option>
                </select>
                <button type="button" class="btn btn-outline-primary" style="width: 100px;" disabled>Verify</button>
              </div>

              <!-- Plotted By -->
              <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 me-2" style="width: 140px;">Plotted By</label>
                <select class="form-select" style="width: 30%;" name="plotted_by" disabled>
                  <option disabled <?= empty($cert_data['plotted']) ? 'selected' : '' ?>>Select plotter</option>
                  <option <?= ($cert_data['plotted'] ?? '') === 'Malapajo, Antonio Menorca' ? 'selected' : '' ?>>
                    Malapajo, Antonio Menorca
                  </option>
                </select>
              </div>

              <!-- Noted By -->
              <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 me-2" style="width: 140px;">Noted By</label>
                <select class="form-select" style="width: 30%;" name="noted_by" disabled>
                  <option disabled <?= empty($cert_data['noted']) ? 'selected' : '' ?>>Select noter</option>
                  <option <?= ($cert_data['noted'] ?? '') === 'Lingon, Nestor Jacolbia' ? 'selected' : '' ?>>
                    Lingon, Nestor Jacolbia
                  </option>
                </select>
              </div>

              <!-- Appraised By -->
              <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 me-2" style="width: 140px;">Appraised By</label>
                <select class="form-select me-2" style="width: 30%;" name="appraised_by" disabled>
                  <option disabled <?= empty($cert_data['appraised']) ? 'selected' : '' ?>>Select appraiser</option>
                  <option <?= ($cert_data['appraised'] ?? '') === 'Lingon, Nestor Jacolbia' ? 'selected' : '' ?>>
                    Lingon, Nestor Jacolbia
                  </option>
                </select>
                <label class="form-label mb-0 me-2" style="width: 60px;">Date</label>
                <input type="date" class="form-control" name="appraisal_date" id="appraisalDate" style="width: 30%;"
                  disabled value="<?= htmlspecialchars($cert_data['appraised_date'] ?? '') ?>">
              </div>

              <!-- Recommending Approval -->
              <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 me-2" style="width: 140px;">Recommending Approval</label>
                <select class="form-select me-2" style="width: 30%;" name="recommending_approval" disabled>
                  <option disabled <?= empty($cert_data['recom_approval']) ? 'selected' : '' ?>>Select recommender
                  </option>
                  <option <?= ($cert_data['recom_approval'] ?? '') === 'Malapajo, Antonio Menorca' ? 'selected' : '' ?>>
                    Malapajo, Antonio Menorca
                  </option>
                </select>
                <label class="form-label mb-0 me-2" style="width: 60px;">Date</label>
                <input type="date" class="form-control" name="recommendation_date" id="recommendationDate"
                  style="width: 30%;" disabled value="<?= htmlspecialchars($cert_data['recom_date'] ?? '') ?>">
              </div>

              <!-- Approved By -->
              <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 me-2" style="width: 140px;">Approved By</label>
                <select class="form-select me-2" style="width: 30%;" name="approved_by" disabled>
                  <option disabled <?= empty($cert_data['approved']) ? 'selected' : '' ?>>Select approver</option>
                  <option <?= ($cert_data['approved'] ?? '') === 'Lingon, Nestor Jacolbia' ? 'selected' : '' ?>>
                    Lingon, Nestor Jacolbia
                  </option>
                </select>
                <label class="form-label mb-0 me-2" style="width: 60px;">Date</label>
                <input type="date" class="form-control" name="approval_date" id="approvalDate" style="width: 30%;"
                  disabled value="<?= htmlspecialchars($cert_data['approved_date'] ?? '') ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Miscellaneous Section -->
        <div class="section-wrap px-4 mb-5 border rounded p-3">
          <h5 class="section-title mt-5">Miscellaneous</h5>
          <div class="row">
            <div class="col-md-6 mb-4">
              <div class="mb-3">
                <label class="form-label d-block">Idle</label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="idleStatus" id="idleYes" value="yes" disabled
                    <?= ($cert_data['idle'] ?? '') === 'yes' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="idleYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="idleStatus" id="idleNo" value="no" disabled
                    <?= ($cert_data['idle'] ?? 'no') === 'no' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="idleNo">No</label>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="mb-3">
                <label class="form-label d-block">Contested</label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="contestedStatus" id="contestedYes" value="yes"
                    disabled <?= ($cert_data['contested'] ?? '') === 'yes' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="contestedYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="contestedStatus" id="contestedNo" value="no"
                    disabled <?= ($cert_data['contested'] ?? 'no') === 'no' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="contestedNo">No</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <input type="submit" name="submit" value="Submit">

      </form>

      <!-- Print Button at Bottom Right -->
      <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-outline-secondary py-2 px-4" style="font-size: 1.1rem;">
          <i class="fas fa-print me-2"></i>Print
        </button>
      </div>
    </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function () {

      // Set today's date in the date input
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('approvalDate').value = today;
      document.getElementById('recommendationDate').value = today;
      document.getElementById('appraisalDate').value = today;

      function toggleEdit() {
        const editButton = document.getElementById('editRPUButton');
        const inputs = document.querySelectorAll('#rpu-identification-section input, #rpu-identification-section select, #rpu-identification-section textarea');

        const isEditing = editButton.textContent.trim() === 'Edit';
        editButton.textContent = isEditing ? 'Done' : 'Edit';

        const excludeIds = [
          "marketValue",
          "valueAdjustment",
          "adjustedMarketValue",
          "assessedValue",
          "recommendedAssessmentLevel",
          "recommendedUnitValue"
        ];

        inputs.forEach(input => {
          if (excludeIds.includes(input.id)) return;
          input.disabled = !isEditing;
        });
      }

      // Attach event listener for edit button
      document.getElementById('editRPUButton').addEventListener('click', toggleEdit);

      // DOM Elements
      const areaInput = document.getElementById("area");
      const unitValueInput = document.getElementById("unitValue");
      const marketValueInput = document.getElementById("marketValue");

      const sqmRadio = document.querySelector("input[name='areaUnit'][value='sqm']");
      const hectareRadio = document.querySelector("input[name='areaUnit'][value='hectare']");

      const percentAdjustmentInput = document.getElementById("percentAdjustment");
      const valueAdjustmentInput = document.getElementById("valueAdjustment");
      const adjustedMarketValueInput = document.getElementById("adjustedMarketValue");

      const assessmentLevelInput = document.getElementById("assessmentLevel");
      const assessedValueInput = document.getElementById("assessedValue");

      // Utility: Debounce
      function debounce(func, wait) {
        let timeout;
        return function (...args) {
          clearTimeout(timeout);
          timeout = setTimeout(() => func.apply(this, args), wait);
        };
      }

      // Convert area between sqm and hectare
      function convertArea() {
        const raw = areaInput.value.trim();
        if (raw === '' || isNaN(raw)) {
          marketValueInput.value = '';
          return;
        }

        const val = parseFloat(raw);
        areaInput.value = hectareRadio.checked
          ? (val / 10000).toFixed(4) // sqm → ha
          : (val * 10000).toFixed(2); // ha → sqm

        calculateMarketValue();
      }

      // Calculate market value = area * unit value
      function calculateMarketValue() {
        const area = parseFloat(areaInput.value);
        const unitValue = parseFloat(unitValueInput.value);

        if (isNaN(area) || isNaN(unitValue)) {
          clearValues([marketValueInput, valueAdjustmentInput, adjustedMarketValueInput]);
          return;
        }

        const marketValue = area * unitValue;
        marketValueInput.value = marketValue.toFixed(2);
        calculateAdjustment(marketValue);
      }

      // Calculate adjustment and adjusted market value
      function calculateAdjustment(marketValue) {
        const percent = parseFloat(percentAdjustmentInput.value);
        if (isNaN(percent)) {
          clearValues([valueAdjustmentInput, adjustedMarketValueInput]);
          return;
        }

        const adjustedValue = marketValue * (percent / 100);
        const adjustment = adjustedValue - marketValue;

        valueAdjustmentInput.value = formatSigned(adjustment);
        adjustedMarketValueInput.value = adjustedValue.toFixed(2);

        calculateAssessedValue(); // Recalculate assessed value on adjustment
      }

      // Calculate assessed value = adjustedMarketValue * (assessmentLevel / 100)
      function calculateAssessedValue() {
        const adjustedMarketValue = parseFloat(adjustedMarketValueInput.value.replace(/,/g, '')) || 0;
        const assessmentLevel = parseFloat(assessmentLevelInput.value) || 0;

        if (!isNaN(adjustedMarketValue) && !isNaN(assessmentLevel) && assessmentLevel > 0) {
          const assessed = adjustedMarketValue * (assessmentLevel / 100);
          assessedValueInput.value = assessed.toFixed(2).toLocaleString();
        } else {
          assessedValueInput.value = '';
        }
      }

      // Visual input validation
      function validateInputs() {
        highlightInvalid(areaInput, isNaN(parseFloat(areaInput.value)) || parseFloat(areaInput.value) <= 0);
        highlightInvalid(unitValueInput, isNaN(parseFloat(unitValueInput.value)) || parseFloat(unitValueInput.value) <= 0);
      }

      // Helpers
      function formatSigned(num) {
        return (num < 0 ? "-" : "") + Math.abs(num).toFixed(2);
      }

      function clearValues(elements) {
        elements.forEach(el => el.value = '');
      }

      function highlightInvalid(input, condition) {
        input.classList.toggle('is-invalid', condition);
        input.style.borderColor = condition ? 'red' : '';
      }

      // Event listeners
      sqmRadio.addEventListener('change', convertArea);
      hectareRadio.addEventListener('change', convertArea);

      areaInput.addEventListener('input', debounce(() => {
        calculateMarketValue();
        validateInputs();
      }, 300));

      unitValueInput.addEventListener('input', debounce(() => {
        calculateMarketValue();
        validateInputs();
      }, 300));

      percentAdjustmentInput.addEventListener('input', debounce(() => {
        const marketValue = parseFloat(marketValueInput.value) || 0;
        calculateAdjustment(marketValue);
      }, 300));

      assessmentLevelInput.addEventListener('input', debounce(calculateAssessedValue, 300));

      // Initial run
      calculateMarketValue();
      validateInputs();

    });
  </script>


  <!-- Load External Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb5g5/5hb5g5/5hb5g5/5hb5g5/5hb5g5" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>