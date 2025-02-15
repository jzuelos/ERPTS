<?php
include 'database.php'; // Your database connection file

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from AJAX request
    $region_id = $_POST['region_id'];
    $municipality_code = $_POST['municipality_code'];
    $municipality_description = $_POST['municipality_description'];
    $status = $_POST['status'];

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO municipality (r_id, m_code, m_description, m_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $region_id, $municipality_code, $municipality_description, $status);

    if ($stmt->execute()) {
        echo "Municipality details successfully saved.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
