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

  // Collect text values from the form (matching the names we added below)
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

  // Build types string safely:
  //  - 2 integers (faas_id, oct_no)
  //  - 19 strings (survey_no, north, east, south, west, boun_desc, last_name, first_name, middle_name, contact_no, email, house_street, barangay, district, municipality, province, land_desc, classification, sub_class)
  //  - 1 double (area)
  //  - 1 string (actual_use)
  //  - 8 doubles (unit_value, market_value, adjust_factor, adjust_percent, adjust_value, adjust_mv, assess_lvl, assess_value)
  $types = 'ii' . str_repeat('s', 19) . 'd' . 's' . str_repeat('d', 8);

  // Bind in the same order as INSERT columns
  $bind_result = $stmt->bind_param(
    $types,
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

  if ($bind_result === false) {
    echo "<script>alert('Bind Param Error: " . addslashes($stmt->error) . "');</script>";
  } else {
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

// Fetch classification
$classificationQuery = "SELECT c_id, c_description, c_uv FROM classification WHERE c_status = 'Active'";
$classificationResult = mysqli_query($conn, $classificationQuery);

// Fetch sub-class
$subClassQuery = "SELECT sc_id, sc_description, sc_uv FROM subclass WHERE sc_status = 'Active'";
$subClassResult = mysqli_query($conn, $subClassQuery);

// Fetch actual use
$actualUseQuery = "SELECT lu_id, lu_description, lu_al FROM land_use WHERE lu_status = 'Active'";
$actualUseResult = mysqli_query($conn, $actualUseQuery);

// Fetch land data using faas_id
$land_data = [];
$land_query = $conn->prepare("SELECT * FROM land WHERE faas_id = ?");
$land_query->bind_param("i", $faas_id);
$land_query->execute();
$land_result = $land_query->get_result();

if ($land_result && $land_result->num_rows > 0) {
  $land_data = $land_result->fetch_assoc();
}

$land_id = $land_data['land_id'] ?? 0; //fetch land_id

$land_query->close();

// Fetch certification data using the land_id (only if land exists)
$cert_data = [];

if (!empty($land_id)) { // only try if land exists
  $cert_query = $conn->prepare("SELECT * FROM certification WHERE land_id = ?");
  $cert_query->bind_param("i", $land_id);
  $cert_query->execute();
  $cert_result = $cert_query->get_result();

  if ($cert_result && $cert_result->num_rows > 0) {
    $cert_data = $cert_result->fetch_assoc();
  }
  $cert_query->close();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css"> <!-- Custom CSS -->
  <link rel="stylesheet" href="FAAS.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <?php include 'header.php'; ?>

  <!-- LAND Section -->
  <section class="container my-5" id="land-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">
        <?php
        $p_id = isset($_GET['p_id']) ? htmlspecialchars($_GET['p_id']) : '';
        ?>
        <a href="FAAS.php?id=<?= $p_id; ?>" class="text-decoration-none me-2">
          <i class="fas fa-arrow-left"></i>
        </a>
        Land
      </h4>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Land Details Section -->
      <h5 class="section-title">Land Details</h5>

      <!-- Identification Numbers -->
      <h6 class="section-subtitle mt-4">Identification Numbers</h6>
      <div class="row">
        <div class="col-md-6 mb-4">
          <label for="octTctNumber" class="form-label">OCT/TCT Number</label>
          <input type="text" id="octTctNumber" name="oct_no" class="form-control" placeholder="Enter OCT/TCT Number">
        </div>
        <div class="col-md-6 mb-4">
          <label for="surveyNumber" class="form-label">Survey Number</label>
          <input type="text" id="surveyNumber" name="survey_no" class="form-control" placeholder="Enter Survey Number">
        </div>
      </div>

      <!-- Boundaries -->
      <h6 class="section-subtitle mt-4">Boundaries</h6>
      <div class="row">
        <div class="col-md-3 mb-4">
          <label for="north" class="form-label">North</label>
          <input type="text" id="north" name="north_boundary" class="form-control" placeholder="Enter North Boundary">
        </div>
        <div class="col-md-3 mb-4">
          <label for="south" class="form-label">South</label>
          <input type="text" id="south" name="south_boundary" class="form-control" placeholder="Enter South Boundary">
        </div>
        <div class="col-md-3 mb-4">
          <label for="east" class="form-label">East</label>
          <input type="text" id="east" name="east_boundary" class="form-control" placeholder="Enter East Boundary">
        </div>
        <div class="col-md-3 mb-4">
          <label for="west" class="form-label">West</label>
          <input type="text" id="west" name="west_boundary" class="form-control" placeholder="Enter West Boundary">
        </div>
      </div>

      <!-- Boundary Description -->
      <h6 class="section-subtitle mt-4">Boundary Description</h6>
      <textarea class="form-control mb-4" id="boundaryDescriptionModal" name="boun_desc" rows="2"
        placeholder="Enter boundary description"></textarea>

      <!-- Administrator Information Section -->
      <h5 class="section-title mt-5">Administrator Information</h5>
      <div class="row">
        <div class="col-md-4 mb-4">
          <label for="adminLastName" class="form-label">Last Name</label>
          <input type="text" id="adminLastName" name="last_name" class="form-control" placeholder="Enter last name">
        </div>
        <div class="col-md-4 mb-4">
          <label for="adminFirstName" class="form-label">First Name</label>
          <input type="text" id="adminFirstName" name="first_name" class="form-control" placeholder="Enter first name">
        </div>
        <div class="col-md-4 mb-4">
          <label for="adminMiddleName" class="form-label">Middle Name</label>
          <input type="text" id="adminMiddleName" name="middle_name" class="form-control"
            placeholder="Enter middle name">
        </div>
      </div>

      <!-- Contact Information -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <label for="adminContact" class="form-label">Contact Number</label>
          <input type="text" id="adminContact" name="contact_no" class="form-control"
            placeholder="Enter contact number">
        </div>
        <div class="col-md-6 mb-4">
          <label for="adminEmail" class="form-label">Email</label>
          <input type="email" id="adminEmail" name="email" class="form-control" placeholder="Enter email">
        </div>
      </div>

      <!-- Address Information -->
      <h6 class="section-subtitle mt-4">Address</h6>
      <div class="row">
        <div class="col-md-3 mb-4">
          <label for="adminAddressNumber" class="form-label">House Number</label>
          <input type="text" id="adminAddressNumber" name="house_street" class="form-control"
            placeholder="Enter house number">
        </div>
        <div class="col-md-3 mb-4">
          <label for="adminAddressStreet" class="form-label">Street</label>
          <input type="text" id="adminAddressStreet" name="street" class="form-control" placeholder="Enter street">
        </div>
        <div class="col-md-3 mb-4">
          <label for="adminAddressBarangay" class="form-label">Barangay</label>
          <input type="text" id="adminAddressBarangay" name="barangay" class="form-control"
            placeholder="Enter barangay">
        </div>
        <div class="col-md-3 mb-4">
          <label for="adminAddressDistrict" class="form-label">District</label>
          <input type="text" id="adminAddressDistrict" name="district" class="form-control"
            placeholder="Enter district">
        </div>
        <div class="col-md-6 mb-4">
          <label for="adminAddressMunicipality" class="form-label">Municipality/City</label>
          <input type="text" id="adminAddressMunicipality" name="municipality" class="form-control"
            placeholder="Enter municipality or city">
        </div>
        <div class="col-md-6 mb-4">
          <label for="adminAddressProvince" class="form-label">Province</label>
          <input type="text" id="adminAddressProvince" name="province" class="form-control"
            placeholder="Enter province">
        </div>
      </div>

      <!-- Land Appraisal Section -->
      <h5 class="section-title mt-5">Land Appraisal</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <label for="description" class="form-label">Description</label>
          <input type="text" id="description" name="land_desc" class="form-control" placeholder="Enter description">
        </div>
        <div class="col-md-6 mb-4">
          <label for="classification" class="form-label">Classification</label>
          <select id="classification" name="classification" class="form-select">
            <option value="">Select classification</option>
          </select>
        </div>
        <div class="col-md-6 mb-4">
          <label for="subClass" class="form-label">Sub-Class</label>
          <select id="subClass" name="sub_class" class="form-select">
            <option value="">Select sub-class</option>
          </select>
        </div>
        <div class="col-md-6 mb-4">
          <label for="actualUse" class="form-label">Actual Use</label>
          <select id="actualUse" name="actual_use" class="form-select">
            <option value="">Select actual use</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <label for="area" class="form-label">Area</label>
          <div class="input-group">
            <input type="text" id="area" name="area" class="form-control" placeholder="Enter area in sq m">
            <div class="input-group-text">
              <label><input type="radio" name="areaUnit" value="sqm" checked> Sq m</label>
              <label class="ms-2"><input type="radio" name="areaUnit" value="hectare"> Ha</label>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <label for="unitValue" class="form-label">Unit Value</label>
          <input type="text" id="unitValue" name="unit_value" class="form-control" placeholder="Enter unit value">
        </div>
        <div class="col-md-4 mb-4">
          <label for="recommendedUnitValue" class="form-label">Recommended Unit Value</label>
          <input type="text" id="recommendedUnitValue" class="form-control" disabled>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <label for="marketValue" class="form-label">Market Value</label>
          <input type="text" id="marketValue" name="market_value" class="form-control" placeholder="Enter market value">
        </div>
      </div>

      <!-- Value Adjustment Factor Section -->
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
          <label for="adjustmentFactor" class="form-label">Adjustment Factor</label>
          <input type="text" id="adjustmentFactor" name="adjustment_factor" class="form-control"
            placeholder="Enter adjustment factor">
        </div>
        <div class="col-md-4 mb-4">
          <label for="percentAdjustment" class="form-label">% Adjustment</label>
          <input type="text" id="percentAdjustment" name="percent_adjustment" class="form-control"
            placeholder="Enter % adjustment">
        </div>
        <div class="col-md-4 mb-4">
          <label for="valueAdjustment" class="form-label">Value Adjustment</label>
          <input type="text" id="valueAdjustment" name="value_adjustment" class="form-control"
            placeholder="Enter value adjustment">
        </div>
        <div class="col-md-4 mb-4">
          <label for="adjustedMarketValue" class="form-label">Adjusted Market Value</label>
          <input type="text" id="adjustedMarketValue" name="adjusted_market_value" class="form-control"
            placeholder="Enter adjusted market value">
        </div>
      </div>

      <!-- Property Assessment Section -->
      <h5 class="section-title mt-5">Property Assessment</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <label for="assessmentLevel" class="form-label">Assessment Level</label>
          <input type="text" id="assessmentLevel" name="assessment_level" class="form-control"
            placeholder="Enter assessment level">
        </div>
        <div class="col-md-6 mb-4">
          <label for="recommendedAssessmentLevel" class="form-label">% Recommended Assessment Level</label>
          <input type="text" id="recommendedAssessmentLevel" class="form-control"
            placeholder="Enter recommended assessment level">
        </div>
        <div class="col-md-6 mb-4">
          <label for="assessedValue" class="form-label">Assessed Value</label>
          <input type="text" id="assessedValue" name="assessed_value" class="form-control"
            placeholder="Enter assessed value">
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
                <select id="verifiedBy" name="verified_by" class="form-select">
                  <option value="">Select verifier</option>
                </select>
              </div>
            </div>

            <!-- Plotted By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Plotted By</label>
              <div class="col-md-4">
                <select id="plottedBy" name="plotted_by" class="form-select">
                  <option value="">Select plotter</option>
                </select>
              </div>
            </div>

            <!-- Noted By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Noted By</label>
              <div class="col-md-4">
                <select id="notedBy" name="noted_by" class="form-select">
                  <option value="">Select noter</option>
                </select>
              </div>
            </div>

            <!-- Appraised By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Appraised By</label>
              <div class="col-md-4">
                <select id="appraisedBy" name="appraised_by" class="form-select">
                  <option value="">Select appraiser</option>
                </select>
              </div>
              <label class="col-md-1 col-form-label text-end">Date</label>
              <div class="col-md-3">
                <input type="date" name="appraisal_date" class="form-control"
                  value="<?= htmlspecialchars($cert_data['appraised_date'] ?? '') ?>">
              </div>
            </div>

            <!-- Recommending Approval -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Recommending Approval</label>
              <div class="col-md-4">
                <input type="text" name="recommending_approval" class="form-control" placeholder="Enter Recommender"
                  value="<?= htmlspecialchars($cert_data['recom_approval'] ?? '') ?>">
              </div>
              <label class="col-md-1 col-form-label text-end">Date</label>
              <div class="col-md-3">
                <input type="date" name="recommendation_date" class="form-control"
                  value="<?= htmlspecialchars($cert_data['recom_date'] ?? '') ?>">
              </div>
            </div>

            <!-- Approved By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label">Approved By</label>
              <div class="col-md-4">
                <input type="text" name="approved_by" class="form-control" placeholder="Enter Approver"
                  value="<?= htmlspecialchars($cert_data['approved'] ?? '') ?>">
              </div>
              <label class="col-md-1 col-form-label text-end">Date</label>
              <div class="col-md-3">
                <input type="date" name="approval_date" class="form-control"
                  value="<?= htmlspecialchars($cert_data['approved_date'] ?? '') ?>">
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
              <input class="form-check-input" type="radio" name="idleStatus" id="idleYes" value="yes"
                <?= (isset($cert_data['idle']) && $cert_data['idle'] == 1) ? 'checked' : '' ?>>
              <label class="form-check-label" for="idleYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="idleStatus" id="idleNo" value="no"
                <?= (isset($cert_data['idle']) && $cert_data['idle'] == 0) ? 'checked' : '' ?>>
              <label class="form-check-label" for="idleNo">No</label>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label class="form-label d-block">Contested</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="contestedStatus" id="contestedYes" value="yes"
                <?= (isset($cert_data['contested']) && $cert_data['contested'] == 1) ? 'checked' : '' ?>>
              <label class="form-check-label" for="contestedYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="contestedStatus" id="contestedNo" value="no"
                <?= (isset($cert_data['contested']) && $cert_data['contested'] == 0) ? 'checked' : '' ?>>
              <label class="form-check-label" for="contestedNo">No</label>
            </div>
          </div>
        </div>
      </div>

      <!-- Print Button at Bottom Right + Save button -->
      <div class="d-flex justify-content-between mt-4">
        <div>
          <button type="submit" name="save_land_btn" class="btn btn-success">Save</button>
        </div>
        <div>
          <button type="button" class="btn btn-outline-secondary py-2 px-4" style="font-size: 1.1rem;">
            <i class="fas fa-print me-2"></i>Print
          </button>
        </div>
      </div>
      </form>
      <!-- END FORM -->

    </div>
    </div>
  </section>
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

  <!-- Load External Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb5g5/5hb5g5/5hb5g5/5hb5g5/5hb5g5" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="LAND.js"></script>
</body>

</html>