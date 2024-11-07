<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="main_layout.css">
    <link rel="stylesheet" href="User-Control.css">
</head>
<body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">

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
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once 'database.php';

    $conn = Database::getInstance();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch users from the database
    $query = "SELECT user_id, username, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, user_type, status FROM users";
    $result = $conn->query($query);
    $users = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $result->free();
    } else {
        echo "<p>Error fetching users: " . $conn->error . "</p>";
    }

    $conn->close();
?>

<!-- Header Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand">
        <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top" alt="">
        Electronic Real Property Tax System
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="Home.php">Home</a>
            </li>
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    RPU Management
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item active" href="Real-Property-Unit-List.php">RPU List</a>
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

        <h3 class="mb-4">Users</h3>
        <div class="button-group mb-4">
            <a href="ADD_User.php" class="btn btn-outline-primary">Add User</a>
            <button class="btn btn-outline-primary">Show Disabled User</button>
            <button class="btn btn-outline-primary">Hide Disabled User</button>
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
                        <th class="text-center">Update Status</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic Data Rows -->
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><a href="#"><?php echo htmlspecialchars($user['username']); ?></a></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                        <td><?php echo $user['status'] == 1 ? 'Enabled' : 'Disabled'; ?></td>
                        <td class="text-center"><input type="checkbox" <?php echo $user['status'] == 1 ? 'checked' : ''; ?> disabled></td>
                        <td class="text-center">
                            <a href="edit-user.php?id=<?php echo htmlspecialchars($user['user_id']); ?>">
                                <img src="images/pencil.png" alt="Edit" class="edit-icon">
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2020 Copyright:
        <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Your original JS files -->
<script src="jquery.js" defer></script>
<script src="nicepage.js" defer></script>
</body>
</html>