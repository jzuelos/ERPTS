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

// Log the raw JSON data to a file
file_put_contents('request_log.txt', $data . PHP_EOL, FILE_APPEND);

// Decode the JSON data into an associative array
$arpData = json_decode($data, true);

// Check if required data is present
if (isset($arpData['arpNumber'], $arpData['propertyNumber'], $arpData['taxability'], $arpData['effectivity'])) {
    $arpNumber = $arpData['arpNumber'];
    $propertyNumber = $arpData['propertyNumber'];
    $taxability = $arpData['taxability'];
    $effectivity = $arpData['effectivity'];

    if (isset($arpData['id']) && !empty($arpData['id'])) {
        // 🟢 Update existing record in rpu_idnum
        $id = $arpData['id'];
        $sql = "UPDATE rpu_idnum SET arp = ?, pin = ?, taxability = ?, effectivity = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iissi", $arpNumber, $propertyNumber, $taxability, $effectivity, $id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Data updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update data.']);
            }
            $stmt->close();
        }
    } else {
        // 🟢 Insert new record into rpu_idnum
        $sql = "INSERT INTO rpu_idnum (arp, pin, taxability, effectivity) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iiss", $arpNumber, $propertyNumber, $taxability, $effectivity);
            if ($stmt->execute()) {
                $newRpuId = $conn->insert_id; // Get the last inserted ID

                // 🟢 Insert new record into faas table linking to rpu_idnum
                $faasSql = "INSERT INTO faas (rpu_id) VALUES (?)";
                if ($faasStmt = $conn->prepare($faasSql)) {
                    $faasStmt->bind_param("i", $newRpuId);
                    if ($faasStmt->execute()) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Data inserted and linked to FAAS successfully.',
                            'new_rpu_id' => $newRpuId
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Failed to insert into FAAS.']);
                    }
                    $faasStmt->close();
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to insert data into rpu_idnum.']);
            }
            $stmt->close();
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
}
?>