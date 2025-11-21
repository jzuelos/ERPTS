<?php
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Database connection
require_once 'database.php';
$conn = Database::getInstance();


// Handle Pagination
$limit = 5; // Number of rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Handle Server-Side Search
// Capture search term from GET request
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSQL = '';

if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $searchSQL = " AND (
        pc.cert_id = '$searchEscaped' OR
        pc.or_number LIKE '%$searchEscaped%' OR
        pc.owner_admin LIKE '%$searchEscaped%'
    )";
}




// Count total rows for pagination
$countQuery = "
SELECT COUNT(*) as total
FROM print_certifications pc
LEFT JOIN p_info p ON pc.property_id = p.p_id
LEFT JOIN faas f ON pc.faas_id = f.faas_id
LEFT JOIN rpu_dec rd ON f.faas_id = rd.faas_id
LEFT JOIN users u ON pc.created_by = u.user_id
WHERE 1 $searchSQL
";

$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);


// Fetch receipt data with pagination and search applied
$query = "
SELECT 
    pc.cert_id,
    pc.or_number,
    pc.owner_admin,
    pc.certification_date,
    pc.date_paid,
    pc.certification_fee,
    pc.property_id,
    pc.faas_id,
    p.house_no,
    p.barangay,
    p.city,
    p.province,
    rd.arp_no,
    CONCAT(u.first_name, ' ', u.last_name) as created_by_name
FROM print_certifications pc
LEFT JOIN p_info p ON pc.property_id = p.p_id
LEFT JOIN faas f ON pc.faas_id = f.faas_id
LEFT JOIN rpu_dec rd ON f.faas_id = rd.faas_id
LEFT JOIN users u ON pc.created_by = u.user_id
WHERE 1 $searchSQL
ORDER BY pc.created_at DESC
LIMIT $start, $limit
";

$result = mysqli_query($conn, $query);

// Fetch monthly collection data (current year)
$monthlyQuery = "
SELECT MONTH(date_paid) as month, SUM(certification_fee) as total
FROM print_certifications
WHERE YEAR(date_paid) = YEAR(CURDATE())
GROUP BY MONTH(date_paid)
ORDER BY MONTH(date_paid)
";

$monthlyResult = mysqli_query($conn, $monthlyQuery);
$monthlyData = array_fill(1, 12, 0);
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $monthlyData[$row['month']] = (float)$row['total'];
}


// Fetch yearly collection data 
$yearlyQuery = "
SELECT YEAR(date_paid) as year, SUM(certification_fee) as total
FROM print_certifications
WHERE YEAR(date_paid) >= YEAR(CURDATE()) - 4
GROUP BY YEAR(date_paid)
ORDER BY YEAR(date_paid)
";

$yearlyResult = mysqli_query($conn, $yearlyQuery);
$yearlyData = [];
while ($row = mysqli_fetch_assoc($yearlyResult)) {
    $yearlyData[$row['year']] = (float)$row['total'];
}

// Calculate total collection
$totalQuery = "SELECT SUM(certification_fee) as total FROM print_certifications";
$totalCollection = mysqli_query($conn, $totalQuery)->fetch_assoc()['total'] ?? 0;

?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="receipt.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <?php include 'header.php'; ?>

  <!-- Page Header -->
  <div class="page-header">
    <div class="container">
      <h3><i class="fas fa-receipt me-2"></i>Receipt Records</h3>
    </div>
  </div>

  <div class="container mt-4">

    <!-- Collection Summary Card -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title"><i class="fas fa-coins me-2"></i>Total Certification Fee Collection</h5>
            <h2 class="text-success">₱ <?php echo number_format($totalCollection, 2); ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="chart-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Collection Overview</h5>
            <select id="chartFilter" class="form-select w-auto">
              <option value="monthly">Monthly Collection (Current Year)</option>
              <option value="yearly">Yearly Collection (Last 5 Years)</option>
            </select>
          </div>
      <!-- ADD THIS BELOW -->
          <div class="d-flex gap-2 mb-3">
            <input type="date" id="startDate" class="form-control w-auto">
            <input type="date" id="endDate" class="form-control w-auto">
            <button id="applyDateFilter" class="btn btn-success">Filter</button>
              <button id="resetDateFilter" class="btn btn-secondary">Reset</button>
          </div>
          <canvas id="collectionChart"></canvas>
        </div>
      </div>
    </div>

    <!-- RECEIPT TABLE + SEARCH + PRINT CONTROLS CONTAINER -->
    <div class="receipt-section p-3 rounded shadow-sm bg-white">

      <!-- Search Bar -->
      <form method="GET" class="d-flex justify-content-between align-items-center mb-3" action="#receipts">
        <input type="text" name="search" id="searchInput" class="form-control"
              placeholder="Search by Receipt #, OR #, or Owner..."
              value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary ms-2">Search</button>
      </form>


      <!-- Table Wrapper -->
       <a id="receipts"></a>
      <div class="table-responsive">
        <table id="receiptTable" class="table table-hover align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th>Receipt #</th>
              <th>OR #</th>
              <th>Owner</th>
              <th>Date & Time</th>
              <th>Fee</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $receiptNum = "RCPT-" . str_pad($row['cert_id'], 3, '0', STR_PAD_LEFT);
                    $formattedDate = date('Y-m-d h:i A', strtotime($row['date_paid']));
                    $formattedFee = number_format($row['certification_fee'], 2);
                    
                    // Property address
                    $propertyAddress = trim($row['house_no'] . ' ' . $row['barangay'] . ', ' . $row['city'] . ', ' . $row['province']);
            ?>
            <tr>
              <td><input type="checkbox" class="rowCheck" data-cert-id="<?php echo $row['cert_id']; ?>"></td>
              <td><strong><?php echo $receiptNum; ?></strong></td>
              <td><?php echo htmlspecialchars($row['or_number']); ?></td>
              <td><?php echo htmlspecialchars($row['owner_admin']); ?></td>
              <td><small class="text-muted"><?php echo $formattedDate; ?></small></td>
              <td><span class="fee-badge">₱ <?php echo $formattedFee; ?></span></td>
              <td>
                <button class="btn btn-sm btn-details" data-bs-toggle="collapse" data-bs-target="#details<?php echo $counter; ?>">
                  <i class="fas fa-info-circle me-1"></i> Details
                </button>
              </td>
            </tr>
            <tr class="collapse collapse-row" id="details<?php echo $counter; ?>">
              <td colspan="7">
                <div class="p-3">
                  <div class="row">
                    <div class="col-md-6">
                      <strong><i class="fas fa-file-alt me-2"></i>Transaction Details:</strong><br>
                      <small class="text-muted">
                        <strong>Property ID:</strong> <?php echo $row['property_id']; ?><br>
                        <strong>FAAS ID:</strong> <?php echo $row['faas_id']; ?><br>
                        <strong>ARP No:</strong> <?php echo htmlspecialchars($row['arp_no'] ?? 'N/A'); ?><br>
                        <strong>Address:</strong> <?php echo htmlspecialchars($propertyAddress); ?><br>
                      </small>
                    </div>
                    <div class="col-md-6">
                      <strong><i class="fas fa-calendar me-2"></i>Payment Information:</strong><br>
                      <small class="text-muted">
                        <strong>Certification Date:</strong> <?php echo date('F d, Y', strtotime($row['certification_date'])); ?><br>
                        <strong>Date Paid:</strong> <?php echo date('F d, Y', strtotime($row['date_paid'])); ?><br>
                        <strong>Processed By:</strong> <?php echo htmlspecialchars($row['created_by_name']); ?><br>
                      </small>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php
                    $counter++;
                }
            } else {
            ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                No receipt records found
              </td>
            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <nav aria-label="Receipt pagination" class="mt-3">
        <ul class="pagination justify-content-center">

          <!-- Previous Button -->
          <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>#receipts">&laquo;</a>
          </li>

          <!-- Current Page Indicator -->
          <li class="page-item disabled">
            <a class="page-link bg-light fw-bold">
              Page <?php echo $page; ?> of <?php echo $totalPages; ?>
            </a>
          </li>

          <!-- Next Button -->
          <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>#receipts">&raquo;</a>
          </li>

        </ul>
      </nav>
      <!-- PRINT CONTROLS -->
      <div class="print-controls d-flex justify-content-end align-items-center mt-3 gap-3">
        <div>
          <input type="checkbox" id="printAll">
          <label for="printAll" class="mb-0">Print all</label>
        </div>

        <a href="#" id="printSelectedBtn" class="btn btn-print">
          <i class="fas fa-print me-2"></i> Print Selected
        </a>
      </div>

    </div>

  </div>

  <div style="margin-bottom: 3rem;"></div>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
// Prepare original monthly/yearly data from PHP
const monthlyDataOriginal = <?php echo json_encode(array_values($monthlyData)); ?>;
const yearlyDataRawOriginal = <?php echo json_encode($yearlyData); ?>;

const yearlyLabelsOriginal = Object.keys(yearlyDataRawOriginal);
const yearlyValuesOriginal = Object.values(yearlyDataRawOriginal);

let ctx = document.getElementById("collectionChart");
let collectionChart;

// Function to load chart
function loadChart(type, filteredMonthly = null, filteredYearly = null) {
  if (collectionChart) {
    collectionChart.destroy();
  }

  let labels, data, chartTitle;

  if (type === "monthly") {
    labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    data = filteredMonthly || monthlyDataOriginal;
    chartTitle = "Monthly Collection for " + new Date().getFullYear();
  } else {
    labels = yearlyLabelsOriginal;
    data = filteredYearly || yearlyValuesOriginal;
    chartTitle = "Yearly Collection (Last 5 Years)";
  }

  collectionChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [{
        label: "Collection (₱)",
        data: data,
        backgroundColor: "rgba(69, 149, 119, 0.7)",
        borderColor: "#38776b",
        borderWidth: 2,
        borderRadius: 6,
        hoverBackgroundColor: "rgba(69, 149, 119, 0.9)"
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: true, position: 'top' },
        title: { display: true, text: chartTitle, font: { size: 16, weight: 'bold' } }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { callback: value => '₱' + value.toLocaleString() }
        }
      }
    }
  });
}

// Event listener for dropdown filter
document.getElementById("chartFilter").addEventListener("change", function () {
  loadChart(this.value);
});

// -----------------------------
// Date filter functionality
// -----------------------------
document.getElementById("applyDateFilter").addEventListener("click", function() {
  const start = document.getElementById("startDate").value;
  const end = document.getElementById("endDate").value;
  const filterType = document.getElementById("chartFilter").value;

  if (!start || !end) {
    alert("Please select both start and end dates.");
    return;
  }

  const startDate = new Date(start);
  const endDate = new Date(end);

  if (filterType === "monthly") {
    // Filter monthly data
    const filteredMonthly = monthlyDataOriginal.map((value, index) => {
      const monthDate = new Date(new Date().getFullYear(), index, 1);
      return (monthDate >= startDate && monthDate <= endDate) ? value : 0;
    });
    loadChart("monthly", filteredMonthly);
  } else {
    // Filter yearly data
    const filteredYearly = yearlyValuesOriginal.map((value, index) => {
      const year = parseInt(yearlyLabelsOriginal[index]);
      const yearDate = new Date(year, 0, 1);
      return (yearDate >= startDate && yearDate <= endDate) ? value : 0;
    });
    loadChart("yearly", null, filteredYearly);
  }
});

// Reset button functionality
document.getElementById("resetDateFilter").addEventListener("click", function() {
    // Clear date inputs
    document.getElementById("startDate").value = '';
    document.getElementById("endDate").value = '';

    // Reload chart with original data
    const chartType = document.getElementById("chartFilter").value;
    loadChart(chartType);
});


// Initialize chart with monthly data
loadChart("monthly");



    // Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('.rowCheck');
      checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
    });

    // Print All functionality
    document.getElementById('printAll').addEventListener('change', function() {
      const selectAll = document.getElementById('selectAll');
      if (this.checked) {
        selectAll.checked = true;
        selectAll.dispatchEvent(new Event('change'));
      }
    });

    // Print Selected functionality
    document.getElementById('printSelectedBtn').addEventListener('click', function() {
      const selectedCheckboxes = document.querySelectorAll('.rowCheck:checked');
      
      if (selectedCheckboxes.length === 0) {
        alert('Please select at least one receipt to print.');
        return;
      }

      const certIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.certId);
      
      // Redirect to print page with selected IDs
      window.location.href = 'printreceipt.php?cert_ids=' + certIds.join(',');
    });
  </script>

  <script>
  // If the URL contains ?page=, scroll to the table after reload
  document.addEventListener("DOMContentLoaded", function () {
    if (window.location.search.includes("page=")) {
      const tableSection = document.getElementById("receiptTable");
      if (tableSection) {
        tableSection.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    }
  });
</script>




</body>
</html>