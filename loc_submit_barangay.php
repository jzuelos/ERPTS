<?php
// Include the database connection file
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $brgy_code = $_POST['brgy_code'];
    $brgy_name = $_POST['brgy_name'];
    $status = $_POST['status'];

    // Insert into database
    $query = "INSERT INTO brgy (brgy_code, brgy_name, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $brgy_code, $brgy_name, $status);

    if ($stmt->execute()) {
        echo "Barangay details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>