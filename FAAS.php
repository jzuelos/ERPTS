<?php
/**
 * FAAS (Field Appraisal and Assessment Sheet) Management System
 * Handles property information, owners, RPU details, and tax declarations
 */

session_start();

// Security Headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'database.php';

// ============================================================================
// CONFIGURATION & INITIALIZATION
// ============================================================================

$conn = Database::getInstance();
$property_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$user_role = $_SESSION['user_type'] ?? 'user';
$current_user_id = $_SESSION['user_id'] ?? null;

// ============================================================================
// DATABASE HELPER FUNCTIONS
// ============================================================================

/**
 * Fetch property details by ID
 */
function fetchProperty($conn, $p_id)
{
  $sql = "SELECT p.p_id, p.house_no, p.block_no, p.province, p.city, p.district, 
                   p.barangay, p.street, p.house_tag_no, p.land_area, p.desc_land, 
                   p.documents, p.created_at, p.updated_at, p.is_active, 
                   p.disabled_at, p.disabled_by
            FROM p_info p WHERE p.p_id = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $p_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

/**
 * Fetch all owners from the database
 */
function fetchOwners($conn)
{
  $sql = "SELECT own_id,
                   CONCAT(own_fname, ' ', own_mname, ' ', own_surname) AS owner_name,
                   CONCAT(house_no, ', ', barangay, ', ', city, ', ', province) AS address
            FROM owners_tb";

  $result = $conn->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Fetch owner details for a specific property (only retained owners)
 */
function fetchOwnersWithDetails($conn, $property_id)
{
  $sql = "SELECT 
                o.own_id, o.own_fname AS first_name, o.own_mname AS middle_name,
                o.own_surname AS last_name, o.street, o.barangay, o.city, o.province,
                o.own_info, COALESCE(o.owner_type, 'individual') as owner_type,
                o.company_name,
                CASE 
                    WHEN COALESCE(o.owner_type, 'individual') = 'company' THEN 
                        COALESCE(o.company_name, 'Unnamed Company')
                    ELSE CONCAT(
                        TRIM(COALESCE(o.own_fname, '')), 
                        CASE WHEN o.own_mname IS NOT NULL AND TRIM(o.own_mname) != '' 
                             THEN CONCAT(' ', TRIM(o.own_mname)) ELSE '' END,
                        CASE WHEN o.own_surname IS NOT NULL AND TRIM(o.own_surname) != ''
                             THEN CONCAT(' ', TRIM(o.own_surname)) ELSE '' END
                    )
                END AS display_name
            FROM propertyowner po
            JOIN owners_tb o ON po.owner_id = o.own_id
            WHERE po.property_id = ? AND po.is_retained = 1
            ORDER BY o.owner_type DESC, 
                     CASE WHEN o.owner_type = 'company' THEN o.company_name ELSE o.own_surname END,
                     o.own_fname";

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

/**
 * Fetch property owner IDs (only retained)
 */
function fetchPropertyOwnerIDs($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT owner_id FROM propertyowner WHERE property_id = ? AND is_retained = 1");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return array_column($result->fetch_all(MYSQLI_ASSOC), 'owner_id');
}

/**
 * Fetch FAAS information
 */
function fetchFaasInfo($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT faas_id FROM faas WHERE pro_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

/**
 * Fetch RPU details
 */
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

/**
 * Fetch land records by FAAS ID
 */
function fetchLandRecords($conn, $faas_id)
{
  $stmt = $conn->prepare("SELECT * FROM land WHERE faas_id = ?");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Calculate total land values
 */
function calculateTotalLandValues($conn, $faas_id)
{
  $stmt = $conn->prepare("
        SELECT SUM(market_value) AS total_market_value, 
               SUM(assess_value) AS total_assess_value 
        FROM land WHERE faas_id = ?
    ");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

/**
 * Fetch RPU Declaration
 */
function fetchRPUDeclaration($conn, $faas_id)
{
  $stmt = $conn->prepare("
        SELECT dec_id, arp_no, pro_assess, pro_date, mun_assess, mun_date,
               td_cancel, previous_pin, tax_year, entered_by, entered_year,
               prev_own, prev_assess, faas_id, total_property_value
        FROM rpu_dec WHERE faas_id = ?
    ");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc();
}

// ============================================================================
// REQUEST HANDLERS
// ============================================================================

/**
 * Handle property disable request
 */
function handleDisableProperty($conn, $user_role, $current_user_id)
{
  if ($user_role !== 'admin') {
    $_SESSION['flash_error'] = "Permission denied. Only admins can disable properties.";
    return;
  }

  $property_p_id = intval($_POST['p_id'] ?? 0);
  if ($property_p_id <= 0) {
    $_SESSION['flash_error'] = "Invalid property id.";
    return;
  }

  // Check if tax declaration exists
  $stmtF = $conn->prepare("SELECT faas_id FROM faas WHERE pro_id = ?");
  $stmtF->bind_param("i", $property_p_id);
  $stmtF->execute();
  $resF = $stmtF->get_result();
  $faas_row = $resF ? $resF->fetch_assoc() : null;
  $faas_id = $faas_row['faas_id'] ?? null;
  $stmtF->close();

  if (!empty($faas_id)) {
    $chk = $conn->prepare("SELECT dec_id FROM rpu_dec WHERE faas_id = ?");
    $chk->bind_param("i", $faas_id);
    $chk->execute();
    $chk_res = $chk->get_result();
    if ($chk_res && $chk_res->num_rows > 0) {
      $_SESSION['flash_error'] = "Cannot disable property: tax declaration exists.";
      $chk->close();
      return;
    }
    $chk->close();
  }

  // Disable property
  $upd = $conn->prepare("UPDATE p_info SET is_active = 0, disabled_at = NOW(), disabled_by = ? WHERE p_id = ?");
  $upd->bind_param("ii", $current_user_id, $property_p_id);
  if ($upd->execute()) {
    $_SESSION['flash_success'] = "Property #{$property_p_id} cancelled.";
  } else {
    $_SESSION['flash_error'] = "Failed to disable property: " . $upd->error;
  }
  $upd->close();
}

/**
 * Handle RPU declaration save/update
 */
function handleRPUDeclaration($conn, $faas_id)
{
  $data = [
    'arp_no' => $_POST['arp_no'] ?? 0,
    'pro_assess' => $_POST['pro_assess'] ?? '',
    'pro_date' => $_POST['pro_date'] ?? '',
    'mun_assess' => $_POST['mun_assess'] ?? '',
    'mun_date' => $_POST['mun_date'] ?? '',
    'td_cancel' => $_POST['td_cancel'] ?? 0,
    'previous_pin' => $_POST['previous_pin'] ?? 0,
    'tax_year' => $_POST['tax_year'] ?? '',
    'entered_by' => $_POST['entered_by'] ?? 0,
    'entered_year' => $_POST['entered_year'] ?? '',
    'prev_own' => $_POST['prev_own'] ?? '',
    'prev_assess' => $_POST['prev_assess'] ?? 0.00
  ];

  // Calculate total property value
  $totals = calculateTotalLandValues($conn, $faas_id);
  $total_property_value = ($totals['total_market_value'] ?? 0) + ($totals['total_assess_value'] ?? 0);

  // Check if record exists
  $check_stmt = $conn->prepare("SELECT * FROM rpu_dec WHERE faas_id = ?");
  $check_stmt->bind_param("i", $faas_id);
  $check_stmt->execute();
  $exists = $check_stmt->get_result()->num_rows > 0;
  $check_stmt->close();

  if ($exists) {
    // UPDATE
    $stmt = $conn->prepare("UPDATE rpu_dec SET
            arp_no = ?, pro_assess = ?, pro_date = ?, mun_assess = ?, mun_date = ?,
            td_cancel = ?, previous_pin = ?, tax_year = ?, entered_by = ?, entered_year = ?,
            prev_own = ?, prev_assess = ?, total_property_value = ?
            WHERE faas_id = ?");

    $stmt->bind_param(
      "issssiissssdid",
      $data['arp_no'],
      $data['pro_assess'],
      $data['pro_date'],
      $data['mun_assess'],
      $data['mun_date'],
      $data['td_cancel'],
      $data['previous_pin'],
      $data['tax_year'],
      $data['entered_by'],
      $data['entered_year'],
      $data['prev_own'],
      $data['prev_assess'],
      $total_property_value,
      $faas_id
    );

    $message = "Updated: Tax Declaration updated for FAAS ID $faas_id.";
  } else {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO rpu_dec (
            arp_no, pro_assess, pro_date, mun_assess, mun_date, td_cancel, previous_pin,
            tax_year, entered_by, entered_year, prev_own, prev_assess, faas_id, total_property_value
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
      "issssiissssdid",
      $data['arp_no'],
      $data['pro_assess'],
      $data['pro_date'],
      $data['mun_assess'],
      $data['mun_date'],
      $data['td_cancel'],
      $data['previous_pin'],
      $data['tax_year'],
      $data['entered_by'],
      $data['entered_year'],
      $data['prev_own'],
      $data['prev_assess'],
      $faas_id,
      $total_property_value
    );

    $message = "Added: New Tax Declaration for FAAS ID $faas_id.";
  }

  if ($stmt->execute()) {
    echo "<script>alert('$message Total Value: ₱" . number_format($total_property_value, 2) . "');</script>";
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_GET['id']));
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
  $stmt->close();
}

/**
 * Handle land record deletion
 */
function handleLandDelete($conn)
{
  $land_id = $_POST['delete_land_id'];
  $p_id = $_POST['p_id'];

  $stmt = $conn->prepare("DELETE FROM land WHERE land_id = ?");
  $stmt->bind_param("i", $land_id);
  $stmt->execute();
  $stmt->close();

  header("Location: FAAS.php?id=" . urlencode($p_id) . "#land-section");
  exit();
}

// ============================================================================
// MAIN REQUEST PROCESSING
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'disable_property') {
    handleDisableProperty($conn, $user_role, $current_user_id);
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_POST['return_p_id'] ?? ''));
    exit;
  } elseif (isset($_POST['delete_land_id'])) {
    handleLandDelete($conn);
  } elseif (isset($_POST['arp_no'])) {
    $faas_info = fetchFaasInfo($conn, $property_id);
    if ($faas_info) {
      handleRPUDeclaration($conn, $faas_info['faas_id']);
    }
  }
}

// ============================================================================
// DATA LOADING FOR VIEW
// ============================================================================

$property = null;
$owners_details = [];
$rpu_details = null;
$landRecords = [];
$faas_id = null;
$rpu_declaration = null;
$totalMarketValue = 0;
$totalAssessedValue = 0;
$is_active = 1;
$disabled_at = null;

if ($property_id) {
  // Fetch property
  $property = fetchProperty($conn, $property_id);
  $is_active = $property['is_active'] ?? 1;
  $disabled_at = $property['disabled_at'] ?? null;

  // Fetch FAAS info
  $faas_info = fetchFaasInfo($conn, $property_id);
  if ($faas_info) {
    $faas_id = $faas_info['faas_id'];

    // Fetch RPU declaration
    $rpu_declaration = fetchRPUDeclaration($conn, $faas_id);

    // Calculate land values
    $totals = calculateTotalLandValues($conn, $faas_id);
    $totalMarketValue = $totals['total_market_value'] ?? 0;
    $totalAssessedValue = $totals['total_assess_value'] ?? 0;

    // Fetch owners
    $owners_details = fetchOwnersWithDetails($conn, $property_id);

    // Fetch land records
    $landRecords = fetchLandRecords($conn, $faas_id);
  }

  // Fetch RPU details
  $rpu_details = fetchRPUDetails($conn, $property_id);
}

$disableButton = ($is_active == 0) ? 'disabled' : '';
$all_owners = fetchOwners($conn);

if ($faas_id) {
  echo "<div id='faas-id' style='display:none;'>Faas ID: " . htmlspecialchars($faas_id) . "</div>";
}


$conn->close();

// ============================================================================
// VIEW RENDERING
// ============================================================================
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Electronic Real Property Tax System - FAAS</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="FAAS.css">
  <link rel="stylesheet" href="header.css">
</head>

<body>
  <?php include 'header.php'; ?>

  <!-- Flash Messages -->
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
      <strong>This RPU is inactive</strong><br>
      <small>Disabled on: <?= htmlspecialchars($disabled_at) ?></small>
    </div>
  <?php endif; ?>

  <!-- Owner's Information Section -->
  <?php include 'partials/owner_info.php'; ?>

  <!-- Property Information Section -->
  <?php include 'partials/property_info.php'; ?>

  <!-- RPU Identification Section -->
  <?php include 'partials/rpu_identification.php'; ?>

  <!-- Tax Declaration Section -->
  <?php include 'partials/tax_declaration.php'; ?>

  <!-- Land Section -->
  <?php include 'partials/land_section.php'; ?>

  <!-- Memoranda Section -->
  <?php include 'partials/memoranda.php'; ?>

  <!-- Valuation Section -->
  <?php include 'partials/valuation.php'; ?>

  <!-- Floating Navigation Menu -->
  <div class="dropdown" style="position:fixed; bottom:20px; right:20px; z-index:1050;">
    <button class="btn btn-danger btn-lg rounded-circle" type="button" data-bs-toggle="dropdown"
      style="width:60px; height:60px;">
      <i class="fas fa-bars"></i>
    </button>
    <ul class="dropdown-menu shadow dropdown-menu-end">
      <li><a class="dropdown-item scroll-link" href="#owner-info-section">
          <i class="fas fa-user"></i> Owner's Info</a></li>
      <li><a class="dropdown-item scroll-link" href="#property-info-section">
          <i class="fas fa-home"></i> Property Info</a></li>
      <li><a class="dropdown-item scroll-link" href="#rpu-identification-section">
          <i class="fas fa-id-card"></i> RPU Identification</a></li>
      <li><a class="dropdown-item scroll-link" href="#declaration-section">
          <i class="fas fa-file-alt"></i> Tax Declaration</a></li>
      <li><a class="dropdown-item scroll-link" href="#land-section">
          <i class="bi-building-fill"></i> Land</a></li>
      <li><a class="dropdown-item scroll-link" href="#valuation-section">
          <i class="fas fa-balance-scale"></i> Valuation</a></li>
    </ul>
  </div>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright: <a class="text-body" href="#">ERPTS</a>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="FAAS.js"></script>
</body>

</html>