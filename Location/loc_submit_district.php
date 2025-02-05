<?php
// Include database connection
include('database.php');

$conn = Database::getInstance();

// Get POST data
$district_code = $_POST['district_code'];
$description = $_POST['description'];
$status = $_POST['status'];
$m_id = $_POST['m_id'];

// Prepare the SQL statement
$sql = "INSERT INTO district (district_code, description, status, m_id) VALUES (?, ?, ?, ?)";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $district_code, $description, $status, $m_id);

// Execute the query
if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'failure';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>