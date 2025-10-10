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

        // ✅ Log activity with details
        if (isset($_SESSION['user_id'])) {
            // Get municipality name
            $m_stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
            $m_stmt->bind_param("i", $m_id);
            $m_stmt->execute();
            $m_result = $m_stmt->get_result();
            $municipality_name = $m_result->fetch_assoc()['m_description'] ?? 'Unknown';
            $m_stmt->close();

            $log = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
            $action = "Added new Barangay \"$brgy_name\" under Municipality \"$municipality_name\"";
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