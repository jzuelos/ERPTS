<?php
session_start();

// Uncomment the following code to enforce login check and cache control
/*
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if user is not logged in
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

require_once 'database.php';
$conn = Database::getInstance();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$property = null; // Default to null in case no property is loaded

// Check if 'id' is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $p_id = $_GET['id']; // Get the property ID from the URL

  // Prepare the SQL statement with a placeholder
  $sql = "SELECT p.p_id, p.house_no, p.block_no, p.barangay, p.province, p.city, p.district, p.land_area, 
                 CONCAT(o.own_fname, ', ', o.own_mname, ' ', o.own_surname) AS owner_name,
                 o.own_fname AS first_name, o.own_mname AS middle_name, o.own_surname AS last_name
          FROM p_info p
          LEFT JOIN owners_tb o ON p.ownId_Fk = o.own_id
          WHERE p.p_id = ?";

  // Prepare and execute the statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $p_id); // Bind the parameter as an integer
  $stmt->execute();
  $result = $stmt->get_result();
  $property = $result->fetch_assoc();
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="FAAS.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top" alt="">
      Electronic Real Property Tax System
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <!-- Dropdown menu -->
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration.php">Tax Declaration</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="Track.php">Track Paper</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Transaction.php">Transaction</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Reports.php">Reports</a>
        </li>
        <li class="nav-item ml-3">
          <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>
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
    <button type="button" class="btn btn-outline-primary btn-sm" id="editOwnerBtn" onclick="showOISModal()">Edit</button>
  </div>

  <div class="card border-0 shadow p-4 rounded-3">
    <div id="owner-info" class="row">
      <div class="col-md-12 mb-4">
        <form>
          <div class="mb-3 w-50">
            <label for="ownerName" class="form-label">Company or Owner</label>
            <input type="text" class="form-control" id="ownerName"
              value="<?php echo isset($property['owner_name']) ? htmlspecialchars($property['owner_name']) : ''; ?>"
              placeholder="Enter Company or Owner" disabled>
          </div>
        </form>
      </div>
      <div class="col-md-12">
        <h6 class="mb-3">Name</h6>
        <form class="row">
          <div class="col-md-4 mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName"
              value="<?php echo isset($property['first_name']) ? htmlspecialchars($property['first_name']) : ''; ?>"
              placeholder="Enter First Name" disabled>
          </div>
          <div class="col-md-4 mb-3">
            <label for="middleName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middleName"
              value="<?php echo isset($property['middle_name']) ? htmlspecialchars($property['middle_name']) : ''; ?>"
              placeholder="Enter Middle Name" disabled>
          </div>
          <div class="col-md-4 mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName"
              value="<?php echo isset($property['last_name']) ? htmlspecialchars($property['last_name']) : ''; ?>"
              placeholder="Enter Last Name" disabled>
          </div>
        </form>
      </div>
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
        <form id="editOwnerForm">
          <div class="mb-3">
            <label for="ownerNameModal" class="form-label">Company or Owner</label>
            <input type="text" class="form-control" id="ownerNameModal"
              value="<?php echo isset($property['owner_name']) ? htmlspecialchars($property['owner_name']) : ''; ?>"
              placeholder="Enter Company or Owner">
          </div>

          <hr class="my-4">

          <h6 class="mb-3">Name</h6>
          <div class="mb-3">
            <label for="firstNameModal" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstNameModal"
              value="<?php echo isset($property['first_name']) ? htmlspecialchars($property['first_name']) : ''; ?>"
              placeholder="Enter First Name">
          </div>
          <div class="mb-3">
            <label for="middleNameModal" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middleNameModal"
              value="<?php echo isset($property['middle_name']) ? htmlspecialchars($property['middle_name']) : ''; ?>"
              placeholder="Enter Middle Name">
          </div>
          <div class="mb-3">
            <label for="lastNameModal" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastNameModal"
              value="<?php echo isset($property['last_name']) ? htmlspecialchars($property['last_name']) : ''; ?>"
              placeholder="Enter Last Name">
          </div>
        </form>
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
        <input type="hidden" id="propertyIdModal" value="<?php echo isset($property['p_id']) ? htmlspecialchars($property['p_id']) : ''; ?>"> 
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
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="ardNumber" class="form-label">ARD Number</label>
              <input type="text" class="form-control" id="ardNumber"
                value="<?php echo isset($property['ard_no']) ? htmlspecialchars($property['ard_no']) : ''; ?>"
                placeholder="Enter ARD Number" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="taxability" class="form-label">Taxability</label>
              <select class="form-control" id="taxability" disabled>
                <option value="Taxable" <?php echo (isset($property['taxability']) && $property['taxability'] == 'Taxable') ? 'selected' : ''; ?>>Taxable</option>
                <option value="Non-Taxable" <?php echo (isset($property['taxability']) && $property['taxability'] == 'Non-Taxable') ? 'selected' : ''; ?>>Non-Taxable</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="effectivity" class="form-label">Effectivity</label>
              <input type="text" class="form-control" id="effectivity"
                value="<?php echo isset($property['effectivity']) ? htmlspecialchars($property['effectivity']) : ''; ?>"
                placeholder="Enter Effectivity Date" disabled>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- Modal for Editing Property Information -->
  <div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPropertyModalLabel">Edit Property Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Property Information Form inside Modal -->
          <form id="editPropertyForm">
            <div class="row">
              <!-- Location Fields -->
              <input type="hidden" id="propertyIdModal" value="<?php echo isset($property['p_id']) ? htmlspecialchars($property['p_id']) : ''; ?>">
              <div class="col-12 mb-3">
                <label for="streetModal" class="form-label">Street</label>
                <input type="text" class="form-control" id="streetModal"
                  value="<?php echo isset($property['street']) ? htmlspecialchars($property['street']) : ''; ?>"
                  placeholder="Enter Street">
              </div>
              <div class="col-12 mb-3">
                <label for="barangayModal" class="form-label">Barangay</label>
                <input type="text" class="form-control" id="barangayModal"
                  value="<?php echo isset($property['barangay']) ? htmlspecialchars($property['barangay']) : ''; ?>"
                  placeholder="Enter Barangay">
              </div>
              <div class="col-12 mb-3">
                <label for="municipalityModal" class="form-label">Municipality</label>
                <input type="text" class="form-control" id="municipalityModal"
                  value="<?php echo isset($property['city']) ? htmlspecialchars($property['city']) : ''; ?>"
                  placeholder="Enter Municipality">
              </div>
              <div class="col-12 mb-3">
                <label for="provinceModal" class="form-label">Province</label>
                <input type="text" class="form-control" id="provinceModal"
                  value="<?php echo isset($property['province']) ? htmlspecialchars($property['province']) : ''; ?>"
                  placeholder="Enter Province">
              </div>
              <div class="col-12 mb-3">
                <label for="houseNumberModal" class="form-label">House Number</label>
                <input type="text" class="form-control" id="houseNumberModal"
                  value="<?php echo isset($property['house_no']) ? htmlspecialchars($property['house_no']) : ''; ?>"
                  placeholder="Enter House Number">
              </div>
              <div class="col-12 mb-3">
                <label for="landAreaModal" class="form-label">Land Area</label>
                <input type="text" class="form-control" id="landAreaModal"
                  value="<?php echo isset($property['land_area']) ? htmlspecialchars($property['land_area']) : ''; ?>"
                  placeholder="Enter Land Area">
              </div>
              <div class="col-12 mb-3">
                <label for="zoneNumberModal" class="form-label">Zone Number</label>
                <input type="text" class="form-control" id="zoneNumberModal"
                  value="<?php echo isset($property['zone_no']) ? htmlspecialchars($property['zone_no']) : ''; ?>"
                  placeholder="Enter Zone Number">
              </div>
              <div class="col-12 mb-3">
                <label for="ardNumberModal" class="form-label">ARD Number</label>
                <input type="text" class="form-control" id="ardNumberModal"
                  value="<?php echo isset($property['ard_no']) ? htmlspecialchars($property['ard_no']) : ''; ?>"
                  placeholder="Enter ARD Number">
              </div>
              <div class="col-12 mb-3">
                <label for="taxabilityModal" class="form-label">Taxability</label>
                <select class="form-control" id="taxabilityModal">
                  <option value="Taxable" <?php echo (isset($property['taxability']) && $property['taxability'] == 'Taxable') ? 'selected' : ''; ?>>Taxable</option>
                  <option value="Non-Taxable" <?php echo (isset($property['taxability']) && $property['taxability'] == 'Non-Taxable') ? 'selected' : ''; ?>>Non-Taxable</option>
                </select>
              </div>
              <div class="col-12 mb-3">
                <label for="effectivityModal" class="form-label">Effectivity</label>
                <input type="text" class="form-control" id="effectivityModal"
                  value="<?php echo isset($property['effectivity']) ? htmlspecialchars($property['effectivity']) : ''; ?>"
                  placeholder="Enter Effectivity Date">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savePropertyData()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- LAND Section -->
  <section class="container my-5" id="land-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">Land</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editLandModal">Edit</button>
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
      <textarea class="form-control mb-4" id="boundaryDescriptionModal" rows="2" placeholder="Enter boundary description" disabled></textarea>

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
            <input type="text" id="adminAddressMunicipality" class="form-control" placeholder="Enter municipality or city" disabled>
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

      <!-- Value Adjustment Factor Section -->
      <h5 class="section-title mt-5">Value Adjustments Factor</h5>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adjustmentFactor" class="form-label">Adjustment Factor</label>
            <input type="text" id="adjustmentFactor" class="form-control" placeholder="Enter adjustment factor" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="percentageAdjustment" class="form-label">% Adjustment</label>
            <input type="text" id="percentageAdjustment" class="form-control" placeholder="Enter % adjustment" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="valueAdjustment" class="form-label">Value Adjustment</label>
            <input type="text" id="valueAdjustment" class="form-control" placeholder="Enter value adjustment" disabled>
          </div>
        </div>
      </div>

      <!-- Property Assessment Section -->
      <h5 class="section-title mt-5">Property Assessment</h5>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="adjustedMarketValue" class="form-label">Adjusted Market Value</label>
            <input type="text" id="adjustedMarketValue" class="form-control" placeholder="Enter adjusted market value" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="assessmentLevel" class="form-label">Assessment Level (%)</label>
            <input type="text" id="assessmentLevel" class="form-control" placeholder="Enter assessment level" disabled>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="mb-3">
            <label for="assessedValue" class="form-label">Assessed Value</label>
            <input type="text" id="assessedValue" class="form-control" placeholder="Enter assessed value" disabled>
          </div>
        </div>
      </div>

      <!-- Certification Section -->
      <h5 class="section-title mt-5">Certification</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="verifiedBy" class="form-label">Verified By</label>
            <input type="text" id="verifiedBy" class="form-control" placeholder="Enter verifier's name" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="verifiedDate" class="form-label">Date</label>
            <input type="date" id="verifiedDate" class="form-control" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="plottingBy" class="form-label">Plotting By</label>
            <input type="text" id="plottingBy" class="form-control" placeholder="Enter plotter's name" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="plottingDate" class="form-label">Date</label>
            <input type="date" id="plottingDate" class="form-control" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="notedBy" class="form-label">Noted By</label>
            <input type="text" id="notedBy" class="form-control" placeholder="Enter noter's name" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="notedDate" class="form-label">Date</label>
            <input type="date" id="notedDate" class="form-control" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="appraisedBy" class="form-label">Appraised By</label>
            <input type="text" id="appraisedBy" class="form-control" placeholder="Enter appraiser's name" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="appraisedDate" class="form-label">Date</label>
            <input type="date" id="appraisedDate" class="form-control" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="recommendingApproval" class="form-label">Recommending Approval</label>
            <input type="text" id="recommendingApproval" class="form-control" placeholder="Enter recommender's name" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="recommendingDate" class="form-label">Date</label>
            <input type="date" id="recommendingDate" class="form-control" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="approvedBy" class="form-label">Approved By</label>
            <input type="text" id="approvedBy" class="form-control" placeholder="Enter approver's name" disabled>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="approvedDate" class="form-label">Date</label>
            <input type="date" id="approvedDate" class="form-control" disabled>
          </div>
        </div>
      </div>

      <!-- Misc Section -->
      <h5 class="section-title mt-5">Misc</h5>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="idle" class="form-label">Idle</label>
            <select id="idle" class="form-control" disabled>
              <option value="Yes">Yes</option>
              <option value="No" selected>No</option>
            </select>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="mb-3">
            <label for="contested" class="form-label">Contested</label>
            <select id="contested" class="form-control" disabled>
              <option value="Yes">Yes</option>
              <option value="No" selected>No</option>
            </select>
          </div>
        </div>
      </div>


      <!-- Print Button at the Bottom Right -->
      <div class="text-right mt-4">
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openPrintPage()">Print</button>
      </div>
    </div>
  </section>


  <!-- Modal for Editing Land Details -->
  <div class="modal fade" id="editLandModal" tabindex="-1" aria-labelledby="editLandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editLandModalLabel">Edit Land Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Identification Numbers -->
          <div class="row">
            <div class="col-md-6 mb-4">
              <label for="octTctNumberModal" class="form-label">OCT/TCT Number</label>
              <input type="text" id="octTctNumberModal" class="form-control" placeholder="Enter OCT/TCT Number">
            </div>
            <div class="col-md-6 mb-4">
              <label for="surveyNumberModal" class="form-label">Survey Number</label>
              <input type="text" id="surveyNumberModal" class="form-control" placeholder="Enter Survey Number">
            </div>
          </div>

          <!-- Boundaries -->
          <div class="row">
            <div class="col-md-3 mb-4">
              <label for="northModal" class="form-label">North Boundary</label>
              <input type="text" id="northModal" class="form-control" placeholder="Enter North Boundary">
            </div>
            <div class="col-md-3 mb-4">
              <label for="southModal" class="form-label">South Boundary</label>
              <input type="text" id="southModal" class="form-control" placeholder="Enter South Boundary">
            </div>
            <div class="col-md-3 mb-4">
              <label for="eastModal" class="form-label">East Boundary</label>
              <input type="text" id="eastModal" class="form-control" placeholder="Enter East Boundary">
            </div>
            <div class="col-md-3 mb-4">
              <label for="westModal" class="form-label">West Boundary</label>
              <input type="text" id="westModal" class="form-control" placeholder="Enter West Boundary">
            </div>
          </div>

          <!-- Boundary Description -->
          <div class="mb-4">
            <label for="boundaryDescriptionModal" class="form-label">Boundary Description</label>
            <textarea class="form-control" id="boundaryDescriptionModal" rows="2" placeholder="Enter boundary description"></textarea>
          </div>

          <!-- Administrator Information -->
          <h5 class="section-title mt-5">Administrator Information</h5>
          <div class="row">
            <div class="col-md-4 mb-4">
              <div class="mb-3">
                <label for="adminLastNameModal" class="form-label">Last Name</label>
                <input type="text" id="adminLastNameModal" class="form-control" placeholder="Enter last name">
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="mb-3">
                <label for="adminFirstNameModal" class="form-label">First Name</label>
                <input type="text" id="adminFirstNameModal" class="form-control" placeholder="Enter first name">
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="mb-3">
                <label for="adminMiddleNameModal" class="form-label">Middle Name</label>
                <input type="text" id="adminMiddleNameModal" class="form-control" placeholder="Enter middle name">
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="row">
            <div class="col-md-6 mb-4">
              <div class="mb-3">
                <label for="adminContactModal" class="form-label">Contact Number</label>
                <input type="text" id="adminContactModal" class="form-control" placeholder="Enter contact number">
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="mb-3">
                <label for="adminEmailModal" class="form-label">Email</label>
                <input type="email" id="adminEmailModal" class="form-control" placeholder="Enter email">
              </div>
            </div>
          </div>

          <!-- Address Information -->
          <h6 class="section-subtitle mt-4">Address</h6>
          <div class="row">
            <div class="col-md-3 mb-4">
              <div class="mb-3">
                <label for="adminAddressNumberModal" class="form-label">House Number</label>
                <input type="text" id="adminAddressNumberModal" class="form-control" placeholder="Enter house number">
              </div>
            </div>
            <div class="col-md-3 mb-4">
              <div class="mb-3">
                <label for="adminAddressStreetModal" class="form-label">Street</label>
                <input type="text" id="adminAddressStreetModal" class="form-control" placeholder="Enter street">
              </div>
            </div>
            <div class="col-md-3 mb-4">
              <div class="mb-3">
                <label for="adminAddressBarangayModal" class="form-label">Barangay</label>
                <input type="text" id="adminAddressBarangayModal" class="form-control" placeholder="Enter barangay">
              </div>
            </div>
            <div class="col-md-3 mb-4">
              <div class="mb-3">
                <label for="adminAddressDistrictModal" class="form-label">District</label>
                <input type="text" id="adminAddressDistrictModal" class="form-control" placeholder="Enter district">
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="mb-3">
                <label for="adminAddressMunicipalityModal" class="form-label">Municipality/City</label>
                <input type="text" id="adminAddressMunicipalityModal" class="form-control" placeholder="Enter municipality or city">
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="mb-3">
                <label for="adminAddressProvinceModal" class="form-label">Province</label>
                <input type="text" id="adminAddressProvinceModal" class="form-control" placeholder="Enter province">
              </div>
            </div>
          </div>

          <!-- Land Appraisal Section -->
          <h5 class="section-title mt-5">Land Appraisal</h5>
          <div class="row">
            <div class="col-md-4 mb-4">
              <label for="descriptionModal" class="form-label">Description</label>
              <input type="text" id="descriptionModal" class="form-control" placeholder="Enter description">
            </div>
            <div class="col-md-4 mb-4">
              <label for="classificationModal" class="form-label">Classification</label>
              <input type="text" id="classificationModal" class="form-control" placeholder="Enter classification">
            </div>
            <div class="col-md-4 mb-4">
              <label for="subClassModal" class="form-label">Sub-Class</label>
              <input type="text" id="subClassModal" class="form-control" placeholder="Enter sub-class">
            </div>
            <div class="col-md-4 mb-4">
              <label for="areaModal" class="form-label">Area (sq m)</label>
              <input type="text" id="areaModal" class="form-control" placeholder="Enter area">
            </div>
            <div class="col-md-4 mb-4">
              <label for="actualUseModal" class="form-label">Actual Use</label>
              <input type="text" id="actualUseModal" class="form-control" placeholder="Enter actual use">
            </div>
            <div class="col-md-4 mb-4">
              <label for="unitValueModal" class="form-label">Unit Value</label>
              <input type="text" id="unitValueModal" class="form-control" placeholder="Enter unit value">
            </div>
            <div class="col-md-4 mb-4">
              <label for="marketValueModal" class="form-label">Market Value</label>
              <input type="text" id="marketValueModal" class="form-control" placeholder="Enter market value">
            </div>
          </div>

          <!-- Value Adjustment Factor Section -->
          <h5 class="section-title mt-5">Value Adjustment Factor</h5>
          <div class="row">
            <div class="col-md-4 mb-4">
              <label for="adjustmentFactorModal" class="form-label">Adjustment Factor</label>
              <input type="text" id="adjustmentFactorModal" class="form-control" placeholder="Enter adjustment factor">
            </div>
            <div class="col-md-4 mb-4">
              <label for="percentageAdjustmentModal" class="form-label">% Adjustment</label>
              <input type="text" id="percentageAdjustmentModal" class="form-control" placeholder="Enter % adjustment">
            </div>
            <div class="col-md-4 mb-4">
              <label for="valueAdjustmentModal" class="form-label">Value Adjustment</label>
              <input type="text" id="valueAdjustmentModal" class="form-control" placeholder="Enter value adjustment">
            </div>
          </div>

          <!-- Property Assessment Section -->
          <h5 class="section-title mt-5">Property Assessment</h5>
          <div class="row">
            <div class="col-md-4 mb-4">
              <label for="adjustedMarketValueModal" class="form-label">Adjusted Market Value</label>
              <input type="text" id="adjustedMarketValueModal" class="form-control" placeholder="Enter adjusted market value">
            </div>
            <div class="col-md-4 mb-4">
              <label for="assessmentLevelModal" class="form-label">Assessment Level (%)</label>
              <input type="text" id="assessmentLevelModal" class="form-control" placeholder="Enter assessment level">
            </div>
            <div class="col-md-4 mb-4">
              <label for="assessedValueModal" class="form-label">Assessed Value</label>
              <input type="text" id="assessedValueModal" class="form-control" placeholder="Enter assessed value">
            </div>
          </div>

          <!-- Certification Section -->
          <h5 class="section-title mt-5">Certification</h5>
          <div class="row">
            <div class="col-md-6 mb-4">
              <label for="verifiedByModal" class="form-label">Verified By</label>
              <input type="text" id="verifiedByModal" class="form-control" placeholder="Enter verifier's name">
            </div>
            <div class="col-md-6 mb-4">
              <label for="verifiedDateModal" class="form-label">Date</label>
              <input type="date" id="verifiedDateModal" class="form-control">
            </div>
          </div>

          <!-- Misc Section -->
          <h5 class="section-title mt-5">Miscellaneous</h5>
          <div class="row">
            <div class="col-md-6 mb-4">
              <label for="idleModal" class="form-label">Idle</label>
              <select id="idleModal" class="form-control">
                <option value="Yes">Yes</option>
                <option value="No" selected>No</option>
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label for="contestedModal" class="form-label">Contested</label>
              <select id="contestedModal" class="form-control">
                <option value="Yes">Yes</option>
                <option value="No" selected>No</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Memoranda Section -->
  <section class="container my-5">
    <h5 class="mb-3">Memoranda</h5>
    <div class="form-group">
      <div style="border: 1px solid #ddd; padding: 15px; width: 100%; max-width: 800px; margin: 0 auto; text-align: justify;">
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

  <!-- Plants and Trees Section -->
  <section class="container my-5" id="plants-trees-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">PLANTS AND TREES</h4>
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
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="togglePlantsSection()">Add Plants/Trees</button>
        <!-- Enable Print button -->
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
      </div>

      <!-- Remove Button -->
      <div class="form-group mt-3">
        <!-- Enable Remove button -->
        <button type="button" class="btn btn-outline-danger btn-sm" id="removeButton" style="margin-left: 0.5rem;">Remove</button>
      </div>

      <!-- Hidden Plants/Trees Section (Initially Hidden) -->
      <div id="plantsSection" style="display: none;">
        <p>Details for Plants/Trees will appear here.</p>
      </div>
    </div>
  </section>

  <!-- Modal for Editing Plants and Trees -->
  <div class="modal fade" id="editPlantsTreesModal" tabindex="-1" aria-labelledby="editPlantsTreesModalLabel" aria-hidden="true">
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


  <!-- Valuation Section -->
  <section class="container my-5" id="valuation-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">Valuation</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" onclick="showEditValuationModal()">Edit</button>
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
              <input type="text" class="form-control text-center" id="landMarketValue" value="380,160.00" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="landAssessedValue" value="22,810.00" disabled>
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
              <input type="text" class="form-control text-center" id="totalMarketValue" value="380,160.00" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="totalAssessedValue" value="22,810.00" disabled>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Modal for Editing Valuation -->
  <div class="modal fade" id="editValuationModal" tabindex="-1" aria-labelledby="editValuationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editValuationModalLabel">Edit Valuation Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editValuationForm">
            <div class="mb-3">
              <label for="landMarketValueModal" class="form-label">Land Market Value</label>
              <input type="text" class="form-control" id="landMarketValueModal" placeholder="Enter market value for Land">
            </div>
            <div class="mb-3">
              <label for="landAssessedValueModal" class="form-label">Land Assessed Value</label>
              <input type="text" class="form-control" id="landAssessedValueModal" placeholder="Enter assessed value for Land">
            </div>
            <div class="mb-3">
              <label for="plantsMarketValueModal" class="form-label">Plants/Trees Market Value</label>
              <input type="text" class="form-control" id="plantsMarketValueModal" placeholder="Enter market value for Plants/Trees">
            </div>
            <div class="mb-3">
              <label for="plantsAssessedValueModal" class="form-label">Plants/Trees Assessed Value</label>
              <input type="text" class="form-control" id="plantsAssessedValueModal" placeholder="Enter assessed value for Plants/Trees">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveValuationData()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

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
    element.value = element.value.replace(/\b\w/g, function(char) {
      return char.toUpperCase();
    });
  }

  // Function to allow only numeric input in ARD Number
  function restrictToNumbers(element) {
    element.value = element.value.replace(/[^0-9]/g, ''); // Removes any non-numeric character
  }

  // Attach the function to the 'input' event of each relevant field after DOM is fully loaded
  document.addEventListener("DOMContentLoaded", function() {
    // Apply capitalization to specific input fields in the owner info section and modal
    const fieldsToCapitalize = [
      'ownerName', 'firstName', 'middleName', 'lastName',
      'ownerNameModal', 'firstNameModal', 'middleNameModal', 'lastNameModal',
      'streetModal', 'barangayModal', 'municipalityModal', 'provinceModal'
    ];

    fieldsToCapitalize.forEach(fieldId => {
      const inputField = document.getElementById(fieldId);
      if (inputField) {
        inputField.addEventListener("input", function() {
          capitalizeFirstLetter(inputField);
        });
      }
    });

    // Event listener for ARD Number to restrict input to numbers only
    const ardNumberField = document.getElementById("ardNumberModal");
    if (ardNumberField) {
      ardNumberField.addEventListener("input", function() {
        restrictToNumbers(ardNumberField);
      });
    }
  });
  </script>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/main_layout.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="http://localhost/ERPTS/FAAS.js"></script>
</body>

</html>
