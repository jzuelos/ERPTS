<?php
// Include the database connection file
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $code = $_POST['code'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Insert into database
    $query = "INSERT INTO district (district_code, description, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $code, $description, $status);

    if ($stmt->execute()) {
        echo "District details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>