<?php
session_start();
include 'database.php'; // Your database connection

$conn = Database::getInstance();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Insert logout activity into activity_log
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
    $action = "Logged out of the system";
    $stmt->bind_param("is", $user_id, $action);
    $stmt->execute();
    $stmt->close();
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
?>
