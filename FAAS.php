<?php
session_start();

// Cache control headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'database.php';
$conn = Database::getInstance();

// Check connection (only works for mysqli, remove if using PDO)
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle the modal form POST (non-AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
  // Simple CSRF suggestion: you could check a token here.
  $owner_id = intval($_POST['id'] ?? 0);
  $first = trim($_POST['first_name'] ?? '');
  $middle = trim($_POST['middle_name'] ?? '');
  $last = trim($_POST['last_name'] ?? '');

  // Basic validation
  if ($owner_id <= 0) {
    $_SESSION['flash_error'] = 'Invalid owner id.';
  } else {
    // Prepare and run update (adjust table/column names to match your DB)
    $stmt = $conn->prepare("
            UPDATE owners_tb
               SET own_fname = ?, own_mname = ?, own_surname = ?
             WHERE own_id = ?
        ");

    if ($stmt) {
      $stmt->bind_param('sssi', $first, $middle, $last, $owner_id);
      if ($stmt->execute()) {
        $_SESSION['flash_success'] = 'Owner updated successfully.';
      } else {
        $_SESSION['flash_error'] = 'Failed to update owner: ' . $stmt->error;
      }
      $stmt->close();
    } else {
      $_SESSION['flash_error'] = 'DB prepare failed: ' . $conn->error;
    }
  }

  // Redirect back so the page reloads and shows updated data (Post/Redirect/Get)
  $return_id = isset($_GET['id']) ? intval($_GET['id']) : '';
  header('Location: ' . $_SERVER['PHP_SELF'] . ($return_id ? '?id=' . urlencode($return_id) : ''));
  exit;
}

// Fetch faas_id from GET parameter
$property_id = $_GET['id'] ?? null;

// Default values
$is_active = 1;
$disabled_at = null;

if ($property_id) {
  $stmt = $conn->prepare("SELECT is_active, disabled_at FROM p_info WHERE p_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  $is_active = $row['is_active'] ?? 1;
  $disabled_at = $row['disabled_at'] ?? null;
}

$disableButton = ($is_active == 0) ? 'disabled' : '';

// === Disable property (Cancel RPU by disabling p_info) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'disable_property') {
  // Auth check: only admins
  $user_role = $_SESSION['user_type'] ?? 'user';
  $current_user_id = $_SESSION['user_id'] ?? null; // for audit
  if ($user_role !== 'admin') {
    $_SESSION['flash_error'] = "Permission denied. Only admins can disable properties.";
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_POST['return_p_id'] ?? ''));
    exit;
  }

  $property_p_id = intval($_POST['p_id'] ?? 0);
  if ($property_p_id <= 0) {
    $_SESSION['flash_error'] = "Invalid property id.";
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_POST['return_p_id'] ?? ''));
    exit;
  }

  // Get faas info for this property (re-using your helper if exists)
  $stmtF = $conn->prepare("SELECT faas_id FROM faas WHERE pro_id = ?");
  $stmtF->bind_param("i", $property_p_id);
  $stmtF->execute();
  $resF = $stmtF->get_result();
  $faas_row = $resF ? $resF->fetch_assoc() : null;
  $faas_id_to_check = $faas_row['faas_id'] ?? null;
  $stmtF->close();

  // If there's a faas_id, re-check rpu_dec for that faas_id (disallow disable if tax declaration exists)
  if (!empty($faas_id_to_check)) {
    $chk = $conn->prepare("SELECT dec_id FROM rpu_dec WHERE faas_id = ?");
    $chk->bind_param("i", $faas_id_to_check);
    $chk->execute();
    $chk_res = $chk->get_result();
    if ($chk_res && $chk_res->num_rows > 0) {
      $_SESSION['flash_error'] = "Cannot disable property: tax declaration already exists for this FAAS.";
      $chk->close();
      header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($property_p_id));
      exit;
    }
    $chk->close();
  }

  // OK — disable the p_info row (set is_active = 0) and store audit info
  $upd = $conn->prepare("UPDATE p_info SET is_active = 0, disabled_at = NOW(), disabled_by = ? WHERE p_id = ?");
  $upd->bind_param("ii", $current_user_id, $property_p_id);
  if ($upd->execute()) {
    $_SESSION['flash_success'] = "Property #{$property_p_id} cancelled.";
  } else {
    $_SESSION['flash_error'] = "Failed to disable property: " . $upd->error;
  }
  $upd->close();

  // Redirect back to the same view (fresh page)
  header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($property_p_id));
  exit;
}

// Utility function to safely fetch a property by ID
function fetchProperty($conn, $p_id)
{
  $sql = "
    SELECT 
      p.p_id, 
      p.house_no, 
      p.block_no, 
      p.province, 
      p.city, 
      p.district, 
      p.barangay, 
      p.street,
      p.house_tag_no,
      p.land_area, 
      p.desc_land, 
      p.documents, 
      p.created_at, 
      p.updated_at, 
      p.is_active,
      p.disabled_at,
      p.disabled_by
    FROM p_info p
    WHERE p.p_id = ?
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $p_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}


// Fetch owners list
function fetchOwners($conn)
{
  $sql = "
    SELECT own_id,
           CONCAT(own_fname, ' ', own_mname, ' ', own_surname) AS owner_name,
           CONCAT(house_no, ', ', barangay, ', ', city, ', ', province) AS address
    FROM owners_tb
  ";
  $result = $conn->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch junction table (propertyowner) owner IDs
function fetchOwnersByIds($conn, $owner_ids)
{
  if (empty($owner_ids)) {
    return [];
  }

  $ids = implode(',', array_map('intval', $owner_ids));
  $sql = "
        SELECT own_id, 
               CONCAT(own_fname, ' ', COALESCE(own_mname, ''), ' ', own_surname) AS owner_name,
               own_fname AS first_name, 
               own_mname AS middle_name, 
               own_surname AS last_name,
               COALESCE(owner_type, 'individual') as owner_type,
               company_name
        FROM owners_tb
        WHERE own_id IN ($ids)
    ";
  $result = $conn->query($sql);
  return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Fetch owner details by a list of IDs
function fetchOwnersWithDetails($conn, $property_id)
{
  $sql = "
        SELECT 
            o.own_id,
            o.own_fname AS first_name,
            o.own_mname AS middle_name,
            o.own_surname AS last_name,
            COALESCE(o.owner_type, 'individual') as owner_type,
            o.company_name,
            CASE 
                WHEN COALESCE(o.owner_type, 'individual') = 'company' THEN 
                    COALESCE(o.company_name, 'Unnamed Company')
                ELSE CONCAT(
                    TRIM(COALESCE(o.own_fname, '')), 
                    CASE WHEN o.own_mname IS NOT NULL AND TRIM(o.own_mname) != '' 
                         THEN CONCAT(' ', TRIM(o.own_mname)) 
                         ELSE '' END,
                    CASE WHEN o.own_surname IS NOT NULL AND TRIM(o.own_surname) != ''
                         THEN CONCAT(' ', TRIM(o.own_surname))
                         ELSE '' END
                )
            END AS display_name
        FROM propertyowner po
        JOIN owners_tb o ON po.owner_id = o.own_id
        WHERE po.property_id = ?
        ORDER BY 
            o.owner_type DESC, 
            CASE WHEN o.owner_type = 'company' THEN o.company_name ELSE o.own_surname END,
            o.own_fname
    ";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    error_log("SQL prepare failed: " . $conn->error);
    return [];
  }

  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $result = $stmt->get_result();

  return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

//fetch property owner ids
function fetchPropertyOwnerIDs($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT owner_id FROM propertyowner WHERE property_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return array_column($result->fetch_all(MYSQLI_ASSOC), 'owner_id');
}

function fetchFaasInfo($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT faas_id FROM faas WHERE pro_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

// Fetch faas_id from GET parameter(important echo DO NOT DELETE) null;
$faas_info = fetchFaasInfo($conn, $property_id);
if ($faas_info) {
  $faas_id = $faas_info['faas_id'];

  echo "<div id='faas-id'>Faas ID: " . htmlspecialchars($faas_id) . "</div>";
}

// Fetch RPU ID and details
function fetchRPUDetails($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT rpu_idno FROM faas WHERE pro_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $rpu_id = $stmt->get_result()->fetch_assoc()['rpu_idno'] ?? null;

  if (!$rpu_id)
    return null;

  $stmt = $conn->prepare("SELECT arp, pin, taxability, effectivity FROM rpu_idnum WHERE rpu_id = ?");
  $stmt->bind_param("i", $rpu_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

// Fetch land records tied to faas_id
function fetchLandRecords($conn, $faas_id)
{
  $stmt = $conn->prepare("SELECT * FROM land WHERE faas_id = ?");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Calculate total land values (market and assess)
function calculateTotalLandValues($conn, $faas_id)
{
  $stmt = $conn->prepare("
    SELECT 
      SUM(market_value) AS total_market_value, 
      SUM(assess_value) AS total_assess_value 
    FROM land 
    WHERE faas_id = ?
  ");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc(); // returns associative array with total values
}

//Fetch RPU Declaration 1
function fetchRPUDeclaration($conn, $faas_id)
{
  $stmt = $conn->prepare("
    SELECT dec_id, arp_no, pro_assess, pro_date, mun_assess, mun_date,
           td_cancel, previous_pin, tax_year, entered_by, entered_year,
           prev_own, prev_assess, faas_id, total_property_value
    FROM rpu_dec
    WHERE faas_id = ?
  ");

  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc(); // returns associative array of the record
}

// MAIN: Begin processing
$property = null;
$owners = [];
$owners_details = [];
$rpu_details = null;
$landRecords = [];
$faas_id = null;
$totalMarketValue = 0;
$totalAssessedValue = 0;

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $property_id = intval($_GET['id']);

  // Fetch main property
  $property = fetchProperty($conn, $property_id);

  // Fetch faas info
  $faas_info = fetchFaasInfo($conn, $property_id);
  if ($faas_info) {
    $faas_id = $faas_info['faas_id'];

    // Fetch RPU Declaration
    $rpu_declaration = fetchRPUDeclaration($conn, $faas_id);

    // Calculate land values
    $totals = calculateTotalLandValues($conn, $faas_id);
    $totalMarketValue = $totals['total_market_value'] ?? 0;
    $totalAssessedValue = $totals['total_assess_value'] ?? 0;

    // Fetch owner IDs
    $owner_ids = fetchPropertyOwnerIDs($conn, $property_id);

    // Fetch owner details
    if (!empty($owner_ids)) {
      $owners_details = fetchOwnersWithDetails($conn, $property_id);
    }

    // Fetch land records
    $landRecords = fetchLandRecords($conn, $faas_id);
    $land_id = isset($landRecords[0]['land_id']) ? $landRecords[0]['land_id'] : null;
  }

  // Fetch RPU details
  $rpu_details = fetchRPUDetails($conn, $property_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $arp_no = $_POST['arp_no'] ?? 0;
  $pro_assess = $_POST['pro_assess'] ?? '';
  $pro_date = $_POST['pro_date'] ?? '';
  $mun_assess = $_POST['mun_assess'] ?? '';
  $mun_date = $_POST['mun_date'] ?? '';
  $td_cancel = $_POST['td_cancel'] ?? 0;
  $previous_pin = $_POST['previous_pin'] ?? 0;
  $tax_year = $_POST['tax_year'] ?? '';
  $entered_by = $_POST['entered_by'] ?? 0;
  $entered_year = $_POST['entered_year'] ?? '';
  $prev_own = $_POST['prev_own'] ?? '';
  $prev_assess = $_POST['prev_assess'] ?? 0.00;

  // Calculate total property value (market + assessed)
  $totals = calculateTotalLandValues($conn, $faas_id);
  $total_market_value = $totals['total_market_value'] ?? 0;
  $total_assess_value = $totals['total_assess_value'] ?? 0;
  $total_property_value = $total_market_value + $total_assess_value;

  // Check if the faas_id already exists in the rpu_dec table
  $check_stmt = $conn->prepare("SELECT * FROM rpu_dec WHERE faas_id = ?");
  if ($check_stmt) {
    $check_stmt->bind_param("i", $faas_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
      // FAAS already exists → UPDATE
      $update_stmt = $conn->prepare("UPDATE rpu_dec SET
        arp_no = ?, pro_assess = ?, pro_date = ?, mun_assess = ?, mun_date = ?,
        td_cancel = ?, previous_pin = ?, tax_year = ?, entered_by = ?, entered_year = ?,
        prev_own = ?, prev_assess = ?, total_property_value = ?
        WHERE faas_id = ?");

      if ($update_stmt) {
        $update_stmt->bind_param(
          "issssiissssdid",
          $arp_no,
          $pro_assess,
          $pro_date,
          $mun_assess,
          $mun_date,
          $td_cancel,
          $previous_pin,
          $tax_year,
          $entered_by,
          $entered_year,
          $prev_own,
          $prev_assess,
          $total_property_value,
          $faas_id
        );

        if (!$update_stmt->execute()) {
          echo "Update failed: " . $update_stmt->error . "<br>";
        } else {
          echo "<script>alert('Updated: Tax Declaration updated for FAAS ID $faas_id. Total Property Value: ₱" . number_format($total_property_value, 2) . "');</script>";
          header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_GET['id']));
          exit;
        }

        $update_stmt->close();
      } else {
        echo "Update prepare failed: " . $conn->error;
      }
    } else {
      // FAAS does not exist → INSERT
      $insert_stmt = $conn->prepare("INSERT INTO rpu_dec (
        arp_no, pro_assess, pro_date, mun_assess, mun_date,
        td_cancel, previous_pin, tax_year, entered_by, entered_year,
        prev_own, prev_assess, faas_id, total_property_value
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

      if ($insert_stmt) {
        $insert_stmt->bind_param(
          "issssiissssdid",
          $arp_no,
          $pro_assess,
          $pro_date,
          $mun_assess,
          $mun_date,
          $td_cancel,
          $previous_pin,
          $tax_year,
          $entered_by,
          $entered_year,
          $prev_own,
          $prev_assess,
          $faas_id,
          $total_property_value
        );

        if (!$insert_stmt->execute()) {
          echo "Insert failed: " . $insert_stmt->error . "<br>";
        } else {
          echo "<script>alert('Added: New Tax Declaration added for FAAS ID $faas_id. Total Property Value: ₱" . number_format($total_property_value, 2) . "');</script>";
          header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_GET['id']));
          exit;
        }

        $insert_stmt->close();
      } else {
        echo "Insert prepare failed: " . $conn->error;
      }
    }

    $check_stmt->close();
  } else {
    echo "Prepare failed: " . $conn->error;
  }
}

// General owners list
$owners = fetchOwners($conn);

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="FAAS.css">
  <link rel="stylesheet" href="header.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
      <?php echo htmlspecialchars($_SESSION['flash_success']);
      unset($_SESSION['flash_success']); ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_SESSION['flash_error']);
      unset($_SESSION['flash_error']); ?>
    </div>
  <?php endif; ?>

  <?php if ($is_active == 0): ?>
    <div class="alert alert-warning text-center my-3">
      <strong>The RPU no longer appears in listings with all information tied to it discarded</strong>
      <br>
      <small>Disabled on: <?= htmlspecialchars($disabled_at) ?></small>
    </div>
  <?php endif; ?>

  <!--Main Body-->
  <!-- Owner's Information Section -->
  <section class="container mt-4" id="owner-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center">
        <a href="Real-Property-Unit-List.php" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        <h4 class="ms-3 mb-0">Owner's Information</h4>
      </div>
    </div>

    <?php
    if (!empty($property_id)) {
      $no_declaration = empty($rpu_declaration); // true if no rpu_dec present

      if ($is_active == 0): ?>
        <!-- Property already disabled -->
        <span class="btn btn-outline-secondary disabled" title="This property is already inactive.">
          <i class="fas fa-ban"></i> RPU Cancelled
        </span>

      <?php elseif ($no_declaration): ?>
        <!-- Can disable (only if active + no declaration) -->
        <form method="post" onsubmit="return confirm('Disable this property? This will mark the property inactive.');"
          class="d-inline">
          <input type="hidden" name="action" value="disable_property">
          <input type="hidden" name="p_id" value="<?php echo htmlspecialchars($property_id); ?>">
          <input type="hidden" name="return_p_id" value="<?php echo htmlspecialchars($property_id); ?>">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-ban"></i> Cancel RPU (Disable Property)
          </button>
        </form>

      <?php else: ?>
        <!-- Declaration exists, cannot disable -->
        <span class="btn btn-secondary disabled" title="Cannot disable: tax declaration exists for this property">
          <i class="fas fa-ban"></i> Cannot cancel RPU with TD encoded
        </span>
    <?php endif;
    }
    ?>

    <div class="card border-0 shadow p-4 rounded-3">
      <div id="owner-info" class="row">
        <?php if (empty($owners_details)): ?>
          <!-- No owners -->
          <div class="col-md-12 mb-4">
            <div class="alert alert-warning" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              No owner assigned to this property
            </div>
          </div>
        <?php else: ?>
          <!-- Display all owners properly -->
          <div class="col-md-12 mb-4">
            <h6 class="mb-3">Property Owners (<?= count($owners_details) ?>)</h6>
            <?php foreach ($owners_details as $index => $owner): ?>
              <!-- in your loop: render owner row with data attributes -->
              <div class="owner-item mb-3 p-3 bg-light rounded"
                data-owner-id="<?= (int)$owner['own_id'] ?>"
                data-first="<?= htmlspecialchars($owner['first_name'], ENT_QUOTES) ?>"
                data-middle="<?= htmlspecialchars($owner['middle_name'], ENT_QUOTES) ?>"
                data-last="<?= htmlspecialchars($owner['last_name'], ENT_QUOTES) ?>">
                <div class="d-flex justify-content-between">
                  <div>
                    <strong><?= htmlspecialchars($owner['display_name']) ?></strong>
                  </div>
                  <div>
                    <!-- pass the element; editOwner will use .closest('.owner-item') -->
                    <button class="btn btn-sm btn-outline-primary" type="button"
                      onclick="editOwner(this)">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" type="button"
                      onclick="removeOwner(<?= (int)$owner['own_id'] ?>)">🗑</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Modal for Editing Owner's Information -->
  <div class="modal fade" id="editOwnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="editOwnerForm" class="modal-content" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $property_id) ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" class="ownerIdInput" value="">
        <div class="modal-header">
          <h5 class="modal-title">Edit Owner</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input name="first_name" type="text" class="form-control firstNameModal" maxlength="50">
          </div>
          <div class="mb-3">
            <label class="form-label">Middle Name</label>
            <input name="middle_name" type="text" class="form-control middleNameModal" maxlength="50">
          </div>
          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input name="last_name" type="text" class="form-control lastNameModal" maxlength="50">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <!-- normal submit posts back to same PHP file and you handle $_POST['action']=='update' -->
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Property Information Section -->
  <section class="container my-5" id="property-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="section-title">Property Information</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" onclick="showEditPropertyModal()" <?= $disableButton ?>>Edit</button>
    </div>
    <div class="card border-0 shadow p-4 rounded-3">
      <form id="property-info">
        <div class="row">
          <input type="hidden" id="propertyIdModal"
            value="<?php echo isset($property['p_id']) ? htmlspecialchars($property['p_id']) : ''; ?>">
          <!-- Location Fields -->
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="street" class="form-label">Street</label>
              <input type="text" class="form-control" id="street"
                value="<?php echo isset($property['street']) ? htmlspecialchars($property['street']) : ''; ?>"
                placeholder="Enter Street" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="barangay" class="form-label">Barangay</label>
              <input type="text" class="form-control" id="barangay"
                value="<?php echo isset($property['barangay']) ? htmlspecialchars($property['barangay']) : ''; ?>"
                placeholder="Enter Barangay" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="municipality" class="form-label">Municipality</label>
              <input type="text" class="form-control" id="municipality"
                value="<?php echo isset($property['city']) ? htmlspecialchars($property['city']) : ''; ?>"
                placeholder="Enter Municipality" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="province" class="form-label">Province</label>
              <input type="text" class="form-control" id="province"
                value="<?php echo isset($property['province']) ? htmlspecialchars($property['province']) : ''; ?>"
                placeholder="Enter Province" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="houseNumber" class="form-label">House Number</label>
              <input type="text" class="form-control" id="houseNumber"
                value="<?php echo isset($property['house_no']) ? htmlspecialchars($property['house_no']) : ''; ?>"
                placeholder="Enter House Number" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="mb-3">
              <label for="landArea" class="form-label">Land Area</label>
              <input type="text" class="form-control" id="landArea"
                value="<?php echo isset($property['land_area']) ? htmlspecialchars($property['land_area']) : ''; ?>"
                placeholder="Enter Land Area" disabled>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!--Modal for Property Information-->
  <div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPropertyModalLabel">Edit Property Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editPropertyForm">
            <div class="row">
              <input type="hidden" id="propertyIdModal">
              <div class="col-12 mb-3">
                <label for="streetModal" class="form-label">Street</label>
                <input type="text" class="form-control" id="streetModal" placeholder="Enter Street" maxlength="30">
              </div>
              <div class="col-12 mb-3">
                <label for="barangayModal" class="form-label">Barangay</label>
                <input type="text" class="form-control" id="barangayModal" placeholder="Enter Barangay" maxlength="20">
              </div>
              <div class="col-12 mb-3">
                <label for="municipalityModal" class="form-label">Municipality</label>
                <input type="text" class="form-control" id="municipalityModal" placeholder="Enter Municipality"
                  maxlength="20">
              </div>
              <div class="col-12 mb-3">
                <label for="provinceModal" class="form-label">Province</label>
                <input type="text" class="form-control" id="provinceModal" placeholder="Enter Province" maxlength="20">
              </div>
              <div class="col-12 mb-3">
                <label for="houseNumberModal" class="form-label">House Number</label>
                <input type="text" class="form-control" id="houseNumberModal" placeholder="Enter House Number"
                  maxlength="10">
              </div>
              <div class="col-12 mb-3">
                <label for="landAreaModal" class="form-label">Land Area</label>
                <input type="text" class="form-control" id="landAreaModal" placeholder="Enter Land Area" maxlength="20">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
          <button type="button" class="btn btn-primary" onclick="savePropertyData()">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!--RPU Identification Numbers-->
  <section class="container mt-5" id="rpu-identification-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Title and Edit Button -->
      <h4 class="mb-0">RPU Identification Numbers</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" id="editRPUButton" onclick="toggleEdit()"
        <?= $disableButton ?>>Edit</button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <form>
        <div class="row">
          <!-- ARP Number Input (Number only) -->
          <div class="col-md-6 mb-3">
            <label for="arpNumber" class="form-label">ARP Number</label>
            <input type="number" class="form-control" id="arpNumber" placeholder="Enter ARP Number"
              value="<?= isset($rpu_details['arp']) ? htmlspecialchars($rpu_details['arp']) : ''; ?>" maxlength="20"
              disabled>
          </div>

          <!-- Property Number Input -->
          <div class="col-md-6 mb-3">
            <label for="propertyNumber" class="form-label">Property Number</label>
            <input type="text" class="form-control" id="propertyNumber" placeholder="Enter Property Number"
              value="<?= isset($rpu_details['pin']) ? htmlspecialchars($rpu_details['pin']) : ''; ?>" maxlength="17"
              disabled>
          </div>

          <script>
            (function() {
              const input = document.getElementById('propertyNumber');
              const MAX = 13; // digits only

              function formatPin(d) {
                d = d.slice(0, MAX);
                return [d.slice(0, 3), d.slice(3, 5), d.slice(5, 8), d.slice(8, 10), d.slice(10, 13)]
                  .filter(Boolean).join('-');
              }

              function digitsOnly(s) {
                return (s || '').replace(/\D/g, '').slice(0, MAX);
              }

              // Initialize (format existing value)
              input.value = formatPin(digitsOnly(input.value));

              input.addEventListener('input', () => {
                const digits = digitsOnly(input.value);
                input.value = formatPin(digits);
                input.selectionStart = input.selectionEnd = input.value.length; // keep cursor at end
              });

              input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
                const digits = digitsOnly(pasted);
                input.value = formatPin(digits);
              });

              // helper for enabling/disabling
              window.togglePropertyNumberInput = function(enable) {
                input.disabled = !enable;
                if (enable) {
                  input.focus();
                  input.selectionStart = input.selectionEnd = input.value.length;
                }
              };

              // helper for saving: always returns digits-only
              window.getPropertyNumberDigits = function() {
                return digitsOnly(input.value);
              };
            })();
          </script>

          <!-- Taxability Dropdown -->
          <div class="col-md-6 mb-3">
            <label for="taxability" class="form-label">Taxability</label>
            <select class="form-control" id="taxability" disabled>
              <option value="" disabled <?= empty($rpu_details['taxability']) ? 'selected' : ''; ?>>Select Taxability
              </option>
              <option value="taxable" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'taxable') ? 'selected' : ''; ?>>Taxable</option>
              <option value="exempt" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'exempt') ? 'selected' : ''; ?>>Exempt</option>
              <option value="special" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'special') ? 'selected' : ''; ?>>Special</option>
            </select>
          </div>

          <!-- Effectivity Year Input -->
          <div class="col-md-6 mb-3">
            <label for="effectivity" class="form-label">Effectivity (Year)</label>
            <input type="number" class="form-control" id="effectivity" min="1900" max="2100" step="1"
              placeholder="Enter Effectivity Year"
              value="<?= isset($rpu_details['effectivity']) ? htmlspecialchars($rpu_details['effectivity']) : ''; ?>"
              disabled>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!--Declaration of Property-->
  <section class="container mt-5" id="declaration-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Tax Declaration of Property</h4>
      <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
        data-bs-target="#editDeclarationProperty <?= $disableButton ?>">Edit</button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <form>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="taxDeclarationNumber" class="form-label">Identification Numbers (Tax Declaration Number)</label>
            <input type="text" class="form-control" id="taxDeclarationNumber" placeholder="Enter Tax Declaration Number"
              value="<?= htmlspecialchars($rpu_declaration['arp_no'] ?? '') ?>" disabled>
          </div>

          <div class="col-12 mb-3">
            <h6 class="mt-4 mb-3">Approval</h6>
          </div>

          <div class="col-md-6 mb-3">
            <label for="provincialAssessor" class="form-label">Provincial Assessor</label>
            <input type="text" class="form-control" id="provincialAssessor" placeholder="Enter Provincial Assessor"
              value="<?= htmlspecialchars($rpu_declaration['pro_assess'] ?? '') ?>" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label for="provincialDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="provincialDate" placeholder="Select Date"
              value="<?= htmlspecialchars($rpu_declaration['pro_date'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="municipalAssessor" class="form-label">City/Municipal Assessor</label>
            <input type="text" class="form-control" id="municipalAssessor" placeholder="Enter City/Municipal Assessor"
              value="<?= htmlspecialchars($rpu_declaration['mun_assess'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="municipalDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="municipalDate" placeholder="Select Date"
              value="<?= htmlspecialchars($rpu_declaration['mun_date'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="cancelsTD" class="form-label">Cancels TD Number</label>
            <input type="text" class="form-control" id="cancelsTD" placeholder="Enter Cancels TD Number"
              value="<?= htmlspecialchars($rpu_declaration['td_cancel'] ?? '') ?>" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label for="previousPin" class="form-label">Previous Pin</label>
            <input type="text" class="form-control" id="previousPin" placeholder="Enter Previous Pin"
              value="<?= htmlspecialchars($rpu_declaration['previous_pin'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="taxYear" class="form-label">Tax Begin With Year</label>
            <input type="date" class="form-control" id="taxYear" placeholder="Enter Year"
              value="<?= htmlspecialchars($rpu_declaration['tax_year'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="enteredInRPAREForBy" class="form-label">Entered in RPARE For By</label>
            <input type="text" class="form-control" id="enteredInRPAREForBy" placeholder="Enter Value"
              value="<?= htmlspecialchars($rpu_declaration['entered_by'] ?? '') ?>" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label for="enteredInRPAREForYear" class="form-label">Entered in RPARE For Year</label>
            <input type="date" class="form-control" id="enteredInRPAREForYear" placeholder="Enter Year"
              value="<?= htmlspecialchars($rpu_declaration['entered_year'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="previousOwner" class="form-label">Previous Owner</label>
            <input type="text" class="form-control" id="previousOwner" placeholder="Enter Previous Owner"
              value="<?= htmlspecialchars($rpu_declaration['prev_own'] ?? '') ?>" disabled>
          </div>

          <div class="col-md-6 mb-3">
            <label for="previousAssessedValue" class="form-label">Previous Assessed Value</label>
            <input type="text" class="form-control" id="previousAssessedValue" placeholder="Enter Assessed Value"
              value="<?= htmlspecialchars($rpu_declaration['prev_assess'] ?? '') ?>" disabled>
          </div>
        </div>

        <!-- Print Button at the Bottom Right -->
        <div class="text-right mt-4">
          <?php
          // Get the property ID from the current URL (e.g., FAAS.php?id=140)
          $p_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
          ?>
          <a href="DRP.php?p_id=<?= urlencode($p_id); ?>" class="btn btn-sm btn-secondary ml-3" title="print"
            target="_blank">
            <i class="bi bi-printer"></i>
          </a>
        </div>
      </form>
    </div>
  </section>

  <!-- Modal for Declaration of Property -->
  <div class="modal fade" id="editDeclarationProperty" tabindex="-1" aria-labelledby="editDeclarationPropertyLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDeclarationPropertyLabel">Edit Declaration of Property</h5>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="declarationForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="taxDeclarationNumberModal" class="form-label">Identification Numbers (Tax Declaration
                  Number)</label>
                <input type="text" class="form-control" id="taxDeclarationNumberModal" name="arp_no"
                  value="<?= htmlspecialchars($rpu_declaration['arp_no'] ?? '') ?>"
                  placeholder="Enter Tax Declaration Number" maxlength="15">
              </div>

              <div class="col-12 mb-3">
                <h6 class="mt-4 mb-3">Approval</h6>
              </div>

              <div class="col-md-6 mb-3">
                <label for="provincialAssessorModal" class="form-label">Provincial Assessor</label>
                <input type="text" class="form-control" id="provincialAssessorModal" name="pro_assess"
                  value="<?= htmlspecialchars($rpu_declaration['pro_assess'] ?? '') ?>"
                  placeholder="Enter Provincial Assessor" maxlength="20">
              </div>
              <div class="col-md-6 mb-3">
                <label for="provincialDateModal" class="form-label">Date</label>
                <input type="date" class="form-control" id="provincialDateModal" name="pro_date"
                  value="<?= htmlspecialchars($rpu_declaration['pro_date'] ?? '') ?>">
              </div>

              <div class="col-md-6 mb-3">
                <label for="municipalAssessorModal" class="form-label">City/Municipal Assessor</label>
                <input type="text" class="form-control" id="municipalAssessorModal" name="mun_assess"
                  value="<?= htmlspecialchars($rpu_declaration['mun_assess'] ?? '') ?>"
                  placeholder="Enter City/Municipal Assessor" maxlength="20">
              </div>
              <div class="col-md-6 mb-3">
                <label for="municipalDateModal" class="form-label">Date</label>
                <input type="date" class="form-control" id="municipalDateModal" name="mun_date"
                  value="<?= htmlspecialchars($rpu_declaration['mun_date'] ?? '') ?>">
              </div>

              <div class="col-md-6 mb-3">
                <label for="cancelsTDModal" class="form-label">Cancels TD Number</label>
                <input type="text" class="form-control" id="cancelsTDModal" name="td_cancel"
                  value="<?= htmlspecialchars($rpu_declaration['td_cancel'] ?? '') ?>"
                  placeholder="Enter Cancels TD Number" maxlength="20">
              </div>
              <div class="col-md-6 mb-3">
                <label for="previousPinModal" class="form-label">Previous Pin</label>
                <input type="text" class="form-control" id="previousPinModal" name="previous_pin"
                  value="<?= htmlspecialchars($rpu_declaration['previous_pin'] ?? '') ?>"
                  placeholder="Enter Previous Pin" maxlength="20">
              </div>

              <div class="col-md-6 mb-3">
                <label for="taxYearModal" class="form-label">Tax Begin With Year</label>
                <input type="date" class="form-control" id="taxYearModal" name="tax_year"
                  value="<?= htmlspecialchars($rpu_declaration['tax_year'] ?? '') ?>" placeholder="Enter Year">
              </div>

              <div class="col-md-6 mb-3">
                <label for="enteredInRPAREForByModal" class="form-label">Entered in RPARE For By</label>
                <input type="text" class="form-control" id="enteredInRPAREForByModal" name="entered_by"
                  value="<?= htmlspecialchars($rpu_declaration['entered_by'] ?? '') ?>" placeholder="Enter Value">
              </div>
              <div class="col-md-6 mb-3">
                <label for="enteredInRPAREForYearModal" class="form-label">Entered in RPARE For Year</label>
                <input type="date" class="form-control" id="enteredInRPAREForYearModal" name="entered_year"
                  value="<?= htmlspecialchars($rpu_declaration['entered_year'] ?? '') ?>" placeholder="Enter Year">
              </div>

              <div class="col-md-6 mb-3">
                <label for="previousOwnerModal" class="form-label">Previous Owner</label>
                <input type="text" class="form-control" id="previousOwnerModal" name="prev_own"
                  value="<?= htmlspecialchars($rpu_declaration['prev_own'] ?? '') ?>" placeholder="Enter Previous Owner"
                  maxlength="50">
              </div>
              <div class="col-md-6 mb-3">
                <label for="previousAssessedValueModal" class="form-label">Previous Assessed Value</label>
                <input type="text" class="form-control" id="previousAssessedValueModal" name="prev_assess"
                  value="<?= htmlspecialchars($rpu_declaration['prev_assess'] ?? '') ?>"
                  placeholder="Enter Assessed Value" maxlength="20">
              </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
          <button type="submit" form="declarationForm" class="btn btn-primary">Save Changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- LAND Section -->
  <div class="carousel-item active">
    <!-- LAND Section -->
    <section class="container my-5" id="land-section">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">
          </a>
          LAND
        </h4>
      </div>

      <div class="card border-0 shadow p-4 rounded-3">
        <!-- Quick Actions Row -->
        <div class="row mb-4">
          <?php
          // Get the property ID from the current URL (e.g., FAAS.php?id=140)
          $p_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
          ?>
          <div class="col-md-6 mb-3">
            <a href="<?= ($is_active == 1) ? "Land.php?p_id=$p_id" : '#' ?>"
              class="btn w-100 py-2 text-white text-decoration-none <?= ($is_active == 0) ? 'disabled' : '' ?>"
              style="background-color: #379777; border-color: #2e8266; pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
              <i class="fas fa-plus-circle me-2"></i>Add Land
            </a>
          </div>
        </div>

        <!-- Toggle Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
          <span class="fw-bold me-3">Show/Hide</span>
          <div class="form-check form-switch m-0">
            <input class="form-check-input" type="checkbox" id="showToggle" checked style="margin-left: 0;">
          </div>
        </div>

        <!-- Value Table -->
        <div class="table-responsive" id="landTableContainer">
          <table class="table table-borderless text-center">
            <thead class="border-bottom border-2">
              <tr class="border-bottom border-2">
                <th class="bold" style="width: 10%;">OCT/TCT Number</th>
                <th class="bold">Area (sq m)</th>
                <th class="bold">Market Value</th>
                <th class="bold">Assessed Value</th>
                <th class="bold" style="width: 10%;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($landRecords)): ?>
                <?php foreach ($landRecords as $record): ?>
                  <tr class="border-bottom border-3">
                    <td><?= htmlspecialchars($record['oct_no']) ?></td>
                    <td><?= htmlspecialchars($record['area']) ?></td>
                    <td><?= number_format($record['market_value'], 2) ?></td>
                    <td>
                      <?= isset($record['assess_value']) ? number_format($record['assess_value'], 2) : '0.00' ?>
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <a href="LAND_Edit.php?p_id=<?= urlencode($p_id); ?>&land_id=<?= urlencode($record['land_id']); ?>"
                          class="btn btn-sm btn-primary" title="Edit">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= ($is_active == 1)
                                    ? 'print-layout.php?p_id=' . urlencode($p_id) . '&land_id=' . urlencode($record['land_id'])
                                    : '#' ?>" class="btn btn-sm btn-secondary ml-3 <?= ($is_active == 0) ? 'disabled' : '' ?>"
                          title="View" target="_blank" style="pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
                          <i class="bi bi-printer"></i>
                        </a>
                        <a href="ViewAll.php?p_id=<?= urlencode($p_id); ?>" class="btn btn-sm btn-info ml-3"
                          title="View All">
                          <i class="bi bi-eye"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center">No records found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </section>
  </div>

  <!-- Memoranda Section -->
  <section class="container my-5">
    <h5 class="mb-3">Memoranda</h5>
    <div class="form-group">
      <div
        style="border: 1px solid #ddd; padding: 15px; width: 100%; max-width: 800px; margin: 0 auto; text-align: justify;">
        <p class="form-control-plaintext" style="font-weight: bold;">
          Records Updating: TRANSFERRED BY VIRTUE OF TRANSFER CERTIFICATE OF TITLE <br>NO. 079-2023001223,
          DEED OF ABSOLUTE SALE NOTARIZED BY ATTY. RONALD A. RAMOS,<br>
          DOCKET NO. 180, PAGE NO. 37; BOOK NO. 48; SERIES OF 2023,<br>
          BIR cCR202300315293, AND CERT. OF LAND TAX PAYMENT ALL SUBMITTED.<br>
          TRANSFER TAX PRESENTED.
        </p>
      </div>
    </div>
  </section>

  <!-- Valuation Section -->
  <section class="container my-5" id="valuation-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">Valuation</h4>
    </div>

    <div class="card border-0 shadow p-5 rounded-3 bg-light">
      <table class="table table-borderless mt-4">
        <thead class="border-bottom">
          <tr>
            <th scope="col">Total Value</th>
            <th scope="col" class="text-center">Market Value</th>
            <th scope="col" class="text-center">Assessed Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Land</td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="landMarketValue"
                value="<?= number_format($totalMarketValue ?? 0, 2) ?>" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="landAssessedValue"
                value="<?= number_format($totalAssessedValue ?? 0, 2) ?>" disabled>
            </td>
          </tr>
          <tr>
            <td>Plants/Trees</td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="plantsMarketValue" value="0.00" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="plantsAssessedValue" value="0.00" disabled>
            </td>
          </tr>
          <tr class="border-top font-weight-bold">
            <td>Total</td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="totalMarketValue"
                value="<?= number_format($totalMarketValue ?? 0, 2) ?>" disabled>
            </td>
            <td class="text-center">
              <input type="text" class="form-control text-center" id="totalAssessedValue"
                value="<?= number_format($totalAssessedValue ?? 0, 2) ?>" disabled>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>



  <!-- Floating Dropdown Menu (Bottom Right of Page) -->
  <div class="dropdown" style="position:fixed; bottom:20px; right:20px; z-index:1050;">
    <button id="mapMenuBtn"
      class="btn btn-danger btn-lg rounded-circle d-flex align-items-center justify-content-center" type="button"
      data-bs-toggle="dropdown" aria-expanded="false" style="width:60px; height:60px;">
      <i class="fas fa-bars fa-1x"></i>
    </button>

    <ul class="dropdown-menu shadow dropdown-menu-end" style="bottom:100%; right:0;">
      <li><a class="dropdown-item scroll-link" href="#owner-info-section"><i class="fas fa-user"></i> Owner's Info</a>
      </li>
      <li><a class="dropdown-item scroll-link" href="#property-info-section"><i class="fas fa-home"></i> Property
          Info</a></li>
      <li><a class="dropdown-item scroll-link" href="#rpu-identification-section"><i class="fas fa-id-card"></i> RPU
          Identification</a></li>
      <li><a class="dropdown-item scroll-link" href="#declaration-section"><i class="fas fa-file-alt"></i> Tax
          Declaration</a></li>
      <li><a class="dropdown-item scroll-link" href="#land-section"><i class="bi-building-fill"></i> Land</a></li>
      <li><a class="dropdown-item scroll-link" href="#valuation-section"><i class="fas fa-balance-scale"></i>
          Valuation</a></li>
    </ul>
  </div>

  </section>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- Bootstrap 5 JS Bundle (Popper included) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Then your custom script -->
  <script src="http://localhost/ERPTS/FAAS.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>