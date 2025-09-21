<?php
session_start(); // ✅
include 'database.php';

$conn = Database::getInstance();

$district_code = $_POST['district_code'];
$description = $_POST['description'];
$status = $_POST['status'];
$m_id = $_POST['m_id'];

$sql = "INSERT INTO district (district_code, description, status, m_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $district_code, $description, $status, $m_id);

if ($stmt->execute()) {
    echo 'success';

    // ✅ Log activity
    if (isset($_SESSION['user_id'])) {
        $log = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
        $action = "Added District: " . $description;
        $log->bind_param("is", $_SESSION['user_id'], $action);
        $log->execute();
        $log->close();
    }
} else {
    echo 'failure';
}

$stmt->close();
$conn->close();
?>