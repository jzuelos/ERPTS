<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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

    <!-- Chart Filter -->
      <div class="row align-items-center g-3">
        <div class="col-md-12 text-end">
          <select id="chartFilter" class="form-select w-auto d-inline-block">
            <option value="monthly">Monthly Collection</option>
            <option value="yearly">Yearly Collection</option>
          </select>
              <div class="chart-card">
      <canvas id="collectionChart"></canvas>
    </div>
      </div>
    </div>

    <!-- Chart -->

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
            <!-- SAMPLE ROW 1 -->
            <tr>
              <td><input type="checkbox" class="rowCheck"></td>
              <td><strong>RCPT-001</strong></td>
              <td>OR-98432</td>
              <td>Juan Dela Cruz</td>
              <td><small class="text-muted">2025-02-10 10:45 AM</small></td>
              <td><span class="fee-badge">₱ 1,200</span></td>
              <td>
               <button class="btn btn-sm btn-details">
                    <i class="fas fa-info-circle me-1"></i> Details
                    </button>

              </td>
            </tr>
            <tr class="collapse collapse-row" id="details1">
              <td colspan="7">
                <div class="p-2">
                  <strong><i class="fas fa-file-alt me-2"></i>Transaction Details:</strong><br>
                  Payment for property certification and documentation.
                </div>
              </td>
            </tr>

            <!-- SAMPLE ROW 2 -->
            <tr>
              <td><input type="checkbox" class="rowCheck"></td>
              <td><strong>RCPT-002</strong></td>
              <td>OR-45678</td>
              <td>Maria Santos</td>
              <td><small class="text-muted">2025-02-09 03:15 PM</small></td>
              <td><span class="fee-badge">₱ 800</span></td>
              <td>
               <button class="btn btn-sm btn-details">
                <i class="fas fa-info-circle me-1"></i> Details
                </button>

              </td>
            </tr>
            <tr class="collapse collapse-row" id="details2">
              <td colspan="7">
                <div class="p-2">
                  <strong><i class="fas fa-file-alt me-2"></i>Transaction Details:</strong><br>
                  Payment for land record updates and official documents.
                </div>
              </td>
            </tr>

            <!-- SAMPLE ROW 3 -->
            <tr>
              <td><input type="checkbox" class="rowCheck"></td>
              <td><strong>RCPT-003</strong></td>
              <td>OR-23456</td>
              <td>Pedro Reyes</td>
              <td><small class="text-muted">2025-02-08 11:30 AM</small></td>
              <td><span class="fee-badge">₱ 1,500</span></td>
              <td>
                <button class="btn btn-sm btn-details">
                    <i class="fas fa-info-circle me-1"></i> Details
                    </button>

              </td>
            </tr>
            <tr class="collapse collapse-row" id="details3">
              <td colspan="7">
                <div class="p-2">
                  <strong><i class="fas fa-file-alt me-2"></i>Transaction Details:</strong><br>
                  Annual property tax payment for residential lot.
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- PRINT CONTROLS -->
      <div class="print-controls d-flex justify-content-end align-items-center mt-3 gap-3">
        <div>
          <input type="checkbox" id="printAll">
          <label for="printAll" class="mb-0">Print all</label>
        </div>

        <a href="printreceipt.php" id="printSelectedBtn" class="btn btn-print">
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
// ===== CHART.JS MONTHLY/YEARLY =====
let ctx = document.getElementById("collectionChart");
let chart;

function loadChart(type) {
  if (chart) chart.destroy();

  let labels = type === "monthly"
    ? ["Jan", "Feb", "Mar", "Apr", "May", "Jun"]
    : ["2021", "2022", "2023", "2024", "2025"];

  let data = type === "monthly"
    ? [5000, 4200, 4800, 5300, 6000, 5800]
    : [45000, 49000, 52000, 58000, 65000];

  chart = new Chart(ctx, {
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

loadChart("monthly");


  </script>

  <script>
const detailButtons = document.querySelectorAll('.btn-details');

detailButtons.forEach((button, index) => {
  const targetId = `#details${index + 1}`; // match row IDs
  button.addEventListener('click', () => {
    const detailsRow = document.querySelector(targetId);
    const isOpen = detailsRow.classList.contains('show');

    // Close all other rows
    document.querySelectorAll('.collapse-row.show').forEach(row => {
      if (row !== detailsRow) row.classList.remove('show');
    });

    // Toggle clicked row
    if (isOpen) {
      detailsRow.classList.remove('show');
      console.log(targetId + " is now CLOSED");
    } else {
      detailsRow.classList.add('show');
      console.log(targetId + " is now OPEN");
    }
  });
});


</script>


</body>
</html>