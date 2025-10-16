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
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #4caf50;
            --gold: #ffc107;
            --light-gray: #f5f5f5;
            --border-gray: #e0e0e0;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .container {
            max-width: 1100px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 0;
            overflow: hidden;
        }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--light-green) 100%);
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .back-btn:hover {
            background: white;
            color: var(--primary-green);
        }

        /* Content area */
        .content-wrapper {
            padding: 40px;
        }

        /* Transaction code box */
        .transaction-code-box {
            background: var(--light-gray);
            border-left: 4px solid var(--primary-green);
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 40px;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease forwards;
            animation-delay: 0.1s;
        }

        .transaction-code-box .label {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .transaction-code-box .code {
            color: var(--text-dark);
            font-size: 22px;
            font-weight: 700;
        }

        /* Timeline */
        .timeline-container {
            margin: 40px 0;
            position: relative;
        }

        .timeline-step {
            position: relative;
            padding-left: 60px;
            margin-bottom: 35px;
            min-height: 50px;
            opacity: 0;
            animation: slideInFromLeft 0.6s ease forwards;
        }

        .timeline-step:nth-child(1) { animation-delay: 0.1s; }
        .timeline-step:nth-child(2) { animation-delay: 0.2s; }
        .timeline-step:nth-child(3) { animation-delay: 0.3s; }
        .timeline-step:nth-child(4) { animation-delay: 0.4s; }
        .timeline-step:nth-child(5) { animation-delay: 0.5s; }

        .timeline-step:hover {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }

        /* Timeline vertical line */
        .timeline-step::before {
            content: "";
            position: absolute;
            left: 19px;
            top: 30px;
            width: 3px;
            height: 0;
            background: var(--border-gray);
            animation: growLine 0.8s ease forwards;
        }

        .timeline-step:nth-child(1)::before { animation-delay: 0.2s; }
        .timeline-step:nth-child(2)::before { animation-delay: 0.3s; }
        .timeline-step:nth-child(3)::before { animation-delay: 0.4s; }
        .timeline-step:nth-child(4)::before { animation-delay: 0.5s; }
        .timeline-step:nth-child(5)::before { animation-delay: 0.6s; }

        .timeline-step:last-child::before {
            display: none;
        }

        /* Timeline dot */
        .timeline-step .dot {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            border: 4px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transform: scale(0);
            animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        .timeline-step:nth-child(1) .dot { animation-delay: 0.2s; }
        .timeline-step:nth-child(2) .dot { animation-delay: 0.3s; }
        .timeline-step:nth-child(3) .dot { animation-delay: 0.4s; }
        .timeline-step:nth-child(4) .dot { animation-delay: 0.5s; }
        .timeline-step:nth-child(5) .dot { animation-delay: 0.6s; }

        .timeline-step .dot i {
            color: white;
            font-size: 16px;
        }

        /* Completed status */
        .timeline-step.completed .dot {
            background: var(--light-green);
        }

        .timeline-step.completed::before {
            background: var(--light-green);
        }

        .timeline-step.completed .step-title {
            color: var(--light-green);
            font-weight: 600;
        }

        /* Active status */
        .timeline-step.active .dot {
            background: var(--gold);
            animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards, 
                       pulse 2s ease-in-out 0.7s infinite;
        }

        .timeline-step.active .step-title {
            color: var(--gold);
            font-weight: 700;
        }

        .timeline-step.active .step-content {
            background: #fff9e6;
            border-left: 3px solid var(--gold);
        }

        /* Upcoming status */
        .timeline-step.upcoming {
            opacity: 0.5;
        }

        .timeline-step.upcoming .dot {
            background: #e0e0e0;
        }

        /* Step content */
        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .step-date {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .step-content {
            background: var(--light-gray);
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 12px;
            font-size: 14px;
            line-height: 1.6;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease forwards;
            animation-delay: 0.5s;
        }

        .step-content strong {
            color: var(--text-dark);
        }

        /* Track New ID button */
        .track-new-btn {
            display: inline-block;
            background: var(--primary-green);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            margin: 20px 0 40px 0;
        }

        .track-new-btn:hover {
            background: var(--light-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        /* Details section */
        .details-section {
            background: var(--light-gray);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease forwards;
            animation-delay: 0.8s;
        }

        .details-section h2 {
            color: var(--primary-green);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-gray);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 600;
        }

        /* Current Status section */
        .status-section {
            background: white;
            border: 2px solid var(--border-gray);
            padding: 25px 30px;
            border-radius: 12px;
            opacity: 0;
            animation: fadeSlideUp 0.6s ease forwards;
            animation-delay: 0.9s;
        }

        .status-title {
            color: var(--primary-green);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .status-value {
            color: var(--text-dark);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-badge.completed {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.received {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
                transform: scale(1.1);
            }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes growLine {
            from {
                height: 0;
            }
            to {
                height: calc(100% + 35px);
            }
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 20px 25px;
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .content-wrapper {
                padding: 25px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .timeline-step {
                padding-left: 50px;
            }

            .timeline-step .dot {
                width: 35px;
                height: 35px;
            }

            .timeline-step::before {
                left: 16px;
            }
        }
    </style>
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