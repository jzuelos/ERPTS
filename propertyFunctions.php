<?php
// Include the database connection file
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $c_code = $_POST['c_code'];           // Get classification code
    $c_description = $_POST['c_description'];  // Get classification description
    $c_uv = $_POST['c_uv'];               // Get unit value
    $c_status = $_POST['c_status'];       // Get status

    // Insert into database
    $query = "INSERT INTO classification (c_code, c_description, c_uv, c_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $c_code, $c_description, $c_uv, $c_status);

    if ($stmt->execute()) {
        echo "Classification details added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
