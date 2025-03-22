<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main_layout.css">
    <link rel="stylesheet" href="User-Control.css">
</head>

<body>

    <?php
    session_start(); // Start session at the top
    
    // Prevent the browser from caching this page
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

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
            <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50"
                class="d-inline-block align-top" alt="">
            Electronic Real Property Tax System
        </a>
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
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                <td><a href="#"><?php echo htmlspecialchars($user['username']); ?></a></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                                <td><?php echo $user['status'] == 1 ? 'Enabled' : 'Disabled'; ?></td>
                                <td class="text-center">
                                    <input type="checkbox" <?php echo $user['status'] == 1 ? 'checked' : ''; ?> disabled>
                                </td>
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
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="edit-user.php" method="POST">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="userId">User ID</label>
                                                    <input type="text" class="form-control" name="userId"
                                                        value="<?php echo htmlspecialchars($user['user_id']); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" name="username"
                                                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" name="password" required>
                                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>