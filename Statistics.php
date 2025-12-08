<?php
session_start();

// Handle AJAX request for logging export activity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'log_export') {
  header('Content-Type: application/json');

  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
  }

  require_once 'database.php';

  try {
    $conn = Database::getInstance();

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data || !isset($data['user_id']) || !isset($data['chart_title']) || !isset($data['chart_type'])) {
      throw new Exception('Invalid request data');
    }

    $user_id = (int)$data['user_id'];
    $chart_title = $conn->real_escape_string($data['chart_title']);
    $chart_type = $conn->real_escape_string($data['chart_type']);

    $action = "Exported statistics chart\n";
    $action .= "• Chart Type: " . ucfirst(str_replace('_', ' ', $chart_type)) . "\n";
    $action .= "• Chart Title: {$chart_title}\n";
    $action .= "• Export Format: PNG Image\n";
    $action .= "• Export Time: " . date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $action);

    if ($stmt->execute()) {
      echo json_encode([
        'success' => true,
        'message' => 'Export activity logged successfully',
        'log_id' => $stmt->insert_id
      ]);
    } else {
      throw new Exception('Failed to insert log: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'error' => $e->getMessage()
    ]);
  }

  exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: index.php");
  exit;
}

$first_name = $_SESSION['first_name'] ?? 'Guest';

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ============================================
// PROPERTY STATISTICS
// ============================================
$query_owners = "SELECT COUNT(*) AS total FROM owners_tb";
$total_owners = $conn->query($query_owners)->fetch_assoc()['total'];

$query_properties = "SELECT COUNT(*) AS total FROM p_info WHERE is_active = 1";
$total_properties = $conn->query($query_properties)->fetch_assoc()['total'];

$query_land = "SELECT COUNT(*) AS total FROM land";
$total_land = $conn->query($query_land)->fetch_assoc()['total'];

$query_faas = "SELECT COUNT(*) AS total FROM faas";
$total_faas = $conn->query($query_faas)->fetch_assoc()['total'];

$query_municipalities = "SELECT COUNT(*) AS total FROM municipality WHERE m_status = 'Active'";
$total_municipalities = $conn->query($query_municipalities)->fetch_assoc()['total'];

$query_barangays = "SELECT COUNT(*) AS total FROM brgy WHERE status = 'Active'";
$total_barangays = $conn->query($query_barangays)->fetch_assoc()['total'];

// ============================================
// USER ACTIVITY STATISTICS
// ============================================
$query_users = "SELECT COUNT(*) AS total FROM users WHERE status = 1";
$total_users = $conn->query($query_users)->fetch_assoc()['total'];

$login_count = 0;
$logout_count = 0;
$create_count = 0;
$update_count = 0;
$delete_count = 0;

$query_activity = "SELECT action FROM activity_log";
$result_activity = $conn->query($query_activity);

if ($result_activity && $result_activity->num_rows > 0) {
  while ($row = $result_activity->fetch_assoc()) {
    $action = strtolower($row['action']);

    if (strpos($action, 'logged in') !== false) {
      $login_count++;
    } elseif (strpos($action, 'logged out') !== false) {
      $logout_count++;
    } elseif (strpos($action, 'created') !== false || strpos($action, 'added') !== false) {
      $create_count++;
    } elseif (strpos($action, 'updated') !== false) {
      $update_count++;
    } elseif (strpos($action, 'deleted') !== false || strpos($action, 'removed') !== false) {
      $delete_count++;
    }
  }
}

// ============================================
// TRANSACTION/AUDIT STATISTICS
// ============================================
$query_transactions = "SELECT COUNT(*) AS total FROM transactions";
$total_transactions = $conn->query($query_transactions)->fetch_assoc()['total'];

$query_pending = "SELECT COUNT(*) AS total FROM transactions WHERE status = 'Pending'";
$pending_transactions = $conn->query($query_pending)->fetch_assoc()['total'];

$query_in_progress = "SELECT COUNT(*) AS total FROM transactions WHERE status = 'In Progress'";
$in_progress_transactions = $conn->query($query_in_progress)->fetch_assoc()['total'];

// Fetch completed transactions from received_papers table
$query_completed = "SELECT COUNT(*) AS total FROM received_papers";
$completed_transactions = $conn->query($query_completed)->fetch_assoc()['total'];

$query_received = "SELECT COUNT(*) AS total FROM received_papers";
$total_received_papers = $conn->query($query_received)->fetch_assoc()['total'];

$query_certifications = "SELECT COUNT(*) AS total FROM print_certifications";
$total_certifications = $conn->query($query_certifications)->fetch_assoc()['total'];

// ============================================
// RECEIPT COLLECTION STATISTICS
// ============================================
// Monthly collection for current year
$receiptMonthlyQuery = "SELECT 
    MONTH(date_paid) as month,
    SUM(certification_fee) as total
FROM print_certifications
WHERE YEAR(date_paid) = YEAR(CURDATE())
GROUP BY MONTH(date_paid)
ORDER BY MONTH(date_paid)";

$receiptMonthlyResult = $conn->query($receiptMonthlyQuery);
$receiptMonthlyData = array_fill(1, 12, 0);
while ($row = $receiptMonthlyResult->fetch_assoc()) {
  $receiptMonthlyData[$row['month']] = (float)$row['total'];
}

// Yearly collection (last 5 years)
$receiptYearlyQuery = "SELECT 
    YEAR(date_paid) as year,
    SUM(certification_fee) as total
FROM print_certifications
WHERE YEAR(date_paid) >= YEAR(CURDATE()) - 4
GROUP BY YEAR(date_paid)
ORDER BY YEAR(date_paid)";

$receiptYearlyResult = $conn->query($receiptYearlyQuery);
$receiptYearlyLabels = [];
$receiptYearlyData = [];
while ($row = $receiptYearlyResult->fetch_assoc()) {
  $receiptYearlyLabels[] = $row['year'];
  $receiptYearlyData[] = (float)$row['total'];
}

// Total collection
$totalCollectionQuery = "SELECT SUM(certification_fee) as total FROM print_certifications";
$totalCollection = $conn->query($totalCollectionQuery)->fetch_assoc()['total'] ?? 0;

// ============================================
// MONTHLY TRENDS (Last 6 months)
// ============================================
$monthly_properties = [];
$monthly_transactions = [];
$monthly_logins = [];
$months_labels = [];

for ($i = 5; $i >= 0; $i--) {
  $month_start = date('Y-m-01', strtotime("-$i months"));
  $month_end = date('Y-m-t', strtotime("-$i months"));
  $month_label = date('M Y', strtotime("-$i months"));

  $months_labels[] = $month_label;

  $q = "SELECT COUNT(*) as count FROM p_info WHERE created_at BETWEEN '$month_start' AND '$month_end 23:59:59'";
  $monthly_properties[] = $conn->query($q)->fetch_assoc()['count'];

  $q = "SELECT COUNT(*) as count FROM transactions WHERE created_at BETWEEN '$month_start' AND '$month_end 23:59:59'";
  $monthly_transactions[] = $conn->query($q)->fetch_assoc()['count'];

  $q = "SELECT COUNT(*) as count FROM activity_log WHERE action LIKE '%Logged in%' AND log_time BETWEEN '$month_start' AND '$month_end 23:59:59'";
  $monthly_logins[] = $conn->query($q)->fetch_assoc()['count'];
}

// ============================================
// CLASSIFICATION BREAKDOWN
// ============================================
$classification_data = [];
$classification_labels = [];

$query_classifications = "
  SELECT classification, COUNT(*) as count 
  FROM land 
  GROUP BY classification 
  ORDER BY count DESC
";
$result_class = $conn->query($query_classifications);
if ($result_class) {
  while ($row = $result_class->fetch_assoc()) {
    $classification_labels[] = $row['classification'];
    $classification_data[] = (int)$row['count'];
  }
}

// ============================================
// TRANSACTION TYPE BREAKDOWN
// ============================================
$transaction_type_labels = [];
$transaction_type_data = [];

$query_types = "
  SELECT transaction_type, COUNT(*) as count 
  FROM transactions 
  WHERE transaction_type IS NOT NULL
  GROUP BY transaction_type 
  ORDER BY count DESC
  LIMIT 10
";
$result_types = $conn->query($query_types);
if ($result_types) {
  while ($row = $result_types->fetch_assoc()) {
    $transaction_type_labels[] = $row['transaction_type'];
    $transaction_type_data[] = (int)$row['count'];
  }
}

// Prepare JSON data for JavaScript
$property_stats = [
  'labels' => ['Owners', 'Properties', 'Land Records', 'FAAS', 'Municipalities', 'Barangays'],
  'data' => [$total_owners, $total_properties, $total_land, $total_faas, $total_municipalities, $total_barangays]
];

$user_activity_stats = [
  'labels' => ['Active Users', 'Logins', 'Logouts', 'Create Actions', 'Update Actions', 'Delete Actions'],
  'data' => [$total_users, $login_count, $logout_count, $create_count, $update_count, $delete_count]
];

$audit_stats = [
  'labels' => ['Total Transactions', 'Pending', 'In Progress', 'Completed', 'Received Papers', 'Certifications'],
  'data' => [$total_transactions, $pending_transactions, $in_progress_transactions, $completed_transactions, $total_received_papers, $total_certifications]
];

$monthly_trends = [
  'labels' => $months_labels,
  'properties' => $monthly_properties,
  'transactions' => $monthly_transactions,
  'logins' => $monthly_logins
];

$classification_breakdown = [
  'labels' => $classification_labels,
  'data' => $classification_data
];

$transaction_breakdown = [
  'labels' => $transaction_type_labels,
  'data' => $transaction_type_data
];

$receipt_collection_monthly = [
  'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  'data' => array_values($receiptMonthlyData)
];

$receipt_collection_yearly = [
  'labels' => $receiptYearlyLabels,
  'data' => $receiptYearlyData
];
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="ALog.css">
  <title>Electronic Real Property Tax System - Statistics</title>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>

  <main class="container my-5 flex-grow-1 d-flex justify-content-center">
    <div class="col-lg-10 col-md-11 col-sm-12">
      <div class="mb-3">
        <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Back
        </a>
      </div>

      <h4 class="mb-4 text-center"><i class="fas fa-chart-line me-2"></i> Dynamic Statistics Dashboard</h4>

      <!-- Chart Selector -->
      <div class="d-flex justify-content-center mb-3">
        <select id="chartSelector" class="form-select form-select-sm" style="max-width:400px;">
          <option value="property">Property Statistics</option>
          <option value="user">User Activity Statistics</option>
          <option value="audit">Transaction & Audit Trail</option>
          <option value="monthly">Monthly Trends (6 Months)</option>
          <option value="classification">Land Classification Breakdown</option>
          <option value="transaction_types">Transaction Type Breakdown</option>
          <option value="receipt_monthly">Receipt Collection (Monthly)</option>
          <option value="receipt_yearly">Receipt Collection (Yearly)</option>
        </select>
      </div>

      <!-- Chart Type Toggle -->
      <div class="d-flex justify-content-center mb-3 gap-2">
        <button class="btn btn-sm btn-outline-primary" onclick="changeChartType('bar')">
          <i class="fas fa-chart-bar"></i> Bar
        </button>
        <button class="btn btn-sm btn-outline-primary" onclick="changeChartType('line')">
          <i class="fas fa-chart-line"></i> Line
        </button>
        <button class="btn btn-sm btn-outline-primary" onclick="changeChartType('pie')">
          <i class="fas fa-chart-pie"></i> Pie
        </button>
        <button class="btn btn-sm btn-outline-primary" onclick="changeChartType('doughnut')">
          <i class="fas fa-circle-notch"></i> Doughnut
        </button>
      </div>

      <!-- Chart Container -->
      <div class="mb-4" style="max-width: 1000px; margin: auto;">
        <div style="height:500px; position: relative;">
          <canvas id="mainChart"></canvas>
        </div>
      </div>

      <!-- Export Button -->
      <div class="d-flex justify-content-end mt-3">
        <button id="exportBtn" class="btn btn-success btn-sm">
          <i class="fas fa-download"></i> Export as Image
        </button>
      </div>

      <!-- Statistics Summary Cards -->
      <div class="row mt-5">
        <div class="col-md-3 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-home text-primary"></i> Properties</h5>
              <h2 class="text-primary"><?= $total_properties ?></h2>
              <p class="text-muted">Active Properties</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-users text-success"></i> Owners</h5>
              <h2 class="text-success"><?= $total_owners ?></h2>
              <p class="text-muted">Total Owners</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-file-alt text-warning"></i> Transactions</h5>
              <h2 class="text-warning"><?= $total_transactions ?></h2>
              <p class="text-muted">Total Transactions</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-coins text-info"></i> Collection</h5>
              <h2 class="text-info">₱<?= number_format($totalCollection, 2) ?></h2>
              <p class="text-muted">Total Receipts</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const userId = <?= $_SESSION['user_id'] ?? 0 ?>;

    // Data from PHP
    const statisticsData = {
      property: <?= json_encode($property_stats) ?>,
      user: <?= json_encode($user_activity_stats) ?>,
      audit: <?= json_encode($audit_stats) ?>,
      monthly: <?= json_encode($monthly_trends) ?>,
      classification: <?= json_encode($classification_breakdown) ?>,
      transaction_types: <?= json_encode($transaction_breakdown) ?>,
      receipt_monthly: <?= json_encode($receipt_collection_monthly) ?>,
      receipt_yearly: <?= json_encode($receipt_collection_yearly) ?>
    };

    let currentChart = null;
    let currentChartType = 'bar';

    function getRandomColor() {
      const colors = [
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)',
        'rgba(199, 199, 199, 0.8)',
        'rgba(83, 102, 255, 0.8)',
        'rgba(255, 99, 255, 0.8)',
        'rgba(99, 255, 132, 0.8)'
      ];
      return colors;
    }

    function createChart(type, data) {
      const ctx = document.getElementById('mainChart').getContext('2d');

      if (currentChart) {
        currentChart.destroy();
      }

      let chartConfig = {
        type: currentChartType,
        data: data,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'top'
            },
            title: {
              display: true,
              text: getChartTitle(type),
              font: {
                size: 16
              }
            }
          }
        }
      };

      // Special formatting for receipt charts
      if (type === 'receipt_monthly' || type === 'receipt_yearly') {
        if (currentChartType !== 'pie' && currentChartType !== 'doughnut') {
          chartConfig.options.scales = {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '₱' + value.toLocaleString();
                }
              }
            }
          };
        }
      } else if (currentChartType === 'pie' || currentChartType === 'doughnut') {
        chartConfig.options.scales = undefined;
      } else {
        chartConfig.options.scales = {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        };
      }

      currentChart = new Chart(ctx, chartConfig);
    }

    function getChartTitle(type) {
      const titles = {
        property: 'Property Statistics Overview',
        user: 'User Activity Statistics',
        audit: 'Transaction & Audit Trail Statistics',
        monthly: 'Monthly Trends (Last 6 Months)',
        classification: 'Land Classification Distribution',
        transaction_types: 'Transaction Types Distribution',
        receipt_monthly: 'Receipt Collection - Monthly (' + new Date().getFullYear() + ')',
        receipt_yearly: 'Receipt Collection - Yearly (Last 5 Years)'
      };
      return titles[type] || 'Statistics';
    }

    function updateChart(type) {
      const colors = getRandomColor();
      let chartData = {};

      if (type === 'monthly') {
        chartData = {
          labels: statisticsData.monthly.labels,
          datasets: [{
              label: 'Properties Created',
              data: statisticsData.monthly.properties,
              backgroundColor: colors[0],
              borderColor: colors[0].replace('0.8', '1'),
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Transactions',
              data: statisticsData.monthly.transactions,
              backgroundColor: colors[1],
              borderColor: colors[1].replace('0.8', '1'),
              borderWidth: 2,
              fill: false
            },
            {
              label: 'User Logins',
              data: statisticsData.monthly.logins,
              backgroundColor: colors[2],
              borderColor: colors[2].replace('0.8', '1'),
              borderWidth: 2,
              fill: false
            }
          ]
        };
      } else if (type === 'receipt_monthly' || type === 'receipt_yearly') {
        const data = statisticsData[type];
        const color = 'rgba(69, 149, 119, 0.7)';
        chartData = {
          labels: data.labels,
          datasets: [{
            label: 'Collection (₱)',
            data: data.data,
            backgroundColor: color,
            borderColor: '#38776b',
            borderWidth: 2,
            borderRadius: 6
          }]
        };
      } else {
        const data = statisticsData[type];
        chartData = {
          labels: data.labels,
          datasets: [{
            label: 'Count',
            data: data.data,
            backgroundColor: colors,
            borderColor: colors.map(c => c.replace('0.8', '1')),
            borderWidth: 2
          }]
        };
      }

      createChart(type, chartData);
    }

    function changeChartType(type) {
      currentChartType = type;
      const selectedStat = document.getElementById('chartSelector').value;
      updateChart(selectedStat);
    }

    // Chart selector event
    document.getElementById('chartSelector').addEventListener('change', function() {
      updateChart(this.value);
    });

    // Export button
    document.getElementById('exportBtn').addEventListener('click', function() {
      if (currentChart) {
        const selectedStat = document.getElementById('chartSelector').value;
        const chartTitle = getChartTitle(selectedStat);

        // Log the export activity
        logExportActivity(chartTitle, selectedStat);

        // Export the chart
        const link = document.createElement('a');
        link.download = `statistics-${selectedStat}-${Date.now()}.png`;
        link.href = currentChart.toBase64Image();
        link.click();
      }
    });

    // Function to log export activity
    function logExportActivity(chartTitle, chartType) {
      const currentFileName = window.location.pathname.split('/').pop();

      fetch(`${currentFileName}?action=log_export`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            user_id: userId,
            chart_title: chartTitle,
            chart_type: chartType
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            console.log('Export activity logged successfully');
          }
        })
        .catch(error => {
          console.error('Error logging export activity:', error);
        });
    }

    // Initialize with property statistics
    updateChart('property');
  </script>
</body>

</html>