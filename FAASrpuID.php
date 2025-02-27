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
if (empty($data['arpNumber']) || empty($data['propertyNumber']) || empty($data['taxability']) || empty($data['effectivity'])) {
    exit(json_encode(['success' => false, 'error' => 'Missing required fields.']));
}

// Assign values
$arpNumber = $data['arpNumber'];
$propertyNumber = $data['propertyNumber'];
$taxability = $data['taxability'];
$effectivity = $data['effectivity'];

// Insert into `rpu_idnum`
$sql = "INSERT INTO rpu_idnum (arp, pin, taxability, effectivity) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $arpNumber, $propertyNumber, $taxability, $effectivity);

if (!$stmt->execute()) {
    exit(json_encode(['success' => false, 'error' => 'Failed to insert into rpu_idnum.']));
}

$stmt->close();

// Success response
exit(json_encode([
    'success' => true,
    'message' => 'Data inserted successfully.',
]));
