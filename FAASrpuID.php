<?php
header('Content-Type: application/json'); // Ensure JSON response

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require_once 'database.php'; // Database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
    exit(json_encode(['success' => false, 'error' => 'Database connection failed.']));
}

// Read and decode JSON input
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('request_log.txt', json_encode($data) . PHP_EOL, FILE_APPEND); // Log request data

// Validate input fields
if (empty($data['arpNumber']) || empty($data['propertyNumber']) || empty($data['taxability']) || empty($data['effectivity']) || empty($data['faasId'])) {
    exit(json_encode(['success' => false, 'error' => 'Missing required fields.']));
}

// Assign values
$arpNumber = $data['arpNumber'];
$propertyNumber = $data['propertyNumber'];
$taxability = $data['taxability'];
$effectivity = $data['effectivity'];
$faasId = $data['faasId']; // Get faas_id from request

// Step 1: Check if rpu_idno already exists in faas table
$check_sql = "SELECT rpu_idno FROM faas WHERE faas_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $faasId);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_stmt->close();

if ($check_result->num_rows > 0) {
    $row = $check_result->fetch_assoc();
    $existing_rpu_id = $row['rpu_idno'];

    if (!empty($existing_rpu_id)) {
        // Step 2: If rpu_idno exists, update rpu_idnum table
        $update_sql = "UPDATE rpu_idnum SET arp = ?, pin = ?, taxability = ?, effectivity = ? WHERE rpu_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iissi", $arpNumber, $propertyNumber, $taxability, $effectivity, $existing_rpu_id);

        if ($update_stmt->execute()) {
            exit(json_encode(['success' => true, 'message' => 'rpu_idnum record updated successfully.']));
        } else {
            exit(json_encode(['success' => false, 'error' => 'Failed to update rpu_idnum.']));
        }
    }
}

// Step 3: If no existing rpu_idno, insert a new record in rpu_idnum
$sql = "INSERT INTO rpu_idnum (arp, pin, taxability, effectivity) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $arpNumber, $propertyNumber, $taxability, $effectivity);

if (!$stmt->execute()) {
    exit(json_encode(['success' => false, 'error' => 'Failed to insert into rpu_idnum.']));
}

// Get the last inserted rpu_id
$rpu_id = $conn->insert_id;
$stmt->close();

// Step 4: Update faas table with new rpu_idno
$sql2 = "UPDATE faas SET rpu_idno = ? WHERE faas_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("ii", $rpu_id, $faasId);

if (!$stmt2->execute()) {
    exit(json_encode(['success' => false, 'error' => 'Failed to update faas table.']));
}

$stmt2->close();

// Success response
exit(json_encode([
    'success' => true,
    'message' => 'Data inserted and faas table updated successfully.',
]));
