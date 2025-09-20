<?php
require_once "../database.php"; // your DB connection

$conn = Database::getInstance();

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Fetch transaction
    $sql = "SELECT * FROM transactions WHERE transaction_id = '$id' OR transaction_code = '$id' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
    } else {
        die("<h2 style='text-align:center;color:red;'>Transaction not found!</h2>");
    }

    // Fetch logs
    $logs_sql = "SELECT * FROM transaction_logs WHERE transaction_id = '{$transaction['transaction_id']}' ORDER BY created_at ASC";
    $logs_result = $conn->query($logs_sql);

    $logs = [];
    if ($logs_result && $logs_result->num_rows > 0) {
        while ($row = $logs_result->fetch_assoc()) {
            $logs[] = $row;
        }
    }
} else {
    die("<h2 style='text-align:center;color:red;'>Invalid Request!</h2>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track and Trace Result</title>
    <link rel="stylesheet" href="result.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Track and Trace &gt; Result</h1>
        </div>
        
        <div class="order-id">
            ORDER ID: <?php echo htmlspecialchars($transaction['transaction_code']); ?>
        </div>
        
<?php
// Define your fixed flow of statuses
$allStatuses = [
    "Transaction Requested",
    "Pending",
    "Processing Paper",
    "Completed",
    "Received"
];

// Extract reached actions from logs
$reachedStatuses = array_map(function($log) {
    return strtolower(trim($log['action']));
}, $logs);

// Loop through statuses
foreach ($allStatuses as $i => $statusName):
    $normalized = strtolower($statusName);

    // Default flags
    $isReached = false;
    $isActive = false;

    if ($i === 0) {
        // First status: always completed (green)
        $isReached = true;
        $isActive = (count($reachedStatuses) === 0); 
    } else {
        // For other statuses, check logs
        $isReached = in_array($normalized, $reachedStatuses);
        $isActive = ($isReached && $i === count($reachedStatuses));
    }
?>
    <div class="timeline-step 
        <?php 
            if ($isActive) echo ' active'; 
            elseif ($isReached) echo ' completed'; 
            else echo ' upcoming'; 
        ?>">
        
        <div class="step-circle"></div>
        <div class="step-title"><?php echo htmlspecialchars($statusName); ?></div>
        <div class="step-date">
            <?php 
            if ($i === 0) {
                // Always show transaction created_at date
                echo date("m-d-Y", strtotime($transaction['created_at']));
            } elseif ($isReached) {
                foreach ($logs as $log) {
                    if (strtolower(trim($log['action'])) === $normalized) {
                        echo date("m-d-Y", strtotime($log['created_at']));
                        break;
                    }
                }
            }
            ?>
        </div>
    </div>
<?php endforeach; ?>

        
        <a href="Track.php" class="track-new-btn">Track New ID</a>
        
        <div class="details-section">
            <h2>Transaction Details</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Owner Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($transaction['name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact Number:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($transaction['contact_number']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date Started:</span>
                    <span class="detail-value"><?php echo date("m-d-Y", strtotime($transaction['created_at'])); ?></span>
                </div>
            </div>
        </div>
        
        <div class="status-section">
            <div class="status-title">Current Status</div>
            <div class="status-value"><?php echo htmlspecialchars($transaction['status']); ?></div>
            <div class="status-value"><?php echo htmlspecialchars($transaction['description']); ?></div>
        </div>
    </div>
</body>
</html>
