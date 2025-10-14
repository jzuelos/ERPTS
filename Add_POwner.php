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

  // Format optional owner information
  $ownInfo = "Telephone: $telephone, Fax: $fax, Email: $email, Website: $website";

  // Prepare and execute insert statement
  if ($firstName && $surname && $tinNumber && $city) {
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
        
        echo "<p>Error: " . $stmt->error . "</p>";
      }
      $stmt->close();
    } else {
      echo "<p>Error preparing statement: " . $conn->error . "</p>";
    }
  } else {
    echo "<p>Error: Please fill in all required fields.</p>";
  }
}

// Display any session message and then clear it
if (isset($_SESSION['message'])) {
  echo "<div class='alert alert-success text-center'>" . $_SESSION['message'] . "</div>";
  unset($_SESSION['message']);
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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

      <form action="" method="POST">
        <div class="row">
          <!-- Owner’s Information (Row 1) -->
          <div class="col-md-6">
            <h5>Owner's Information <small class="text-muted">(Required)</small></h5>
            <div class="form-group mb-3">
              <label for="firstName"><span class="text-danger">*</span> First Name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" required>
            </div>
            <div class="form-group mb-3">
              <label for="middleName">Middle Name</label>
              <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter Middle Name">
            </div>
            <div class="form-group mb-3">
              <label for="surname"><span class="text-danger">*</span> Surname</label>
              <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname" required>
            </div>
            <div class="form-group mb-3">
              <label for="birthday"><span class="text-danger">*</span> Birthday</label>
              <input type="date" class="form-control" id="birthday" name="birthday" required>
            </div>
            <div class="form-group mb-3">
              <label for="tinNumber"><span class="text-danger">*</span> TIN No.</label>
              <input type="text" class="form-control" id="tinNumber" name="tinNumber" placeholder="Enter TIN Number" required>
            </div>
          </div>

          <div class="col-md-6">
            <h5>Owner Information <small class="text-muted">(Optional)</small></h5>
            <div class="form-group mb-3">
              <label for="telephone">Telephone</label>
              <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Enter Telephone Number">
            </div>
            <div class="form-group mb-3">
              <label for="fax">Fax</label>
              <input type="text" class="form-control" id="fax" name="fax" placeholder="Enter Fax Number">
            </div>
            <div class="form-group mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address">
            </div>
            <div class="form-group mb-3">
              <label for="website">Website</label>
              <input type="url" class="form-control" id="website" name="website" placeholder="Enter Website URL">
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
              <option value="" selected disabled>Select Municipality</option>
              <?php while ($row = $municipalities_result->fetch_assoc()) { ?>
                <option value="<?= htmlspecialchars($row['m_id']) ?>">
                  <?= htmlspecialchars($row['m_description']) ?>
                </option>
              <?php } ?>
            </select>
          </div>

          <!-- District -->
          <div class="col-md-3 mb-3">
            <label for="district"><span class="text-danger">*</span> District</label>
            <select class="form-select" id="district" name="district" required disabled>
              <option value="" selected disabled>Select District</option>
            </select>
          </div>

          <!-- Barangay -->
          <div class="col-md-3 mb-3">
            <label for="barangay"><span class="text-danger">*</span> Barangay</label>
            <select class="form-select" id="barangay" name="barangay" required disabled>
              <option value="" selected disabled>Select Barangay</option>
            </select>
          </div>

          <!-- Street / House Number - Made optional -->
          <div class="col-md-3 mb-3">
            <label for="streetHouse">Street / House No.</label>
            <input type="text" class="form-control" id="streetHouse" name="streetHouse" placeholder="Enter Street / House No.">
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
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3oAi1Kn5/yo9M4aW5rY1LYi9Cj3jRIvYIZAZ5h8oW7B5h2C7z5e8B2CKy7uWgG"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.11.0/dist/umd/popper.min.js"
    integrity="sha384-1A2Z3A6C0e0gB3b3gmJ3g5BO5x0B1DAIlxgG5F8bB1Zqf7uE2W0p1Fh0b2RM0G1Z9"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-Chfqqxu3y5C8LQXhSh2gN5F6azZ9L2H8eY+mcO8b6Q8R9SQh7PQe0i0K+8zG3p7U"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const districts = <?php echo $districts_json; ?>;
      const barangays = <?php echo $barangays_json; ?>;

      const citySelect = document.getElementById("city");
      const districtSelect = document.getElementById("district");
      const barangaySelect = document.getElementById("barangay");

      citySelect.addEventListener("change", function() {
        const m_id = this.value;

        // Reset dropdowns
        districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
        districtSelect.disabled = true;
        barangaySelect.disabled = true;

        // ✅ Auto-fill first district for this municipality
        const filteredDistricts = districts.filter(d => d.m_id == m_id);
        if (filteredDistricts.length > 0) {
          const d = filteredDistricts[0]; // pick first
          const opt = document.createElement("option");
          opt.value = d.district_id;
          opt.textContent = d.description;
          districtSelect.appendChild(opt);
          districtSelect.value = d.district_id;
          districtSelect.disabled = true; // locked
        }

        // ✅ Load barangays for this municipality
        const filteredBarangays = barangays.filter(b => b.m_id == m_id);
        if (filteredBarangays.length > 0) {
          filteredBarangays.forEach(b => {
            const opt = document.createElement("option");
            opt.value = b.brgy_id;
            opt.textContent = b.brgy_name;
            barangaySelect.appendChild(opt);
          });
          barangaySelect.disabled = false;
        }
      });
    });
  </script>

</body>

</html>