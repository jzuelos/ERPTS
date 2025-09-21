<?php
session_start(); // ✅
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $region_id = $_POST['region_id'];
    $municipality_code = $_POST['municipality_code'];
    $municipality_description = $_POST['municipality_description'];
    $status = $_POST['status'];

    $sql = "INSERT INTO municipality (r_id, m_code, m_description, m_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $region_id, $municipality_code, $municipality_description, $status);

    if ($stmt->execute()) {
        echo "Municipality details successfully saved.";

        // ✅ Log activity
        if (isset($_SESSION['user_id'])) {
            $log = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
            $action = "Added Municipality: " . $municipality_description;
            $log->bind_param("is", $_SESSION['user_id'], $action);
            $log->execute();
            $log->close();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>