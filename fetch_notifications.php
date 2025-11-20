<?php
session_start();
require_once 'database.php'; // must contain $conn = new mysqli(...);
$conn = Database::getInstance();

header('Content-Type: application/json');

// Only admin can access
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

// Important notification keywords
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

// Check if notification_reads table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'notification_reads'");
$hasReadTable = ($tableCheck && $tableCheck->num_rows > 0);

// Main query
if ($hasReadTable) {
    $query = "
        SELECT 
            al.log_id,
            al.action,
            al.log_time,
            u.first_name,
            u.last_name,
            nr.read_at,
            CASE
                WHEN al.action LIKE '%Failed login%' OR al.action LIKE '%Permanent lock%' THEN 'danger'
                WHEN al.action LIKE '%FAAS approval%' OR al.action LIKE '%pending%' THEN 'warning'
                WHEN al.action LIKE '%Payment processed%' OR al.action LIKE '%completed%' THEN 'success'
                WHEN al.action LIKE '%New%' OR al.action LIKE '%created%' THEN 'info'
                ELSE 'default'
            END as notification_type
        FROM activity_log al
        LEFT JOIN users u ON al.user_id = u.user_id
        LEFT JOIN notification_reads nr 
            ON al.log_id = nr.log_id AND nr.user_id = ?
        WHERE $where_clause
        ORDER BY al.log_time DESC
        LIMIT 20
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    $query = "
        SELECT 
            al.log_id,
            al.action,
            al.log_time,
            u.first_name,
            u.last_name,
            NULL as read_at,
            CASE
                WHEN al.action LIKE '%Failed login%' OR al.action LIKE '%Permanent lock%' THEN 'danger'
                WHEN al.action LIKE '%FAAS approval%' OR al.action LIKE '%pending%' THEN 'warning'
                WHEN al.action LIKE '%Payment processed%' OR al.action LIKE '%completed%' THEN 'success'
                WHEN al.action LIKE '%New%' OR al.action LIKE '%created%' THEN 'info'
                ELSE 'default'
            END as notification_type
        FROM activity_log al
        LEFT JOIN users u ON al.user_id = u.user_id
        WHERE $where_clause
        ORDER BY al.log_time DESC
        LIMIT 20
    ";

    $result = $conn->query($query);
}

$notifications = [];

while ($notif = $result->fetch_assoc()) {

    $action = $notif['action'];

    // TITLE / DESCRIPTION PARSER
    $title = 'System Activity';
    $description = substr($action, 0, 50) . (strlen($action) > 50 ? '...' : '');

    if (strpos($action, 'Failed login') !== false) {
        $title = 'Security Alert';
        $description = 'Failed login attempt detected';
    } elseif (strpos($action, 'Permanent lock') !== false) {
        $title = 'Account Locked';
        $description = 'IP address permanently locked';
    } elseif (strpos($action, 'New user registration') !== false) {
        $title = 'New User';
        $description = 'New user account registered';
    } elseif (strpos($action, 'FAAS approval') !== false) {
        $title = 'FAAS Approval';
        $description = 'FAAS approval pending review';
    } elseif (strpos($action, 'Tax declaration') !== false) {
        $title = 'Tax Declaration';
        $description = 'Tax declaration updated';
    } elseif (strpos($action, 'Payment processed') !== false) {
        $title = 'Payment';
        $description = 'Payment processed successfully';
    } elseif (strpos($action, 'Transaction created') !== false) {
        $title = 'New Transaction';
        $description = 'New transaction created';
    } elseif (strpos($action, 'Papers Received') !== false) {
        $title = 'Papers Received';
        $description = 'Client papers received';
    } elseif (strpos($action, 'Document Uploaded') !== false) {
        $title = 'Document Upload';
        $description = 'New document uploaded';
    } elseif (strpos($action, 'Printed Property Report') !== false) {
        $title = 'Report Printed';
        $description = 'Property report generated';
    }

    // TIME AGO
    $time_diff = time() - strtotime($notif['log_time']);
    if ($time_diff < 60) $time_ago = 'Just now';
    elseif ($time_diff < 3600) $time_ago = floor($time_diff / 60) . 'm';
    elseif ($time_diff < 86400) $time_ago = floor($time_diff / 3600) . 'h';
    elseif ($time_diff < 604800) $time_ago = floor($time_diff / 86400) . 'd';
    else $time_ago = floor($time_diff / 604800) . 'w';

    // Format notification
    $notifications[] = [
        'id' => $notif['log_id'],
        'title' => $title,
        'description' => $description,
        'time' => $time_ago,
        'type' => $notif['notification_type'],
        'user' => $notif['first_name'] ? $notif['first_name'] . ' ' . $notif['last_name'] : 'System',
        'timestamp' => $notif['log_time'],
        'unread' => $notif['read_at'] ? false : true
    ];
}

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unread_count' => count($notifications)
]);
?>
