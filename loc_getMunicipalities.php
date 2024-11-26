<?php
include 'database.php';

$conn = Database::getInstance();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all municipalities
$query = "SELECT m_id, m_description FROM municipality WHERE m_status = 'Active'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $municipalities = [];
    while ($row = $result->fetch_assoc()) {
        $municipalities[] = $row;
    }
    echo json_encode($municipalities); // Return data as JSON
} else {
    echo json_encode([]);
}

$conn->close();
?>