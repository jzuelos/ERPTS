<?php
require_once "../database.php"; // your DB connection

$conn = Database::getInstance();

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Fetch transaction
    $sql = "SELECT * FROM transactions 
            WHERE transaction_id = '$id' OR transaction_code = '$id' 
            LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
    } else {
        die("<h2 style='text-align:center;color:red;'>Transaction not found!</h2>");
    }

    // Fetch logs
    $logs_sql = "SELECT * FROM transaction_logs 
                 WHERE transaction_id = '{$transaction['transaction_id']}' 
                 ORDER BY created_at ASC";
    $logs_result = $conn->query($logs_sql);

    $logs = [];
    if ($logs_result && $logs_result->num_rows > 0) {
        while ($row = $logs_result->fetch_assoc()) {
            $logs[strtolower(trim($row['action']))] = $row['created_at']; // map action => datetime
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
    <style>
        /* Basic timeline coloring */
        .timeline-step { margin: 15px 0; padding: 10px; border-left: 4px solid #ccc; position: relative; }
        .timeline-step .step-circle { width: 15px; height: 15px; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .timeline-step.completed { border-color: green; }
        .timeline-step.completed .step-circle { background: green; }
        .timeline-step.active { border-color: orange; }
        /* 🔶 Active = orange with pulse */
        .timeline-step.active:before {
            content: '';
            position: absolute;
            left: -11px; top: 15px;
            width: 15px; height: 15px;
            border-radius: 50%;
            background-color: orange;
            box-shadow: 0 0 0 2px orange;
            animation: pulse 1.5s infinite;
        }
        .timeline-step.upcoming { border-color: #aaa; }
        .timeline-step.upcoming:before {
            content: '';
            position: absolute;
            left: -11px; top: 15px;
            width: 15px; height: 15px;
            border-radius: 50%;
            background: #aaa;
        }
        .timeline-step.completed:before {
            content: '';
            position: absolute;
            left: -11px; top: 15px;
            width: 15px; height: 15px;
            border-radius: 50%;
            background: green;
        }
        .step-title { display: inline-block; font-weight: bold; }
        .step-date { margin-left: 25px; color: #555; font-size: 14px; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255,165,0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255,165,0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255,165,0, 0); }
        }
    </style>
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

$currentStatus = strtolower(trim($transaction['status']));

// Loop through statuses
foreach ($allStatuses as $i => $statusName):
    $normalized = strtolower($statusName);

    // Default
    $class = "upcoming";
    $dateToShow = "";

    if ($i === 0) {
        // First status: always completed (created_at)
        $class = "completed";
        $dateToShow = date("m-d-Y h:i A", strtotime($transaction['created_at']));
    } else {
        if ($currentStatus === $normalized) {
            $class = "active"; // current status = orange (see CSS)
        } elseif (
            array_search($currentStatus, array_map('strtolower', $allStatuses)) > $i
        ) {
            $class = "completed"; // past statuses = green
        }

        // Date from logs if available
        if (isset($logs[$normalized])) {
            $dateToShow = date("m-d-Y h:i A", strtotime($logs[$normalized]));
        }
    }
?>
    <div class="timeline-step <?php echo $class; ?>">
        <div class="step-title"><?php echo htmlspecialchars($statusName); ?></div>
        <div class="step-date"><?php echo $dateToShow; ?></div>
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
                    <span class="detail-value"><?php echo date("m-d-Y h:i A", strtotime($transaction['created_at'])); ?></span>
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
