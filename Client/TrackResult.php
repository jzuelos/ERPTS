<?php
require_once "../database.php"; // your DB connection

$conn = Database::getInstance();

// Check if 'source' is set to determine where the data is coming from
$source = isset($_GET['source']) ? $_GET['source'] : 'transactions';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // First, check if the transaction is found in the transactions table
    $sqlTransaction = "SELECT * FROM transactions WHERE transaction_code = '$id' LIMIT 1";
    $resultTransaction = $conn->query($sqlTransaction);

    // If transaction found in the transactions table, use it
    if ($resultTransaction && $resultTransaction->num_rows > 0) {
        $transaction = $resultTransaction->fetch_assoc();
        $source = 'transactions'; // Mark source as transactions
    } else {
        // If not found in transactions, check in received_papers table
        $sqlReceivedPapers = "SELECT * FROM received_papers WHERE transaction_code = '$id' LIMIT 1";
        $resultReceivedPapers = $conn->query($sqlReceivedPapers);

        if ($resultReceivedPapers && $resultReceivedPapers->num_rows > 0) {
            // Fetch the received_papers details
            $receivedPaper = $resultReceivedPapers->fetch_assoc();
            $transaction = $receivedPaper; // Directly use received_papers data
            $source = 'received_papers'; // Mark source as received_papers
            $receivedDate = $receivedPaper['received_date']; // Store the received date
        } else {
            die("<h2 style='text-align:center;color:red;'>Transaction not found!</h2>");
        }
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
        /* Timeline container */
        .timeline-step {
            position: relative;
            padding-left: 50px;
            margin: 30px 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        /* Vertical line */
        .timeline-step::before {
            content: "";
            position: absolute;
            top: 0;
            left: 24px;
            width: 3px;
            height: 100%;
            background: #e0e0e0;
            z-index: 1;
        }

        /* Step circle */
        .timeline-step::after {
            content: "";
            position: absolute;
            top: 0;
            left: 15px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #bbb;
            border: 3px solid #fff;
            z-index: 2;
            box-shadow: 0 0 0 2px #bbb;
        }

        /* Completed steps */
        .timeline-step.completed::before {
            background: #4caf50;
        }

        .timeline-step.completed::after {
            background: #4caf50;
            box-shadow: 0 0 0 2px #4caf50;
        }

        /* Active step */
        .timeline-step.active::before {
            background: #FFD700;
        }

        .timeline-step.active::after {
            background: #FFD700;
            box-shadow: 0 0 0 5px rgba(255, 215, 0, 0.5);
            animation: pulse 1.5s infinite;
        }

        /* Upcoming step */
        .timeline-step.upcoming::before {
            background: #ccc;
        }

        .timeline-step.upcoming::after {
            background: #ccc;
            box-shadow: 0 0 0 2px #ccc;
        }

        /* Text beside step */
        .step-title {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .step-date {
            display: block;
            margin-left: 5px;
            color: #777;
            font-size: 13px;
        }

        /* Received date */
        .received-date-title {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .received-date-value {
            margin-left: 10px;
            color: #777;
            font-size: 14px;
        }

                .status-section {
            font-family: "Segoe UI", Tahoma, sans-serif;
            margin: 15px 0;
        }

        .status-title {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 16px;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-bullet {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4caf50; /* Green dot (can be changed per status) */
            flex-shrink: 0;
        }

        .status-text {
            font-size: 15px;
            color: #333;
        }


        /* Pulse animation for active */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(255, 215, 0, 0);
            }

            /* Step circle */
            .timeline-step::after {
                content: "";
                position: absolute;
                top: 0;
                left: 15px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #bbb;
                border: 3px solid #fff;
                z-index: 2;
                box-shadow: 0 0 0 2px #bbb;
            }

            /* Completed steps */
            .timeline-step.completed::before {
                background: #4caf50;
            }
            .timeline-step.completed::after {
                background: #4caf50;
                box-shadow: 0 0 0 2px #4caf50;
            }

            /* Active step */
            .timeline-step.active::before {
                background: #FFD700;
            }
            .timeline-step.active::after {
                background: #FFD700;
                box-shadow: 0 0 0 5px rgba(255,215,0,0.5);
                animation: pulse 1.5s infinite;
            }

            /* Upcoming step */
            .timeline-step.upcoming::before {
                background: #ccc;
            }
            .timeline-step.upcoming::after {
                background: #ccc;
                box-shadow: 0 0 0 2px #ccc;
            }

            /* Text beside step */
            .step-title {
                font-weight: 600;
                font-size: 16px;
                color: #333;
            }

            .step-date {
                display: block;
                margin-left: 5px;
                color: #777;
                font-size: 13px;
            }

            /* Messages under step */
            .pending-message,
            .processing-message,
            .completion-message {
                margin-top: 10px;
                margin-left: 5px;
                padding: 10px 12px;
                border-radius: 6px;
                font-size: 14px;
                line-height: 1.5;
            }

            .pending-message {
                background: #fff8e1;
                border-left: 4px solid #ff9800;
                color: #e65100;
            }

            .processing-message {
                background: #e3f2fd;
                border-left: 4px solid #2196f3;
                color: #0d47a1;
            }

            .completion-message {
                background: #e8f5e9;
                border-left: 4px solid #4caf50;
                color: #1b5e20;
                font-style: italic;
            }

        
            @keyframes pulse {
                0% { box-shadow: 0 0 0 0 rgba(255,215,0,0.7); }
                70% { box-shadow: 0 0 0 15px rgba(255,215,0,0); }
                100% { box-shadow: 0 0 0 0 rgba(255,215,0,0); }
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Track and Trace &gt; Result</h1>
        </div>

        <div class="order-id">
            Transaction Code: <?php echo htmlspecialchars($transaction['transaction_code']); ?>
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

        // Normalize database status for timeline logic
        $dbStatus = strtolower(trim($transaction['status']));
        if ($dbStatus === 'in progress') {
            $currentStatus = 'processing paper';
        } else {
            $currentStatus = $dbStatus;
        }

        // Find index of current status in $allStatuses
        $allNormalized = array_map('strtolower', $allStatuses);
        $currentIndex = array_search($currentStatus, $allNormalized);

        foreach ($allStatuses as $i => $statusName):
            $normalized = strtolower($statusName);
            $class = "upcoming";
            $dateToShow = "";

            if ($i === 0) {
                // First status always green
                $class = "completed";
                $dateToShow = date("m-d-Y h:i A", strtotime($transaction['created_at']));
            } else {
                if ($i < $currentIndex) {
                    $class = "completed"; // all previous statuses green
                } elseif ($i === $currentIndex) {
                    if ($normalized === 'completed' || $normalized === 'received') {
                        $class = "completed"; // these turn green immediately when current
                    } else {
                        $class = "active"; // pending/in progress yellow
                    }
                } else {
                    $class = "upcoming"; // grey
                }

                // Date from logs if available
                if (isset($logs[$normalized])) {
                    $dateToShow = date("m-d-Y h:i A", strtotime($logs[$normalized]));
                }

                // If the status is "Received", handle it separately and show received date
                if ($normalized === 'received') {
                    // You might want to store the received date in the received_papers table
                    if (isset($receivedDate)) {
                        $dateToShow = date("m-d-Y h:i A", strtotime($receivedDate));
                    }
                }
            }

            // If source is 'received_papers', make everything green
            if ($source == 'received_papers') {
                $class = "completed";
            }
        ?>
            <div class="timeline-step <?php echo $class; ?>">
                <div class="step-title"><?php echo htmlspecialchars($statusName); ?></div>
                <div class="step-date"><?php echo $dateToShow; ?></div>
                <?php
                if ($normalized === 'pending' && $class === 'active'):
                    // Calculate estimated completion (7 days from creation)
                    $estimatedDate = date("M d, Y", strtotime($transaction['created_at'] . ' +7 days'));
                ?>
                    <div class="pending-message">
                        Your transaction is currently in queue and will be reviewed by our staff shortly.<br>
                        <strong>Estimated completion:</strong> <?php echo $estimatedDate; ?> (within 7 business days)
                    </div>
                <?php elseif ($normalized === 'processing paper' && $class === 'active'):
                    // Calculate remaining time based on creation date
                    $createdDate = strtotime($transaction['created_at']);
                    $currentDate = time();
                    $daysPassed = floor(($currentDate - $createdDate) / (60 * 60 * 24));
                    $remainingDays = max(0, 7 - $daysPassed);
                    $estimatedDate = date("M d, Y", strtotime($transaction['created_at'] . ' +7 days'));
                ?>
                    <div class="processing-message">
                        Your documents are currently being processed by our team.<br>
                        <strong>Estimated completion:</strong> <?php echo $estimatedDate; ?>
                        <?php if ($remainingDays > 0): ?>
                            (approximately <?php echo $remainingDays; ?> <?php echo $remainingDays == 1 ? 'day' : 'days'; ?> remaining)
                        <?php else: ?>
                            (processing may be completed soon)
                        <?php endif; ?>
                    </div>
                <?php elseif ($normalized === 'completed' && $class === 'completed' && $source !== 'received_papers'): ?>
                    <div class="completion-message">
                        Document processing has been completed. Your papers are ready for pickup at our office during business hours.
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>


        <a href="Track.php" class="track-new-btn">Track New ID</a>

        <div class="details-section">
            <h2>Transaction Details</h2>
            <div class="details-grid">
                <?php if ($source == 'transactions'): ?>
                    <div class="detail-item">
                        <span class="detail-label">Owner Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($transaction['name']); ?></span>
                    </div>
                <?php elseif ($source == 'received_papers'): ?>
                    <div class="detail-item">
                        <span class="detail-label">Owner Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($transaction['client_name']); ?></span>
                    </div>
                <?php endif; ?>

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
                <div class="status-item">
                    <span class="status-bullet"></span>
                    <span class="status-text">
                        <?php echo htmlspecialchars($transaction['status']); ?> – 
                        <?php echo htmlspecialchars($transaction['description']); ?>
                    </span>
                </div>
            </div>

    </div>
</body>

</html>