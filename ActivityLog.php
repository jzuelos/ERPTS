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
            <tr>
              <td>1</td>
              <td>Logged in to the system</td>
              <td>2024-09-09 09:15:23</td>
              <td>admin</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Viewed Real Property Records</td>
              <td>2024-09-09 09:45:10</td>
              <td>jdoe</td>
            </tr>
            <tr>
              <td>3</td>
              <td>Updated Property Record #1045</td>
              <td>2024-09-09 10:20:34</td>
              <td>maria</td>
            </tr>
            <tr>
              <td>4</td>
              <td>Generated Tax Report</td>
              <td>2024-09-09 11:05:47</td>
              <td>admin</td>
            </tr>
            <tr>
              <td>5</td>
              <td>Logged out of the system</td>
              <td>2024-09-09 11:30:12</td>
              <td>jdoe</td>
            </tr>
          </tbody>
        </table>
      </div>
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
