<?php
// Dummy data - replace with your database or POSTed data
$receiptList = [
    ["R-001", "OR-2024-101", "Juan Dela Cruz", "2025-02-18 10:32 AM", 1500],
    ["R-002", "OR-2024-102", "Maria Santos", "2025-02-18 01:20 PM", 2300],
    ["R-003", "OR-2024-103", "Pedro Lopez", "2025-02-19 09:50 AM", 900],
];

$totalFee = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt History</title>
    <style>
        @page {
            size: landscape; /* Landscape mode for short bond paper */
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 12px;
            font-size: 12px;
            word-wrap: break-word;
        }

        th {
            text-align: left;
        }

        tfoot td {
            font-weight: bold;
        }

        .footer-info {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
        }

        @media print {
            .footer-info {
                position: fixed;
            }
        }
    </style>
</head>
<body>

<h2>RECEIPT HISTORY</h2>

<table>
    <thead>
        <tr>
            <th>Receipt #</th>
            <th>OR #</th>
            <th>Owner</th>
            <th>Date & Time</th>
            <th>Fee (₱)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($receiptList as $r): 
            $totalFee += $r[4];
        ?>
        <tr>
            <td><?= $r[0] ?></td>
            <td><?= $r[1] ?></td>
            <td><?= $r[2] ?></td>
            <td><?= $r[3] ?></td>
            <td><?= number_format($r[4],2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right">Total Collection:</td>
            <td><?= number_format($totalFee,2) ?></td>
        </tr>
    </tfoot>
</table>

<div class="footer-info">
    Printed on: <?= date("F d, Y h:i A") ?><br>
    Printed by: <strong>AdminUser</strong>
</div>

<script>
    window.print();
</script>

</body>
</html>
