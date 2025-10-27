<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Activity Log Report</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      font-size: 12px;
      color: #000;
      margin: 1cm;
    }

    h1 {
      text-align: center;
      font-size: 20px;
      margin-bottom: 20px;
      line-height: 1.4;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 11.5px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
      vertical-align: middle;
    }

    th {
      text-align: center;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    /* Footer fixed at bottom right */
    .footer {
      position: fixed;
      bottom: 15px;
      right: 30px;
      text-align: right;
      font-size: 13px;
    }

    @media print {
      @page {
        size: A4 landscape; /* Horizontal layout */
        margin: 1cm;
      }

      body::before {
        content: "";
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
       /* background: url('images/Seal.png') no-repeat center; */
        background-size: 400px;
        opacity: 0.08;
        width: 100%;
        height: 100%;
        z-index: -1;
        pointer-events: none;
      }

      .footer {
        position: fixed;
        bottom: 1cm;
        right: 1cm;
      }

      thead {
        display: table-header-group; /* Repeat header on each page */
      }
    }
  </style>
</head>

<body>
  <?php
  // Dummy data for testing
  date_default_timezone_set('Asia/Manila');
  $username = "AdminUser";

  $dummy_logs = [
    ['username' => 'AdminUser', 'action' => 'Logged in to the system', 'timestamp' => '2025-10-26 09:30:12'],
    ['username' => 'Clerk01', 'action' => 'Printed Property Report (Classification: Residential)', 'timestamp' => '2025-10-26 10:05:48'],
    ['username' => 'Assessor', 'action' => 'Updated land record for parcel #CN-1023', 'timestamp' => '2025-10-26 11:22:09'],
    ['username' => 'AdminUser', 'action' => 'Deleted duplicate tax declaration record', 'timestamp' => '2025-10-26 13:15:33'],
    ['username' => 'Inspector', 'action' => 'Generated Valuation Report for Camarines Norte', 'timestamp' => '2025-10-26 14:45:01'],
    ['username' => 'Clerk02', 'action' => 'Logged out', 'timestamp' => '2025-10-26 15:00:00'],
  ];
  ?>

  <h1>
    ELECTRONIC PROPERTY TAX SYSTEM <br>
    <span style="font-size:17px;">ACTIVITY LOG</span>
  </h1>

  <table>
    <thead>
      <tr>
        <th style="width:5%;">#</th>
        <th style="width:15%;">Username</th>
        <th style="width:60%;">Action</th>
        <th style="width:20%;">Date & Time</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($dummy_logs as $log): ?>
        <tr>
          <td style="text-align:center;"><?= $i++ ?></td>
          <td><?= htmlspecialchars($log['username']) ?></td>
          <td><?= nl2br(htmlspecialchars($log['action'])) ?></td>
          <td style="text-align:center;">
            <?= date("M d, Y h:i A", strtotime($log['timestamp'])) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="footer">
    <b>PRINTED BY:</b> <?= htmlspecialchars($username) ?><br>
    <b>Date & Time:</b> <?= date("F d, Y h:i A") ?>
  </div>

  <script>
    // Automatically open print dialog
    window.onload = () => {
      setTimeout(() => window.print(), 500);
    };
  </script>
</body>

</html>
