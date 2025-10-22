<?php
/**
 * FAAS (Field Appraisal and Assessment Sheet) Management System
 * Handles property information, owners, RPU details, and tax declarations
 * WITH COMPREHENSIVE ACTIVITY LOGGING
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
// ACTIVITY LOGGING FUNCTIONS
// ============================================================================

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

/**
 * Helper function to get municipality name
 */
function getMunicipalityName($conn, $m_id)
{
  if (empty($m_id)) return 'None';
  $stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
  $stmt->bind_param("i", $m_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row ? $row['m_description'] : "ID: $m_id";
}

/**
 * Helper function to get district name
 */
function getDistrictName($conn, $district_id)
{
  if (empty($district_id)) return 'None';
  $stmt = $conn->prepare("SELECT description FROM district WHERE district_id = ?");
  $stmt->bind_param("i", $district_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row ? $row['description'] : "ID: $district_id";
}

/**
 * Helper function to get barangay name
 */
function getBarangayName($conn, $brgy_id)
{
  if (empty($brgy_id)) return 'None';
  $stmt = $conn->prepare("SELECT brgy_name FROM brgy WHERE brgy_id = ?");
  $stmt->bind_param("i", $brgy_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row ? $row['brgy_name'] : "ID: $brgy_id";
}

/**
 * Helper function to get property location details
 */
function getPropertyLocationDetails($conn, $property_id)
{
  $property = fetchProperty($conn, $property_id);
  if (!$property) return "Property ID: $property_id";

  $municipalityName = getMunicipalityName($conn, $property['city']);
  $barangayName = getBarangayName($conn, $property['barangay']);
  $districtName = getDistrictName($conn, $property['district']);

  return "House #" . $property['house_no'] . ", " . $barangayName . ", " . $districtName . ", " . $municipalityName;
}

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
                 own_fname,
                 own_mname,
                 own_surname,
                 house_no,
                 barangay,
                 city,
                 province
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
// REQUEST HANDLERS WITH COMPREHENSIVE LOGGING
// ============================================================================

/**
 * Handle property disable request (WITH LOGGING)
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

      // ✅ LOG FAILED ATTEMPT
      if ($current_user_id) {
        $locationDetails = getPropertyLocationDetails($conn, $property_p_id);
        $logMessage  = "Failed to disable property\n";
        $logMessage .= "Property ID: $property_p_id\n";
        $logMessage .= "Location: $locationDetails\n";
        $logMessage .= "Reason: Tax declaration exists (cannot disable)";

        logActivity($conn, $current_user_id, $logMessage);
      }
      return;
    }
    $chk->close();
  }

  // Disable property
  $upd = $conn->prepare("UPDATE p_info SET is_active = 0, disabled_at = NOW(), disabled_by = ? WHERE p_id = ?");
  $upd->bind_param("ii", $current_user_id, $property_p_id);
  if ($upd->execute()) {
    $_SESSION['flash_success'] = "Property #{$property_p_id} cancelled.";

    // ✅ LOG SUCCESSFUL DISABLE
    if ($current_user_id) {
      $locationDetails = getPropertyLocationDetails($conn, $property_p_id);
      $logMessage  = "Disabled property\n";
      $logMessage .= "Property ID: $property_p_id\n";
      $logMessage .= "Location: $locationDetails\n";
      $logMessage .= "Status: Property set to inactive";

      logActivity($conn, $current_user_id, $logMessage);
    }
  } else {
    $_SESSION['flash_error'] = "Failed to disable property: " . $upd->error;

    // ✅ LOG ERROR
    if ($current_user_id) {
      $logMessage  = "Error disabling property\n";
      $logMessage .= "Property ID: $property_p_id\n";
      $logMessage .= "Error: " . $upd->error;

      logActivity($conn, $current_user_id, $logMessage);
    }
  }
  $upd->close();
}

/**
 * Handle Tax Declaration Update (WITH COMPREHENSIVE LOGGING)
 */
function handleRPUDeclaration($conn, $faas_id, $property_id = null)
{
  $data = [
    'arp_no' => $_POST['arp_no'] ?? '',
    'pro_assess' => $_POST['pro_assess'] ?? '',
    'pro_date' => $_POST['pro_date'] ?? '',
    'mun_assess' => $_POST['mun_assess'] ?? '',
    'mun_date' => $_POST['mun_date'] ?? '',
    'td_cancel' => $_POST['td_cancel'] ?? '',
    'previous_pin' => $_POST['previous_pin'] ?? '',
    'tax_year' => $_POST['tax_year'] ?? '',
    'entered_by' => $_POST['entered_by'] ?? '',
    'entered_year' => $_POST['entered_year'] ?? '',
    'prev_own' => $_POST['prev_own'] ?? '',
    'prev_assess' => $_POST['prev_assess'] ?? 0.00
  ];

  // Calculate total property value
  $totals = calculateTotalLandValues($conn, $faas_id);
  $total_property_value = ($totals['total_market_value'] ?? 0) + ($totals['total_assess_value'] ?? 0);

  // ✅ Get old data for comparison
  $check_stmt = $conn->prepare("SELECT * FROM rpu_dec WHERE faas_id = ?");
  $check_stmt->bind_param("i", $faas_id);
  $check_stmt->execute();
  $old_data = $check_stmt->get_result()->fetch_assoc();
  $check_stmt->close();

  $isUpdate = !empty($old_data);

  if ($isUpdate) {
    // UPDATE
    $stmt = $conn->prepare("UPDATE rpu_dec SET
            arp_no = ?, pro_assess = ?, pro_date = ?, mun_assess = ?, mun_date = ?,
            td_cancel = ?, previous_pin = ?, tax_year = ?, entered_by = ?, entered_year = ?,
            prev_own = ?, prev_assess = ?, total_property_value = ?
            WHERE faas_id = ?");

    $stmt->bind_param(
      "sssssssssssddi",
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
      "sssssssssssddi",
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
    // ✅ LOG TAX DECLARATION ACTION WITH CHANGES
    if (isset($_SESSION['user_id']) && $property_id) {
      $userId = $_SESSION['user_id'];
      $locationDetails = getPropertyLocationDetails($conn, $property_id);

      if ($isUpdate) {
        // Compare changes for update
        $changes = [];
        
        if ($old_data['arp_no'] != $data['arp_no']) {
          $changes[] = "• ARP Number changed from '{$old_data['arp_no']}' to '{$data['arp_no']}'";
        }
        if ($old_data['tax_year'] != $data['tax_year']) {
          $changes[] = "• Tax Year changed from '{$old_data['tax_year']}' to '{$data['tax_year']}'";
        }
        if ($old_data['pro_assess'] != $data['pro_assess']) {
          $changes[] = "• Provincial Assessor changed from '{$old_data['pro_assess']}' to '{$data['pro_assess']}'";
        }
        if ($old_data['pro_date'] != $data['pro_date']) {
          $changes[] = "• Provincial Date changed from '{$old_data['pro_date']}' to '{$data['pro_date']}'";
        }
        if ($old_data['mun_assess'] != $data['mun_assess']) {
          $changes[] = "• Municipal Assessor changed from '{$old_data['mun_assess']}' to '{$data['mun_assess']}'";
        }
        if ($old_data['mun_date'] != $data['mun_date']) {
          $changes[] = "• Municipal Date changed from '{$old_data['mun_date']}' to '{$data['mun_date']}'";
        }
        if ($old_data['td_cancel'] != $data['td_cancel']) {
          $old_val = $old_data['td_cancel'] ?: 'None';
          $new_val = $data['td_cancel'] ?: 'None';
          $changes[] = "• TD Cancelled changed from '$old_val' to '$new_val'";
        }
        if ($old_data['previous_pin'] != $data['previous_pin']) {
          $old_val = $old_data['previous_pin'] ?: 'None';
          $new_val = $data['previous_pin'] ?: 'None';
          $changes[] = "• Previous PIN changed from '$old_val' to '$new_val'";
        }
        if ($old_data['entered_by'] != $data['entered_by']) {
          $old_val = $old_data['entered_by'] ?: 'None';
          $new_val = $data['entered_by'] ?: 'None';
          $changes[] = "• Entered By changed from '$old_val' to '$new_val'";
        }
        if ($old_data['entered_year'] != $data['entered_year']) {
          $old_val = $old_data['entered_year'] ?: 'None';
          $new_val = $data['entered_year'] ?: 'None';
          $changes[] = "• Entered Year changed from '$old_val' to '$new_val'";
        }
        if ($old_data['prev_own'] != $data['prev_own']) {
          $old_val = $old_data['prev_own'] ?: 'None';
          $new_val = $data['prev_own'] ?: 'None';
          $changes[] = "• Previous Owner changed from '$old_val' to '$new_val'";
        }
        if ($old_data['prev_assess'] != $data['prev_assess']) {
          $old_val = number_format($old_data['prev_assess'], 2);
          $new_val = number_format($data['prev_assess'], 2);
          $changes[] = "• Previous Assessed Value changed from '₱{$old_val}' to '₱{$new_val}'";
        }
        if ($old_data['total_property_value'] != $total_property_value) {
          $old_total = number_format($old_data['total_property_value'], 2);
          $new_total = number_format($total_property_value, 2);
          $changes[] = "• Total Property Value changed from '₱{$old_total}' to '₱{$new_total}'";
        }

        if (!empty($changes)) {
          $logMessage  = "Updated tax declaration\n";
          $logMessage .= "Property ID: $property_id\n";
          $logMessage .= "FAAS ID: $faas_id\n";
          $logMessage .= "Location: $locationDetails\n\n";
          $logMessage .= "Changes:\n" . implode("\n", $changes);
          
          logActivity($conn, $userId, $logMessage);
        }
      } else {
        // New tax declaration
        $logMessage  = "Added new tax declaration\n";
        $logMessage .= "Property ID: $property_id\n";
        $logMessage .= "FAAS ID: $faas_id\n";
        $logMessage .= "Location: $locationDetails\n\n";
        
        $logMessage .= "Tax Declaration Details:\n";
        if (!empty($data['arp_no'])) {
          $logMessage .= "• ARP Number: {$data['arp_no']}\n";
        }
        if (!empty($data['tax_year'])) {
          $logMessage .= "• Tax Year: {$data['tax_year']}\n";
        }
        if (!empty($data['pro_assess'])) {
          $logMessage .= "• Provincial Assessor: {$data['pro_assess']}\n";
        }
        if (!empty($data['pro_date'])) {
          $logMessage .= "• Provincial Date: {$data['pro_date']}\n";
        }
        if (!empty($data['mun_assess'])) {
          $logMessage .= "• Municipal Assessor: {$data['mun_assess']}\n";
        }
        if (!empty($data['mun_date'])) {
          $logMessage .= "• Municipal Date: {$data['mun_date']}\n";
        }
        if (!empty($data['prev_own'])) {
          $logMessage .= "• Previous Owner: {$data['prev_own']}\n";
        }
        if (!empty($data['prev_assess']) && $data['prev_assess'] > 0) {
          $logMessage .= "• Previous Assessed Value: ₱" . number_format($data['prev_assess'], 2) . "\n";
        }
        
        $logMessage .= "\nTotal Property Value: ₱" . number_format($total_property_value, 2);
        
        logActivity($conn, $userId, $logMessage);
      }
    }

    echo "<script>alert('$message Total Value: ₱" . number_format($total_property_value, 2) . "');</script>";
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_GET['id']));
    exit;
  } else {
    // ✅ LOG ERROR
    if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      $logMessage  = "Failed to save tax declaration\n";
      $logMessage .= "FAAS ID: $faas_id\n";
      if ($property_id) {
        $logMessage .= "Property ID: $property_id\n";
      }
      $logMessage .= "Error: " . $stmt->error;

      logActivity($conn, $userId, $logMessage);
    }

    echo "Error: " . $stmt->error;
  }
  $stmt->close();
}

/**
 * Handle land record deletion (WITH LOGGING)
 */
function handleLandDelete($conn)
{
  $land_id = $_POST['delete_land_id'];
  $p_id = $_POST['p_id'];

  // ✅ Get land details before deletion for logging
  $land_info = null;
  if (isset($_SESSION['user_id'])) {
    $get_land = $conn->prepare("SELECT classification, sub_class, area, unit_value, market_value, assess_value FROM land WHERE land_id = ?");
    $get_land->bind_param("i", $land_id);
    $get_land->execute();
    $land_info = $get_land->get_result()->fetch_assoc();
    $get_land->close();
  }

  $stmt = $conn->prepare("DELETE FROM land WHERE land_id = ?");
  $stmt->bind_param("i", $land_id);

  if ($stmt->execute()) {
    // ✅ LOG LAND DELETION
    if (isset($_SESSION['user_id']) && $land_info) {
      $userId = $_SESSION['user_id'];
      $locationDetails = getPropertyLocationDetails($conn, $p_id);

      $logMessage  = "Deleted land record\n";
      $logMessage .= "Property ID: $p_id\n";
      $logMessage .= "Land ID: $land_id\n";
      $logMessage .= "Location: $locationDetails\n\n";

      $logMessage .= "Deleted Land Details:\n";
      $logMessage .= "• Classification: {$land_info['classification']}\n";
      if (!empty($land_info['sub_class'])) {
        $logMessage .= "• Sub-Class: {$land_info['sub_class']}\n";
      }
      $logMessage .= "• Area: {$land_info['area']} sq.m\n";
      $logMessage .= "• Market Value: ₱" . number_format($land_info['market_value'], 2) . "\n";
      $logMessage .= "• Assessed Value: ₱" . number_format($land_info['assess_value'], 2);

      logActivity($conn, $userId, $logMessage);
    }
  }

  $stmt->close();

  header("Location: FAAS.php?id=" . urlencode($p_id) . "#land-section");
  exit();
}

/**
 * Handle Property Information Update (WITH COMPREHENSIVE LOGGING)
 */
function handlePropertyUpdate($conn, $property_id)
{
  // Get old property data
  $old_property = fetchProperty($conn, $property_id);

  // Get new data from POST
  $house_no = $_POST['house_no'] ?? '';
  $block_no = $_POST['block_no'] ?? '';
  $land_area = $_POST['land_area'] ?? '';
  $city = $_POST['city'] ?? '';
  $district = $_POST['district'] ?? '';
  $barangay = $_POST['barangay'] ?? '';
  $house_tag_no = $_POST['house_tag_no'] ?? '';
  $desc_land = $_POST['desc_land'] ?? '';

  // Update property
  $stmt = $conn->prepare("UPDATE p_info SET house_no = ?, block_no = ?, land_area = ?, 
                          city = ?, district = ?, barangay = ?, house_tag_no = ?, desc_land = ?
                          WHERE p_id = ?");
  $stmt->bind_param(
    "ssdsssssi",
    $house_no,
    $block_no,
    $land_area,
    $city,
    $district,
    $barangay,
    $house_tag_no,
    $desc_land,
    $property_id
  );

  if ($stmt->execute()) {
    // ✅ LOG PROPERTY UPDATE WITH PREVIOUS VALUES
    if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      
      // Compare changes
      $changes = [];

      if ($old_property['house_no'] != $house_no) {
        $changes[] = "• House Number changed from '{$old_property['house_no']}' to '$house_no'";
      }
      if ($old_property['block_no'] != $block_no) {
        $old_val = $old_property['block_no'] ?: 'None';
        $new_val = $block_no ?: 'None';
        $changes[] = "• Block Number changed from '$old_val' to '$new_val'";
      }
      if ($old_property['land_area'] != $land_area) {
        $changes[] = "• Land Area changed from '{$old_property['land_area']}' to '$land_area' sq.m";
      }
      if ($old_property['city'] != $city) {
        $old_city = getMunicipalityName($conn, $old_property['city']);
        $new_city = getMunicipalityName($conn, $city);
        $changes[] = "• Municipality changed from '$old_city' to '$new_city'";
      }
      if ($old_property['district'] != $district) {
        $old_dist = getDistrictName($conn, $old_property['district']);
        $new_dist = getDistrictName($conn, $district);
        $changes[] = "• District changed from '$old_dist' to '$new_dist'";
      }
      if ($old_property['barangay'] != $barangay) {
        $old_brgy = getBarangayName($conn, $old_property['barangay']);
        $new_brgy = getBarangayName($conn, $barangay);
        $changes[] = "• Barangay changed from '$old_brgy' to '$new_brgy'";
      }
      if ($old_property['house_tag_no'] != $house_tag_no) {
        $old_val = $old_property['house_tag_no'] ?: 'None';
        $new_val = $house_tag_no ?: 'None';
        $changes[] = "• House Tag Number changed from '$old_val' to '$new_val'";
      }
      if ($old_property['desc_land'] != $desc_land) {
        $old_val = $old_property['desc_land'] ?: 'None';
        $new_val = $desc_land ?: 'None';
        $changes[] = "• Land Description changed from '$old_val' to '$new_val'";
      }

      if (!empty($changes)) {
        $locationDetails = getPropertyLocationDetails($conn, $property_id);
        
        $logMessage  = "Updated property information\n";
        $logMessage .= "Property ID: $property_id\n";
        $logMessage .= "Location: $locationDetails\n\n";
        $logMessage .= "Changes:\n" . implode("\n", $changes);

        logActivity($conn, $userId, $logMessage);
      }
    }

    $_SESSION['flash_success'] = "Property information updated successfully!";
  } else {
    $_SESSION['flash_error'] = "Error updating property: " . $stmt->error;
  }

  $stmt->close();
}

/**
 * Handle RPU Identification Update (WITH COMPREHENSIVE LOGGING)
 */
function handleRPUUpdate($conn, $property_id)
{
  // Get FAAS ID
  $faas_info = fetchFaasInfo($conn, $property_id);
  if (!$faas_info) {
    $_SESSION['flash_error'] = "FAAS information not found.";
    return;
  }

  $faas_id = $faas_info['faas_id'];

  // Get old RPU data
  $stmt = $conn->prepare("SELECT rpu_idno FROM faas WHERE faas_id = ?");
  $stmt->bind_param("i", $faas_id);
  $stmt->execute();
  $old_rpu_id = $stmt->get_result()->fetch_assoc()['rpu_idno'] ?? null;
  $stmt->close();

  $old_rpu = null;
  if ($old_rpu_id) {
    $stmt = $conn->prepare("SELECT arp, pin, taxability, effectivity FROM rpu_idnum WHERE rpu_id = ?");
    $stmt->bind_param("i", $old_rpu_id);
    $stmt->execute();
    $old_rpu = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }

  // Get new data from POST
  $arp = $_POST['arp'] ?? '';
  $pin = $_POST['pin'] ?? '';
  $taxability = $_POST['taxability'] ?? '';
  $effectivity = $_POST['effectivity'] ?? '';

  // Update or insert RPU
  if ($old_rpu_id && $old_rpu) {
    // UPDATE existing RPU
    $stmt = $conn->prepare("UPDATE rpu_idnum SET arp = ?, pin = ?, taxability = ?, effectivity = ? WHERE rpu_id = ?");
    $stmt->bind_param("ssssi", $arp, $pin, $taxability, $effectivity, $old_rpu_id);

    if ($stmt->execute()) {
      // ✅ LOG RPU UPDATE WITH PREVIOUS VALUES
      if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $locationDetails = getPropertyLocationDetails($conn, $property_id);

        $changes = [];

        if ($old_rpu['arp'] != $arp) {
          $old_val = $old_rpu['arp'] ?: 'None';
          $new_val = $arp ?: 'None';
          $changes[] = "• ARP changed from '$old_val' to '$new_val'";
        }
        if ($old_rpu['pin'] != $pin) {
          $old_val = $old_rpu['pin'] ?: 'None';
          $new_val = $pin ?: 'None';
          $changes[] = "• PIN changed from '$old_val' to '$new_val'";
        }
        if ($old_rpu['taxability'] != $taxability) {
          $old_val = $old_rpu['taxability'] ?: 'None';
          $new_val = $taxability ?: 'None';
          $changes[] = "• Taxability changed from '$old_val' to '$new_val'";
        }
        if ($old_rpu['effectivity'] != $effectivity) {
          $old_val = $old_rpu['effectivity'] ?: 'None';
          $new_val = $effectivity ?: 'None';
          $changes[] = "• Effectivity changed from '$old_val' to '$new_val'";
        }

        if (!empty($changes)) {
          $logMessage  = "Updated RPU identification\n";
          $logMessage .= "Property ID: $property_id\n";
          $logMessage .= "FAAS ID: $faas_id\n";
          $logMessage .= "Location: $locationDetails\n\n";
          $logMessage .= "Changes:\n" . implode("\n", $changes);

          logActivity($conn, $userId, $logMessage);
        }
      }

      $_SESSION['flash_success'] = "RPU Identification updated successfully!";
    } else {
      $_SESSION['flash_error'] = "Error updating RPU: " . $stmt->error;
    }
    $stmt->close();
  } else {
    // INSERT new RPU
    $stmt = $conn->prepare("INSERT INTO rpu_idnum (arp, pin, taxability, effectivity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $arp, $pin, $taxability, $effectivity);
    
    if ($stmt->execute()) {
      $new_rpu_id = $stmt->insert_id;
      
      // Link to FAAS
      $update_faas = $conn->prepare("UPDATE faas SET rpu_idno = ? WHERE faas_id = ?");
      $update_faas->bind_param("ii", $new_rpu_id, $faas_id);
      $update_faas->execute();
      $update_faas->close();
      
      // ✅ LOG NEW RPU
      if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $locationDetails = getPropertyLocationDetails($conn, $property_id);
        
        $logMessage  = "Added new RPU identification\n";
        $logMessage .= "Property ID: $property_id\n";
        $logMessage .= "FAAS ID: $faas_id\n";
        $logMessage .= "RPU ID: $new_rpu_id\n";
        $logMessage .= "Location: $locationDetails\n\n";
        
        $logMessage .= "RPU Details:\n";
        if (!empty($arp)) $logMessage .= "• ARP: $arp\n";
        if (!empty($pin)) $logMessage .= "• PIN: $pin\n";
        if (!empty($taxability)) $logMessage .= "• Taxability: $taxability\n";
        if (!empty($effectivity)) $logMessage .= "• Effectivity: $effectivity\n";
        
        logActivity($conn, $userId, $logMessage);
      }
      
      $_SESSION['flash_success'] = "RPU Identification added successfully!";
    } else {
      $_SESSION['flash_error'] = "Error adding RPU: " . $stmt->error;
    }
    $stmt->close();
  }
}

/**
 * Handle Land Record Update (WITH COMPREHENSIVE LOGGING)
 */
function handleLandUpdate($conn)
{
  $land_id = $_POST['land_id'];
  $p_id = $_POST['p_id'];

  // Get old land data
  $stmt = $conn->prepare("SELECT * FROM land WHERE land_id = ?");
  $stmt->bind_param("i", $land_id);
  $stmt->execute();
  $old_land = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$old_land) return;

  // Get new data from POST
  $classification = $_POST['classification'] ?? '';
  $sub_class = $_POST['sub_class'] ?? '';
  $area = $_POST['area'] ?? 0;
  $unit_value = $_POST['unit_value'] ?? 0;
  $market_value = $_POST['market_value'] ?? 0;
  $assess_level = $_POST['assess_level'] ?? 0;
  $assess_value = $_POST['assess_value'] ?? 0;

  // Update land record
  $stmt = $conn->prepare("UPDATE land SET classification = ?, sub_class = ?, area = ?, 
                          unit_value = ?, market_value = ?, assess_level = ?, assess_value = ?
                          WHERE land_id = ?");
  $stmt->bind_param(
    "ssdddddi",
    $classification,
    $sub_class,
    $area,
    $unit_value,
    $market_value,
    $assess_level,
    $assess_value,
    $land_id
  );

  if ($stmt->execute()) {
    // ✅ LOG LAND UPDATE WITH PREVIOUS VALUES
    if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      $locationDetails = getPropertyLocationDetails($conn, $p_id);

      $changes = [];

      if ($old_land['classification'] != $classification) {
        $changes[] = "• Classification changed from '{$old_land['classification']}' to '$classification'";
      }
      if ($old_land['sub_class'] != $sub_class) {
        $old_val = $old_land['sub_class'] ?: 'None';
        $new_val = $sub_class ?: 'None';
        $changes[] = "• Sub-Class changed from '$old_val' to '$new_val'";
      }
      if ($old_land['area'] != $area) {
        $changes[] = "• Area changed from '{$old_land['area']}' to '$area' sq.m";
      }
      if ($old_land['unit_value'] != $unit_value) {
        $changes[] = "• Unit Value changed from '₱" . number_format($old_land['unit_value'], 2) . "' to '₱" . number_format($unit_value, 2) . "'";
      }
      if ($old_land['market_value'] != $market_value) {
        $changes[] = "• Market Value changed from '₱" . number_format($old_land['market_value'], 2) . "' to '₱" . number_format($market_value, 2) . "'";
      }
      if ($old_land['assess_level'] != $assess_level) {
        $changes[] = "• Assessment Level changed from '{$old_land['assess_level']}%' to '{$assess_level}%'";
      }
      if ($old_land['assess_value'] != $assess_value) {
        $changes[] = "• Assessed Value changed from '₱" . number_format($old_land['assess_value'], 2) . "' to '₱" . number_format($assess_value, 2) . "'";
      }

      if (!empty($changes)) {
        $logMessage  = "Updated land record\n";
        $logMessage .= "Property ID: $p_id\n";
        $logMessage .= "Land ID: $land_id\n";
        $logMessage .= "Location: $locationDetails\n\n";
        $logMessage .= "Changes:\n" . implode("\n", $changes);

        logActivity($conn, $userId, $logMessage);
      }
    }

    $_SESSION['flash_success'] = "Land record updated successfully!";
  } else {
    $_SESSION['flash_error'] = "Error updating land: " . $stmt->error;
  }

  $stmt->close();

  header("Location: FAAS.php?id=" . urlencode($p_id) . "#land-section");
  exit();
}

/**
 * Handle Owner Changes (WITH COMPREHENSIVE LOGGING)
 */
function handleOwnerUpdate($conn, $property_id)
{
  // Get old owners with their details
  $old_owner_ids = fetchPropertyOwnerIDs($conn, $property_id);
  $old_owner_details = [];
  foreach ($old_owner_ids as $owner_id) {
    $stmt = $conn->prepare("SELECT CONCAT(own_fname, ' ', own_mname, ' ', own_surname) as name FROM owners_tb WHERE own_id = ?");
    $stmt->bind_param("i", $owner_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $old_owner_details[$owner_id] = $result['name'] ?? "Unknown";
    $stmt->close();
  }

  // Get new owners from POST
  $new_owners = isset($_POST['owner_ids']) ? explode(',', $_POST['owner_ids']) : [];
  $new_owners = array_map('intval', array_filter($new_owners));

  // Find added and removed owners
  $added_owners = array_diff($new_owners, $old_owner_ids);
  $removed_owners = array_diff($old_owner_ids, $new_owners);

  // Only proceed if there are changes
  if (empty($added_owners) && empty($removed_owners)) {
    $_SESSION['flash_info'] = "No changes made to property owners.";
    return;
  }

  // Update database (mark removed as not retained, add new ones)
  foreach ($removed_owners as $owner_id) {
    $stmt = $conn->prepare("UPDATE propertyowner SET is_retained = 0 WHERE property_id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $property_id, $owner_id);
    $stmt->execute();
    $stmt->close();
  }

  foreach ($added_owners as $owner_id) {
    // Check if already exists
    $check = $conn->prepare("SELECT propertyowner_id FROM propertyowner WHERE property_id = ? AND owner_id = ?");
    $check->bind_param("ii", $property_id, $owner_id);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();

    if ($exists) {
      // Re-activate
      $stmt = $conn->prepare("UPDATE propertyowner SET is_retained = 1 WHERE property_id = ? AND owner_id = ?");
      $stmt->bind_param("ii", $property_id, $owner_id);
      $stmt->execute();
      $stmt->close();
    } else {
      // Insert new
      $stmt = $conn->prepare("INSERT INTO propertyowner (property_id, owner_id, is_retained) VALUES (?, ?, 1)");
      $stmt->bind_param("ii", $property_id, $owner_id);
      $stmt->execute();
      $stmt->close();
    }
  }

  // ✅ LOG OWNER CHANGES WITH DETAILS
  if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $locationDetails = getPropertyLocationDetails($conn, $property_id);

    $logMessage  = "Updated property owners\n";
    $logMessage .= "Property ID: $property_id\n";
    $logMessage .= "Location: $locationDetails\n\n";

    // Show previous owners if any were removed
    if (!empty($removed_owners)) {
      $logMessage .= "Previous Owners:\n";
      foreach ($old_owner_ids as $owner_id) {
        $logMessage .= "• {$old_owner_details[$owner_id]} (ID: $owner_id)\n";
      }
      $logMessage .= "\n";
    }

    if (!empty($added_owners)) {
      $logMessage .= "Added Owners:\n";
      foreach ($added_owners as $owner_id) {
        $stmt = $conn->prepare("SELECT CONCAT(own_fname, ' ', own_mname, ' ', own_surname) as name FROM owners_tb WHERE own_id = ?");
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $owner_name = $stmt->get_result()->fetch_assoc()['name'] ?? "ID: $owner_id";
        $stmt->close();
        $logMessage .= "• $owner_name (ID: $owner_id)\n";
      }
    }

    if (!empty($removed_owners)) {
      if (!empty($added_owners)) $logMessage .= "\n";
      $logMessage .= "Removed Owners:\n";
      foreach ($removed_owners as $owner_id) {
        $logMessage .= "• {$old_owner_details[$owner_id]} (ID: $owner_id)\n";
      }
    }

    // Show current owners after changes
    $logMessage .= "\nCurrent Owners:\n";
    foreach ($new_owners as $owner_id) {
      $stmt = $conn->prepare("SELECT CONCAT(own_fname, ' ', own_mname, ' ', own_surname) as name FROM owners_tb WHERE own_id = ?");
      $stmt->bind_param("i", $owner_id);
      $stmt->execute();
      $owner_name = $stmt->get_result()->fetch_assoc()['name'] ?? "ID: $owner_id";
      $stmt->close();
      $logMessage .= "• $owner_name (ID: $owner_id)\n";
    }

    logActivity($conn, $userId, $logMessage);
  }

  $_SESSION['flash_success'] = "Property owners updated successfully!";
}

// ========================================================
// Inline OR Number Duplication Check (AJAX endpoint)
// ========================================================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'check_or_number') {
    header('Content-Type: application/json');
    require_once 'database.php';

    if (!isset($_GET['or']) || trim($_GET['or']) === '') {
        echo json_encode(['exists' => false]);
        exit;
    }

    $conn = Database::getInstance();
    $or_number = strtoupper(trim($_GET['or']));

    $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM print_certifications WHERE or_number = ?");
    $stmt->bind_param("s", $or_number);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    echo json_encode(['exists' => $row['cnt'] > 0]);
    $stmt->close();
    $conn->close();
    exit;
}

// ============================================================================
// MAIN REQUEST PROCESSING
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  // Disable property
  if ($action === 'disable_property') {
    handleDisableProperty($conn, $user_role, $current_user_id);
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($_POST['return_p_id'] ?? ''));
    exit;
  }

  // Delete land record
  elseif (isset($_POST['delete_land_id'])) {
    handleLandDelete($conn);
  }

  // Tax declaration add/update
  elseif (isset($_POST['arp_no'])) {
    $faas_info = fetchFaasInfo($conn, $property_id);
    if ($faas_info) {
      handleRPUDeclaration($conn, $faas_info['faas_id'], $property_id);
    }
  }

  // ✅ UPDATE PROPERTY INFORMATION
  elseif ($action === 'update_property') {
    handlePropertyUpdate($conn, $property_id);
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($property_id));
    exit;
  }

  // ✅ UPDATE RPU IDENTIFICATION
  elseif ($action === 'update_rpu') {
    handleRPUUpdate($conn, $property_id);
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($property_id) . "#rpu-identification-section");
    exit;
  }

  // ✅ UPDATE LAND RECORD
  elseif ($action === 'update_land') {
    handleLandUpdate($conn);
  }

  // ✅ UPDATE OWNERS
  elseif ($action === 'update_owners') {
    handleOwnerUpdate($conn, $property_id);
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($property_id) . "#owner-info-section");
    exit;
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

  <!-- History Section --> 
  <?php include 'partials/history.php'; ?>

  <!-- Valuation Section -->
  <?php include 'partials/valuation.php'; ?>

  <?php
  // Close the database connection safely AFTER all includes have used it
  if ($conn) {
    $conn->close();
  }
  ?>

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