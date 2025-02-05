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
  <link rel="stylesheet" href="Add_POwner.css"> 
  <title>Electronic Real Property Tax System</title> 
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
<section class="container mt-5">
  <div class="card p-4 shadow-sm form-container">
    <h3 class="mb-4 text-center">Add Property Owner</h3>
    <form action="" method="POST">
<?php
  session_start(); // Start session at the top

  // Check if the user is logged in by verifying if 'user_id' exists in the session
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if user is not logged in
    exit; // Stop further execution after redirection
  }

  // Prevent the browser from caching this page
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // Instruct the browser not to store or cache the page
  header("Cache-Control: post-check=0, pre-check=0", false); // Additional caching rules to prevent the page from being reloaded from cache
  header("Pragma: no-cache"); // Older cache control header for HTTP/1.0 compatibility

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

  // Check if form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $middleName = filter_input(INPUT_POST, 'middleName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tinNumber = filter_input(INPUT_POST, 'tinNumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $houseNumber = filter_input(INPUT_POST, 'houseNumber', FILTER_SANITIZE_NUMBER_INT);
    $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Optional fields for owner information
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);

    // Format optional owner information
    $ownInfo = "Telephone: $telephone, Fax: $fax, Email: $email, Website: $website";

    // Prepare and execute insert statement
    if ($firstName && $surname && $tinNumber && $houseNumber && $city && $province) {
      $stmt = $conn->prepare("INSERT INTO owners_tb (own_fname, own_mname, own_surname, tin_no, house_no, street, barangay, district, city, province, own_info) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

      if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssssissssss", $firstName, $middleName, $surname, $tinNumber, $houseNumber, $street, $barangay, $district, $city, $province, $ownInfo);

        // Execute the statement
        if ($stmt->execute()) {
          $_SESSION['message'] = "Property owner added successfully!";
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
        } else {
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
?>

     <!-- Owner's Information -->
<div class="form-group">
  <label for="firstName"><span style="color: red;">*</span> First Name</label>
  <input type="text" class="form-control input-field" id="firstName" name="firstName" placeholder="Enter First Name" required>
</div>
<div class="form-group">
  <label for="middleName">Middle Name</label>
  <input type="text" class="form-control input-field" id="middleName" name="middleName" placeholder="Enter Middle Name">
</div>
<div class="form-group">
  <label for="surname"><span style="color: red;">*</span> Surname</label>
  <input type="text" class="form-control input-field" id="surname" name="surname" placeholder="Enter Surname" required>
</div>
<div class="form-group">
  <label for="tinNumber"><span style="color: red;">*</span> TIN No.</label>
  <input type="text" class="form-control input-field" id="tinNumber" name="tinNumber" placeholder="Enter TIN Number" required>
</div>

<!-- Address -->
<h5 class="mt-4">Address</h5>
<div class="form-group">
  <label for="houseNumber"><span style="color: red;">*</span> House Number</label>
  <input type="text" class="form-control input-field" id="houseNumber" name="houseNumber" placeholder="Enter House Number" required>
</div>
<div class="form-group">
  <label for="street"><span style="color: red;">*</span> Street</label>
  <input type="text" class="form-control input-field" id="street" name="street" placeholder="Enter Street" required>
</div>
<div class="form-group">
  <label for="barangay"><span style="color: red;">*</span> Barangay</label>
  <input type="text" class="form-control input-field" id="barangay" name="barangay" placeholder="Enter Barangay" required>
</div>
<div class="form-group">
  <label for="district"><span style="color: red;">*</span> District</label>
  <input type="text" class="form-control input-field" id="district" name="district" placeholder="Enter District" required>
</div>
<div class="form-group">
  <label for="city"><span style="color: red;">*</span> City</label>
  <input type="text" class="form-control input-field" id="city" name="city" placeholder="Enter City" required>
</div>
<div class="form-group">
  <label for="province"><span style="color: red;">*</span> Province</label>
  <input type="text" class="form-control input-field" id="province" name="province" placeholder="Enter Province" required>
</div>

<!-- Owner Contact Information -->
<h5 class="mt-4">Owner Information<small> (Optional)</small></h5>
<div class="form-group">
  <label for="telephone">Telephone</label>
  <input type="text" class="form-control input-field" id="telephone" name="telephone" placeholder="Enter Telephone Number">
</div>
<div class="form-group">
  <label for="fax">Fax</label>
  <input type="text" class="form-control input-field" id="fax" name="fax" placeholder="Enter Fax Number">
</div>
<div class="form-group">
  <label for="email">Email</label>
  <input type="email" class="form-control input-field" id="email" name="email" placeholder="Enter Email Address">
</div>
<div class="form-group">
  <label for="website">Website</label>
  <input type="url" class="form-control input-field" id="website" name="website" placeholder="Enter Website URL">
</div>

<button type="submit" class="btn btn-primary submit-btn">Submit</button>
</form>
</div>
</section>

  <!-- Footer -->
  <footer class="footer mt-auto py-3 bg-custom"> 
    <div class="container"> 
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
</body>
</html>