<?php
session_start(); // ✅ needed for user_id
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $m_id = $_POST['m_id']; 
    $brgy_code = $_POST['brgy_code'];
    $brgy_name = $_POST['brgy_name'];
    $status = $_POST['status'];

    $query = "INSERT INTO brgy (m_id, brgy_code, brgy_name, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $m_id, $brgy_code, $brgy_name, $status);

    if ($stmt->execute()) {
        echo "Barangay details added successfully!";

        // ✅ Log activity
        if (isset($_SESSION['user_id'])) {
            $log = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
            $action = "Added Barangay: " . $brgy_name;
            $log->bind_param("is", $_SESSION['user_id'], $action);
            $log->execute();
            $log->close();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>