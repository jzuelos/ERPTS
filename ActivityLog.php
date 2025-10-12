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
  $limit = 10;

  // --- MAIN LOGS (excluding login/logout) ---
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;

  // Build WHERE clause
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
  $where[] = "a.action NOT IN ('Logged in to the system', 'Logged out of the system')"; // exclude login/logout
  $where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

  // Count total rows
  $stmt_count = $conn->prepare("SELECT COUNT(*) as total 
                                FROM activity_log a
                                JOIN users u ON a.user_id = u.user_id
                                $where_sql");
  if ($params) $stmt_count->bind_param(str_repeat("s", count($params)), ...$params);
  $stmt_count->execute();
  $total_rows = $stmt_count->get_result()->fetch_assoc()['total'];
  $total_pages = ceil($total_rows / $limit);

  // Fetch logs
  $stmt = $conn->prepare("SELECT a.log_id, a.action, a.log_time, 
                                CONCAT(u.first_name, ' ', u.last_name) AS fullname
                          FROM activity_log a
                          JOIN users u ON a.user_id = u.user_id
                          $where_sql
                          ORDER BY a.log_time DESC
                          LIMIT ? OFFSET ?");
  $params_main = $params;
  $params_main[] = $limit;
  $params_main[] = $offset;
  $types_main = str_repeat("s", count($params)) . "ii";
  $stmt->bind_param($types_main, ...$params_main);
  $stmt->execute();
  $result = $stmt->get_result();

  // --- LOGIN/LOGOUT LOGS ---
  $page_login = isset($_GET['page_login']) && is_numeric($_GET['page_login']) ? (int)$_GET['page_login'] : 1;
  $offset_login = ($page_login - 1) * $limit;

  $where_login = [];
  $params_login = [];
  if ($start_date) {
    $where_login[] = "DATE(a.log_time) >= ?";
    $params_login[] = $start_date;
  }
  if ($end_date) {
    $where_login[] = "DATE(a.log_time) <= ?";
    $params_login[] = $end_date;
  }
  $where_login[] = "a.action IN ('Logged in to the system', 'Logged out of the system')";
  $where_sql_login = $where_login ? "WHERE " . implode(" AND ", $where_login) : "";

  // Count login logs
  $stmt_count_login = $conn->prepare("SELECT COUNT(*) as total 
                                      FROM activity_log a
                                      JOIN users u ON a.user_id = u.user_id
                                      $where_sql_login");
  if ($params_login) $stmt_count_login->bind_param(str_repeat("s", count($params_login)), ...$params_login);
  $stmt_count_login->execute();
  $total_rows_login = $stmt_count_login->get_result()->fetch_assoc()['total'];
  $total_pages_login = ceil($total_rows_login / $limit);

  // Fetch login logs
  $stmt_login = $conn->prepare("SELECT a.log_id, a.action, a.log_time, 
                                      CONCAT(u.first_name, ' ', u.last_name) AS fullname
                                FROM activity_log a
                                JOIN users u ON a.user_id = u.user_id
                                $where_sql_login
                                ORDER BY a.log_time DESC
                                LIMIT ? OFFSET ?");
  $params_login2 = $params_login;
  $params_login2[] = $limit;
  $params_login2[] = $offset_login;
  $types_login = str_repeat("s", count($params_login)) . "ii";
  $stmt_login->bind_param($types_login, ...$params_login2);
  $stmt_login->execute();
  $result_login = $stmt_login->get_result();
  ?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <title>Activity Log</title>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php include 'header.php'; ?>

  <main class="container my-5 flex-grow-1 justify-content-center">
    <div class="col-12 px-4">
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

        <div class="col-auto ms-auto">
          <button type="button" id="toggleLogsBtn" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-1"></i> Log In/Log Out Logs
          </button>
        </div>
      </form>

<!-- Main Activity Logs -->
<div id="activitylogs" class="table-responsive">
  <table class="table table-striped table-hover align-middle text-start w-100 mb-0">
    <thead class="table-dark">
      <tr>
        <th style="width: 8%">No.</th>
        <th style="width: 32%">Activity</th>
        <th style="width: 20%">User</th>
        <th style="width: 20%">Date and Time</th>
      </tr>
    </thead>
    <tbody>
       <?php 
      if ($result->num_rows > 0) {
        $no = $offset + 1; 
        while ($row = $result->fetch_assoc()) { 
          $activity = htmlspecialchars($row['action']);
          $fullname = htmlspecialchars($row['fullname']);
          $log_time = htmlspecialchars($row['log_time']);
          
          echo "
          <tr>
            <td>{$no}</td>
            <td style='max-width: 350px; vertical-align: middle;'>
              <div id='activity{$no}' 
                   class='activity-text text-truncate' 
                   style='cursor: pointer; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>
                {$activity}
              </div>
            </td>
            <td style='vertical-align: middle;'>{$fullname}</td>
            <td style='white-space: nowrap; vertical-align: middle; position: relative;'>
              <span>{$log_time}</span>
              <i class='bi bi-caret-down-fill text-secondary toggle-btn' 
                 data-id='{$no}' 
                 style='cursor: pointer; margin-left: 8px;'></i>
            </td>
          </tr>";
          $no++;
        }
      } else {
        echo "<tr><td colspan='4' class='text-center text-muted'>No activity logs found.</td></tr>"; 
      }
      ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav aria-label="Page navigation" class="mt-2">
    <div class="d-flex justify-content-center align-items-center gap-2">
      <?php if ($page > 1): ?>
        <a class="btn btn-outline-primary btn-sm" href="?page=<?= $page - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Prev</a>
      <?php else: ?>
        <button class="btn btn-outline-secondary btn-sm" disabled>Prev</button>
      <?php endif; ?>

      <span class="small text-muted">Page <?= $page ?> of <?= $total_pages ?></span>

      <?php if ($page < $total_pages): ?>
        <a class="btn btn-outline-primary btn-sm" href="?page=<?= $page + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next</a>
      <?php else: ?>
        <button class="btn btn-outline-secondary btn-sm" disabled>Next</button>
      <?php endif; ?>
    </div>
  </nav>
</div>          


      <!-- Login/Logout Logs -->
      <div id="loginlogs" class="table-responsive d-none">
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
            if ($result_login->num_rows > 0) {
              $no = $offset_login + 1;
              while ($row = $result_login->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['action']}</td>
                        <td>{$row['log_time']}</td>
                        <td>{$row['fullname']}</td>
                      </tr>";
                $no++;
              }
            } else {
              echo "<tr><td colspan='4' class='text-center text-muted'>No login/logout logs found.</td></tr>";
            }
            ?>
          </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-2">
          <div class="d-flex justify-content-center align-items-center gap-2">
            <?php if ($page_login > 1): ?>
              <a class="btn btn-outline-primary btn-sm" href="?page_login=<?= $page_login - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Prev</a>
            <?php else: ?>
              <button class="btn btn-outline-secondary btn-sm" disabled>Prev</button>
            <?php endif; ?>

            <span class="small text-muted">Page <?= $page_login ?> of <?= $total_pages_login ?></span>

            <?php if ($page_login < $total_pages_login): ?>
              <a class="btn btn-outline-primary btn-sm" href="?page_login=<?= $page_login + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next</a>
            <?php else: ?>
              <button class="btn btn-outline-secondary btn-sm" disabled>Next</button>
            <?php endif; ?>
          </div>
        </nav>
      </div>
    </div>
  </main>

  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="activitylog.js"></script>
</body>
</html>
