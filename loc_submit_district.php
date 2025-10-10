<?php
session_start(); // ✅ Required for user session
include 'database.php';

$conn = Database::getInstance();

// Get POST data
$district_code = $_POST['district_code'];
$description = $_POST['description'];
$status = $_POST['status'];
$m_id = $_POST['m_id'];

// Insert new district
$sql = "INSERT INTO district (district_code, description, status, m_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $district_code, $description, $status, $m_id);

if ($stmt->execute()) {
    echo 'success';

    // ✅ Log activity in detailed format
    if (isset($_SESSION['user_id'])) {
        // Get municipality name
        $m_stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
        $m_stmt->bind_param("i", $m_id);
        $m_stmt->execute();
        $m_result = $m_stmt->get_result();
        $municipality_name = $m_result->fetch_assoc()['m_description'] ?? 'Unknown Municipality';
        $m_stmt->close();

        // ✅ Format: Added “Municipality” in “District”
        $action = "Added \"$municipality_name\" in \"$description\"";

        // Insert activity log
        $log = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
        $log->bind_param("is", $_SESSION['user_id'], $action);
        $log->execute();
        $log->close();
    }
} else {
    echo 'failure';
}

// Cleanup
$stmt->close();
$conn->close();
?>
