<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

// Cache control headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'database.php';
$conn = Database::getInstance();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Utility function to safely fetch a property by ID
function fetchProperty($conn, $p_id)
{
  $sql = "
    SELECT p.p_id, p.house_no, p.block_no, p.barangay, p.province, p.city, p.district, p.land_area,
           CONCAT(o.own_fname, ', ', o.own_mname, ' ', o.own_surname) AS owner_name,
           o.own_fname AS first_name, o.own_mname AS middle_name, o.own_surname AS last_name
    FROM p_info p
    LEFT JOIN owners_tb o ON p.ownId_Fk = o.own_id
    WHERE p.p_id = ?
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $p_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

// Fetch owners list
function fetchOwners($conn)
{
  $sql = "
    SELECT own_id,
           CONCAT(own_fname, ' ', own_mname, ' ', own_surname) AS owner_name,
           CONCAT(house_no, ', ', barangay, ', ', city, ', ', province) AS address
    FROM owners_tb
  ";
  $result = $conn->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch junction table (propertyowner) owner IDs
function fetchPropertyOwnerIDs($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT owner_id FROM propertyowner WHERE property_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return array_column($result->fetch_all(MYSQLI_ASSOC), 'owner_id');
}

// Fetch owner details by a list of IDs
function fetchOwnersByIds($conn, $owner_ids)
{
  $ids = implode(',', array_map('intval', $owner_ids));
  $sql = "
    SELECT own_id, 
           CONCAT(own_fname, ', ', own_mname, ' ', own_surname) AS owner_name,
           own_fname AS first_name, own_mname AS middle_name, own_surname AS last_name
    FROM owners_tb
    WHERE own_id IN ($ids)
  ";
  $result = $conn->query($sql);
  return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Fetch faas info (faas_id, propertyowner_id)
function fetchFaasInfo($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT faas_id, propertyowner_id FROM faas WHERE pro_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

// Fetch RPU ID and details
function fetchRPUDetails($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT rpu_idno FROM faas WHERE pro_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $rpu_id = $stmt->get_result()->fetch_assoc()['rpu_idno'] ?? null;

  if (!$rpu_id)
    return null;

  $stmt = $conn->prepare("SELECT arp, pin, taxability, effectivity FROM rpu_idnum WHERE rpu_id = ?");
  $stmt->bind_param("i", $rpu_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

// Fetch land records tied to faas_id
function fetchLandRecords($conn, $faas_id)
{
  $stmt = $conn->prepare("SELECT * FROM land WHERE faas_id = ?");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Calculate total land values (market and assess)
function calculateTotalLandValues($conn, $faas_id)
{
  $stmt = $conn->prepare("
    SELECT 
      SUM(market_value) AS total_market_value, 
      SUM(assess_value) AS total_assess_value 
    FROM land 
    WHERE faas_id = ?
  ");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc(); // returns associative array with total values
}

// MAIN: Begin processing
$property = null;
$owners = [];
$owners_details = [];
$rpu_details = null;
$landRecords = [];
$faas_id = null;
$totalMarketValue = 0;
$totalAssessedValue = 0;

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $property_id = intval($_GET['id']);
  echo "<div style='margin-top:10px;'>&nbsp;&nbsp;&nbsp;&nbsp;Property ID: $property_id<br></div>";

  // Fetch main property
  $property = fetchProperty($conn, $property_id);

  // Fetch faas info
  $faas_info = fetchFaasInfo($conn, $property_id);
  if ($faas_info) {
    $faas_id = $faas_info['faas_id'];

    // ✅ Calculate land values right after faas_id is known
    $totals = calculateTotalLandValues($conn, $faas_id);
    $totalMarketValue = $totals['total_market_value'] ?? 0;
    $totalAssessedValue = $totals['total_assess_value'] ?? 0;

    echo "&nbsp;&nbsp;&nbsp;&nbsp;Faas ID: {$faas_info['faas_id']}<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;Property Owner ID: {$faas_info['propertyowner_id']}<br>";

    // Fetch owner IDs
    $owner_ids = fetchPropertyOwnerIDs($conn, $property_id);
    echo "&nbsp;&nbsp;&nbsp;&nbsp;Owner IDs: " . implode(", ", $owner_ids) . "<br>";

    // Fetch owner details
    if (!empty($owner_ids)) {
      $owners_details = fetchOwnersByIds($conn, $owner_ids);
      echo "&nbsp;&nbsp;&nbsp;&nbsp;Found owners: " . count($owners_details) . "<br>";
      foreach ($owners_details as $owner) {
        echo "Owner ID: {$owner['own_id']}<br>";
        echo "Owner Name: {$owner['owner_name']}<br>";
        echo "First Name: {$owner['first_name']}<br>";
        echo "Middle Name: {$owner['middle_name']}<br>";
        echo "Last Name: {$owner['last_name']}<br><br>";
      }
    } else {
      echo "No owners found for the given property.<br>";
    }

    // Fetch land records
    $landRecords = fetchLandRecords($conn, $faas_id);
    $land_id = isset($landRecords[0]['land_id']) ? $landRecords[0]['land_id'] : null;
  } else {
    echo "No data found for the given property ID.<br>";
  }

  // Fetch RPU details
  $rpu_details = fetchRPUDetails($conn, $property_id);
} else {
  echo "Property ID not provided.<br>";
}
echo "<pre>";
print_r($landRecords);
echo "</pre>";

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $arp_no = $_POST['arp_no'] ?? 0;
  $pro_assess = $_POST['pro_assess'] ?? '';
  $pro_date = $_POST['pro_date'] ?? '';
  $mun_assess = $_POST['mun_assess'] ?? '';
  $mun_date = $_POST['mun_date'] ?? '';
  $td_cancel = $_POST['td_cancel'] ?? 0;
  $previous_pin = $_POST['previous_pin'] ?? 0;
  $tax_year = $_POST['tax_year'] ?? '';
  $entered_by = $_POST['entered_by'] ?? 0;
  $entered_year = $_POST['entered_year'] ?? '';
  $prev_own = $_POST['prev_own'] ?? '';
  $prev_assess = $_POST['prev_assess'] ?? 0.00;

  // Check if the faas_id already exists in the rpu_dec table
  $check_stmt = $conn->prepare("SELECT * FROM rpu_dec WHERE faas_id = ?");
  if ($check_stmt) {
    $check_stmt->bind_param("i", $faas_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
      // FAAS already exists, so update the record
      $update_stmt = $conn->prepare("UPDATE rpu_dec SET
        arp_no = ?, pro_assess = ?, pro_date = ?, mun_assess = ?, mun_date = ?,
        td_cancel = ?, previous_pin = ?, tax_year = ?, entered_by = ?, entered_year = ?,
        prev_own = ?, prev_assess = ?
        WHERE faas_id = ?");

      if ($update_stmt) {
        $update_stmt->bind_param(
          "issssiiisisdi",
          $arp_no,
          $pro_assess,
          $pro_date,
          $mun_assess,
          $mun_date,
          $td_cancel,
          $previous_pin,
          $tax_year,
          $entered_by,
          $entered_year,
          $prev_own,
          $prev_assess,
          $faas_id
        );

        if (!$update_stmt->execute()) {
          echo "Update failed: " . $update_stmt->error . "<br>";
        } else {
          // ✅ Redirect with an alert after successful update
          echo "<script>alert('Tax Declaration for FAAS ID $faas_id has been successfully updated.');</script>";
          header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_GET['id']));
          exit;
        }

        $update_stmt->close();
      } else {
        echo "Update prepare failed: " . $conn->error;
      }
    } else {
      // FAAS does not exist, so insert a new record
      $insert_stmt = $conn->prepare("INSERT INTO rpu_dec (
        arp_no, pro_assess, pro_date, mun_assess, mun_date,
        td_cancel, previous_pin, tax_year, entered_by, entered_year,
        prev_own, prev_assess, faas_id
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

      if ($insert_stmt) {
        $insert_stmt->bind_param(
          "issssiiisisdi",
          $arp_no,
          $pro_assess,
          $pro_date,
          $mun_assess,
          $mun_date,
          $td_cancel,
          $previous_pin,
          $tax_year,
          $entered_by,
          $entered_year,
          $prev_own,
          $prev_assess,
          $faas_id
        );

        if (!$insert_stmt->execute()) {
          echo "Insert failed: " . $insert_stmt->error . "<br>";
        } else {
          // ✅ Redirect with an alert after successful insert
          echo "<script>alert('New Tax Declaration for FAAS ID $faas_id has been successfully added.');</script>";
          header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_GET['id']));
          exit;
        }

        $insert_stmt->close();
      } else {
        echo "Insert prepare failed: " . $conn->error;
      }
    }

    $check_stmt->close();
  } else {
    echo "Prepare failed: " . $conn->error;
  }
}

// General owners list
$owners = fetchOwners($conn);

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="FAAS.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>
  <!--Main Body-->
  <!-- Owner's Information Section -->
  <section class="container mt-5" id="owner-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center">
        <a href="Real-Property-Unit-List.php">
          <img src="images/backward.png" width="35" height="35" alt="Back">
        </a>
        <h4 class="ms-3 mb-0">Owner's Information</h4>
      </div>
      <button type="button" class="btn btn-outline-primary btn-sm" id="editOwnerBtn"
        onclick="showOISModal()">Edit</button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <div id="owner-info" class="row">
        <!-- Loop through each owner and display their info -->
        <?php foreach ($owners_details as $owner): ?>
          <div class="col-md-12 mb-4">
            <form>
              <hr class="my-4">
              <div class="mb-3 w-50">
                <label for="ownerName" class="form-label">Company or Owner</label>
                <input type="text" class="form-control" id="ownerName"
                  value="<?php echo htmlspecialchars($owner['owner_name']); ?>" placeholder="Enter Company or Owner"
                  disabled>
              </div>
            </form>
          </div>
          <div class="col-md-12">
            <h6 class="mb-3">Name</h6>
            <form class="row">
              <div class="col-md-4 mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName"
                  value="<?php echo htmlspecialchars($owner['first_name']); ?>" placeholder="Enter First Name" disabled>
              </div>
              <div class="col-md-4 mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middleName"
                  value="<?php echo htmlspecialchars($owner['middle_name']); ?>" placeholder="Enter Middle Name" disabled>
              </div>
              <div class="col-md-4 mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName"
                  value="<?php echo htmlspecialchars($owner['last_name']); ?>" placeholder="Enter Last Name" disabled>
              </div>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Modal for Editing Owner's Information -->
  <div class="modal fade" id="editOwnerModal" tabindex="-1" aria-labelledby="editOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editOwnerModalLabel">Edit Owner's Information</h5>
        </div>
        <div class="modal-body">
          <!-- Owner Info (Editable) -->
          <form id="editOwnerForm">
            <!-- Loop through each owner and display their info for editing -->
            <?php foreach ($owners_details as $owner): ?>
              <div class="mb-3">
                <label for="ownerNameModal" class="form-label">Company or Owner</label>
                <input type="text" class="form-control" id="ownerNameModal"
                  value="<?php echo htmlspecialchars($owner['owner_name']); ?>" placeholder="Enter Company or Owner">
              </div>
              <h6 class="mb-3">Name</h6>
              <div class="mb-3">
                <label for="firstNameModal" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstNameModal"
                  value="<?php echo htmlspecialchars($owner['first_name']); ?>" placeholder="Enter First Name">
              </div>
              <div class="mb-3">
                <label for="middleNameModal" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middleNameModal"
                  value="<?php echo htmlspecialchars($owner['middle_name']); ?>" placeholder="Enter Middle Name">
              </div>
              <div class="mb-3">
                <label for="lastNameModal" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastNameModal"
                  value="<?php echo htmlspecialchars($owner['last_name']); ?>" placeholder="Enter Last Name">
              </div>
              <hr class="my-4">
            <?php endforeach; ?>
          </form>

          <hr class="my-4">

          <!-- Owner List Table (Selectable) -->
          <h6 class="mb-3">Owner List</h6>
          <table class="table table-bordered table-striped table-sm">
            <thead class="table-dark">
              <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Selection</th>
                <th class="text-center">Owner Name</th>
                <th class="text-center">Address</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($owners)): ?>
                <?php foreach ($owners as $owner): ?>
                  <tr>
                    <td class="text-center"><?php echo htmlspecialchars($owner['own_id']); ?></td>
                    <td class="text-center">
                      <input type="checkbox" name="owner_selection[]"
                        value="<?php echo htmlspecialchars($owner['own_id']); ?>">
                    </td>
                    <td class="text-center"><?php echo htmlspecialchars($owner['owner_name']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($owner['address']); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center">No owner data found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveOwnerData()">Save changes</button>
          <button type="button" class="btn btn-success" onclick="addOwnerData()">Add</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Property Information Section -->
  <section class="container my-5" id="property-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="section-title">Property Information</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" onclick="showEditPropertyModal()">Edit</button>
    </div>
    <div class="card border-0 shadow p-4 rounded-3">
      <form id="property-info">
        <div class="row">
          <input type="hidden" id="propertyIdModal"
            value="<?php echo isset($property['p_id']) ? htmlspecialchars($property['p_id']) : ''; ?>">
          <!-- Location Fields -->
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="street" class="form-label">Street</label>
              <input type="text" class="form-control" id="street"
                value="<?php echo isset($property['street']) ? htmlspecialchars($property['street']) : ''; ?>"
                placeholder="Enter Street" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="barangay" class="form-label">Barangay</label>
              <input type="text" class="form-control" id="barangay"
                value="<?php echo isset($property['barangay']) ? htmlspecialchars($property['barangay']) : ''; ?>"
                placeholder="Enter Barangay" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="municipality" class="form-label">Municipality</label>
              <input type="text" class="form-control" id="municipality"
                value="<?php echo isset($property['city']) ? htmlspecialchars($property['city']) : ''; ?>"
                placeholder="Enter Municipality" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="province" class="form-label">Province</label>
              <input type="text" class="form-control" id="province"
                value="<?php echo isset($property['province']) ? htmlspecialchars($property['province']) : ''; ?>"
                placeholder="Enter Province" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="houseNumber" class="form-label">House Number</label>
              <input type="text" class="form-control" id="houseNumber"
                value="<?php echo isset($property['house_no']) ? htmlspecialchars($property['house_no']) : ''; ?>"
                placeholder="Enter House Number" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="landArea" class="form-label">Land Area</label>
              <input type="text" class="form-control" id="landArea"
                value="<?php echo isset($property['land_area']) ? htmlspecialchars($property['land_area']) : ''; ?>"
                placeholder="Enter Land Area" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="zoneNumber" class="form-label">Zone Number</label>
              <input type="text" class="form-control" id="zoneNumber"
                value="<?php echo isset($property['zone_no']) ? htmlspecialchars($property['zone_no']) : ''; ?>"
                placeholder="Enter Zone Number" disabled>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!--Modal for Property Information-->
  <div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPropertyModalLabel">Edit Property Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editPropertyForm">
            <div class="row">
              <input type="hidden" id="propertyIdModal">
              <div class="col-12 mb-3">
                <label for="streetModal" class="form-label">Street</label>
                <input type="text" class="form-control" id="streetModal" placeholder="Enter Street">
              </div>
              <div class="col-12 mb-3">
                <label for="barangayModal" class="form-label">Barangay</label>
                <input type="text" class="form-control" id="barangayModal" placeholder="Enter Barangay">
              </div>
              <div class="col-12 mb-3">
                <label for="municipalityModal" class="form-label">Municipality</label>
                <input type="text" class="form-control" id="municipalityModal" placeholder="Enter Municipality">
              </div>
              <div class="col-12 mb-3">
                <label for="provinceModal" class="form-label">Province</label>
                <input type="text" class="form-control" id="provinceModal" placeholder="Enter Province">
              </div>
              <div class="col-12 mb-3">
                <label for="houseNumberModal" class="form-label">House Number</label>
                <input type="text" class="form-control" id="houseNumberModal" placeholder="Enter House Number">
              </div>
              <div class="col-12 mb-3">
                <label for="landAreaModal" class="form-label">Land Area</label>
                <input type="text" class="form-control" id="landAreaModal" placeholder="Enter Land Area">
              </div>
              <div class="col-12 mb-3">
                <label for="zoneNumberModal" class="form-label">Zone Number</label>
                <input type="text" class="form-control" id="zoneNumberModal" placeholder="Enter Zone Number">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
          <button type="button" class="btn btn-primary" onclick="savePropertyData()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!--RPU Identification Numbers-->
  <section class="container mt-5" id="rpu-identification-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Title and Edit Button -->
      <h4 class="mb-0">RPU Identification Numbers</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" id="editRPUButton"
        onclick="toggleEdit()">Edit</button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <form>
        <div class="row">
          <!-- ARP Number Input (Number only) -->
          <div class="col-md-6 mb-3">
            <label for="arpNumber" class="form-label">ARP Number</label>
            <input type="number" class="form-control" id="arpNumber" placeholder="Enter ARP Number"
              value="<?= isset($rpu_details['arp']) ? htmlspecialchars($rpu_details['arp']) : ''; ?>" disabled>
          </div>

          <!-- Property Number Input (Number only) -->
          <div class="col-md-6 mb-3">
            <label for="propertyNumber" class="form-label">Property Number</label>
            <input type="number" class="form-control" id="propertyNumber" placeholder="Enter Property Number"
              value="<?= isset($rpu_details['pin']) ? htmlspecialchars($rpu_details['pin']) : ''; ?>" disabled>
          </div>

          <!-- Taxability Dropdown -->
          <div class="col-md-6 mb-3">
            <label for="taxability" class="form-label">Taxability</label>
            <select class="form-control" id="taxability" disabled>
              <option value="" disabled <?= empty($rpu_details['taxability']) ? 'selected' : ''; ?>>Select Taxability
              </option>
              <option value="taxable" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'taxable') ? 'selected' : ''; ?>>Taxable</option>
              <option value="exempt" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'exempt') ? 'selected' : ''; ?>>Exempt</option>
              <option value="special" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'special') ? 'selected' : ''; ?>>Special</option>
            </select>
          </div>

          <!-- Effectivity Year Input -->
          <div class="col-md-6 mb-3">
            <label for="effectivity" class="form-label">Effectivity (Year)</label>
            <input type="number" class="form-control" id="effectivity" min="1900" max="2100" step="1"
              placeholder="Enter Effectivity Year"
              value="<?= isset($rpu_details['effectivity']) ? htmlspecialchars($rpu_details['effectivity']) : ''; ?>"
              disabled>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!--Declaration of Property-->
  <section class="container mt-5" id="property-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Declaration of Property</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
        data-bs-target="#editDeclarationProperty">Edit</button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <form>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="taxDeclarationNumber" class="form-label">Identification Numbers (Tax Declaration Number)</label>
            <input type="text" class="form-control" id="taxDeclarationNumber" placeholder="Enter Tax Declaration Number"
              value="<?= isset($rpu_details['arp']) ? htmlspecialchars($rpu_details['arp']) : ''; ?>" disabled>
          </div>

          <div class="col-12 mb-3">
            <h6 class="mt-4 mb-3">Approval</h6>
          </div>

          <div class="col-md-6 mb-3">
            <label for="provincialAssessor" class="form-label">Provincial Assessor</label>
            <input type="text" class="form-control" id="provincialAssessor" placeholder="Enter Provincial Assessor"
              disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label for="provincialDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="provincialDate" placeholder="Select Date" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="municipalAssessor" class="form-label">City/Municipal Assessor</label>
            <input type="text" class="form-control" id="municipalAssessor" placeholder="Enter City/Municipal Assessor"
              disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="municipalDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="municipalDate" placeholder="Select Date" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="cancelsTD" class="form-label">Cancels TD Number</label>
            <input type="text" class="form-control" id="cancelsTD" placeholder="Enter Cancels TD Number" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label for="previousPin" class="form-label">Previous Pin</label>
            <input type="text" class="form-control" id="previousPin" placeholder="Enter Previous Pin" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="taxYear" class="form-label">Tax Begin With Year</label>
            <input type="number" class="form-control" id="taxYear" placeholder="Enter Year" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="enteredInRPAREForBy" class="form-label">enteredInRPAREForBy</label>
            <input type="text" class="form-control" id="enteredInRPAREForBy" placeholder="Enter Value" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label for="enteredInRPAREForYear" class="form-label">enteredInRPAREForYear</label>
            <input type="number" class="form-control" id="enteredInRPAREForYear" placeholder="Enter Year" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="previousOwner" class="form-label">Previous Owner</label>
            <input type="text" class="form-control" id="previousOwner" placeholder="Enter Previous Owner" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="previousAssessedValue" class="form-label">Previous Assessed Value</label>
            <input type="text" class="form-control" id="previousAssessedValue" placeholder="Enter Assessed Value"
              disabled>
          </div>
        </div>

        <!-- Print Button at the Bottom Right -->
        <div class="text-right mt-4">
          <a href="print-layout.php?p_id=<?= urlencode($p_id); ?>&land_id=<?= urlencode($record['land_id']); ?>"
            class="btn btn-sm btn-secondary" title="Print" target="_blank">
            <i class="bi bi-printer"></i> Print
          </a>
        </div>
      </form>
    </div>
  </section>

  <!-- Modal for Declaration of Property -->
  <div class="modal fade" id="editDeclarationProperty" tabindex="-1" aria-labelledby="editDeclarationPropertyLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDeclarationPropertyLabel">Edit Declaration of Property</h5>
        </div>
        <div class="modal-body">
          <!-- Add the form ID here -->
          <form method="POST" action="" id="declarationForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="taxDeclarationNumberModal" class="form-label">Identification Numbers (Tax Declaration
                  Number)</label>
                <input type="text" class="form-control" id="taxDeclarationNumberModal" name="arp_no"
                  value="<?= isset($rpu_details['arp']) ? htmlspecialchars($rpu_details['arp']) : ''; ?>"
                  placeholder="Enter Tax Declaration Number">
              </div>

              <div class="col-12 mb-3">
                <h6 class="mt-4 mb-3">Approval</h6>
              </div>

              <div class="col-md-6 mb-3">
                <label for="provincialAssessorModal" class="form-label">Provincial Assessor</label>
                <input type="text" class="form-control" id="provincialAssessorModal" name="pro_assess"
                  placeholder="Enter Provincial Assessor">
              </div>
              <div class="col-md-6 mb-3">
                <label for="provincialDateModal" class="form-label">Date</label>
                <input type="date" class="form-control" id="provincialDateModal" name="pro_date">
              </div>

              <div class="col-md-6 mb-3">
                <label for="municipalAssessorModal" class="form-label">City/Municipal Assessor</label>
                <input type="text" class="form-control" id="municipalAssessorModal" name="mun_assess"
                  placeholder="Enter City/Municipal Assessor">
              </div>
              <div class="col-md-6 mb-3">
                <label for="municipalDateModal" class="form-label">Date</label>
                <input type="date" class="form-control" id="municipalDateModal" name="mun_date">
              </div>

              <div class="col-md-6 mb-3">
                <label for="cancelsTDModal" class="form-label">Cancels TD Number</label>
                <input type="text" class="form-control" id="cancelsTDModal" name="td_cancel"
                  placeholder="Enter Cancels TD Number">
              </div>
              <div class="col-md-6 mb-3">
                <label for="previousPinModal" class="form-label">Previous Pin</label>
                <input type="text" class="form-control" id="previousPinModal" name="previous_pin"
                  placeholder="Enter Previous Pin">
              </div>

              <div class="col-md-6 mb-3">
                <label for="taxYearModal" class="form-label">Tax Begin With Year</label>
                <input type="number" class="form-control" id="taxYearModal" name="tax_year" placeholder="Enter Year">
              </div>

              <div class="col-md-6 mb-3">
                <label for="enteredInRPAREForByModal" class="form-label">Entered in RPARE For By</label>
                <input type="text" class="form-control" id="enteredInRPAREForByModal" name="entered_by"
                  placeholder="Enter Value">
              </div>
              <div class="col-md-6 mb-3">
                <label for="enteredInRPAREForYearModal" class="form-label">Entered in RPARE For Year</label>
                <input type="number" class="form-control" id="enteredInRPAREForYearModal" name="entered_year"
                  placeholder="Enter Year">
              </div>

              <div class="col-md-6 mb-3">
                <label for="previousOwnerModal" class="form-label">Previous Owner</label>
                <input type="text" class="form-control" id="previousOwnerModal" name="prev_own"
                  placeholder="Enter Previous Owner">
              </div>
              <div class="col-md-6 mb-3">
                <label for="previousAssessedValueModal" class="form-label">Previous Assessed Value</label>
                <input type="text" class="form-control" id="previousAssessedValueModal" name="prev_assess"
                  placeholder="Enter Assessed Value">
              </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
          <button type="submit" form="declarationForm" class="btn btn-primary">Save Changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>


  <!-- LAND Section -->
  <section class="container my-5" id="land-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">
        </a>
        LAND
      </h4>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Quick Actions Row -->
      <div class="row mb-4">
        <?php
        // Get the property ID from the current URL (e.g., FAAS.php?id=140)
        $p_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
        ?>
        <div class="col-md-6 mb-3">
          <?php if ($p_id): ?>
            <p>Debug: p_id = <?= $p_id ?></p>
          <?php else: ?>
            <p>Debug: p_id is not set!</p>
          <?php endif; ?>
          <a href="Land.php?p_id=<?= $p_id; ?>" class="btn w-100 py-2 text-white text-decoration-none"
            style="background-color: #379777; border-color: #2e8266;">
            <i class="fas fa-plus-circle me-2"></i>Add Land
          </a>
        </div>
      </div>

      <!-- Toggle Section -->
      <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
        <span class="fw-bold me-3">Show/Hide</span>
        <div class="form-check form-switch m-0">
          <input class="form-check-input" type="checkbox" id="showToggle" checked style="margin-left: 0;">
        </div>
      </div>

      <!-- Value Table -->
      <div class="table-responsive">
        <table class="table table-borderless text-center"> <!-- Added text-center here -->
          <thead class="border-bottom border-2">
            <tr class="border-bottom border-2">
              <th class="bold" style="width: 10%;">OCT/TCT Number</th>
              <th class="bold">Area (sq m)</th>
              <th class="bold">Market Value</th>
              <th class="bold">Assessed Value</th>
              <th class="bold" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($landRecords)): ?>
              <?php foreach ($landRecords as $record): ?>
                <tr class="border-bottom border-3">
                  <td><?= htmlspecialchars($record['oct_no']) ?></td>
                  <td><?= htmlspecialchars($record['area']) ?></td>
                  <td><?= number_format($record['market_value'], 2) ?></td>
                  <td>
                    <?= isset($record['assess_value']) ? number_format($record['assess_value'], 2) : '0.00' ?>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="LAND_Edit.php?p_id=<?= urlencode($p_id); ?>&land_id=<?= urlencode($record['land_id']); ?>"
                        class="btn btn-sm btn-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="print-layout.php?p_id=<?= urlencode($p_id); ?>&land_id=<?= urlencode($record['land_id']); ?>"
                        class="btn btn-sm btn-secondary ml-3" title="View" target="_blank">
                        <i class="bi bi-printer"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">No records found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>

  <!-- Memoranda Section -->
  <section class="container my-5">
    <h5 class="mb-3">Memoranda</h5>
    <div class="form-group">
      <div
        style="border: 1px solid #ddd; padding: 15px; width: 100%; max-width: 800px; margin: 0 auto; text-align: justify;">
        <p class="form-control-plaintext" style="font-weight: bold;">
          Records Updating: TRANSFERRED BY VIRTUE OF TRANSFER CERTIFICATE OF TITLE <br>NO. 079-2023001223,
          DEED OF ABSOLUTE SALE NOTARIZED BY ATTY. RONALD A. RAMOS,<br>
          DOCKET NO. 180, PAGE NO. 37; BOOK NO. 48; SERIES OF 2023,<br>
          BIR cCR202300315293, AND CERT. OF LAND TAX PAYMENT ALL SUBMITTED.<br>
          TRANSFER TAX PRESENTED.
        </p>
      </div>
    </div>
  </section>

  <!-- PLANTS AND TREES Section -->
  <section class="container my-5" id="plants-trees-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">
        PLANTS AND TREES
      </h4>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Quick Actions Row -->
      <div class="row mb-4">
        <div class="col-md-6 mb-3">
          <a href="PnTrees.php" class="btn w-100 py-2 text-white text-decoration-none"
            style="background-color: #379777; border-color: #2e8266;">
            <i class="fas fa-plus-circle me-2"></i>Add Plants/Trees
          </a>
        </div>
      </div>

      <!-- Toggle Section -->
      <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
        <span class="fw-bold me-3">Show/Hide</span>
        <div class="form-check form-switch m-0">
          <input class="form-check-input" type="checkbox" id="showPlantsToggle" checked style="margin-left: 0;">
        </div>
      </div>

      <!-- Value Table -->
      <div class="table-responsive">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th class="text-muted">Market Value</th>
              <th class="text-muted">Assessed Value</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>None</td>
              <td>None</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>


  <!-- Valuation Section -->
  <section class="container my-5" id="valuation-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">Valuation</h4>
    </div>

    <div class="card border-0 shadow p-5 rounded-3 bg-light">
      <table class="table table-borderless mt-4">
        <thead class="border-bottom">
          <tr>
            <th scope="col">Total Value</th>
            <th scope="col" class="text-center">Market Value</th>
            <th scope="col" class="text-center">Assessed Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Land</td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="landMarketValue"
                value="<?= number_format($totalMarketValue ?? 0, 2) ?>" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="landAssessedValue"
                value="<?= number_format($totalAssessedValue ?? 0, 2) ?>" disabled>
            </td>
          </tr>
          <tr>
            <td>Plants/Trees</td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="plantsMarketValue" value="0.00" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="plantsAssessedValue" value="0.00" disabled>
            </td>
          </tr>
          <tr class="border-top font-weight-bold">
            <td>Total</td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="totalMarketValue"
                value="<?= number_format($totalMarketValue ?? 0, 2) ?>" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="totalAssessedValue"
                value="<?= number_format($totalAssessedValue ?? 0, 2) ?>" disabled>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <script>
    // Function to capitalize the first letter of each word
    function capitalizeFirstLetter(element) {
      element.value = element.value.replace(/\b\w/g, function (char) {
        return char.toUpperCase();
      });
    }

    // Function to allow only numeric input in ARD Number
    function restrictToNumbers(element) {
      element.value = element.value.replace(/[^0-9]/g, ''); // Removes any non-numeric character
    }

    // Attach the function to the 'input' event of each relevant field after DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function () {
      // Apply capitalization to specific input fields in the owner info section and modal
      const fieldsToCapitalize = [
        'ownerName', 'firstName', 'middleName', 'lastName',
        'ownerNameModal', 'firstNameModal', 'middleNameModal', 'lastNameModal',
        'streetModal', 'barangayModal', 'municipalityModal', 'provinceModal'
      ];

      fieldsToCapitalize.forEach(fieldId => {
        const inputField = document.getElementById(fieldId);
        if (inputField) {
          inputField.addEventListener("input", function () {
            capitalizeFirstLetter(inputField);
          });
        }
      });

      // Event listener for ARD Number to restrict input to numbers only
      const ardNumberField = document.getElementById("ardNumberModal");
      if (ardNumberField) {
        ardNumberField.addEventListener("input", function () {
          restrictToNumbers(ardNumberField);
        });
      }
    });
  </script>
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
  <!-- Bootstrap 5 JS Bundle (Popper included) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Then your custom script -->
  <script src="http://localhost/ERPTS/FAAS.js"></script>

</body>

</html>