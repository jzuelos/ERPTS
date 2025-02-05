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
        $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'User'; // Default to 'User'
    
        // Validate and process passwords
        if ($password !== $confirm_password) {
            echo "<p style='color: red;'>Passwords do not match.</p>";
            exit();
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO users (username, password, last_name, first_name, middle_name, gender, birthdate, marital_status, tin, house_number, street, barangay, district, municipality, province, contact_number, email, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param(
                "ssssssssssssssssss",
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
                $email,
                $user_type
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

    // Display session message
    if (isset($_SESSION['message'])) {
        echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>


    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
        <a class="navbar-brand" href="#">
            <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50"
                class="d-inline-block align-top" alt="">
            Electronic Real Property Tax System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Home.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
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
                <li class="nav-item">
                    <button type="button" class="btn btn-danger">Log Out</button>
                </li>
            </ul>
        </div>
    </nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Create New User</h2>
    <form action="" method="POST">

        <!-- User Credentials Section -->
        <div class="mb-4">
            <legend class="font-weight-bold">User Credentials</legend>
            <div class="form-group">
                <label for="username"><span style="color: red;">*</span> Username:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label for="password"><span style="color: red;">*</span> New Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password"><span style="color: red;">*</span> Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-4">
            <legend class="font-weight-bold">Personal Information</legend>

            <div class="form-group">
                <label for="lastname"><span style="color: red;">*</span> Last Name:</label>
                <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name" required>
            </div>

            <div class="form-group">
                <label for="firstname"><span style="color: red;">*</span> First Name:</label>
                <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter first name" required>
            </div>

            <div class="form-group">
                <label for="middlename">Middle Name:</label>
                <input type="text" id="middlename" name="middlename" class="form-control" placeholder="Enter middle name (optional)">
            </div>

            <div class="form-group">
                <label><span style="color: red;">*</span> Gender:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="male" name="gender" value="male" required>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="female" name="gender" value="female">
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>

            <div class="form-group">
                <label for="birthdate"><span style="color: red;">*</span> Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" class="form-control" required>
            </div>

            <div class="form-group">
                <label><span style="color: red;">*</span> Marital Status:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="single" name="marital_status" value="single" required>
                    <label class="form-check-label" for="single">Single</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="married" name="marital_status" value="married">
                    <label class="form-check-label" for="married">Married</label>
                </div>
            </div>

            <div class="form-group">
                <label for="tin"><span style="color: red;">*</span> Tax Identification Number (TIN):</label>
                <input type="text" id="tin" name="tin" class="form-control" placeholder="Enter TIN" required>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="mb-4">
            <legend class="font-weight-bold">Contact Information</legend>

            <div class="form-group">
                <label for="house_number"><span style="color: red;">*</span> House Number:</label>
                <input type="text" id="house_number" name="house_number" class="form-control" placeholder="Enter house number" required>
            </div>

            <div class="form-group">
                <label for="street"><span style="color: red;">*</span> Street:</label>
                <input type="text" id="street" name="street" class="form-control" placeholder="Enter street name" required>
            </div>

            <div class="form-group">
                <label for="barangay"><span style="color: red;">*</span> Barangay:</label>
                <select id="barangay" name="barangay" class="form-control" required>
                    <option value="" disabled selected>Select Barangay</option>
                </select>
            </div>

            <div class="form-group">
                <label for="district"><span style="color: red;">*</span> District:</label>
                <select id="district" name="district" class="form-control" required>
                    <option value="" disabled selected>Select District</option>
                </select>
            </div>

            <div class="form-group">
                <label for="municipality"><span style="color: red;">*</span> Municipality/City:</label>
                <select id="municipality" name="municipality" class="form-control" required>
                    <option value="" disabled selected>Select Municipality</option>
                </select>
            </div>

            <div class="form-group">
                <label for="province"><span style="color: red;">*</span> Province:</label>
                <select id="province" name="province" class="form-control" required>
                    <option value="Camarines Norte" selected>Camarines Norte</option>
                </select>
            </div>

            <div class="form-group">
                <label for="contact_number"><span style="color: red;">*</span> Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Enter contact number" required>
            </div>

            <div class="form-group">
                <label for="email"><span style="color: red;">*</span> Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" required>
            </div>
        </div>

        <div class="form-group">
            <label for="userType">User Type:</label>
            <select id="userType" name="user_type" class="form-control">
                <option value="User" selected>User</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn">Create User</button>
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
    <script src="ADD_User.js"></script>

</body>

</html>