<?php
include 'database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method.");
}

// Validate required fields
if (empty($_POST['property_id'])) {
    die("Error: Property ID is missing.");
}

// Sanitize POST data
$propertyId   = intval($_POST['property_id']);
$street       = $_POST['street'] ?? '';
$barangay     = $_POST['barangay'] ?? '';
$municipality = $_POST['municipality'] ?? '';
$province     = $_POST['province'] ?? '';
$houseNumber  = $_POST['houseNumber'] ?? '';
$landArea     = $_POST['landArea'] ?? '';

// Prepare and execute the SQL update query
try {
    $stmt = $conn->prepare("
        UPDATE p_info 
        SET street = ?, barangay = ?, city = ?, province = ?, house_no = ?, land_area = ?
        WHERE p_id = ?
    ");

    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssssi", $street, $barangay, $municipality, $province, $houseNumber, $landArea, $propertyId);

    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Property information updated successfully!";
        } else {
            echo "No rows were updated. The data may already be up-to-date, or the record does not exist.";
        }
    } else {
        throw new Exception("Error executing query: " . $stmt->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    error_log("Error: " . $e->getMessage());
} finally {
    if (isset($stmt) && $stmt !== null) {
        $stmt->close();
    }
    $conn->close();
}
?>
