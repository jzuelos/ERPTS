<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Database connection
require_once 'database.php';

$conn = Database::getInstance();

// Fetch receipt data with property and user details
$query = "SELECT 
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
ORDER BY pc.created_at DESC";

$result = mysqli_query($conn, $query);

// Fetch monthly collection data for current year
$monthlyQuery = "SELECT 
    MONTH(date_paid) as month,
    SUM(certification_fee) as total
FROM print_certifications
WHERE YEAR(date_paid) = YEAR(CURDATE())
GROUP BY MONTH(date_paid)
ORDER BY MONTH(date_paid)";

$monthlyResult = mysqli_query($conn, $monthlyQuery);
$monthlyData = array_fill(1, 12, 0);
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $monthlyData[$row['month']] = (float)$row['total'];
}

// Fetch yearly collection data (last 5 years)
$yearlyQuery = "SELECT 
    YEAR(date_paid) as year,
    SUM(certification_fee) as total
FROM print_certifications
WHERE YEAR(date_paid) >= YEAR(CURDATE()) - 4
GROUP BY YEAR(date_paid)
ORDER BY YEAR(date_paid)";

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
          <canvas id="collectionChart"></canvas>
        </div>
      </div>
    </div>

    <!-- RECEIPT TABLE + SEARCH + PRINT CONTROLS CONTAINER -->
    <div class="receipt-section p-3 rounded shadow-sm bg-white">

      <!-- Search Bar -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by receipt number, owner, or OR number...">
      </div>

      <!-- Table Wrapper -->
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
    // Monthly and Yearly data from PHP
    const monthlyData = <?php echo json_encode(array_values($monthlyData)); ?>;
    const yearlyDataRaw = <?php echo json_encode($yearlyData); ?>;
    
    // Prepare yearly data
    const yearlyLabels = Object.keys(yearlyDataRaw);
    const yearlyValues = Object.values(yearlyDataRaw);

    let ctx = document.getElementById("collectionChart");
    let collectionChart;

    function loadChart(type) {
      if (collectionChart) {
        collectionChart.destroy();
      }

      let labels, data, chartTitle;
      
      if (type === "monthly") {
        labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        data = monthlyData;
        chartTitle = "Monthly Collection for " + new Date().getFullYear();
      } else {
        labels = yearlyLabels;
        data = yearlyValues;
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
            legend: {
              display: true,
              position: 'top'
            },
            title: {
              display: true,
              text: chartTitle,
              font: {
                size: 16,
                weight: 'bold'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '₱' + value.toLocaleString();
                }
              }
            }
          }
        }
      });
    }

    document.getElementById("chartFilter").addEventListener("change", function () {
      loadChart(this.value);
    });

    // Initialize with monthly chart
    loadChart("monthly");

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchValue = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('#receiptTable tbody tr:not(.collapse-row)');
      
      tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
        
        // Hide associated detail rows too
        const nextRow = row.nextElementSibling;
        if (nextRow && nextRow.classList.contains('collapse-row')) {
          nextRow.style.display = text.includes(searchValue) ? '' : 'none';
        }
      });
    });

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

</body>
</html>