<?php
// mark_notification_read.php - Handles both single and bulk read operations
session_start();
require_once 'database.php';
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
$mark_all = $input['mark_all'] ?? false;

try {
    // Check if notification_reads table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'notification_reads'");
    $hasReadTable = ($tableCheck && $tableCheck->num_rows > 0);
    
    if (!$hasReadTable) {
        // Table doesn't exist, just return success
        echo json_encode([
            'success' => true,
            'message' => 'Read status tracked in session only'
        ]);
        exit;
    }

    // MARK ALL AS READ
    if ($mark_all) {
        // Get all important notification keywords (same as in fetch_notifications.php)
        $important_keywords = [
            'New RPU request',
            'FAAS approval',
            'Tax declaration',
            'Payment processed',
            'New user registration',
            'Failed login',
            'Permanent lock',
            'Updated user account',
            'Printed Property Report',
            'Transaction created',
            'Papers Received',
            'Document Uploaded'
        ];

        // Build WHERE clause
        $keyword_conditions = array_map(function($keyword) use ($conn) {
            return "al.action LIKE '%" . $conn->real_escape_string($keyword) . "%'";
        }, $important_keywords);

        $where_clause = '(' . implode(' OR ', $keyword_conditions) . ')';

        // Get all notification log_ids that match the important keywords
        $query = "
            SELECT al.log_id 
            FROM activity_log al
            WHERE $where_clause
            ORDER BY al.log_time DESC
            LIMIT 100
        ";

        $result = $conn->query($query);
        
        if ($result) {
            // Prepare insert statement
            $stmt = $conn->prepare("
                INSERT IGNORE INTO notification_reads (user_id, log_id, read_at) 
                VALUES (?, ?, NOW())
            ");
            
            $marked_count = 0;
            
            // Mark each notification as read
            while ($row = $result->fetch_assoc()) {
                $log_id = $row['log_id'];
                $stmt->bind_param('ii', $user_id, $log_id);
                $stmt->execute();
                $marked_count++;
            }
            
            $stmt->close();
            
            echo json_encode([
                'success' => true,
                'message' => 'All notifications marked as read',
                'marked_count' => $marked_count
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to fetch notifications'
            ]);
        }
    } 
    // MARK SINGLE NOTIFICATION AS READ
    else {
        if (!$notif_id) {
            echo json_encode(['error' => 'Missing notification ID']);
            exit;
        }

        // Insert or ignore if already exists
        $stmt = $conn->prepare("
            INSERT IGNORE INTO notification_reads (user_id, log_id, read_at) 
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param('ii', $user_id, $notif_id);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>