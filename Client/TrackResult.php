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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="result.css">

</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <a href="Track.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h1>Track and Trace > Result</h1>
        </div>

        <div class="content-wrapper">
            <!-- Transaction Code -->
            <div class="transaction-code-box">
                <div class="label">Transaction Code:</div>
                <div class="code"><?php echo htmlspecialchars($transaction['transaction_code']); ?></div>
            </div>

            <!-- Timeline -->
            <div class="timeline-container">
                <?php
                // Define your fixed flow of statuses
                $allStatuses = [
                    "Transaction Requested",
                    "Pending",
                    "Processing Paper",
                    "Completed",
                    "Received"
                ];

                // Icon mapping
                $statusIcons = [
                    "transaction requested" => "fa-file-alt",
                    "pending" => "fa-clock",
                    "processing paper" => "fa-cog",
                    "completed" => "fa-check-circle",
                    "received" => "fa-handshake"
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
                            if (isset($receivedDate)) {
                                $dateToShow = date("m-d-Y h:i A", strtotime($receivedDate));
                            }
                        }
                    }

                    // If source is 'received_papers', make everything green
                    if ($source == 'received_papers') {
                        $class = "completed";
                    }

                    $icon = isset($statusIcons[$normalized]) ? $statusIcons[$normalized] : 'fa-circle';
                ?>
                    <div class="timeline-step <?php echo $class; ?>">
                        <div class="dot">
                            <i class="fas <?php echo $icon; ?>"></i>
                        </div>
                        <div class="step-title"><?php echo htmlspecialchars($statusName); ?></div>
                        <div class="step-date"><?php echo $dateToShow; ?></div>
                        <?php
                        if ($normalized === 'pending' && $class === 'active'):
                            // Calculate estimated completion (7 days from creation)
                            $estimatedDate = date("M d, Y", strtotime($transaction['created_at'] . ' +7 days'));
                        ?>
                            <div class="step-content">
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
                            <div class="step-content">
                                Your documents are currently being processed by our team.<br>
                                <strong>Estimated completion:</strong> <?php echo $estimatedDate; ?>
                                <?php if ($remainingDays > 0): ?>
                                    (approximately <?php echo $remainingDays; ?> <?php echo $remainingDays == 1 ? 'day' : 'days'; ?> remaining)
                                <?php else: ?>
                                    (processing may be completed soon)
                                <?php endif; ?>
                            </div>
                        <?php elseif ($normalized === 'completed' && $class === 'completed' && $source !== 'received_papers'): ?>
                            <div class="step-content">
                                Document processing has been completed. Your papers are ready for pickup at our office during business hours.
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Track New ID Button -->
            <a href="Track.php" class="track-new-btn">
                <i class="fas fa-search"></i> Track New ID
            </a>

            <!-- Transaction Details -->
            <div class="details-section">
                <h2>Transaction Details</h2>
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Owner Name</span>
                        <span class="detail-value">
                            <?php 
                            if ($source == 'transactions') {
                                echo htmlspecialchars($transaction['name']);
                            } else {
                                echo htmlspecialchars($transaction['client_name']);
                            }
                            ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Contact Number</span>
                        <span class="detail-value"><?php echo htmlspecialchars($transaction['contact_number']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date Started</span>
                        <span class="detail-value"><?php echo date("m-d-Y h:i A", strtotime($transaction['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Current Status -->
            <div class="status-section">
                <div class="status-title">Current Status</div>
                <div class="status-badge <?php echo strtolower($transaction['status']); ?>">
                    <?php echo htmlspecialchars($transaction['status']); ?>
                </div>
                <div class="status-value">
                    <?php 
                    if ($source == 'transactions') {
                        echo htmlspecialchars($transaction['description']);
                    } else {
                        echo htmlspecialchars($transaction['notes']);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>