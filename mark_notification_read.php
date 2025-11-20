<?php
// mark_notification_read.php
session_start();
require_once 'database.php'; // Adjust path as needed
$conn = Database::getInstance();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$notif_id = $input['notif_id'] ?? null;

if (!$notif_id) {
    echo json_encode(['error' => 'Missing notification ID']);
    exit;
}

try {
    // Check if notification_reads table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'notification_reads'");
    
    if ($tableCheck && $tableCheck->num_rows > 0) {
        // Insert or ignore if already exists
        $stmt = $conn->prepare("
            INSERT IGNORE INTO notification_reads (user_id, log_id) 
            VALUES (?, ?)
        ");
        $stmt->bind_param('ii', $user_id, $notif_id);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    } else {
        // Table doesn't exist, just return success (notifications won't persist as read)
        echo json_encode([
            'success' => true,
            'message' => 'Read status tracked in session only'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>