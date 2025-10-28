<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

include 'database.php';
$conn = Database::getInstance();

// Get the current user's name for the footer
$user_id = $_SESSION['user_id'];
$stmt_user = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS fullname FROM users WHERE user_id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$current_user = $stmt_user->get_result()->fetch_assoc();
$username = $current_user['fullname'] ?? 'Unknown User';

// Get filter parameters from session or query string
$start_date = $_GET['start_date'] ?? $_SESSION['print_start_date'] ?? '';
$end_date = $_GET['end_date'] ?? $_SESSION['print_end_date'] ?? '';
$log_type = $_GET['log_type'] ?? $_SESSION['print_log_type'] ?? 'activity';

// Store in session for future use
if (isset($_GET['start_date'])) $_SESSION['print_start_date'] = $start_date;
if (isset($_GET['end_date'])) $_SESSION['print_end_date'] = $end_date;
if (isset($_GET['log_type'])) $_SESSION['print_log_type'] = $log_type;

// Build WHERE clause based on log type
$where = [];
$params = [];

if ($start_date) {
  $where[] = "DATE(a.log_time) >= ?";
  $params[] = $start_date;
}
if ($end_date) {
  $where[] = "DATE(a.log_time) <= ?";
  $params[] = $end_date;
}

// Filter based on log type
if ($log_type === 'login') {
  $where[] = "(a.action LIKE 'User logged in%' OR a.action LIKE 'Failed login attempt%' OR a.action = 'Logged out of the system')";
} else {
  $where[] = "a.action NOT LIKE 'User logged in%' AND a.action NOT LIKE 'Failed login attempt%' AND a.action != 'Logged out of the system'";
}

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Fetch all matching logs (no pagination for print)
$sql = "SELECT a.log_id, a.action, a.log_time, 
              CONCAT(u.first_name, ' ', u.last_name) AS fullname,
              u.email, u.user_type, u.contact_number
        FROM activity_log a
        JOIN users u ON a.user_id = u.user_id
        $where_sql
        ORDER BY a.log_time DESC";

$stmt = $conn->prepare($sql);
if ($params) {
  $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Determine report title
$report_title = $log_type === 'login' ? 'LOGIN/LOGOUT ACTIVITY LOG' : 'ACTIVITY LOG';
?>

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
      margin-bottom: 10px;
      line-height: 1.4;
    }

    .date-range {
      text-align: center;
      font-size: 13px;
      margin-bottom: 20px;
      font-weight: 600;
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
      background-color: #e8e8e8;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    .no-data {
      text-align: center;
      padding: 20px;
      font-style: italic;
      color: #666;
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
        size: A4 landscape;
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
        display: table-header-group;
      }
    }
  </style>
</head>

<body>
  <h1>
    ELECTRONIC PROPERTY TAX SYSTEM <br>
    <span style="font-size:17px;"><?= htmlspecialchars($report_title) ?></span>
  </h1>

  <?php if ($start_date && $end_date): ?>
    <div class="date-range">
      Period: <?= date("F d, Y", strtotime($start_date)) ?> to <?= date("F d, Y", strtotime($end_date)) ?>
    </div>
  <?php elseif ($start_date): ?>
    <div class="date-range">
      From: <?= date("F d, Y", strtotime($start_date)) ?>
    </div>
  <?php elseif ($end_date): ?>
    <div class="date-range">
      Until: <?= date("F d, Y", strtotime($end_date)) ?>
    </div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th style="width:5%;">#</th>
        <th style="width:20%;">User</th>
        <th style="width:15%;">Role</th>
        <th style="width:40%;">Action</th>
        <th style="width:20%;">Date & Time</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($result->num_rows > 0):
        $i = 1; 
        while ($log = $result->fetch_assoc()): 
      ?>
        <tr>
          <td style="text-align:center;"><?= $i++ ?></td>
          <td><?= htmlspecialchars($log['fullname']) ?></td>
          <td><?= htmlspecialchars($log['user_type']) ?></td>
          <td><?= nl2br(htmlspecialchars($log['action'])) ?></td>
          <td style="text-align:center;">
            <?= date("M d, Y h:i A", strtotime($log['log_time'])) ?>
          </td>
        </tr>
      <?php 
        endwhile;
      else:
      ?>
        <tr>
          <td colspan="5" class="no-data">
            No activity logs found for the selected date range.
          </td>
        </tr>
      <?php endif; ?>
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