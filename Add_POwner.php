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
<?php
session_start();
require_once 'database.php';

// Establish a database connection
$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable to hold the success message
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $middleName = filter_input(INPUT_POST, 'middleName', FILTER_SANITIZE_STRING);
    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING);
    $tinNumber = filter_input(INPUT_POST, 'tinNumber', FILTER_SANITIZE_STRING);
    $houseNumber = filter_input(INPUT_POST, 'houseNumber', FILTER_SANITIZE_STRING);
    $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
    $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_STRING);
    $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_STRING);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
    $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);

    // Set website to NULL if empty
    $website = empty($website) ? null : $website;

    // Prepare SQL statement to insert owner
    $stmt = $conn->prepare("INSERT INTO owners_tb (own_fname, own_mname, own_surname, tin_no, house_no, street, barangay, district, city, province, own_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        // Concatenate contact information
        $own_info = "$telephone, $fax, $email, $website";

        $stmt->bind_param("sssssssssss", $firstName, $middleName, $surname, $tinNumber, $houseNumber, $street, $barangay, $district, $city, $province, $own_info);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $successMessage = "Record added successfully!";
        } else {
            $successMessage = "Error adding record.";
        }

        $stmt->close();
    } else {
        $successMessage = "Error preparing statement: " . $conn->error;
    }
}
$conn->close();
?>

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
        <li class="nav-item dropdown active"> 
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown"> 
            <a class="dropdown-item active" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration-List.php">Tax Declaration</a>
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
          <button type="button" class="btn btn-danger">Log Out</button> 
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
<section class="container mt-5">
  <div class="card p-4 shadow-sm form-container">
    <h3 class="mb-4 text-center">Add Property Owner</h3>
    <form action="" method="POST">
      <!-- Owner's Information -->
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control input-field" id="firstName" name="firstName" placeholder="Enter First Name" required>
      </div>
      <div class="form-group">
        <label for="middleName">Middle Name</label>
        <input type="text" class="form-control input-field" id="middleName" name="middleName" placeholder="Enter Middle Name">
      </div>
      <div class="form-group">
        <label for="surname">Surname</label>
        <input type="text" class="form-control input-field" id="surname" name="surname" placeholder="Enter Surname" required>
      </div>
      <div class="form-group">
        <label for="tinNumber">TIN No.</label>
        <input type="text" class="form-control input-field" id="tinNumber" name="tinNumber" placeholder="Enter TIN Number" required>
      </div>

      <!-- Address -->
      <h5 class="mt-4">Address</h5>
      <div class="form-group">
        <label for="houseNumber">House Number</label>
        <input type="text" class="form-control input-field" id="houseNumber" name="houseNumber" placeholder="Enter House Number" required>
      </div>
      <div class="form-group">
        <label for="street">Street</label>
        <input type="text" class="form-control input-field" id="street" name="street" placeholder="Enter Street" required>
      </div>
      <div class="form-group">
        <label for="barangay">Barangay</label>
        <input type="text" class="form-control input-field" id="barangay" name="barangay" placeholder="Enter Barangay" required>
      </div>
      <div class="form-group">
        <label for="district">District</label>
        <input type="text" class="form-control input-field" id="district" name="district" placeholder="Enter District" required>
      </div>
      <div class="form-group">
        <label for="city">City</label>
        <input type="text" class="form-control input-field" id="city" name="city" placeholder="Enter City" required>
      </div>
      <div class="form-group">
        <label for="province">Province</label>
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
