<?php
include 'database.php'; // Include your database connection

$conn = Database::getInstance();

$sql = "SELECT r_id, r_no FROM region";
$result = $conn->query($sql);

$regions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $regions[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($regions);

$conn->close();
?>