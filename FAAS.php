<?php
session_start();

// Uncomment the following code to enforce login check and cache control

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php"); // Redirect to login page if user is not logged in
  exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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

// Separate SQL query to fetch data from owners_tb
$sql_owners = "SELECT 
                  own_id, 
                  CONCAT(own_fname, ' ', own_mname, ' ', own_surname) AS owner_name,
                  CONCAT(house_no, ', ', barangay, ', ', city, ', ', province) AS address
               FROM owners_tb"; // Corrected the CONCAT syntax

// Execute the owner query
$ownersResult = $conn->query($sql_owners);

// Fetch owner data
$owners = [];
if ($ownersResult && $ownersResult->num_rows > 0) {
  while ($row = $ownersResult->fetch_assoc()) {
    $owners[] = $row; // Append each owner to the owners array
  }
}

// Step 1: Prepare the SQL statement to get the faas_id and propertyowner_id from the `faas` table
$sql_editowner = "
SELECT 
    f.faas_id,            -- Fetch the faas_id
    f.propertyowner_id    -- Fetch the propertyowner_id
FROM faas f
WHERE f.pro_id = ?";    // Use the property_id from the URL

// Check if property ID (`pro_id`) is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $p_id = intval($_GET['id']); // Sanitize input
  // Echo property ID with a top margin for debugging
  echo "<div style='margin-top: 10px;'>&nbsp;&nbsp;&nbsp;&nbsp;Property ID: " . $p_id . "<br></div>";


  // Step 2: Prepare and execute the query to get both faas_id and propertyowner_id (junction table IDs)
  if ($stmt = $conn->prepare($sql_editowner)) {
    $stmt->bind_param("i", $p_id); // Bind property ID to the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Step 3: Check if the query returned a row
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Step 4: Get the faas_id and propertyowner_id
      $faas_id = $row['faas_id'];
      $propertyowner_id = $row['propertyowner_id'];

      // Echo both faas_id and propertyowner_id for debugging with indentation
      echo "&nbsp;&nbsp;&nbsp;&nbsp;Faas ID: " . $faas_id . "<br>"; // Indentation using non-breaking spaces
      echo "&nbsp;&nbsp;&nbsp;&nbsp;Property Owner ID: " . $propertyowner_id . "<br>"; // Indentation using non-breaking spaces


      // Step 5: Query the junction table `propertyowner` to get the related owner IDs
      $sql_propertyowner = "
                SELECT owner_id 
                FROM propertyowner 
                WHERE property_id = ?"; // Use property_id from the URL to get related owner_ids

      if ($stmt2 = $conn->prepare($sql_propertyowner)) {
        $stmt2->bind_param("i", $p_id); // Bind property ID to the query
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        // Step 6: Collect all the owner IDs
        $owner_ids = [];
        while ($row2 = $result2->fetch_assoc()) {
          $owner_ids[] = $row2['owner_id']; // Store each owner_id from the junction table
        }

        // Echo all the collected owner IDs for debugging
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Owner IDs: " . implode(", ", $owner_ids) . "<br>";

        if (!empty($owner_ids)) {
          // Step 7: Build the query to fetch owner details from owners_tb
          $ids = implode(',', array_map('intval', $owner_ids)); // Convert IDs to a comma-separated string

          $sql_owners = "
  SELECT 
    own_id, 
    CONCAT(own_fname, ', ', own_mname, ' ', own_surname) AS owner_name,
    own_fname AS first_name, 
    own_mname AS middle_name, 
    own_surname AS last_name
  FROM owners_tb
  WHERE own_id IN ($ids)"; // Use IN clause to get all matching owners

          // Step 8: Execute the query to fetch owner details
          $owners_result = $conn->query($sql_owners);

          // Store owners' details in an array
          $owners_details = [];
          if ($owners_result->num_rows > 0) {
            while ($owner = $owners_result->fetch_assoc()) {
              $owners_details[] = $owner;
            }
          }
          // Step 9: Check if we got owner details
          if ($owners_result->num_rows > 0) {
            // Echo the number of owners found for debugging
            echo "&nbsp;&nbsp;&nbsp;&nbsp;Found owners: " . $owners_result->num_rows . "<br>";

            while ($owner = $owners_result->fetch_assoc()) {
              // Echo the details of each owner for debugging
              echo "Owner ID: " . $owner['own_id'] . "<br>";
              echo "Owner Name: " . $owner['owner_name'] . "<br>";
              echo "First Name: " . $owner['first_name'] . "<br>";
              echo "Middle Name: " . $owner['middle_name'] . "<br>";
              echo "Last Name: " . $owner['last_name'] . "<br><br>";
            }
          } else {
            echo "No owner details found for the given property.<br>";
          }
        } else {
          echo "No owners found for the given property.<br>";
        }
      } else {
        echo "Error preparing query for junction table.<br>";
      }
    } else {
      echo "No data found for the given property ID.<br>";
    }
    $stmt->close();
  } else {
    echo "Error preparing the statement.<br>";
  }
} else {
  echo "Property ID not provided.<br>";
}

// Fetching the RPU ID Section
//get the faas_id
$sql_rpu = "SELECT rpu_idno FROM faas WHERE pro_id = ?";
$stmt_rpu = $conn->prepare($sql_rpu);
$stmt_rpu->bind_param("i", $p_id);
$stmt_rpu->execute();
$result_rpu = $stmt_rpu->get_result();
$rpu_idno = null;

if ($row_rpu = $result_rpu->fetch_assoc()) {
  $rpu_idno = $row_rpu['rpu_idno'];
}

$rpu_details = null;
if (!empty($rpu_idno)) {
  $sql_rpu_details = "SELECT arp, pin, taxability, effectivity FROM rpu_idnum WHERE rpu_id = ?";
  $stmt_rpu_details = $conn->prepare($sql_rpu_details);
  $stmt_rpu_details->bind_param("i", $rpu_idno);
  $stmt_rpu_details->execute();
  $result_rpu_details = $stmt_rpu_details->get_result();

  if ($row_rpu_details = $result_rpu_details->fetch_assoc()) {
    $rpu_details = $row_rpu_details; // Store RPU details
  }
}

//Fetch land property data
$landRecords = []; // Store land records

// Get property ID from URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $property_id = intval($_GET['id']); // Sanitize input

  // Fetch the faas_id from the faas table
  $sql_faas = "SELECT faas_id FROM faas WHERE pro_id = ?";
  if ($stmt_faas = $conn->prepare($sql_faas)) {
    $stmt_faas->bind_param("i", $property_id);
    $stmt_faas->execute();
    $result_faas = $stmt_faas->get_result();

    if ($result_faas->num_rows > 0) {
      $faas_data = $result_faas->fetch_assoc();
      $faas_id = $faas_data['faas_id'];

      // Fetch land records matching the faas_id
      $sql_land = "SELECT oct_no, survey_no, area, market_value, assess_value FROM land WHERE faas_id = ?";
      if ($stmt_land = $conn->prepare($sql_land)) {
        $stmt_land->bind_param("i", $faas_id);
        $stmt_land->execute();
        $result_land = $stmt_land->get_result();

        while ($row = $result_land->fetch_assoc()) {
          $landRecords[] = $row;
        }
        $stmt_land->close();
      }
    }
    $stmt_faas->close();
  }
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
              value="<?= isset($rpu_idno) ? htmlspecialchars($rpu_idno) : ''; ?>" disabled>
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
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="editID" class="form-label">Edit ID</label>
            <input type="text" class="form-control" id="editID" placeholder="Enter Edit ID" disabled>
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="editButton"
              onclick="toggleEdit()">Edit</button>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="taxDeclarationNumber" class="form-label">Identification Numbers (Tax Declaration Number)</label>
            <input type="text" class="form-control" id="taxDeclarationNumber" placeholder="Enter Tax Declaration Number"
              disabled>
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
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="DRPprint()">Print</button>
        </div>
      </form>
    </div>
  </section>


  <!--Modal for Declaration of Property-->
  <div class="modal fade" id="editDeclarationProperty" tabindex="-1" aria-labelledby="editDeclarationPropertyLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDeclarationPropertyLabel">Edit Declaration of Property</h5>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="taxDeclarationNumberModal" class="form-label">Identification Numbers (Tax Declaration
                  Number)</label>
                <input type="text" class="form-control" id="taxDeclarationNumberModal"
                  placeholder="Enter Tax Declaration Number">
              </div>

              <div class="col-12 mb-3">
                <h6 class="mt-4 mb-3">Approval</h6>
              </div>

              <div class="col-md-6 mb-3">
                <label for="provincialAssessorModal" class="form-label">Provincial Assessor</label>
                <input type="text" class="form-control" id="provincialAssessorModal"
                  placeholder="Enter Provincial Assessor">
              </div>
              <div class="col-md-6 mb-3">
                <label for="provincialDateModal" class="form-label">Date</label>
                <input type="date" class="form-control" id="provincialDateModal">
              </div>

              <div class="col-md-6 mb-3">
                <label for="municipalAssessorModal" class="form-label">City/Municipal Assessor</label>
                <input type="text" class="form-control" id="municipalAssessorModal"
                  placeholder="Enter City/Municipal Assessor">
              </div>
              <div class="col-md-6 mb-3">
                <label for="municipalDateModal" class="form-label">Date</label>
                <input type="date" class="form-control" id="municipalDateModal">
              </div>

              <div class="col-md-6 mb-3">
                <label for="cancelsTDModal" class="form-label">Cancels TD Number</label>
                <input type="text" class="form-control" id="cancelsTDModal" placeholder="Enter Cancels TD Number">
              </div>
              <div class="col-md-6 mb-3">
                <label for="previousPinModal" class="form-label">Previous Pin</label>
                <input type="text" class="form-control" id="previousPinModal" placeholder="Enter Previous Pin">
              </div>

              <div class="col-md-6 mb-3">
                <label for="taxYearModal" class="form-label">Tax Begin With Year</label>
                <input type="number" class="form-control" id="taxYearModal" placeholder="Enter Year">
              </div>

              <div class="col-md-6 mb-3">
                <label for="enteredInRPAREForByModal" class="form-label">Entered in RPARE For By</label>
                <input type="text" class="form-control" id="enteredInRPAREForByModal" placeholder="Enter Value">
              </div>
              <div class="col-md-6 mb-3">
                <label for="enteredInRPAREForYearModal" class="form-label">Entered in RPARE For Year</label>
                <input type="number" class="form-control" id="enteredInRPAREForYearModal" placeholder="Enter Year">
              </div>

              <div class="col-md-6 mb-3">
                <label for="previousOwnerModal" class="form-label">Previous Owner</label>
                <input type="text" class="form-control" id="previousOwnerModal" placeholder="Enter Previous Owner">
              </div>
              <div class="col-md-6 mb-3">
                <label for="previousAssessedValueModal" class="form-label">Previous Assessed Value</label>
                <input type="text" class="form-control" id="previousAssessedValueModal"
                  placeholder="Enter Assessed Value">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
          <button type="button" class="btn btn-primary">Save Changes</button>
        </div>
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
        <table class="table table-borderless">
          <thead class="border-bottom border-2">
            <tr class="border-bottom border-2">
              <th class="bold">OCT/TCT Number</th>
              <th class="bold">Survey Number</th>
              <th class="bold">Area (sq m)</th>
              <th class="bold">Market Value</th>
              <th class="bold">Assessed Value</th> <!-- New column header -->
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($landRecords)): ?>
              <?php foreach ($landRecords as $record): ?>
                <tr class="border-bottom border-3">
                  <td><?= htmlspecialchars($record['oct_no']) ?></td>
                  <td><?= htmlspecialchars($record['survey_no']) ?></td>
                  <td><?= htmlspecialchars($record['area']) ?></td>
                  <td><?= number_format($record['market_value'], 2) ?></td>
                  <td>
                    <?= isset($record['assess_value']) ? number_format($record['assess_value'], 2) : '0.00' ?>
                    <!-- Check if 'assess_value' exists -->
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">No records found</td> <!-- Adjust colspan to 5 -->
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
  <div class="modal fade" id="editValuationModal" tabindex="-1" aria-labelledby="editValuationModalLabel"
    aria-hidden="true">
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
              <input type="text" class="form-control" id="landMarketValueModal"
                placeholder="Enter market value for Land">
            </div>
            <div class="mb-3">
              <label for="landAssessedValueModal" class="form-label">Land Assessed Value</label>
              <input type="text" class="form-control" id="landAssessedValueModal"
                placeholder="Enter assessed value for Land">
            </div>
            <div class="mb-3">
              <label for="plantsMarketValueModal" class="form-label">Plants/Trees Market Value</label>
              <input type="text" class="form-control" id="plantsMarketValueModal"
                placeholder="Enter market value for Plants/Trees">
            </div>
            <div class="mb-3">
              <label for="plantsAssessedValueModal" class="form-label">Plants/Trees Assessed Value</label>
              <input type="text" class="form-control" id="plantsAssessedValueModal"
                placeholder="Enter assessed value for Plants/Trees">
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