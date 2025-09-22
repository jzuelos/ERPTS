<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

// prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'database.php';
$conn = Database::getInstance();

// Get filter inputs
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Pagination setup
$limit = 10; // logs per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build WHERE clause for date filter
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
$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Count total rows for pagination
$stmt_count = $conn->prepare("SELECT COUNT(*) as total 
                              FROM activity_log a
                              JOIN users u ON a.user_id = u.user_id
                              $where_sql");
if ($params) $stmt_count->bind_param(str_repeat("s", count($params)), ...$params);
$stmt_count->execute();
$total_rows = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch logs with pagination
$stmt = $conn->prepare("SELECT a.log_id, a.action, a.log_time, u.username
                        FROM activity_log a
                        JOIN users u ON a.user_id = u.user_id
                        $where_sql
                        ORDER BY a.log_time DESC
                        LIMIT ? OFFSET ?");
$params[] = $limit;
$params[] = $offset;

$types = str_repeat("s", count($params)-2)."ii"; // last two are integers
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
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

  <title>Activity Log</title>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>

  <main class="container my-5 flex-grow-1 d-flex justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="mb-3">
        <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Back
        </a>
      </div>

      <h4 class="mb-3"><i class="fas fa-history me-2"></i> Activity Log</h4>

      <form method="get" class="row g-3 mb-3">
        <div class="col-auto">
          <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" placeholder="Start Date">
        </div>
        <div class="col-auto">
          <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" placeholder="End Date">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-success">Filter</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-start">
          <thead class="table-dark">
            <tr>
              <th style="width: 10%">No.</th>
              <th style="width: 40%">Activity</th>
              <th style="width: 25%">Date and Time</th>
              <th style="width: 25%">User</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
              $no = 1;
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['action']}</td>
                        <td>{$row['log_time']}</td>
                        <td>{$row['username']}</td>
                      </tr>";
                $no++;
              }
            } else {
              echo "<tr><td colspan='4' class='text-center text-muted'>No activity logs found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">
    <?php if($page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?page=<?= $page-1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Previous</a>
      </li>
    <?php endif; ?>

    <?php for($p=1; $p<=$total_pages; $p++): ?>
      <li class="page-item <?= $p==$page?'active':'' ?>">
        <a class="page-link" href="?page=<?= $p ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>"><?= $p ?></a>
      </li>
    <?php endfor; ?>

    <?php if($page < $total_pages): ?>
      <li class="page-item">
        <a class="page-link" href="?page=<?= $page+1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>

    </div>
  </main>

  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
