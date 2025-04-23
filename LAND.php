<?php
// Handle AJAX data fetch for classifications, subclasses, and land uses first
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch'])) {
  header('Content-Type: application/json');
  require_once 'database.php';
  $conn = Database::getInstance();

  $classifications = $conn->query("SELECT c_id, c_code, c_description FROM classification WHERE c_status = 'Active'");
  $subclasses = $conn->query("SELECT sc_id, sc_code, sc_description FROM subclass WHERE sc_status = 'Active'");
  $land_uses = $conn->query("SELECT lu_id, lu_description FROM land_use WHERE lu_status = 'Active'");

  $data = [
      'classifications' => [],
      'subclasses' => [],
      'land_uses' => []
  ];

  while ($row = $classifications->fetch_assoc()) {
      $data['classifications'][] = [
          'id' => $row['c_id'],
          'text' => "{$row['c_description']} ({$row['c_code']})"
      ];
  }

  while ($row = $subclasses->fetch_assoc()) {
      $data['subclasses'][] = [
          'id' => $row['sc_id'],
          'text' => "{$row['sc_description']} ({$row['sc_code']})"
      ];
  }

  while ($row = $land_uses->fetch_assoc()) {
      $data['land_uses'][] = [
          'id' => $row['lu_id'],
          'text' => $row['lu_description']
      ];
  }

  echo json_encode($data);
  exit;
}

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


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Collect numeric values
  $oct_no = (int) ($_POST['oct_no'] ?? 0);
  $unit_value = (float) ($_POST['unit_value'] ?? 0);
  $market_value = (float) ($_POST['market_value'] ?? 0);
  $adjust_percent = (float) ($_POST['percent_adjustment'] ?? 100);
  $adjust_value = (float) ($_POST['value_adjustment'] ?? 0);
  $adjust_mv = (float) ($_POST['adjusted_market_value'] ?? 0);
  $assess_lvl = (float) ($_POST['assessment_level'] ?? 0);
  $assess_value = (float) ($_POST['assessed_value'] ?? 0);

  // Collect text values from the form
  $fields = [
    'survey_no',
    'north_boundary',
    'south_boundary',
    'east_boundary',
    'west_boundary',
    'boun_desc',
    'last_name',
    'first_name',
    'middle_name',
    'contact_no',
    'email',
    'house_street',
    'barangay',
    'district',
    'municipality',
    'province',
    'land_desc',
    'classification',
    'sub_class',
    'area',
    'actual_use',
    'adjustment_factor'
  ];

  foreach ($fields as $field) {
    $$field = $_POST[$field] ?? '';
  }

  // === INSERT INTO land ===
  $stmt = $conn->prepare("
      INSERT INTO land (
          faas_id, oct_no, survey_no, north, east, south, west, boun_desc, last_name, 
          first_name, middle_name, contact_no, email, house_street, barangay, 
          district, municipality, province, land_desc, classification, sub_class, 
          area, actual_use, unit_value, market_value, adjust_factor, 
          adjust_percent, adjust_value, adjust_mv, assess_lvl, assess_value
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  $stmt->bind_param(
    "iisssssssssssssssssssisddsddddd",
    $faas_id,
    $oct_no,
    $survey_no,
    $north_boundary,
    $east_boundary,
    $south_boundary,
    $west_boundary,
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
    $market_value,
    $adjustment_factor,
    $adjust_percent,
    $adjust_value,
    $adjust_mv,
    $assess_lvl,
    $assess_value
  );

  if ($stmt->execute()) {
    $land_id = $conn->insert_id; // Get last inserted ID from land table

    // Add land_id to $_POST so it's passed into insertCertification
    $_POST['land_id'] = $land_id;

    insertCertification($conn, $_POST);

    $p_id = htmlspecialchars($_GET['p_id'] ?? '');
    echo "<script>
            alert('Land record added successfully!');
            window.location.href = 'FAAS.php?id=$p_id';
          </script>";
    exit();
  } else {
    echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
  }

  $stmt->close();
}

function insertCertification($conn, $data)
{
  $land_id = $data['land_id'] ?? null; // Foreign key reference

  $verified = $data['verified_by'] ?? null;
  $noted = $data['noted_by'] ?? null;
  $recomApproval = $data['recommending_approval'] ?? null;
  $recomDate = !empty($data['recommendation_date']) ? $data['recommendation_date'] : null;
  $plotted = $data['plotted_by'] ?? null;
  $appraised = $data['appraised_by'] ?? null;
  $appraisedDate = !empty($data['appraisal_date']) ? $data['appraisal_date'] : null;
  $approved = $data['approved_by'] ?? null;
  $approvedDate = !empty($data['approval_date']) ? $data['approval_date'] : null;

  $idle = isset($data['idleStatus']) && $data['idleStatus'] === 'yes' ? 1 : 0;
  $contested = isset($data['contestedStatus']) && $data['contestedStatus'] === 'yes' ? 1 : 0;

  // Start building dynamic query
  $fields = ['land_id', 'verified', 'noted', 'recom_approval', 'plotted', 'appraised', 'approved', 'idle', 'contested'];
  $placeholders = ['?', '?', '?', '?', '?', '?', '?', '?', '?'];
  $values = [$land_id, $verified, $noted, $recomApproval, $plotted, $appraised, $approved, $idle, $contested];
  $types = 'issssssii';

  if ($recomDate !== null) {
    $fields[] = 'recom_date';
    $placeholders[] = '?';
    $values[] = $recomDate;
    $types .= 's';
  }

  if ($appraisedDate !== null) {
    $fields[] = 'appraised_date';
    $placeholders[] = '?';
    $values[] = $appraisedDate;
    $types .= 's';
  }

  if ($approvedDate !== null) {
    $fields[] = 'approved_date';
    $placeholders[] = '?';
    $values[] = $approvedDate;
    $types .= 's';
  }

  $sql = "INSERT INTO certification (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    echo "<script>alert('Certification Prep Error: " . addslashes($conn->error) . "');</script>";
    return;
  }

  $stmt->bind_param($types, ...$values);

  if (!$stmt->execute()) {
    echo "<script>alert('Certification Insert Error: " . addslashes($stmt->error) . "');</script>";
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
        <div class="col-md-6 col-12 mb-4">
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" id="description" class="form-control" placeholder="Enter description" disabled>
          </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
          <div class="mb-3">
            <label for="classification" class="form-label">Classification</label>
            <input type="text" id="classification" class="form-control" placeholder="Enter classification" disabled>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-12 mb-4">
          <div class="mb-3">
            <label for="actualUse" class="form-label">Actual Use</label>
            <input type="text" id="actualUse" class="form-control" placeholder="Enter actual use" disabled>
          </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
          <div class="mb-3">
            <label for="subClass" class="form-label">Sub-Class</label>
            <input type="text" id="subClass" class="form-control" placeholder="Enter sub-class" disabled>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="area" class="form-label">Area</label>
            <div class="input-group">
              <input type="text" id="area" class="form-control" placeholder="Enter area in sq m" disabled>
              <div class="input-group-text">
                <label><input type="radio" name="areaUnit" value="sqm" checked> Sq m</label>
                <label class="ms-2"><input type="radio" name="areaUnit" value="hectare" disabled> Ha</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="unitValue" class="form-label">Unit Value</label>
            <input type="text" id="unitValue" class="form-control" placeholder="Enter unit value" disabled>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="marketValue" class="form-label">Market Value</label>
            <input type="text" id="marketValue" class="form-control" placeholder="Enter market value" disabled>
          </div>
        </div>
      </div>

      <!-- Value Adjustment Factor Section -->
      <h5 class="section-title mt-5">Value Adjustment Factor</h5>

      <div class="row">
        <div class="col-md-12 mb-4">
          <label for="adjustmentFactorModal" class="form-label">Adjustment Factor</label>
          <textarea id="adjustmentFactorModal" name="adjustment_factor" class="form-control" rows="3"
            placeholder="Enter adjustment factor" disabled></textarea>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adjustmentFactor" class="form-label">Adjustment Factor</label>
            <input type="text" id="adjustmentFactor" class="form-control" placeholder="Enter adjustment factor"
              disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="percentAdjustment" class="form-label">% Adjustment</label>
            <input type="text" id="percentAdjustment" class="form-control" placeholder="Enter % adjustment" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="valueAdjustment" class="form-label">Value Adjustment</label>
            <input type="text" id="valueAdjustment" class="form-control" placeholder="Enter value adjustment" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adjustedMarketValue" class="form-label">Adjusted Market Value</label>
            <input type="text" id="adjustedMarketValue" class="form-control" placeholder="Enter adjusted market value"
              disabled>
          </div>
        </div>
      </div>

      <!-- Property Assessment Section -->
      <h5 class="section-title mt-5">Property Assessment</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="assessmentLevel" class="form-label">Assessment Level</label>
            <input type="text" id="assessmentLevel" class="form-control" placeholder="Enter assessment level" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="recommendedAssessmentLevel" class="form-label">% Recommended Assessment Level</label>
            <input type="text" id="recommendedAssessmentLevel" class="form-control"
              placeholder="Enter recommended assessment level" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="assessedValue" class="form-label">Assessed Value</label>
            <input type="text" id="assessedValue" class="form-control" placeholder="Enter assessed value" disabled>
          </div>
        </div>
      </div>

      <!-- Certification Section -->
      <h5 class="section-title mt-5">Certification</h5>
      <div class="container">
        <div class="row">
          <div class="col-12">

            <!-- Verified By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Verified By</label>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Enter Verifier" disabled>
              </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-outline-primary btn-sm w-100">Verify</button>
              </div>
            </div>

            <!-- Plotted By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Plotted By</label>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Enter Plotter" disabled>
              </div>
            </div>

            <!-- Noted By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Noted By</label>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Enter Noter" disabled>
              </div>
            </div>

            <!-- Appraised By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Appraised By</label>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Enter Appraiser" disabled>
              </div>
              <label class="col-md-1 col-form-label text-end">Date</label>
              <div class="col-md-3">
                <input type="date" class="form-control" disabled>
              </div>
            </div>

            <!-- Recommending Approval -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Recommending Approval</label>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Enter Recommender" disabled>
              </div>
              <label class="col-md-1 col-form-label text-end">Date</label>
              <div class="col-md-3">
                <input type="date" class="form-control" disabled>
              </div>
            </div>

            <!-- Approved By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Approved By</label>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Enter Approver" disabled>
              </div>
              <label class="col-md-1 col-form-label text-end">Date</label>
              <div class="col-md-3">
                <input type="date" class="form-control" disabled>
              </div>
            </div>

          </div>
        </div>
      </div>


      <!-- Miscellaneous Section -->
      <h5 class="section-title mt-5">Miscellaneous</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label class="form-label d-block">Idle</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="idleStatus" id="idleYes" value="yes" disabled>
              <label class="form-check-label" for="idleYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="idleStatus" id="idleNo" value="no" disabled>
              <label class="form-check-label" for="idleNo">No</label>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label class="form-label d-block">Contested</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="contestedStatus" id="contestedYes" value="yes"
                disabled>
              <label class="form-check-label" for="contestedYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="contestedStatus" id="contestedNo" value="no"
                disabled>
              <label class="form-check-label" for="contestedNo">No</label>
            </div>
          </div>
        </div>
      </div>
      <!-- Print Button at Bottom Right -->
      <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-outline-secondary py-2 px-4" style="font-size: 1.1rem;">
          <i class="fas fa-print me-2"></i>Print
        </button>
      </div>
    </div>
    </div>
  </section>


  <!-- Modal for Editing Land Details -->
  <div class="modal fade" id="editLandModal" tabindex="-1" aria-labelledby="editLandModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 55%; width: 55%;">
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
                <input type="text" id="octTctNumberModal" name="oct_no" class="form-control"
                  placeholder="Enter OCT/TCT Number" required>
              </div>
              <div class="col-md-6 mb-4">
                <label for="surveyNumberModal" class="form-label">Survey Number</label>
                <input type="text" id="surveyNumberModal" name="survey_no" class="form-control"
                  placeholder="Enter Survey Number" required>
              </div>
            </div>

            <!-- Boundaries -->
            <div class="row">
              <div class="col-md-3 mb-4">
                <label for="northModal" class="form-label">North Boundary</label>
                <input type="text" id="northModal" name="north_boundary" class="form-control"
                  placeholder="Enter North Boundary">
              </div>
              <div class="col-md-3 mb-4">
                <label for="southModal" class="form-label">South Boundary</label>
                <input type="text" id="southModal" name="south_boundary" class="form-control"
                  placeholder="Enter South Boundary">
              </div>
              <div class="col-md-3 mb-4">
                <label for="eastModal" class="form-label">East Boundary</label>
                <input type="text" id="eastModal" name="east_boundary" class="form-control"
                  placeholder="Enter East Boundary">
              </div>
              <div class="col-md-3 mb-4">
                <label for="westModal" class="form-label">West Boundary</label>
                <input type="text" id="westModal" name="west_boundary" class="form-control"
                  placeholder="Enter West Boundary">
              </div>
            </div>

            <!-- Boundary Description -->
            <div class="mb-4">
              <label for="boundaryDescriptionModal" class="form-label">Boundary Description</label>
              <textarea class="form-control" id="boundaryDescriptionModal" name="boun_desc" rows="2"
                placeholder="Enter boundary description"></textarea>
            </div>

            <!-- Administrator Information -->
            <h5 class="section-title mt-5">Administrator Information</h5>
            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="adminLastNameModal" class="form-label">Last Name</label>
                <input type="text" id="adminLastNameModal" name="last_name" class="form-control"
                  placeholder="Enter last name">
              </div>
              <div class="col-md-4 mb-4">
                <label for="adminFirstNameModal" class="form-label">First Name</label>
                <input type="text" id="adminFirstNameModal" name="first_name" class="form-control"
                  placeholder="Enter first name">
              </div>
              <div class="col-md-4 mb-4">
                <label for="adminMiddleNameModal" class="form-label">Middle Name</label>
                <input type="text" id="adminMiddleNameModal" name="middle_name" class="form-control"
                  placeholder="Enter middle name">
              </div>
            </div>

            <!-- Contact Information -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="adminContactModal" class="form-label">Contact Number</label>
                <input type="text" id="adminContactModal" name="contact_no" class="form-control"
                  placeholder="Enter contact number">
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
                <input type="text" id="adminAddressNumberModal" name="house_street" class="form-control"
                  placeholder="Enter house number">
              </div>
              <div class="col-md-3 mb-4">
                <label for="adminAddressStreetModal" class="form-label">Street</label>
                <input type="text" id="adminAddressStreetModal" name="barangay" class="form-control"
                  placeholder="Enter street">
              </div>
              <div class="col-md-3 mb-4">
                <label for="adminAddressMunicipalityModal" class="form-label">Municipality</label>
                <input type="text" id="adminAddressMunicipalityModal" name="municipality" class="form-control"
                  placeholder="Enter municipality">
              </div>
              <div class="col-md-3 mb-4">
                <label for="adminAddressProvinceModal" class="form-label">Province</label>
                <input type="text" id="adminAddressProvinceModal" name="province" class="form-control"
                  placeholder="Enter province">
              </div>
            </div>

            <!-- Land Appraisal Section -->
            <h5 class="section-title mt-5">Land Appraisal</h5>
            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="landDescModal" class="form-label">Land Description</label>
                <input type="text" id="landDescModal" name="land_desc" class="form-control"
                  placeholder="Enter land description">
              </div>
              <div class="col-md-6 mb-4">
                <label for="classificationModal" class="form-label">Classification</label>
                <select id="classificationModal" name="classification" class="form-select"></select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="subClassModal" class="form-label">Sub-Class</label>
                <select id="subClassModal" name="sub_class" class="form-select"></select>
              </div>
              <div class="col-md-6 mb-4">
                <label for="actualUseModal" class="form-label">Actual Use</label>
                <select id="actualUseModal" name="actual_use" class="form-select"></select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-8 mb-4">
                <label for="areaModal" class="form-label">Area</label>
                <div class="input-group">
                  <input type="number" id="areaModal" name="area" class="form-control" placeholder="Enter area"
                    step="any" required>
                  <div class="input-group-append ml-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="areaUnit" value="sqm" id="sqm" checked>
                      <label class="form-check-label" for="sqm">Sqm</label>
                    </div>
                    <div class="form-check ms-2 ml-3">
                      <input class="form-check-input" type="radio" name="areaUnit" value="hectare" id="hectare">
                      <label class="form-check-label" for="hectare">Ha</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="unitValueModal" class="form-label">Unit Value</label>
                <input type="number" id="unitValueModal" name="unit_value" class="form-control"
                  placeholder="Enter unit value">
              </div>
              <div class="col-md-4 mb-4">
                <label for="recommendedUnitValue" class="form-label">Recommended Unit Value</label>
                <input type="number" id="recommendedUnitValue" name="recommended_unit_value" class="form-control"
                  placeholder="loading..." disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="marketValueModal" class="form-label">Market Value</label>
                <input type="number" id="marketValueModal" name="market_value" class="form-control"
                  placeholder="Market value" readonly>
              </div>
            </div>
          </div>

          <!-- Value Adjustment Factor Section in Modal -->
          <div class="section-wrap px-4 mb-5">
            <h5 class="section-title mt-5">Value Adjustment Factor</h5>
            <div class="row">
              <div class="col-md-12 mb-4">
                <label for="adjustmentFactorModal" class="form-label">Adjustment Factor</label>
                <textarea id="adjustmentFactorModal" name="adjustment_factor" class="form-control" rows="3"
                  placeholder="Enter adjustment factor"></textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="percentAdjustmentModal" class="form-label">% Adjustment</label>
                <input type="text" id="percentAdjustmentModal" name="percent_adjustment" class="form-control"
                  value="100">
              </div>
              <div class="col-md-4 mb-4">
                <label for="valueAdjustmentModal" class="form-label">Value Adjustment</label>
                <input type="text" id="valueAdjustmentModal" name="value_adjustment" class="form-control"
                  placeholder="Enter value adjustment" readonly>
              </div>
              <div class="col-md-4 mb-4">
                <label for="adjustedMarketValueModal" class="form-label">Adjusted Market Value</label>
                <input type="text" id="adjustedMarketValueModal" name="adjusted_market_value" class="form-control"
                  placeholder="Enter adjusted market value" readonly>
              </div>
            </div>
          </div>

          <!-- Property Assessment Section in Modal -->
          <div class="section-wrap px-4 mb-5">
            <h5 class="section-title mt-5">Property Assessment</h5>
            <div class="row">
              <div class="col-md-5 mb-4">
                <label for="assessmentLevelModal" class="form-label">Assessment Level</label>
                <input type="number" id="assessmentLevelModal" name="assessment_level" class="form-control"
                  placeholder="Enter assessment level">
              </div>
              <div class="col-md-4 mb-4 ml-5">
                <label for="recommendedAssessmentLevelModal" class="form-label">% Recommended Assessment Level</label>
                <input type="number" id="recommendedAssessmentLevelModal" name="recommended_assessment_level"
                  class="form-control" placeholder="loading..." readonly>
              </div>
              <div class="col-md-5 mb-4">
                <label for="assessedValueModal" class="form-label">Assessed Value</label>
                <input type="number" id="assessedValueModal" name="assessed_value" class="form-control"
                  placeholder="Assessed Value" readonly>
              </div>
            </div>
          </div>

          <!-- Certification Section Modal -->
          <div class="section-wrap px-4 mb-5">
            <h5 class="section-title mt-4">Certification</h5>
            <div class="row gx-4">
              <div class="col-md-12">
                <!-- Verified By -->
                <div class="d-flex align-items-center mb-3">
                  <label class="form-label mb-0 me-2" style="width: 140px;">Verified By</label>
                  <select class="form-select me-2" style="width: 30%;" name="verified_by">
                    <option selected disabled>Select verifier</option>
                    <option>Malapajo, Antonio Menorca</option>
                  </select>
                  <button type="button" class="btn btn-outline-primary" style="width: 100px;">Verify</button>
                </div>

                <!-- Plotted By -->
                <div class="d-flex align-items-center mb-3">
                  <label class="form-label mb-0 me-2" style="width: 140px;">Plotted By</label>
                  <select class="form-select" style="width: 30%;" name="plotted_by">
                    <option selected disabled>Select plotter</option>
                    <option>Malapajo, Antonio Menorca</option>
                  </select>
                </div>

                <!-- Noted By -->
                <div class="d-flex align-items-center mb-3">
                  <label class="form-label mb-0 me-2" style="width: 140px;">Noted By</label>
                  <select class="form-select" style="width: 30%;" name="noted_by">
                    <option selected disabled>Select noter</option>
                    <option>Lingon, Nestor Jacolbia</option>
                  </select>
                </div>

                <!-- Appraised By -->
                <div class="d-flex align-items-center mb-3">
                  <label class="form-label mb-0 me-2" style="width: 140px;">Appraised By</label>
                  <select class="form-select me-2" style="width: 30%;" name="appraised_by">
                    <option selected disabled>Select appraiser</option>
                    <option>Lingon, Nestor Jacolbia</option>
                  </select>
                  <label class="form-label mb-0 me-2" style="width: 60px;">Date</label>
                  <input type="date" class="form-control" name="appraisal_date" id="appraisalDate" style="width: 30%;">
                </div>

                <!-- Recommending Approval -->
                <div class="d-flex align-items-center mb-3">
                  <label class="form-label mb-0 me-2" style="width: 140px;">Recommending Approval</label>
                  <select class="form-select me-2" style="width: 30%;" name="recommending_approval">
                    <option selected disabled>Select recommender</option>
                    <option>Malapajo, Antonio Menorca</option>
                  </select>
                  <label class="form-label mb-0 me-2" style="width: 60px;">Date</label>
                  <input type="date" class="form-control" name="recommendation_date" id="recommendationDate" style="width: 30%;">
                </div>

                <!-- Approved By -->
                <div class="d-flex align-items-center mb-3">
                  <label class="form-label mb-0 me-2" style="width: 140px;">Approved By</label>
                  <select class="form-select me-2" style="width: 30%;" name="approved_by">
                    <option selected disabled>Select approver</option>
                    <option>Lingon, Nestor Jacolbia</option>
                  </select>
                  <label class="form-label mb-0 me-2" style="width: 60px;">Date</label>
                  <input type="date" class="form-control" name="approval_date" id="approvalDate" style="width: 30%;">
                </div>
              </div>
            </div>
          </div>


          <!-- Miscellaneous Section Modal -->
          <div class="section-wrap px-4 mb-5 border rounded p-3">
            <h5 class="section-title mt-5">Miscellaneous</h5>
            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="mb-3">
                  <label class="form-label d-block">Idle</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="idleStatus" id="idleYes" value="yes">
                    <label class="form-check-label" for="idleYes">Yes</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="idleStatus" id="idleNo" value="no" checked>
                    <label class="form-check-label" for="idleNo">No</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="mb-3">
                  <label class="form-label d-block">Contested</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="contestedStatus" id="contestedYes" value="yes">
                    <label class="form-check-label" for="contestedYes">Yes</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="contestedStatus" id="contestedNo" value="no"
                      checked>
                    <label class="form-check-label" for="contestedNo">No</label>
                  </div>
                </div>
              </div>
            </div>
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      // Set today's date in the date input
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('approvalDate').value = today;
      document.getElementById('recommendationDate').value = today;
      document.getElementById('appraisalDate').value = today;

      // Function to reset all modal forms
      function resetForm() {
        document.querySelectorAll('.modal form').forEach(form => form.reset());
        document.querySelectorAll('.modal input, .modal select, .modal textarea').forEach(field => {
          if (field.type === "checkbox" || field.type === "radio") {
            field.checked = field.defaultChecked;
          } else if (field.tagName === "SELECT") {
            field.selectedIndex = 0;
          } else {
            field.value = "";
          }
        });
      }

      // Function to toggle edit mode
      function toggleEdit() {
        const editButton = document.getElementById('editRPUButton');
        const inputs = document.querySelectorAll('#rpu-identification-section input, #rpu-identification-section select');

        if (editButton.textContent === 'Edit') {
          editButton.textContent = 'Save';
          inputs.forEach(input => input.disabled = false);
        } else {
          saveRPUData();
          editButton.textContent = 'Edit';
          inputs.forEach(input => input.disabled = true);
        }
      }

      let arpData = {};

      // Function to save data
      function saveRPUData() {
        const propertyId = new URLSearchParams(window.location.search).get('id');
        const faasIdMatch = document.body.innerHTML.match(/Faas ID:\s*(\d+)/);
        const faasId = faasIdMatch ? faasIdMatch[1] : null;

        if (!faasId) {
          alert("Error: FAAS ID not found on the page.");
          return;
        }

        arpData = {
          faasId: faasId,
          arpNumber: document.getElementById('arpNumber').value,
          propertyNumber: document.getElementById('propertyNumber').value,
          taxability: document.getElementById('taxability').value,
          effectivity: document.getElementById('effectivity').value
        };

        fetch('FAASrpuID.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(arpData)
          })
          .then(response => response.json())
          .then(data => {
            alert(data.success ? 'Success' : 'Failed to insert data: ' + data.error);
          })
          .catch(error => alert('An error occurred: ' + error));
      }

      // Update area unit and recalculate market value
      function updateAreaUnit() {
        const areaInput = document.getElementById("areaModal");
        const sqmRadio = document.getElementById("sqm");
        const hectareRadio = document.getElementById("hectare");
        const unitValueInput = document.getElementById("unitValueModal");
        const marketValueInput = document.getElementById("marketValueModal");
        const valueAdjustmentInput = document.getElementById("valueAdjustmentModal");
        const adjustedMarketValueInput = document.getElementById("adjustedMarketValueModal");
        const percentAdjustmentInput = document.getElementById("percentAdjustmentModal");

        // Convert area based on selected unit (sqm or hectare)
        function convertArea() {
          let value = parseFloat(areaInput.value) || 0;

          // Convert area when unit changes
          if (sqmRadio.checked) {
            areaInput.value = (value * 10000).toFixed(2); // Convert hectares to sqm
          } else if (hectareRadio.checked) {
            areaInput.value = (value / 10000).toFixed(4); // Convert sqm to hectares
          }

          // Recalculate market value after area conversion
          calculateMarketValue();
        }

        // Debounced version of the input event to improve performance
        function debounce(func, wait) {
          let timeout;
          return function() {
            clearTimeout(timeout);
            timeout = setTimeout(func, wait);
          };
        }

        // Calculate market value based on area and unit value
        function calculateMarketValue() {
          const area = parseFloat(areaInput.value) || 0;
          const unitValue = parseFloat(unitValueInput.value) || 0;

          // If hectares are selected, we need to convert them to sqm for calculation
          let areaInSquareMeters = hectareRadio.checked ? area * 10000 : area;

          // Only calculate if both area and unit value are valid
          if (!isNaN(areaInSquareMeters) && !isNaN(unitValue) && areaInSquareMeters > 0 && unitValue > 0) {
            let marketValue = areaInSquareMeters * unitValue; // Calculate market value
            marketValueInput.value = marketValue.toFixed(2).toLocaleString(); // Display result with 2 decimal points and commas

            // Calculate value adjustment based on percentage
            calculateValueAdjustment(marketValue);
          } else {
            marketValueInput.value = ''; // Clear market value if inputs are invalid
            valueAdjustmentInput.value = ''; // Clear value adjustment
          }
        }

        // Calculate value adjustment based on market value and percentage adjustment
        function calculateValueAdjustment(marketValue) {
          const percentAdjustment = parseFloat(percentAdjustmentInput.value) || 0;
          let valueAdjustment = (marketValue * (percentAdjustment / 100 - 1)); // Adjusted calculation

          // Format the value adjustment with "-" if it's negative
          const formattedValue = (valueAdjustment < 0 ? "-" : "") + Math.abs(valueAdjustment).toFixed(2).toLocaleString();

          valueAdjustmentInput.value = formattedValue; // Display result
          calculateAdjustedMarketValue(marketValue, valueAdjustment);
        }

        // Function to calculate the adjusted market value
        function calculateAdjustedMarketValue(marketValue, valueAdjustment) {
          const adjustedMarketValue = marketValue + valueAdjustment;
          adjustedMarketValueInput.value = adjustedMarketValue.toFixed(2).toLocaleString();
        }

        // Function to calculate assessed value
        function calculateAssessedValue() {
          const adjustedMarketValue = parseFloat(adjustedMarketValueInput.value.replace(/,/g, '')) || 0;
          const assessmentLevelInput = document.getElementById("assessmentLevelModal");
          const assessedValueInput = document.getElementById("assessedValueModal");

          const assessmentLevel = parseFloat(assessmentLevelInput.value) || 0;

          if (!isNaN(adjustedMarketValue) && !isNaN(assessmentLevel) && assessmentLevel > 0) {
            const assessedValue = adjustedMarketValue * (assessmentLevel / 100);
            assessedValueInput.value = assessedValue.toFixed(2).toLocaleString();
          } else {
            assessedValueInput.value = '';
          }
        }

        calculateAssessedValue(); // Recalculate assessed value whenever adjusted market value changes

        document.getElementById("assessmentLevelModal").addEventListener("input", calculateAssessedValue); //Event listener for assessment level input

        // Event listener for percentage adjustment input
        percentAdjustmentInput.addEventListener('input', function() {
          const marketValue = parseFloat(marketValueInput.value.replace(/,/g, '')) || 0; // Get current market value
          calculateValueAdjustment(marketValue); // Recalculate value adjustment
        });

        // Adding event listeners for area conversion
        sqmRadio.addEventListener("change", convertArea);
        hectareRadio.addEventListener("change", convertArea);

        // Adding event listeners for input changes (debounced to reduce calls)
        areaInput.addEventListener("input", debounce(calculateMarketValue, 300));
        unitValueInput.addEventListener("input", debounce(calculateMarketValue, 300));

        function validateInputs() {
          const area = parseFloat(areaInput.value);
          const unitValue = parseFloat(unitValueInput.value);

          // Highlight the area input if it's invalid (empty or non-positive)
          if (isNaN(area) || area <= 0) {
            areaInput.classList.add('is-invalid'); // Add 'is-invalid' class to highlight the field
            areaInput.style.borderColor = 'red'; // Optional: Set the border color to red
          } else {
            areaInput.classList.remove('is-invalid');
            areaInput.style.borderColor = ''; // Reset the border color if the input is valid
          }

          // Highlight the unit value input if it's invalid (empty or non-positive)
          if (isNaN(unitValue) || unitValue <= 0) {
            unitValueInput.classList.add('is-invalid');
            unitValueInput.style.borderColor = 'red'; // Set the border color to red
          } else {
            unitValueInput.classList.remove('is-invalid');
            unitValueInput.style.borderColor = ''; // Reset the border color if the input is valid
          }
        }

        // Event listeners to validate inputs
        areaInput.addEventListener("input", validateInputs);
        unitValueInput.addEventListener("input", validateInputs);
      }

      // Initialize the area unit update function
      updateAreaUnit();
    });

    window.addEventListener("beforeunload", function(e) {
      e.preventDefault(); // Required for some browsers
      e.returnValue = ""; // Show the default browser confirmation dialog
    });
  </script>

  <!-- Load External Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb5g5/5hb5g5/5hb5g5/5hb5g5/5hb5g5" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="LAND.js"></script>
</body>

</html>