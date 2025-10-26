<?php
session_start();
require_once 'database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$log_id = isset($_GET['log_id']) ? intval($_GET['log_id']) : 0;

if ($log_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid log ID']);
    exit;
}

$conn = Database::getInstance();

try {
    // Fetch the snapshot from owner_audit_log
    $stmt = $conn->prepare("
        SELECT 
            log_id,
            action,
            owner_id,
            property_id,
            user_id,
            `tax-dec_id` as tax_dec_id,
            details,
            created_at
        FROM owner_audit_log 
        WHERE log_id = ? AND action = 'Snapshot'
        LIMIT 1
    ");
    
    $stmt->bind_param("i", $log_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Snapshot not found']);
        exit;
    }
    
    $record = $result->fetch_assoc();
    $stmt->close();
    
    // Decode the JSON snapshot data
    $snapshot_data = json_decode($record['details'], true);
    
    if (!$snapshot_data) {
        echo json_encode(['success' => false, 'message' => 'Invalid snapshot data']);
        exit;
    }
    
    // Return the snapshot data
    echo json_encode([
        'success' => true,
        'log_id' => $record['log_id'],
        'property_id' => $record['property_id'],
        'tax_dec_id' => $record['tax_dec_id'],
        'created_at' => $record['created_at'],
        'snapshot' => $snapshot_data
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching historical snapshot: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>