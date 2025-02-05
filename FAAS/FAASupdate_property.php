<?php
include 'database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Log raw POST data for debugging
error_log("Raw POST Data: " . file_get_contents("php://input"));

// Ensure the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method.");
}

// Log all received POST data
error_log("Received POST Data: " . print_r($_POST, true));

// Respond with received data for debugging purposes
echo "Received Data: " . json_encode($_POST);

// Validate required fields
if (empty($_POST['property_id'])) {
    die("Error: Property ID is missing.");
}

// Sanitize POST data
$propertyId = $_POST['property_id'];
$street = $_POST['street'] ?? '';
$barangay = $_POST['barangay'] ?? '';
$municipality = $_POST['municipality'] ?? '';
$province = $_POST['province'] ?? '';
$houseNumber = $_POST['houseNumber'] ?? '';
$landArea = $_POST['landArea'] ?? '';
$zoneNumber = $_POST['zoneNumber'] ?? ''; // Not used in query
$ardNumber = $_POST['ardNumber'] ?? '';   // Not used in query
$taxability = $_POST['taxability'] ?? ''; // Not used in query
$effectivity = $_POST['effectivity'] ?? ''; // Not used in query

// Log sanitized variables for debugging
error_log("Sanitized Variables: property_id=$propertyId, street=$street, barangay=$barangay, municipality=$municipality, province=$province, houseNumber=$houseNumber, landArea=$landArea");

// Initialize $stmt to avoid undefined errors
$stmt = null;

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
    // Clean up resources only if $stmt was successfully created
    if ($stmt !== null) {
        $stmt->close();
    }
    $conn->close();
}
?>
