<?php
session_start(); // ✅ Required for user session
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $region_id = $_POST['region_id'];
    $municipality_code = $_POST['municipality_code'];
    $municipality_description = $_POST['municipality_description'];
    $status = $_POST['status'];

    // Insert municipality record
    $sql = "INSERT INTO municipality (r_id, m_code, m_description, m_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $region_id, $municipality_code, $municipality_description, $status);

    if ($stmt->execute()) {
        echo "Municipality details successfully saved.";

        // ✅ Log activity with detailed message
        if (isset($_SESSION['user_id'])) {
            // Get region number
            $r_stmt = $conn->prepare("SELECT r_no FROM region WHERE r_id = ?");
            $r_stmt->bind_param("i", $region_id);
            $r_stmt->execute();
            $r_result = $r_stmt->get_result();
            $region_no = $r_result->fetch_assoc()['r_no'] ?? 'Unknown Region';
            $r_stmt->close();

            // ✅ Example: Added "Municipality of Daet" under Region "Region V"
            $action = "Added new Municipality \"$municipality_description\" under \"$region_no\"";

            // Insert activity log
            $log = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
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
