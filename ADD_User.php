<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Function to log user activity
 */
function logActivity($conn, $userId, $action)
{
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("is", $userId, $action);
        $stmt->execute();
        $stmt->close();
    }
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
    $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_NUMBER_INT);
    $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_NUMBER_INT);
    $municipality = filter_input(INPUT_POST, 'municipality', FILTER_SANITIZE_NUMBER_INT);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'User'; // Default to 'User'

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "<p style='color: red;'>Passwords do not match.</p>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare insert statement
    $stmt = $conn->prepare("
        INSERT INTO users 
        (username, password, last_name, first_name, middle_name, gender, birthdate, marital_status, tin, house_number, street, brgy_id, district_id, m_id, province, contact_number, email, user_type) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param(
            "sssssssssssiiissss",
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

        if ($stmt->execute()) {
            // ✅ Log admin activity instead of the new user
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id']; // current logged-in admin
                $fullname = trim("$firstname $middlename $lastname");
                $role = htmlspecialchars($user_type);

                logActivity($conn, $userId, "Created new user account. Username: $username, Full Name: $fullname, Role: $role.");
            }

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

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="main_layout.css">
    <link rel="stylesheet" href="ADD_user.css">
    <link rel="stylesheet" href="header.css">
    <title>Electronic Real Property Tax System</title>
</head>

<body>
    <!-- Header Navigation -->
    <?php include 'header.php'; ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color: #379777; font-weight: 600;">Create New User</h2>
        <form action="" method="POST" class="bg-white p-4 rounded-lg" style="border: 1px solid #379777; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

            <!-- User Credentials Section -->
            <div class="mb-4 p-3" style="border-radius: 8px; border-left: 4px solid #379777;">
                <legend class="font-weight-bold mb-3" style="color: #379777; font-size: 1.1rem;">User Credentials</legend>
                <div class="form-group mb-3">
                    <label for="username" class="form-label"><span style="color: #F3E407;">*</span> Username:</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter username"
                        required style="border-radius: 6px; border: 1px solid #379777;">
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label"><span style="color: #F3E407;">*</span> New Password:</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Enter password" required pattern="^(?=.*[0-9])(?=.*[\W_]).{8,}$"
                        title="Password must be at least 8 characters long, include at least one special character, and one number."
                        style="border-radius: 6px; border: 1px solid #379777;">
                    <small class="text-muted">Must be 8+ characters with a number and special character</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label"><span style="color: #F3E407;">*</span> Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                        placeholder="Confirm password" required pattern="^(?=.*[0-9])(?=.*[\W_]).{8,}$"
                        title="Password must match the required format."
                        style="border-radius: 6px; border: 1px solid #379777;">
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="mb-4 p-3" style="border-radius: 8px; border-left: 4px solid #379777;">
                <legend class="font-weight-bold mb-3" style="color: #379777; font-size: 1.1rem;">Personal Information</legend>

                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label for="lastname" class="form-label"><span style="color: #F3E407;">*</span> Last Name:</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name"
                            required style="border-radius: 6px; border: 1px solid #379777;">
                    </div>

                    <div class="col-md-4 form-group mb-3">
                        <label for="firstname" class="form-label"><span style="color: #F3E407;">*</span> First Name:</label>
                        <input type="text" id="firstname" name="firstname" class="form-control"
                            placeholder="Enter first name" required style="border-radius: 6px; border: 1px solid #379777;">
                    </div>

                    <div class="col-md-4 form-group mb-3">
                        <label for="middlename" class="form-label">Middle Name:</label>
                        <input type="text" id="middlename" name="middlename" class="form-control"
                            placeholder="Middle name (optional)" style="border-radius: 6px; border: 1px solid #379777;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label"><span style="color: #F3E407;">*</span> Gender:</label>
                        <div class="d-flex">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" id="male" name="gender" value="male" required
                                    style="border: 1px solid #379777;">
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="female" name="gender" value="female"
                                    style="border: 1px solid #379777;">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="birthdate" class="form-label"><span style="color: #F3E407;">*</span> Birthdate:</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control" required
                            style="border-radius: 6px; border: 1px solid #379777;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label"><span style="color: #F3E407;">*</span> Marital Status:</label>
                        <div class="d-flex">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="radio" id="single" name="marital_status" value="single"
                                    required style="border: 1px solid #379777;">
                                <label class="form-check-label" for="single">Single</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="married" name="marital_status" value="married"
                                    style="border: 1px solid #379777;">
                                <label class="form-check-label" for="married">Married</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="tin" class="form-label"><span style="color: #F3E407;">*</span> Tax Identification Number (TIN):</label>
                        <input type="text" id="tin" name="tin" class="form-control" placeholder="Enter TIN" required
                            style="border-radius: 6px; border: 1px solid #379777;">
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="mb-4 p-3" style="border-radius: 8px; border-left: 4px solid #379777;">
                <legend class="font-weight-bold mb-3" style="color: #379777; font-size: 1.1rem;">Contact Information</legend>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="house_number" class="form-label"><span style="color: #F3E407;">*</span> House Number:</label>
                        <input type="text" id="house_number" name="house_number" class="form-control"
                            placeholder="Enter house number" required style="border-radius: 6px; border: 1px solid #379777;">
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="street" class="form-label"><span style="color: #F3E407;">*</span> Street:</label>
                        <input type="text" id="street" name="street" class="form-control" placeholder="Enter street name"
                            required style="border-radius: 6px; border: 1px solid #379777;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="barangay" class="form-label"><span style="color: #F3E407;">*</span> Barangay:</label>
                        <select id="barangay" name="barangay" class="form-control" required
                            style="border-radius: 6px; border: 1px solid #379777;" disabled>
                            <option value="" disabled selected>Select Barangay</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="district" class="form-label"><span style="color: #F3E407;">*</span> District:</label>
                        <select id="district" class="form-control" style="border-radius: 6px; border: 1px solid #379777;" disabled>
                            <option value="" disabled selected>District</option>
                        </select>
                        <!-- Hidden input to actually submit the value -->
                        <input type="hidden" name="district" id="hidden_district">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="municipality" class="form-label"><span style="color: #F3E407;">*</span> Municipality/City:</label>
                        <select id="municipality" name="municipality" class="form-control" required
                            style="border-radius: 6px; border: 1px solid #379777;">
                            <option value="" disabled selected>Select Municipality</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="province" class="form-label"><span style="color: #F3E407;">*</span> Province:</label>
                        <select id="province" name="province" class="form-control" required
                            style="border-radius: 6px; border: 1px solid #379777;">
                            <option value="Camarines Norte" selected>Camarines Norte</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="contact_number" class="form-label"><span style="color: #F3E407;">*</span> Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" class="form-control"
                            placeholder="Enter contact number" required style="border-radius: 6px; border: 1px solid #379777;">
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="email" class="form-label"><span style="color: #F3E407;">*</span> Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address"
                            required style="border-radius: 6px; border: 1px solid #379777;">
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="userType" class="form-label">User Type:</label>
                <select id="userType" name="user_type" class="form-control"
                    style="border-radius: 6px; border: 1px solid #379777;">
                    <option value="User" selected>User</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn w-100 py-2" style="background-color: #379777; color: white; border: none; border-radius: 6px; font-weight: 500; transition: all 0.3s;">
                Create User
            </button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ADD_User.js"></script>

</body>

</html>