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
    <link rel="stylesheet" href="ADD_user.css">
    <title>Electronic Real Property Tax System</title>
</head>

<body>

<?php
    session_start(); // Start session at the top
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once 'database.php';

    $conn = Database::getInstance();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "<p>Connected to database successfully.</p>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capture and sanitize form data
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $middlename = filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $marital_status = filter_input(INPUT_POST, 'marital_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tin = filter_input(INPUT_POST, 'tin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $house_number = filter_input(INPUT_POST, 'house_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $municipality = filter_input(INPUT_POST, 'municipality', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        // Debugging output for form data
        echo "<h3>Form Data Received:</h3>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        // Validate and process passwords
        if ($password !== $confirm_password) {
            echo "<p style='color: red;'>Passwords do not match.</p>";
            exit();
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO users (username, password, last_name, first_name, middle_name, gender, birthdate, marital_status, tin, house_number, street, barangay, district, municipality, province, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param(
                "sssssssssssssssss",
                $username,
                $hashed_password,
                $lastname,
                $firstname,
                $middlename,
                $gender,
                $birthdate,
                $marital_status,
                $tin,
                $house_number,
                $street,
                $barangay,
                $district,
                $municipality,
                $province,
                $contact_number,
                $email
            );

            // Execute statement and check for errors
            if ($stmt->execute()) {
                $_SESSION['message'] = "New user created successfully!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "<p style='color: red;'>Error executing query: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red;'>Error preparing statement: " . $conn->error . "</p>";
        }
    }

    // Display session messag   e
    if (isset($_SESSION['message'])) {
        echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
        <a class="navbar-brand">
            <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50"
                class="d-inline-block align-top" alt="">
            Electronic Real Property Tax System
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto"> <!-- Use ml-auto to align items to the right -->
                <li class="nav-item">
                    <a class="nav-link" href="Home.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        RPU Management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
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
                <li class="nav-item" style="margin-left: 20px">
                    <button type="button" class="btn btn-danger" data-toggle="button" aria-pressed="false"
                        autocomplete="off">
                        Log Out</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Create New User</h2>
        <form action="" method="POST"> <!-- Form action points to the same file -->
            <!-- User Credentials Section -->
            <fieldset>
                <legend>User Credentials</legend>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </fieldset>

            <!-- Personal Information Section -->
            <fieldset>
                <legend>Personal Information</legend>
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" required>

                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" required>

                <label for="middlename">Middle Name:</label>
                <input type="text" id="middlename" name="middlename">

                <label>Gender:</label>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="male" required>
                    <label for="male">Male</label>
                    <input type="radio" id="female" name="gender" value="female">
                    <label for="female">Female</label>
                </div>

                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" required>

                <label>Marital Status:</label>
                <div class="radio-group">
                    <input type="radio" id="single" name="marital_status" value="single" required>
                    <label for="single">Single</label>
                    <input type="radio" id="married" name="marital_status" value="married">
                    <label for="married">Married</label>
                </div>

                <label for="tin">Tax Identification Number (TIN):</label>
                <input type="text" id="tin" name="tin" required>
            </fieldset>

            <!-- Contact Information Section -->
            <fieldset>
                <legend>Contact Information</legend>
                <label for="house_number">House Number:</label>
                <input type="text" id="house_number" name="house_number" required>

                <label for="street">Street:</label>
                <input type="text" id="street" name="street" required>

                <label for="barangay">Barangay:</label>
                <input type="text" id="barangay" name="barangay" required>

                <label for="district">District:</label>
                <input type="text" id="district" name="district" required>

                <label for="municipality">Municipality/City:</label>
                <input type="text" id="municipality" name="municipality" required>

                <label for="province">Province:</label>
                <input type="text" id="province" name="province" required>

                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </fieldset>

            <button type="submit">Create User</button>
        </form>
    </div>

    <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <script src="http://localhost/ERPTS/Add-New-Real-Property-Unit.js"></script>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>

</body>

</html>