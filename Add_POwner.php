<?php
session_start(); // Start session at the top

// Check if the user is logged in by verifying if 'user_id' exists in the session
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php"); // Redirect to login page if user is not logged in
  exit; // Stop further execution after redirection
}

// Prevent the browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once 'database.php';

// Connect to the database
$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/**
 * Function to log activity
 */
function logActivity($conn, $userId, $action)
{
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)");
  $stmt->bind_param("is", $userId, $action);
  $stmt->execute();
  $stmt->close();
}

/**
 * Helper function to get municipality name
 */
function getMunicipalityName($conn, $m_id)
{
  if (empty($m_id)) return 'None';
  $stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
  $stmt->bind_param("i", $m_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row ? $row['m_description'] : "ID: $m_id";
}

/**
 * Helper function to get district name
 */
function getDistrictName($conn, $district_id)
{
  if (empty($district_id)) return 'None';
  $stmt = $conn->prepare("SELECT description FROM district WHERE district_id = ?");
  $stmt->bind_param("i", $district_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row ? $row['description'] : "ID: $district_id";
}

/**
 * Helper function to get barangay name
 */
function getBarangayName($conn, $brgy_id)
{
  if (empty($brgy_id)) return 'None';
  $stmt = $conn->prepare("SELECT brgy_name FROM brgy WHERE brgy_id = ?");
  $stmt->bind_param("i", $brgy_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row ? $row['brgy_name'] : "ID: $brgy_id";
}

/**
 * Function to check for duplicate owners
 * Returns array with 'exists' boolean and 'messages' array
 */
function checkDuplicateOwner($conn, $tinNumber, $firstName, $surname, $birthday)
{
  $errors = [];

  // Check 1: TIN number already exists
  $stmt = $conn->prepare("SELECT own_id, own_fname, own_surname FROM owners_tb WHERE tin_no = ?");
  $stmt->bind_param("s", $tinNumber);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $existing = $result->fetch_assoc();
    $errors[] = "A property owner with TIN number '$tinNumber' already exists (Owner: {$existing['own_fname']} {$existing['own_surname']}, ID: {$existing['own_id']}).";
  }
  $stmt->close();

  // Check 2: Same name and birthday combination exists
  $stmt = $conn->prepare("SELECT own_id, tin_no FROM owners_tb WHERE own_fname = ? AND own_surname = ? AND date_birth = ?");
  $stmt->bind_param("sss", $firstName, $surname, $birthday);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $existing = $result->fetch_assoc();
    $errors[] = "A property owner with the same name ('$firstName $surname') and birthday ('$birthday') already exists (TIN: {$existing['tin_no']}, ID: {$existing['own_id']}).";
  }
  $stmt->close();

  return [
    'exists' => count($errors) > 0,
    'messages' => $errors
  ];
}

// Initialize form data array
$formData = [
  'firstName' => '',
  'middleName' => '',
  'surname' => '',
  'birthday' => '',
  'tinNumber' => '',
  'city' => '',
  'barangay' => '',
  'district' => '',
  'province' => '',
  'streetHouse' => '',
  'telephone' => '',
  'fax' => '',
  'email' => '',
  'website' => ''
];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and retrieve form data
  $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $middleName = filter_input(INPUT_POST, 'middleName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $tinNumber = filter_input(INPUT_POST, 'tinNumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // Optional address fields - set empty string if not provided
  $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $barangay = $barangay ?: ''; // Use empty string if null

  $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $district = $district ?: ''; // Use empty string if null

  $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $province = $province ?: ''; // Use empty string if null

  $streetHouse = filter_input(INPUT_POST, 'streetHouse', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $streetHouse = $streetHouse ?: ''; // Use empty string if null

  // Optional fields for owner information
  $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $telephone = $telephone ?: ''; // Use empty string if null

  $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $fax = $fax ?: ''; // Use empty string if null

  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $email = $email ?: ''; // Use empty string if null

  $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);
  $website = $website ?: ''; // Use empty string if null

  // Store form data in session for preservation
  $_SESSION['form_data'] = [
    'firstName' => $firstName,
    'middleName' => $middleName,
    'surname' => $surname,
    'birthday' => $birthday,
    'tinNumber' => $tinNumber,
    'city' => $city,
    'barangay' => $barangay,
    'district' => $district,
    'province' => $province,
    'streetHouse' => $streetHouse,
    'telephone' => $telephone,
    'fax' => $fax,
    'email' => $email,
    'website' => $website
  ];

  // Format optional owner information
  $ownInfo = "Telephone: $telephone, Fax: $fax, Email: $email, Website: $website";

  // Prepare and execute insert statement
  if ($firstName && $surname && $tinNumber && $city) {

    // ✅ VALIDATE FOR DUPLICATES BEFORE INSERTING
    $duplicateCheck = checkDuplicateOwner($conn, $tinNumber, $firstName, $surname, $birthday);

    if ($duplicateCheck['exists']) {
      // Store error messages in session to display after redirect
      $_SESSION['error_messages'] = $duplicateCheck['messages'];
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    }

    // No duplicates found - proceed with insertion
    // Note: Using streetHouse for both house_no and street since they're combined in one field
    $stmt = $conn->prepare("INSERT INTO owners_tb (own_fname, own_mname, own_surname, date_birth, tin_no, house_no, street, barangay, district, city, province, own_info) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
      // Bind parameters - using streetHouse for both house_no and street
      $stmt->bind_param("ssssssssssss", $firstName, $middleName, $surname, $birthday, $tinNumber, $streetHouse, $streetHouse, $barangay, $district, $city, $province, $ownInfo);

      // Execute the statement
      if ($stmt->execute()) {
        $owner_id = $stmt->insert_id; // Get the newly inserted owner ID

        // ✅ LOG ACTIVITY - Owner Added
        if (isset($_SESSION['user_id'])) {
          $userId = $_SESSION['user_id'];

          // Get readable names for location (only if IDs are provided)
          $municipalityName = !empty($city) ? getMunicipalityName($conn, $city) : 'None';
          $barangayName = !empty($barangay) ? getBarangayName($conn, $barangay) : 'None';
          $districtName = !empty($district) ? getDistrictName($conn, $district) : 'None';

          // Build full name
          $fullName = trim("$firstName $middleName $surname");

          // Build detailed log message
          $logMessage  = "Added new property owner\n";
          $logMessage .= "Owner ID: $owner_id\n";
          $logMessage .= "Name: $fullName\n\n";

          $logMessage .= "Personal Information:\n";
          $logMessage .= "• First Name: $firstName\n";
          if (!empty($middleName)) {
            $logMessage .= "• Middle Name: $middleName\n";
          }
          $logMessage .= "• Surname: $surname\n";
          if (!empty($birthday)) {
            $logMessage .= "• Birthday: $birthday\n";
          }
          $logMessage .= "• TIN Number: $tinNumber\n";

          $logMessage .= "\nAddress Details:\n";
          $logMessage .= "• Municipality: $municipalityName\n";
          if (!empty($district)) {
            $logMessage .= "• District: $districtName\n";
          }
          if (!empty($barangay)) {
            $logMessage .= "• Barangay: $barangayName\n";
          }
          if (!empty($streetHouse)) {
            $logMessage .= "• Street/House: $streetHouse\n";
          }

          // Add optional contact information if provided
          $hasContactInfo = false;
          $contactInfo = "\nContact Information:\n";

          if (!empty($telephone)) {
            $contactInfo .= "• Telephone: $telephone\n";
            $hasContactInfo = true;
          }
          if (!empty($fax)) {
            $contactInfo .= "• Fax: $fax\n";
            $hasContactInfo = true;
          }
          if (!empty($email)) {
            $contactInfo .= "• Email: $email\n";
            $hasContactInfo = true;
          }
          if (!empty($website)) {
            $contactInfo .= "• Website: $website\n";
            $hasContactInfo = true;
          }

          if ($hasContactInfo) {
            $logMessage .= $contactInfo;
          }

          // Save to activity log
          logActivity($conn, $userId, $logMessage);
        }

        // Clear form data on success
        unset($_SESSION['form_data']);
        
        $_SESSION['message'] = "Property owner added successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
      } else {
        // ✅ LOG FAILED ATTEMPT
        if (isset($_SESSION['user_id'])) {
          $userId = $_SESSION['user_id'];
          $logMessage  = "Failed to add property owner\n";
          $logMessage .= "Error: " . $stmt->error . "\n";
          $logMessage .= "Attempted name: $firstName $surname";

          logActivity($conn, $userId, $logMessage);
        }

        $_SESSION['error_messages'] = ["Database error: " . $stmt->error];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
      }
      $stmt->close();
    } else {
      $_SESSION['error_messages'] = ["Error preparing statement: " . $conn->error];
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    }
  } else {
    $_SESSION['error_messages'] = ["Please fill in all required fields."];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
}

// Restore form data from session if available
if (isset($_SESSION['form_data'])) {
  $formData = $_SESSION['form_data'];
}

// ✅ Fetch municipalities
$municipalities_stmt = $conn->prepare("SELECT m_id, m_description FROM municipality");
$municipalities_stmt->execute();
$municipalities_result = $municipalities_stmt->get_result();

// ✅ Fetch districts
$districts_stmt = $conn->prepare("SELECT district_id, description, m_id FROM district");
$districts_stmt->execute();
$districts_result = $districts_stmt->get_result();
$districts = [];
while ($row = $districts_result->fetch_assoc()) {
  $districts[] = $row;
}

// ✅ Fetch barangays
$barangays_stmt = $conn->prepare("SELECT brgy_id, brgy_name, m_id FROM brgy");
$barangays_stmt->execute();
$barangays_result = $barangays_stmt->get_result();
$barangays = [];
while ($row = $barangays_result->fetch_assoc()) {
  $barangays[] = $row;
}

// Encode for JS
$districts_json = json_encode($districts);
$barangays_json = json_encode($barangays);
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Add_POwner.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <section class="container mt-5">
    <div class="card p-4 form-container">

      <!-- Back + Title inline -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="Add-New-Real-Property-Unit.php " class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        <h3 class="mb-0 text-center flex-grow-1">Add Property Owner</h3>
      </div>

      <?php
      // Display error messages
      if (isset($_SESSION['error_messages'])) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
        echo "<strong><i class='fas fa-exclamation-triangle'></i> Error:</strong> Unable to add property owner.<br><br>";
        foreach ($_SESSION['error_messages'] as $error) {
          echo "• " . htmlspecialchars($error) . "<br>";
        }
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        echo "</div>";
        unset($_SESSION['error_messages']);
      }

      // Display success message
      if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success alert-dismissible fade show text-center' role='alert'>";
        echo "<i class='fas fa-check-circle'></i> " . $_SESSION['message'];
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        echo "</div>";
        unset($_SESSION['message']);
      }
      ?>

      <form action="" method="POST">
        <div class="row">
          <!-- Owner's Information (Row 1) -->
          <div class="col-md-6">
            <h5>Owner's Information <small class="text-muted">(Required)</small></h5>
            <div class="form-group mb-3">
              <label for="firstName"><span class="text-danger">*</span> First Name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" value="<?= htmlspecialchars($formData['firstName']) ?>" required>
            </div>
            <div class="form-group mb-3">
              <label for="middleName">Middle Name</label>
              <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter Middle Name" value="<?= htmlspecialchars($formData['middleName']) ?>">
            </div>
            <div class="form-group mb-3">
              <label for="surname"><span class="text-danger">*</span> Surname</label>
              <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname" value="<?= htmlspecialchars($formData['surname']) ?>" required>
            </div>
            <div class="form-group mb-3">
              <label for="birthday"><span class="text-danger">*</span> Birthday</label>
              <input type="date" class="form-control" id="birthday" name="birthday" value="<?= htmlspecialchars($formData['birthday']) ?>" required>
            </div>
            <div class="form-group mb-3">
              <label for="tinNumber"><span class="text-danger">*</span> TIN No.</label>
              <input type="text" class="form-control" id="tinNumber" name="tinNumber" placeholder="Enter TIN Number" value="<?= htmlspecialchars($formData['tinNumber']) ?>" required>
            </div>
          </div>

          <div class="col-md-6">
            <h5>Owner Information <small class="text-muted">(Optional)</small></h5>
            <div class="form-group mb-3">
              <label for="telephone">Telephone</label>
              <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Enter Telephone Number" value="<?= htmlspecialchars($formData['telephone']) ?>">
            </div>
            <div class="form-group mb-3">
              <label for="fax">Fax</label>
              <input type="text" class="form-control" id="fax" name="fax" placeholder="Enter Fax Number" value="<?= htmlspecialchars($formData['fax']) ?>">
            </div>
            <div class="form-group mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" value="<?= htmlspecialchars($formData['email']) ?>">
            </div>
            <div class="form-group mb-3">
              <label for="website">Website</label>
              <input type="url" class="form-control" id="website" name="website" placeholder="Enter Website URL" value="<?= htmlspecialchars($formData['website']) ?>">
            </div>
          </div>
        </div>

        <!-- Address (Row 2) -->
        <div class="row mt-4">
          <div class="col-12">
            <h5>Address</h5>
          </div>

          <!-- Municipality -->
          <div class="col-md-3 mb-3">
            <label for="city"><span class="text-danger">*</span> Municipality / City</label>
            <select class="form-select" id="city" name="city" required>
              <option value="" <?= empty($formData['city']) ? 'selected' : '' ?> disabled>Select Municipality</option>
              <?php while ($row = $municipalities_result->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($row['m_id']) ?>" <?= $formData['city'] == $row['m_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($row['m_description']) ?>
                </option>
              <?php } ?>
            </select>
          </div>

          <!-- District -->
          <div class="col-md-3 mb-3">
            <label for="district"><span class="text-danger">*</span> District</label>
            <select class="form-select" id="district" name="district" required <?= empty($formData['district']) ? 'disabled' : '' ?>>
              <option value="" <?= empty($formData['district']) ? 'selected' : '' ?> disabled>Select District</option>
              <?php if (!empty($formData['district'])): ?>
                <option value="<?= htmlspecialchars($formData['district']) ?>" selected><?= htmlspecialchars(getDistrictName($conn, $formData['district'])) ?></option>
              <?php endif; ?>
            </select>
          </div>

          <!-- Barangay -->
          <div class="col-md-3 mb-3">
            <label for="barangay"><span class="text-danger">*</span> Barangay</label>
            <select class="form-select" id="barangay" name="barangay" required <?= empty($formData['barangay']) ? 'disabled' : '' ?>>
              <option value="" <?= empty($formData['barangay']) ? 'selected' : '' ?> disabled>Select Barangay</option>
              <?php if (!empty($formData['barangay'])): ?>
                <option value="<?= htmlspecialchars($formData['barangay']) ?>" selected><?= htmlspecialchars(getBarangayName($conn, $formData['barangay'])) ?></option>
              <?php endif; ?>
            </select>
          </div>

          <!-- Street / House Number - Made optional -->
          <div class="col-md-3 mb-3">
            <label for="streetHouse">Street / House No.</label>
            <input type="text" class="form-control" id="streetHouse" name="streetHouse" placeholder="Enter Street / House No." value="<?= htmlspecialchars($formData['streetHouse']) ?>">
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-end mt-3">
          <button type="submit" class="btn btn-primary submit-btn">Submit</button>
        </div>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const districts = <?php echo $districts_json; ?>;
      const barangays = <?php echo $barangays_json; ?>;

      const citySelect = document.getElementById("city");
      const districtSelect = document.getElementById("district");
      const barangaySelect = document.getElementById("barangay");

      // Preserve selected values
      const savedCity = "<?= htmlspecialchars($formData['city']) ?>";
      const savedDistrict = "<?= htmlspecialchars($formData['district']) ?>";
      const savedBarangay = "<?= htmlspecialchars($formData['barangay']) ?>";

      // Function to populate districts
      function populateDistricts(m_id, selectDistrictId = null) {
        districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
        
        const filteredDistricts = districts.filter(d => d.m_id == m_id);
        if (filteredDistricts.length > 0) {
          const d = filteredDistricts[0];
          const opt = document.createElement("option");
          opt.value = d.district_id;
          opt.textContent = d.description;
          districtSelect.appendChild(opt);
          
          if (selectDistrictId && selectDistrictId == d.district_id) {
            districtSelect.value = d.district_id;
          } else {
            districtSelect.value = d.district_id;
          }
          districtSelect.disabled = true;
        }
      }

      // Function to populate barangays
      function populateBarangays(m_id, selectBarangayId = null) {
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
        
        const filteredBarangays = barangays.filter(b => b.m_id == m_id);
        if (filteredBarangays.length > 0) {
          filteredBarangays.forEach(b => {
            const opt = document.createElement("option");
            opt.value = b.brgy_id;
            opt.textContent = b.brgy_name;
            barangaySelect.appendChild(opt);
          });
          
          if (selectBarangayId) {
            barangaySelect.value = selectBarangayId;
          }
          barangaySelect.disabled = false;
        }
      }

      // On page load, restore selections if form data exists
      if (savedCity) {
        populateDistricts(savedCity, savedDistrict);
        populateBarangays(savedCity, savedBarangay);
      }

      citySelect.addEventListener("change", function() {
        const m_id = this.value;
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
        barangaySelect.disabled = true;
        
        populateDistricts(m_id);
        populateBarangays(m_id);
      });
    });
  </script>
  <script>
    // Wait for DOM to load
    document.addEventListener("DOMContentLoaded", function() {
      // Select all alerts
      const alerts = document.querySelectorAll(".alert");

      // Set a timer to fade out after 4 seconds
      setTimeout(() => {
        alerts.forEach(alert => {
          alert.classList.remove("show"); // fade out (Bootstrap)
          alert.classList.add("fade");
          setTimeout(() => alert.remove(), 500); // remove from DOM after fade
        });
      }, 5000); // milliseconds x 1000 = seconds
    });
  </script>
</body>

</html>