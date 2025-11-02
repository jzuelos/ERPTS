<?php
session_start();
require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/**
 * Function to log activity
 */
function logActivity($conn, $userId, $action)
{
  $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)");
  $stmt->bind_param("is", $userId, $action);
  $stmt->execute();
  $stmt->close();
}

// Function to unban IP address (move to history and delete from ip_lockout)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["unban_ip"])) {
  $lockoutId = filter_input(INPUT_POST, 'lockout_id', FILTER_SANITIZE_NUMBER_INT);
  $ipAddress = filter_input(INPUT_POST, 'ip_address', FILTER_SANITIZE_STRING);

  // Get lockout data first
  $getLockout = $conn->prepare("SELECT ip_address, last_attempt FROM ip_lockout WHERE id = ?");
  $getLockout->bind_param("i", $lockoutId);
  $getLockout->execute();
  $lockoutData = $getLockout->get_result()->fetch_assoc();
  $getLockout->close();

  if ($lockoutData && isset($_SESSION['user_id'])) {
    $adminId = $_SESSION['user_id'];

    // Get admin name
    $getAdmin = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) as full_name FROM users WHERE user_id = ?");
    $getAdmin->bind_param("i", $adminId);
    $getAdmin->execute();
    $adminData = $getAdmin->get_result()->fetch_assoc();
    $adminName = $adminData['full_name'] ?? 'Unknown';
    $getAdmin->close();

    // Insert into history table
    $insertHistory = $conn->prepare("INSERT INTO ip_ban_history (ip_address, banned_at, unbanned_at, unbanned_by, unbanned_by_name) VALUES (?, ?, NOW(), ?, ?)");
    $insertHistory->bind_param("ssis", $lockoutData['ip_address'], $lockoutData['last_attempt'], $adminId, $adminName);

    if ($insertHistory->execute()) {
      // Delete from ip_lockout
      $deleteLockout = $conn->prepare("DELETE FROM ip_lockout WHERE id = ?");
      $deleteLockout->bind_param("i", $lockoutId);

      if ($deleteLockout->execute()) {
        logActivity($conn, $adminId, "Unbanned IP address: $ipAddress");
        echo "<script>alert('IP address unbanned successfully!'); window.location.href='User-Control.php';</script>";
      } else {
        echo "<script>alert('Error deleting lockout: " . $deleteLockout->error . "');</script>";
      }
      $deleteLockout->close();
    } else {
      echo "<script>alert('Error adding to history: " . $insertHistory->error . "');</script>";
    }
    $insertHistory->close();
  } else {
    echo "<script>alert('IP lockout not found or user not logged in');</script>";
  }
}

// Function to update user details with input filtering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
  // Server-side filtering & sanitization
  $userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT);
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
  $middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_STRING);
  $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
  $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
  $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);
  $marital_status = filter_input(INPUT_POST, 'marital_status', FILTER_SANITIZE_STRING);
  $tin = filter_input(INPUT_POST, 'tin', FILTER_SANITIZE_STRING);
  $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_STRING);
  $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
  $municipality = filter_input(INPUT_POST, 'municipality', FILTER_SANITIZE_NUMBER_INT);
  $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_NUMBER_INT);
  $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_NUMBER_INT);

  // Check for valid email
  if (!$email) {
    echo "<script>alert('Invalid email address'); window.location.href='User-Control.php';</script>";
    exit();
  }

  // Fetch old user data before updating
  $oldStmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
  $oldStmt->bind_param("i", $userId);
  $oldStmt->execute();
  $oldResult = $oldStmt->get_result();
  $oldData = $oldResult->fetch_assoc();
  $oldStmt->close();

  if (!$oldData) {
    echo "<script>alert('User not found.'); window.location.href='User-Control.php';</script>";
    exit();
  }

  // Build UPDATE query (with or without password)
  if (!empty($_POST["password"])) {
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
                username = ?, password = ?, first_name = ?, middle_name = ?, last_name = ?, 
                gender = ?, birthdate = ?, marital_status = ?, tin = ?, 
                contact_number = ?, email = ?, user_type = ?, status = ?,
                m_id = ?, district_id = ?, brgy_id = ?
              WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
      "ssssssssssssiiiii",
      $username,
      $password,
      $first_name,
      $middle_name,
      $last_name,
      $gender,
      $birthdate,
      $marital_status,
      $tin,
      $contact_number,
      $email,
      $user_type,
      $status,
      $municipality,
      $district,
      $barangay,
      $userId
    );
  } else {
    $query = "UPDATE users SET 
                username = ?, first_name = ?, middle_name = ?, last_name = ?, 
                gender = ?, birthdate = ?, marital_status = ?, tin = ?, 
                contact_number = ?, email = ?, user_type = ?, status = ?,
                m_id = ?, district_id = ?, brgy_id = ?
              WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
      "sssssssssssiiiii",
      $username,
      $first_name,
      $middle_name,
      $last_name,
      $gender,
      $birthdate,
      $marital_status,
      $tin,
      $contact_number,
      $email,
      $user_type,
      $status,
      $municipality,
      $district,
      $barangay,
      $userId
    );
  }

  if ($stmt->execute()) {
    // Log admin activity with proper formatting
    if (isset($_SESSION['user_id'])) {
      $adminId = $_SESSION['user_id'];
      $fullname = trim("$first_name $middle_name $last_name");

      // Helper function to get municipality name
      function getMunicipalityName($conn, $m_id)
      {
        if (empty($m_id))
          return 'None';
        $stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
        $stmt->bind_param("i", $m_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row['m_description'] : "ID: $m_id";
      }

      // Helper function to get district name
      function getDistrictName($conn, $district_id)
      {
        if (empty($district_id))
          return 'None';
        $stmt = $conn->prepare("SELECT description FROM district WHERE district_id = ?");
        $stmt->bind_param("i", $district_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row['description'] : "ID: $district_id";
      }

      // Helper function to get barangay name
      function getBarangayName($conn, $brgy_id)
      {
        if (empty($brgy_id))
          return 'None';
        $stmt = $conn->prepare("SELECT brgy_name FROM brgy WHERE brgy_id = ?");
        $stmt->bind_param("i", $brgy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row['brgy_name'] : "ID: $brgy_id";
      }

      // Compare changes with readable formats
      $changes = [];

      if ($oldData['username'] !== $username) {
        $changes[] = "• Username changed from '{$oldData['username']}' to '$username'";
      }
      if ($oldData['first_name'] !== $first_name) {
        $changes[] = "• First Name changed from '{$oldData['first_name']}' to '$first_name'";
      }
      if ($oldData['middle_name'] !== $middle_name) {
        $changes[] = "• Middle Name changed from '{$oldData['middle_name']}' to '$middle_name'";
      }
      if ($oldData['last_name'] !== $last_name) {
        $changes[] = "• Last Name changed from '{$oldData['last_name']}' to '$last_name'";
      }
      if ($oldData['gender'] !== $gender) {
        $changes[] = "• Gender changed from '{$oldData['gender']}' to '$gender'";
      }
      if ($oldData['birthdate'] !== $birthdate) {
        $changes[] = "• Birthdate changed from '{$oldData['birthdate']}' to '$birthdate'";
      }
      if ($oldData['marital_status'] !== $marital_status) {
        $changes[] = "• Marital Status changed from '{$oldData['marital_status']}' to '$marital_status'";
      }
      if ($oldData['tin'] !== $tin) {
        $changes[] = "• TIN changed from '{$oldData['tin']}' to '$tin'";
      }
      if ($oldData['contact_number'] !== $contact_number) {
        $changes[] = "• Contact Number changed from '{$oldData['contact_number']}' to '$contact_number'";
      }
      if ($oldData['email'] !== $email) {
        $changes[] = "• Email changed from '{$oldData['email']}' to '$email'";
      }
      if ($oldData['user_type'] !== $user_type) {
        $changes[] = "• Role changed from '{$oldData['user_type']}' to '$user_type'";
      }

      // Format status as Enabled/Disabled
      if ($oldData['status'] != $status) {
        $oldStatus = ($oldData['status'] == 1) ? 'Enabled' : 'Disabled';
        $newStatus = ($status == 1) ? 'Enabled' : 'Disabled';
        $changes[] = "• Status changed from '$oldStatus' to '$newStatus'";
      }

      // Show municipality name instead of ID
      if ($oldData['m_id'] != $municipality) {
        $oldMunName = getMunicipalityName($conn, $oldData['m_id']);
        $newMunName = getMunicipalityName($conn, $municipality);
        $changes[] = "• Municipality changed from '$oldMunName' to '$newMunName'";
      }

      // Show district name instead of ID
      if ($oldData['district_id'] != $district) {
        $oldDistName = getDistrictName($conn, $oldData['district_id']);
        $newDistName = getDistrictName($conn, $district);
        $changes[] = "• District changed from '$oldDistName' to '$newDistName'";
      }

      // Show barangay name instead of ID
      if ($oldData['brgy_id'] != $barangay) {
        $oldBrgyName = getBarangayName($conn, $oldData['brgy_id']);
        $newBrgyName = getBarangayName($conn, $barangay);
        $changes[] = "• Barangay changed from '$oldBrgyName' to '$newBrgyName'";
      }

      // Check if password was changed
      if (!empty($_POST["password"])) {
        $changes[] = "• Password was updated";
      }

      // Create readable multiline log text
      $logMessage = "Updated user account\n";
      $logMessage .= "Username: $username\n";
      $logMessage .= "Full Name: $fullname\n";
      $logMessage .= "Role: $user_type\n\n";

      if (!empty($changes)) {
        $logMessage .= "Changes:\n" . implode("\n", $changes);
      } else {
        $logMessage .= "No changes detected.";
      }

      // Save clean message
      logActivity($conn, $adminId, $logMessage);
    }

    echo "<script>alert('User updated successfully!'); window.location.href='User-Control.php';</script>";
  } else {
    echo "<script>alert('Error updating user: " . $stmt->error . "');</script>";
  }

  $stmt->close();
}

// ---------- LOAD DATA FOR DROPDOWNS ---------- //
$username = $_SESSION['username'] ?? 'Admin';

// Municipalities
$municipalities = [];
$mun_query = "SELECT m_id, m_description FROM municipality WHERE m_status = 1 ORDER BY m_description";
$mun_result = $conn->query($mun_query);
if ($mun_result) {
  while ($row = $mun_result->fetch_assoc()) {
    $municipalities[] = $row;
  }
  $mun_result->free();
}

// Districts
$districts = [];
$dist_query = "SELECT district_id, district_code, description, m_id FROM district WHERE status = 1 ORDER BY description";
$dist_result = $conn->query($dist_query);
if ($dist_result) {
  while ($row = $dist_result->fetch_assoc()) {
    $districts[] = $row;
  }
  $dist_result->free();
}

// Barangays
$barangays = [];
$brgy_query = "SELECT brgy_id, brgy_name, m_id FROM brgy WHERE status = 1 ORDER BY brgy_name";
$brgy_result = $conn->query($brgy_query);
if ($brgy_result) {
  while ($row = $brgy_result->fetch_assoc()) {
    $barangays[] = $row;
  }
  $brgy_result->free();
}

// Users
$query = "SELECT * FROM users";
$result = $conn->query($query);
$users = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  $result->free();
}

// Fetch ACTIVE IP Lockouts (only is_permanent = 1)
$ip_lockout = [];
$lockout_query = "SELECT * FROM ip_lockout WHERE is_permanent = 1 ORDER BY last_attempt DESC";
$lockout_result = $conn->query($lockout_query);
if ($lockout_result) {
  while ($row = $lockout_result->fetch_assoc()) {
    $ip_lockout[] = $row;
  }
  $lockout_result->free();
}

// Fetch IP Ban History
$ip_history = [];
$history_query = "SELECT history_id, ip_address, banned_at, unbanned_at, unbanned_by, unbanned_by_name 
                  FROM ip_ban_history 
                  ORDER BY unbanned_at DESC";
$history_result = $conn->query($history_query);
if ($history_result) {
  while ($row = $history_result->fetch_assoc()) {
    $ip_history[] = $row;
  }
  $history_result->free();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Control</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="User-Control.css">
</head>

<body>
  <?php include 'header.php'; ?>

  <!-- Pass PHP data to JavaScript -->
  <script>
    const districtsData = <?php echo json_encode($districts); ?>;
    const barangaysData = <?php echo json_encode($barangays); ?>;
  </script>

  <div class="container py-4">
    <!-- SERVER STATUS + LOGGED IN INFO -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card border-success shadow-sm">
          <div class="card-body text-center text-success fw-bold">
            <i class="bi bi-server me-2"></i> Server Status: Online
          </div>
        </div>
      </div>

      <div class="col-md-6 offset-md-1">
        <div class="alert alert-info text-center shadow-sm mb-0">
          <i class="bi bi-person-circle me-2"></i>
          Logged in as <strong><?= htmlspecialchars($username); ?></strong>
        </div>
      </div>
    </div>

    <!-- BACK BUTTON -->
    <div class="mb-4">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
      </a>
    </div>

    <!-- USERS SECTION -->
    <div class="table-section mx-auto" style="width: 85%;">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="fw-bold text-primary mb-3 mb-md-0">
          <i class="bi bi-people-fill me-2"></i> Users
        </h3>

        <div class="d-flex align-items-center flex-wrap gap-3">
          <!-- Add User -->
          <a href="ADD_User.php" class="btn btn-outline-primary">
            <i class="bi bi-person-plus"></i> Add User
          </a>

          <!-- Show/Hide Disabled Users -->
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="userStatusFilter" id="showDisabled" value="show" checked>
            <label class="form-check-label" for="showDisabled">Show Disabled User</label>
          </div>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="userStatusFilter" id="hideDisabled" value="hide">
            <label class="form-check-label" for="hideDisabled">Hide Disabled User</label>
          </div>

          <!-- Banned Accounts/IP Toggle -->
          <div class="form-check form-switch ms-3">
            <input class="form-check-input" type="checkbox" id="bannedToggle">
            <label class="form-check-label fw-semibold text-danger" for="bannedToggle">
              <i class="bi bi-shield-lock"></i> Banned IP Addresses
            </label>
          </div>
        </div>
      </div>

      <!-- USERS TABLES SECTION -->
      <!-- DEFAULT USERS TABLE -->
      <div id="defaultUsersTable" class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-dark text-white text-center">
            <tr>
              <th style="width: 8%">ID</th>
              <th style="width: 18%">Username</th>
              <th style="width: 25%">Full Name</th>
              <th style="width: 15%">User Type</th>
              <th style="width: 15%">Status</th>
              <th style="width: 10%">Actions</th>
            </tr>
          </thead>
          <tbody class="text-start">
            <?php foreach ($users as $user): ?>
              <tr class="border-bottom">
                <td><?= htmlspecialchars($user['user_id'] ?? '') ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($user['username'] ?? '') ?></td>
                <td><?= htmlspecialchars(trim("{$user['first_name']} {$user['middle_name']} {$user['last_name']}")) ?>
                </td>
                <td><?= htmlspecialchars($user['user_type'] ?? '') ?></td>
                <td>
                  <?php if ($user['status'] == 1): ?>
                    <span class="badge bg-success px-3 py-2">Enabled</span>
                  <?php else: ?>
                    <span class="badge bg-secondary px-3 py-2">Disabled</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <a href="#" data-toggle="modal" data-target="#editUserModal-<?= $user['user_id'] ?>"
                    class="btn btn-outline-primary btn-sm rounded-circle">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- BANNED IP ADDRESSES TABLE (is_permanent = 1) -->
      <div id="bannedTable" class="table-responsive d-none">
        <!-- IP Ban History Toggle inside Banned Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-danger mb-0">
            <i class="bi bi-shield-lock me-2"></i>Banned IP Addresses
          </h5>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="historyToggle">
            <label class="form-check-label fw-semibold text-secondary" for="historyToggle">
              <i class="bi bi-clock-history"></i> Show History
            </label>
          </div>
        </div>

        <table class="table table-hover align-middle mb-0">
          <thead class="bg-danger text-white text-center">
            <tr>
              <th style="width: 8%">ID</th>
              <th style="width: 20%">IP Address</th>
              <th style="width: 12%">Attempts</th>
              <th style="width: 20%">Locked Until</th>
              <th style="width: 15%">Status</th>
              <th style="width: 20%">Last Attempt</th>
              <th style="width: 15%">Actions</th>
            </tr>
          </thead>
          <tbody class="text-start">
            <?php if (empty($ip_lockout)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="bi bi-info-circle me-2"></i>No banned IP addresses found
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($ip_lockout as $lockout): ?>
                <tr>
                  <td><?= htmlspecialchars($lockout['id']) ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($lockout['ip_address']) ?></td>
                  <td><?= htmlspecialchars($lockout['attempts']) ?></td>
                  <td>
                    <?php
                    $lockUntil = $lockout['lock_until'];
                    if ($lockout['is_permanent'] == 1) {
                      echo '<span class="badge bg-dark">Permanent</span>';
                    } elseif ($lockUntil) {
                      echo date('Y-m-d H:i:s', strtotime($lockUntil));
                    } else {
                      echo '<span class="text-muted">N/A</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <span class="badge bg-danger">Permanent Ban</span>
                  </td>
                  <td><?= date('Y-m-d H:i:s', strtotime($lockout['last_attempt'])) ?></td>
                  <td class="text-center">
                    <button class="btn btn-outline-success btn-sm unban-btn" data-id="<?= $lockout['id'] ?>"
                      data-ip="<?= htmlspecialchars($lockout['ip_address']) ?>">
                      <i class="bi bi-unlock"></i> Unban
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- IP BAN HISTORY TABLE -->
      <div id="historyTable" class="table-responsive d-none">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-secondary text-white text-center">
            <tr>
              <th style="width: 8%">ID</th>
              <th style="width: 20%">IP Address</th>
              <th style="width: 20%">Banned At</th>
              <th style="width: 20%">Unbanned At</th>
              <th style="width: 32%">Unbanned By</th>
            </tr>
          </thead>
          <tbody class="text-start">
            <?php if (empty($ip_history)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  <i class="bi bi-info-circle me-2"></i>No IP ban history found
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($ip_history as $history): ?>
                <tr>
                  <td><?= htmlspecialchars($history['history_id']) ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($history['ip_address']) ?></td>
                  <td>
                    <?php
                    echo !empty($history['banned_at'])
                      ? date('Y-m-d H:i:s', strtotime($history['banned_at']))
                      : '<span class="text-muted">N/A</span>';
                    ?>
                  </td>
                  <td>
                    <?php
                    echo !empty($history['unbanned_at'])
                      ? date('Y-m-d H:i:s', strtotime($history['unbanned_at']))
                      : '<span class="text-muted">N/A</span>';
                    ?>
                  </td>
                  <td>
                    <?php
                    $name = trim($history['unbanned_by_name'] ?? '');
                    if ($name !== '') {
                      echo htmlspecialchars($name);
                    } else {
                      echo '<span class="text-muted">ID: ' . htmlspecialchars($history['unbanned_by'] ?? 'N/A') . '</span>';
                    }
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>




  <?php foreach ($users as $user): ?>
    <div class="modal fade" id="editUserModal-<?= $user['user_id'] ?>" tabindex="-1" aria-labelledby="editUserModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

          <!-- Modal Header -->
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-pencil-square me-2"></i> Edit User
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!-- Modal Form -->
          <form action="" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="update_user" value="1">

            <div class="modal-body">
              <div class="row g-4">

                <!-- LEFT COLUMN -->
                <div class="col-md-6">
                  <h6 class="fw-bold text-success">User Credentials</h6>
                  <hr class="mt-1 mb-3">

                  <div class="mb-3">
                    <label class="form-label">User ID</label>
                    <input type="text" class="form-control" name="userId"
                      value="<?= htmlspecialchars($user['user_id'] ?? '') ?>" readonly>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username"
                      value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password"
                      placeholder="Enter new password (leave blank to keep current)">
                  </div>

                  <h6 class="fw-bold text-success mt-4">Personal Information</h6>
                  <hr class="mt-1 mb-3">

                  <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name"
                      value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name"
                      value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="middle_name"
                      value="<?= htmlspecialchars($user['middle_name'] ?? '') ?>">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                      <option value="Male" <?= ($user['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                      <option value="Female" <?= ($user['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                  </div>

                  <h6 class="fw-bold text-success mt-4">User Settings</h6>
                  <hr class="mt-1 mb-3">

                  <div class="mb-3">
                    <label class="form-label">User Type</label>
                    <select class="form-select" name="user_type">
                      <option value="Admin" <?= ($user['user_type'] == 'Admin') ? 'selected' : '' ?>>admin</option>
                      <option value="User" <?= ($user['user_type'] == 'User') ? 'selected' : '' ?>>user</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                      <option value="1" <?= ($user['status'] == 1) ? 'selected' : '' ?>>Enabled</option>
                      <option value="0" <?= ($user['status'] == 0) ? 'selected' : '' ?>>Disabled</option>
                    </select>
                  </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-md-6">
                  <h6 class="fw-bold text-success">Additional Details</h6>
                  <hr class="mt-1 mb-3">

                  <div class="mb-3">
                    <label class="form-label">Birthdate</label>
                    <input type="date" class="form-control" name="birthdate"
                      value="<?= htmlspecialchars($user['birthdate'] ?? '') ?>">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Marital Status</label>
                    <input type="text" class="form-control" name="marital_status"
                      value="<?= htmlspecialchars($user['marital_status'] ?? '') ?>">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">TIN</label>
                    <input type="text" class="form-control" name="tin"
                      value="<?= htmlspecialchars($user['tin'] ?? '') ?>">
                  </div>

                  <h6 class="fw-bold text-success mt-4">Contact Information</h6>
                  <hr class="mt-1 mb-3">

                  <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" name="contact_number"
                      value="<?= htmlspecialchars($user['contact_number'] ?? '') ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email"
                      value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                  </div>

                  <h6 class="fw-bold text-success mt-4">Location</h6>
                  <hr class="mt-1 mb-3">

                  <div class="mb-3">
                    <label class="form-label">Municipality</label>
                    <select class="form-select municipality-select" name="municipality"
                      data-user-id="<?= $user['user_id'] ?>">
                      <option value="">Select municipality</option>
                      <?php foreach ($municipalities as $mun): ?>
                        <option value="<?= $mun['m_id'] ?>" <?= (isset($user['m_id']) && $user['m_id'] == $mun['m_id']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($mun['m_description']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">District</label>
                    <select class="form-select district-select" name="district" data-user-id="<?= $user['user_id'] ?>"
                      disabled>
                      <option value="">Select district</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Barangay</label>
                    <select class="form-select barangay-select" name="barangay" data-user-id="<?= $user['user_id'] ?>"
                      data-selected-id="<?= $user['brgy_id'] ?? '' ?>" disabled>
                      <option value="">Select barangay</option>
                    </select>
                  </div>
                </div>

              </div>
            </div>

            <!-- Footer Buttons -->
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Close
              </button>
              <button type="reset" class="btn btn-primary">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
              </button>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Save changes
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- UNBAN CONFIRMATION MODAL -->
  <div class="modal fade" id="confirmUnbanModal" tabindex="-1" aria-labelledby="confirmUnbanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-success shadow">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="confirmUnbanModalLabel">
            <i class="bi bi-unlock"></i> Confirm Unban IP Address
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" id="unbanForm">
          <input type="hidden" name="unban_ip" value="1">
          <input type="hidden" name="lockout_id" id="unbanLockoutId">
          <input type="hidden" name="ip_address" id="unbanIpAddress">

          <div class="modal-body text-center">
            <p class="fw-semibold mb-3">
              Are you sure you want to unban the IP address <span id="unbanTarget" class="text-danger"></span>?
            </p>
            <p class="text-muted small">This will move the IP to history and remove all restrictions.</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-unlock"></i> Yes, Unban
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>




  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

  <script>
    $(document).ready(function () {
      // User status filter
      $("input[name='userStatusFilter']").change(function () {
        var showDisabled = $("#showDisabled").is(":checked");
        $("#defaultUsersTable tbody tr").each(function () {
          var statusText = $(this).find("td:eq(4)").text().trim();
          if (statusText === "Disabled") {
            $(this).toggle(showDisabled);
          }
        });
      });

      // Function to populate districts based on municipality (auto-filled, always disabled)
      function populateDistricts(municipalityId, districtSelect, selectedDistrictId = null) {
        districtSelect.empty();
        districtSelect.append('<option value="">Select district</option>');

        if (municipalityId) {
          const filteredDistricts = districtsData.filter(d => d.m_id == municipalityId);
          filteredDistricts.forEach(district => {
            const selected = selectedDistrictId && district.district_id == selectedDistrictId ? 'selected' : '';
            districtSelect.append(`<option value="${district.district_id}" ${selected}>${district.description}</option>`);
          });

          // Auto-select if only one district exists
          if (filteredDistricts.length === 1) {
            districtSelect.val(filteredDistricts[0].district_id);
          }
        }

        // Always keep district disabled
        districtSelect.prop('disabled', true);
      }

      // Function to populate barangays based on municipality and auto-select user's saved barangay
      function populateBarangays(municipalityId, barangaySelect, selectedBarangayId = null) {
        barangaySelect.empty();
        barangaySelect.append('<option value="">Select barangay</option>');

        if (municipalityId) {
          const filteredBarangays = barangaysData.filter(b => b.m_id == municipalityId);
          filteredBarangays.forEach(barangay => {
            const selected = selectedBarangayId && barangay.brgy_id == selectedBarangayId ? 'selected' : '';
            barangaySelect.append(`<option value="${barangay.brgy_id}" ${selected}>${barangay.brgy_name}</option>`);
          });

          // Enable dropdown if barangays exist
          barangaySelect.prop('disabled', filteredBarangays.length === 0);
        } else {
          barangaySelect.prop('disabled', true);
        }
      }

      // Initialize dropdowns when modal opens (edit user)
      $('.modal').on('shown.bs.modal', function () {
        const modal = $(this);
        const municipalitySelect = modal.find('.municipality-select');
        const districtSelect = modal.find('.district-select');
        const barangaySelect = modal.find('.barangay-select');

        const selectedMunicipalityId = municipalitySelect.val();
        const selectedDistrictId = districtSelect.data('selected-id');
        const selectedBarangayId = barangaySelect.data('selected-id'); // saved barangay ID from user

        if (selectedMunicipalityId) {
          populateDistricts(selectedMunicipalityId, districtSelect, selectedDistrictId);
          populateBarangays(selectedMunicipalityId, barangaySelect, selectedBarangayId);
        }
      });

      // Municipality change event (updates both district + barangay)
      $('.municipality-select').on('change', function () {
        const municipalityId = $(this).val();
        const userId = $(this).data('user-id');
        const districtSelect = $(`.district-select[data-user-id="${userId}"]`);
        const barangaySelect = $(`.barangay-select[data-user-id="${userId}"]`);

        // reset and repopulate
        populateDistricts(municipalityId, districtSelect);
        populateBarangays(municipalityId, barangaySelect);
      });
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const bannedToggle = document.getElementById("bannedToggle");
      const historyToggle = document.getElementById("historyToggle");
      const defaultTable = document.getElementById("defaultUsersTable");
      const bannedTable = document.getElementById("bannedTable");
      const historyTable = document.getElementById("historyTable");

      bannedToggle.addEventListener("change", function () {
        if (this.checked) {
          bannedTable.classList.remove("d-none");
          defaultTable.classList.add("d-none");
          // Reset history toggle when switching to banned view
          historyToggle.checked = false;
          historyTable.classList.add("d-none");
        } else {
          bannedTable.classList.add("d-none");
          defaultTable.classList.remove("d-none");
          historyTable.classList.add("d-none");
        }
      });

      historyToggle.addEventListener("change", function () {
        if (this.checked) {
          historyTable.classList.remove("d-none");
          bannedTable.querySelector('table').classList.add("d-none");
        } else {
          historyTable.classList.add("d-none");
          bannedTable.querySelector('table').classList.remove("d-none");
        }
      });
    });
  </script>


  <script>
    // Unban Confirmation Modal with Bootstrap 5
    document.addEventListener('DOMContentLoaded', () => {
      const unbanButtons = document.querySelectorAll('.unban-btn');
      const confirmUnbanModal = new bootstrap.Modal(document.getElementById('confirmUnbanModal'));
      const unbanTargetSpan = document.getElementById('unbanTarget');
      const unbanLockoutIdInput = document.getElementById('unbanLockoutId');
      const unbanIpAddressInput = document.getElementById('unbanIpAddress');

      // When an Unban button is clicked
      unbanButtons.forEach(button => {
        button.addEventListener('click', () => {
          const lockoutId = button.getAttribute('data-id');
          const ipAddress = button.getAttribute('data-ip');

          unbanTargetSpan.textContent = ipAddress;
          unbanLockoutIdInput.value = lockoutId;
          unbanIpAddressInput.value = ipAddress;

          confirmUnbanModal.show();
        });
      });
    });
  </script>

</body>

</html>