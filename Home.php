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

    $action = "Exported statistics chart from Dashboard\n";
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

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: index.php");
  exit;
}

$first_name = $_SESSION['first_name'] ?? '';
$last_name = $_SESSION['last_name'] ?? '';
$user_id = $_SESSION['user_id'] ?? 0;

$full_name = trim("$first_name $last_name");
if (empty($full_name)) {
  $full_name = 'Guest';
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch basic counts
$query_owners = "SELECT COUNT(*) AS total FROM owners_tb";
$total_owners = $conn->query($query_owners)->fetch_assoc()['total'];

$query_properties = "SELECT COUNT(*) AS total FROM p_info WHERE is_active = 1";
$total_properties = $conn->query($query_properties)->fetch_assoc()['total'];

$query_land = "SELECT COUNT(*) AS total FROM land";
$land_count = $conn->query($query_land)->fetch_assoc()['total'];

$query_faas = "SELECT COUNT(*) AS total FROM faas";
$total_faas = $conn->query($query_faas)->fetch_assoc()['total'];

$query_municipalities = "SELECT COUNT(*) AS total FROM municipality WHERE m_status = 'Active'";
$total_municipalities = $conn->query($query_municipalities)->fetch_assoc()['total'];

$query_barangays = "SELECT COUNT(*) AS total FROM brgy WHERE status = 'Active'";
$total_barangays = $conn->query($query_barangays)->fetch_assoc()['total'];

// User Activity Stats
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

// Transaction Stats
$query_transactions = "SELECT COUNT(*) AS total FROM transactions";
$total_transactions = $conn->query($query_transactions)->fetch_assoc()['total'];

$query_pending = "SELECT COUNT(*) AS total FROM transactions WHERE status = 'Pending'";
$pending_transactions = $conn->query($query_pending)->fetch_assoc()['total'];

$query_in_progress = "SELECT COUNT(*) AS total FROM transactions WHERE status = 'In Progress'";
$in_progress_transactions = $conn->query($query_in_progress)->fetch_assoc()['total'];

$query_completed = "SELECT COUNT(*) AS total FROM transactions WHERE status = 'Completed'";
$completed_transactions = $conn->query($query_completed)->fetch_assoc()['total'];

$query_received = "SELECT COUNT(*) AS total FROM received_papers";
$total_received_papers = $conn->query($query_received)->fetch_assoc()['total'];

$query_certifications = "SELECT COUNT(*) AS total FROM print_certifications";
$total_certifications = $conn->query($query_certifications)->fetch_assoc()['total'];

// Monthly Trends (Last 6 months)
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

// Prepare JSON data
$property_stats = [
  'labels' => ['Owners', 'Properties', 'Land Records', 'FAAS', 'Municipalities', 'Barangays'],
  'data' => [$total_owners, $total_properties, $land_count, $total_faas, $total_municipalities, $total_barangays]
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

// Dummy data
$building_count = 843;
$plant_count = 327;
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Home.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="container-fluid p-0 main-content" style="margin-top: 20px;">
    <div class="row px-4">
      <h2 class="fw-bold fst-italic text-black">
        Welcome, <?php echo htmlspecialchars($full_name); ?>!
      </h2>
      <div class="row mt-4">
        <!-- Left Column -->
        <div class="col-lg-8">
          <!-- Stats + Dynamic Chart Card -->
          <div class="modern-card shadow-lg p-4 rounded-lg mb-4">
            <div class="row g-3">
              <!-- Stats Cards -->
              <div class="col-md-4 d-flex flex-column gap-3">
                <a href="Real-Property-Unit-List.php" class="text-decoration-none text-dark">
                  <div class="stats-card text-center p-3 shadow-sm">
                    <h6 class="fw-bold">Property Listings</h6>
                    <i class="fas fa-building fa-2x text-warning my-2"></i>
                    <h3 class="mb-0"><?php echo $total_properties; ?></h3>
                    <small class="text-muted">Total Properties</small>
                  </div>
                </a>

                <a href="Tax-Declaration-List.php" class="text-decoration-none text-dark">
                  <div class="stats-card text-center p-3 shadow-sm">
                    <h6 class="fw-bold">Owner Statistics</h6>
                    <i class="fas fa-users fa-2x text-warning my-2"></i>
                    <h3 class="mb-0"><?php echo $total_owners; ?></h3>
                    <small class="text-muted">Total Owners</small>
                  </div>
                </a>

                <a href="Track.php" class="text-decoration-none text-dark">
                  <div class="stats-card text-center p-3 shadow-sm">
                    <h6 class="fw-bold">Property Types</h6>
                    <div class="d-flex justify-content-around mt-2">
                      <div>
                        <i class="fas fa-map fa-lg text-warning mb-1"></i>
                        <div class="parcel-count"><?php echo $land_count; ?></div>
                        <small>Land</small>
                      </div>
                      <div>
                        <i class="fas fa-building fa-lg text-warning mb-1"></i>
                        <div class="parcel-count"><?php echo $building_count; ?></div>
                        <small>Building</small>
                      </div>
                      <div>
                        <i class="fas fa-tree fa-lg text-warning mb-1"></i>
                        <div class="parcel-count"><?php echo $plant_count; ?></div>
                        <small>Plant/Trees</small>
                      </div>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Dynamic Chart -->
              <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Statistics Dashboard</h6>
                </div>

                <!-- Chart Type Selector -->
                <div class="mb-2">
                  <select id="chartSelector" class="form-select form-select-sm">
                    <option value="property">Property Statistics</option>
                    <option value="user">User Activity</option>
                    <option value="audit">Transactions & Audit</option>
                    <option value="monthly">Monthly Trends (6 Months)</option>
                  </select>
                </div>

                <!-- Chart Type Toggle -->
                <div class="btn-group btn-group-sm mb-2" role="group">
                  <button class="btn btn-outline-primary active" onclick="changeChartType('bar')" data-type="bar">
                    <i class="fas fa-chart-bar"></i>
                  </button>
                  <button class="btn btn-outline-primary" onclick="changeChartType('line')" data-type="line">
                    <i class="fas fa-chart-line"></i>
                  </button>
                  <button class="btn btn-outline-primary" onclick="changeChartType('pie')" data-type="pie">
                    <i class="fas fa-chart-pie"></i>
                  </button>
                </div>

                <div style="height:300px; position: relative;">
                  <canvas id="dashboardChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Tax Declaration Table -->
          <div class="modern-card shadow-lg p-4 rounded-lg">
            <h4 class="font-weight-bold custom-text-color mb-4 d-flex align-items-center justify-content-between">
              TAX DECLARATION
              <a href="tax-declaration-list.php" class="btn btn-sm btn-outline-primary">View All</a>
            </h4>
            <div class="table-responsive">
              <table class="table table-hover table-custom">
                <thead class="thead-light">
                  <tr>
                    <th>TD ID</th>
                    <th>OWNER</th>
                    <th>TD NUMBER</th>
                    <th>PROPERTY VALUE</th>
                    <th>YEAR</th>
                  </tr>
                </thead>
                <?php
                $sql = "
                  SELECT 
                    r.dec_id,
                    r.arp_no,
                    r.total_property_value,
                    r.tax_year,
                    f.faas_id,
                    f.pro_id AS p_id,
                    GROUP_CONCAT(
                      DISTINCT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname)
                      SEPARATOR ', '
                    ) AS owner_names
                  FROM rpu_dec r
                  INNER JOIN faas f ON r.faas_id = f.faas_id
                  LEFT JOIN propertyowner po  
                    ON po.property_id = f.pro_id 
                    AND po.is_retained = 1
                  LEFT JOIN owners_tb o ON o.own_id = po.owner_id
                  WHERE f.faas_id IS NOT NULL
                  GROUP BY r.dec_id, f.faas_id, f.pro_id
                  ORDER BY r.dec_id DESC
                  LIMIT 5
                ";
                $result = $conn->query($sql);
                ?>

                <tbody>
                  <?php
                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $owner_names = $row['owner_names'] ?? '';
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['dec_id'] ?? '') . "</td>";
                      echo "<td>" . htmlspecialchars($owner_names) . "</td>";
                      echo "<td>" . htmlspecialchars($row['arp_no'] ?? '') . "</td>";
                      $property_value = isset($row['total_property_value']) ? number_format((float) $row['total_property_value'], 2) : '0.00';
                      echo "<td>₱ {$property_value}</td>";
                      echo "<td>" . (!empty($row['tax_year']) ? htmlspecialchars(date('Y', strtotime($row['tax_year']))) : '') . "</td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='5' class='text-center'>No records found.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Right Section -->
        <div class="col-lg-4 mb-4">
          <div class="modern-card shadow-lg p-4 rounded-lg" style="height: 100%;">
            <h3 class="font-weight-bold custom-text-color">CITIZEN'S CHARTER OFFICE OF THE PROVINCIAL ASSESSOR</h3>
            <h5 class="text-secondary mb-4 custom-text-color">Capitol, Daet, Camarines Norte</h5>
            <p class="lead custom-text-color">The Office of the Provincial Assessor is a key entity in the Provincial
              Government, operating under Republic Act No. 7160, also known as the Local Government Code of 1991.</p>
            <p class="custom-text-color">Its primary goal is to perform duties related to real property taxation,
              adhering to fundamental principles such as:</p>
            <ul class="list-group list-group-flush">
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Appraising
                real property at its current and fair market value.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i>
                Classification of property for assessment based on actual use.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring
                uniform assessment classification within the local government unit.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Restricting
                private persons from performing appraisal and assessment tasks.</li>
              <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring
                equitable property appraisal and assessment.</li>
            </ul>
            <p class="mt-3 custom-text-color">Under Sec. 472, par (b) of the Code, the Office has the following key
              responsibilities:</p>
            <ol class="ml-3">
              <li class="custom-text-color">Enforcing laws and policies regarding property appraisal and taxation.</li>
              <li class="custom-text-color">Reviewing and recommending improvements to policies and practices in
                property valuation and assessment.</li>
              <li class="custom-text-color">Establishing efficient property assessment systems and maintaining accurate
                property records.</li>
              <li class="custom-text-color">Ensuring proper tax mapping and conducting frequent surveys for verification
                of listed properties.</li>
              <li class="custom-text-color">Coordinating with municipal assessors for better data management and tax
                mapping operations.</li>
            </ol>
            <p class="mt-4 custom-text-color">The office operates under the supervision of the Governor, with technical
              oversight from the Department of Finance and the Bureau of Local Government Finance.</p>
            <p class="font-weight-bold custom-text-color mt-4">Currently, the Office is composed of three divisions:
              Assessment and Appraisal, Tax Mapping and Records, and Administrative.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    const userId = <?= $user_id ?>;

    const statisticsData = {
      property: <?= json_encode($property_stats) ?>,
      user: <?= json_encode($user_activity_stats) ?>,
      audit: <?= json_encode($audit_stats) ?>,
      monthly: <?= json_encode($monthly_trends) ?>
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

    function getChartTitle(type) {
      const titles = {
        property: 'Property Statistics Overview',
        user: 'User Activity Statistics',
        audit: 'Transaction & Audit Trail Statistics',
        monthly: 'Monthly Trends (Last 6 Months)'
      };
      return titles[type] || 'Statistics';
    }

    function createChart(type, data) {
      const ctx = document.getElementById('dashboardChart').getContext('2d');

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
              display: false
            }
          }
        }
      };

      if (currentChartType === 'pie' || currentChartType === 'doughnut') {
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

      // Update active button
      document.querySelectorAll('.btn-group button').forEach(btn => {
        btn.classList.remove('active');
      });
      document.querySelector(`button[data-type="${type}"]`).classList.add('active');

      const selectedStat = document.getElementById('chartSelector').value;
      updateChart(selectedStat);
    }

    document.getElementById('chartSelector').addEventListener('change', function() {
      updateChart(this.value);
    });

    // Initialize with property statistics
    updateChart('property');
  </script>
</body>

</html>