<?php
session_start();
require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update user details with input filtering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    // Server-side filtering & sanitization
    $userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING); // use string or date validator if needed
    $marital_status = filter_input(INPUT_POST, 'marital_status', FILTER_SANITIZE_STRING);
    $tin = filter_input(INPUT_POST, 'tin', FILTER_SANITIZE_STRING);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

    // Check for required valid email
    if (!$email) {
        echo "<script>alert('Invalid email address'); window.location.href='User-Control.php';</script>";
        exit();
    }

    // Check if a new password is provided
    if (!empty($_POST["password"])) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $query = "UPDATE users SET 
                        username = ?, password = ?, first_name = ?, middle_name = ?, last_name = ?, 
                        gender = ?, birthdate = ?, marital_status = ?, tin = ?, 
                        contact_number = ?, email = ?, user_type = ?, status = ? 
                      WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "sssssssssssssi",
            $username,
            $password,
            $first_name,
            $middle_name,
            $last_name,
            $gender,
            $birthdate,
            $marital_status,
            $tin,
            $contact_number,
            $email,
            $user_type,
            $status,
            $userId
        );
    } else {
        // Don't update the password if not provided
        $query = "UPDATE users SET 
                        username = ?, first_name = ?, middle_name = ?, last_name = ?, 
                        gender = ?, birthdate = ?, marital_status = ?, tin = ?, 
                        contact_number = ?, email = ?, user_type = ?, status = ? 
                      WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "sssssssssssii",
            $username,
            $first_name,
            $middle_name,
            $last_name,
            $gender,
            $birthdate,
            $marital_status,
            $tin,
            $contact_number,
            $email,
            $user_type,
            $status,
            $userId
        );
    }

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='User-Control.php';</script>";
    } else {
        echo "<script>alert('Error updating user: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$query = "SELECT * FROM users";
$result = $conn->query($query);
$users = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $result->free();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control</title>
    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main_layout.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="User-Control.css">
</head>

<body>
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

    <!-- Main Content -->
    <section class="section-user-management">
        <div class="container py-4">
            <h4 class="text-success">Server Status: Online</h4>
            <div class="alert alert-info text-center" role="alert">
                Logged in as Admin
            </div>
            
            <div class="mb-4 d-flex justify-content-start">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
            <h3 class="mb-4">Users</h3>
            <div class="button-group mb-4">
                <a href="ADD_User.php" class="btn btn-outline-primary">Add User</a>

                <div class="form-check form-check-inline ml-3">
                    <input class="form-check-input" type="radio" name="userStatusFilter" id="showDisabled" value="show"
                        checked>
                    <label class="form-check-label" for="showDisabled">Show Disabled User</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="userStatusFilter" id="hideDisabled" value="hide">
                    <label class="form-check-label" for="hideDisabled">Hide Disabled User</label>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['user_id'] ?? ''); ?></td>
                                <td><a href="#"><?php echo htmlspecialchars($user['username'] ?? ''); ?></a></td>
                                <td><?php echo htmlspecialchars(trim("{$user['first_name']} {$user['middle_name']} {$user['last_name']}")); ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['user_type'] ?? ''); ?></td>
                                <td><?php echo ($user['status'] == 1) ? 'Enabled' : 'Disabled'; ?></td>
                                <td class="text-center">
                                    <a href="#" data-toggle="modal"
                                        data-target="#editUserModal-<?php echo $user['user_id']; ?>">
                                        <i class="bi bi-pencil-square edit-icon"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- User Edit Modal -->
                            <div class="modal fade" id="editUserModal-<?php echo $user['user_id']; ?>" tabindex="-1"
                                aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <!-- Submitting to the same file -->
                                        <form action="" method="POST">
                                            <!-- Hidden field to trigger update -->
                                            <input type="hidden" name="update_user" value="1">
                                            <div class="modal-body">
                                                <div class="container">
                                                    <div class="row">
                                                        <!-- Left Column -->
                                                        <div class="col-md-6">
                                                            <h5 class="text-primary">User Credentials</h5>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="userId">User ID</label>
                                                                <input type="text" class="form-control" name="userId"
                                                                    value="<?php echo htmlspecialchars($user['user_id'] ?? ''); ?>"
                                                                    readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="username">Username</label>
                                                                <input type="text" class="form-control" name="username"
                                                                    value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="password">Password</label>
                                                                <input type="password" class="form-control" name="password"
                                                                    placeholder="Enter new password (leave blank to keep current)">
                                                            </div>

                                                            <h5 class="text-primary mt-4">Personal Information</h5>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="last_name">Last Name</label>
                                                                <input type="text" class="form-control" name="last_name"
                                                                    value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="first_name">First Name</label>
                                                                <input type="text" class="form-control" name="first_name"
                                                                    value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="middle_name">Middle Name</label>
                                                                <input type="text" class="form-control" name="middle_name"
                                                                    value="<?php echo htmlspecialchars($user['middle_name'] ?? ''); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="gender">Gender</label>
                                                                <select class="form-control" name="gender">
                                                                    <option value="Male" <?php echo ($user['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                                                    <option value="Female" <?php echo ($user['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                                                                </select>
                                                            </div>

                                                            <h5 class="text-primary mt-4">User Settings</h5>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="user_type">User Type</label>
                                                                <select class="form-control" name="user_type">
                                                                    <option value="Admin" <?php echo ($user['user_type'] == 'Admin') ? 'selected' : ''; ?>>
                                                                        Admin</option>
                                                                    <option value="User" <?php echo ($user['user_type'] == 'User') ? 'selected' : ''; ?>>
                                                                        User</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="1" <?php echo ($user['status'] == 1) ? 'selected' : ''; ?>>Enabled</option>
                                                                    <option value="0" <?php echo ($user['status'] == 0) ? 'selected' : ''; ?>>Disabled</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Right Column -->
                                                        <div class="col-md-6">
                                                            <h5 class="text-primary">Additional Details</h5>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="birthdate">Birthdate</label>
                                                                <input type="date" class="form-control" name="birthdate"
                                                                    value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="marital_status">Marital Status</label>
                                                                <input type="text" class="form-control"
                                                                    name="marital_status"
                                                                    value="<?php echo htmlspecialchars($user['marital_status'] ?? ''); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tin">TIN</label>
                                                                <input type="text" class="form-control" name="tin"
                                                                    value="<?php echo htmlspecialchars($user['tin'] ?? ''); ?>">
                                                            </div>

                                                            <h5 class="text-primary mt-4">Contact Information</h5>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="contact_number">Contact Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="contact_number"
                                                                    value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="email">Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div> <!-- End Row -->
                                                </div> <!-- End Container -->
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="reset" class="btn btn-warning">Reset</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("input[name='userStatusFilter']").change(function () {
                var showDisabled = $("#showDisabled").is(":checked");
                $("tbody tr").each(function () {
                    var statusText = $(this).find("td:eq(4)").text().trim();
                    if (statusText === "Disabled") {
                        $(this).toggle(showDisabled);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>