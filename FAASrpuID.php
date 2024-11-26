<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once 'database.php';  // Your database connection file

// Get the database connection using your singleton pattern
$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the incoming raw JSON data
$data = file_get_contents('php://input');

// Decode the JSON data into an associative array
$arpData = json_decode($data, true);

// Check if required data is present
if (isset($arpData['arpNumber'], $arpData['propertyNumber'], $arpData['taxability'], $arpData['effectivity'])) {
    // Extract the values from the JSON data
    $arpNumber = $arpData['arpNumber'];
    $propertyNumber = $arpData['propertyNumber'];
    $taxability = $arpData['taxability'];
    $effectivity = $arpData['effectivity'];

    // Prepare SQL query to insert or update data
    // If editing, you can also add an "id" field to identify the record to update

    // Assuming you have a primary key like 'id' to update, and it is sent with the request
    if (isset($arpData['id']) && !empty($arpData['id'])) {
        // Update existing record if 'id' is present
        $id = $arpData['id'];  // Unique identifier (primary key)

        // Prepare SQL query to update record
        $sql = "UPDATE rpu_idnum SET arp = ?, pin = ?, taxability = ?, effectivity = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("iissi", $arpNumber, $propertyNumber, $taxability, $effectivity, $id);

            // Execute the query
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Data updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update data.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL query.']);
        }
    } else {
        // Insert new record if no 'id' is provided
        $sql = "INSERT INTO rpu_idnum (arp, pin, taxability, effectivity) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("iiss", $arpNumber, $propertyNumber, $taxability, $effectivity);

            // Execute the query
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Data inserted successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to insert data.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL query.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
}
?>
