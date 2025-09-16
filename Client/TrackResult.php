<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="result.css">
    <title>Track and Trace Result</title>

</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Track and Trace &gt; Result</h1>
        </div>
        
        <div class="order-id">
            ORDER ID: 345193906092743
        </div>
        
        <div class="timeline">
            <div class="timeline-step completed">
                <div class="step-title">Transaction Requested</div>
                <div class="step-date">08-21-2024</div>
            </div>
            
            <div class="timeline-step completed">
                <div class="step-title">Pending</div>
                <div class="step-date">08-21-2024</div>
            </div>
            
            <div class="timeline-step completed">
                <div class="step-title">Processing Paper</div>
                <div class="step-date">08-21-2024</div>
            </div>
            
            <div class="timeline-step completed">
                <div class="step-title">Completed</div>
                <div class="step-date">09-7-2024</div>
            </div>
            
            <div class="timeline-step active">
                <div class="step-title">Received</div>
                <div class="step-date">09-10-2024</div>
            </div>
        </div>
        
        <a href="Track.php" class="track-new-btn">Track New ID</a>
        
        <div class="details-section">
            <h2>Transaction Details</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Owner Name:</span>
                    <span class="detail-value">James O. Gacho</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact Number:</span>
                    <span class="detail-value">09123456789</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date Started:</span>
                    <span class="detail-value">8-21-2024</span>
                </div>
            </div>
        </div>
        
        <div class="status-section">
            <div class="status-title">Status of Transaction</div>
            <div class="status-value">Transferred to Taxmapping Division</div>
            <div class="status-value">To receive in Assessment Division</div>
        </div>
    </div>
</body>
</html>