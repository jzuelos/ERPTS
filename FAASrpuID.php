<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
    exit(json_encode(['success' => false, 'error' => 'Database connection failed.']));
}

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('request_log.txt', json_encode($data) . PHP_EOL, FILE_APPEND);

if (empty($data['arpNumber']) || empty($data['propertyNumber']) || empty($data['taxability']) || empty($data['effectivity']) || empty($data['faasId'])) {
    exit(json_encode(['success' => false, 'error' => 'Missing required fields.']));
}

$arpNumber = trim($data['arpNumber']);
$propertyNumber = trim($data['propertyNumber']);
$taxability = trim($data['taxability']);
$effectivity = trim($data['effectivity']);
$faasId = (int)$data['faasId'];

// Step 1: Check if faas has rpu_idno
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
        // Step 2: Fetch current rpu_idnum data
        $get_sql = "SELECT arp, pin, taxability, effectivity FROM rpu_idnum WHERE rpu_id = ?";
        $get_stmt = $conn->prepare($get_sql);
        $get_stmt->bind_param("i", $existing_rpu_id);
        $get_stmt->execute();
        $current = $get_stmt->get_result()->fetch_assoc();
        $get_stmt->close();

        // Step 3: Compare existing vs new data
        if (
            $current &&
            $current['arp'] === $arpNumber &&
            $current['pin'] === $propertyNumber &&
            $current['taxability'] === $taxability &&
            $current['effectivity'] === $effectivity
        ) {
            exit(json_encode(['success' => false, 'error' => 'No changes detected.']));
        }

        // Step 4: Update only if changes exist
        $update_sql = "UPDATE rpu_idnum 
                       SET arp = ?, pin = ?, taxability = ?, effectivity = ?, faas_id = ? 
                       WHERE rpu_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssii", $arpNumber, $propertyNumber, $taxability, $effectivity, $faasId, $existing_rpu_id);

        if ($update_stmt->execute()) {
            exit(json_encode(['success' => true, 'message' => 'rpu_idnum updated.']));
        } else {
            exit(json_encode(['success' => false, 'error' => 'Update failed.']));
        }
    }
}

// Step 5: If no existing rpu_idnum, insert new record
$insert_sql = "INSERT INTO rpu_idnum (arp, pin, taxability, effectivity, faas_id) 
               VALUES (?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("ssssi", $arpNumber, $propertyNumber, $taxability, $effectivity, $faasId);

if (!$insert_stmt->execute()) {
    exit(json_encode(['success' => false, 'error' => 'Insert into rpu_idnum failed.']));
}

$new_rpu_id = $conn->insert_id;
$insert_stmt->close();

// Step 6: Update faas table with new rpu_id
$update_faas_sql = "UPDATE faas SET rpu_idno = ? WHERE faas_id = ?";
$update_faas_stmt = $conn->prepare($update_faas_sql);
$update_faas_stmt->bind_param("ii", $new_rpu_id, $faasId);

if (!$update_faas_stmt->execute()) {
    exit(json_encode(['success' => false, 'error' => 'Failed to update faas with new rpu_idno.']));
}

$update_faas_stmt->close();

exit(json_encode([
    'success' => true,
    'message' => 'New rpu_idnum inserted and faas updated.',
    'rpu_id' => $new_rpu_id
]));
