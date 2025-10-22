<?php
session_start();
require_once 'database.php';

$conn = Database::getInstance();

$user_id = $_SESSION['user_id'] ?? 0;
$property_id = (int) ($_POST['property_id'] ?? 0);
$remove_ids = $_POST['owners_to_remove'] ?? [];
$add_ids = $_POST['owners_to_add'] ?? [];

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
 * Helper function to get property location details
 */
function getPropertyLocationDetails($conn, $property_id)
{
  $stmt = $conn->prepare("SELECT house_no, city, district, barangay FROM p_info WHERE p_id = ?");
  $stmt->bind_param("i", $property_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $property = $result->fetch_assoc();
  $stmt->close();
  
  if (!$property) return "Property ID: $property_id";
  
  // Get readable names
  $municipalityName = 'Unknown';
  $barangayName = 'Unknown';
  $districtName = 'Unknown';
  
  if (!empty($property['city'])) {
    $stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
    $stmt->bind_param("i", $property['city']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) $municipalityName = $row['m_description'];
    $stmt->close();
  }
  
  if (!empty($property['barangay'])) {
    $stmt = $conn->prepare("SELECT brgy_name FROM brgy WHERE brgy_id = ?");
    $stmt->bind_param("i", $property['barangay']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) $barangayName = $row['brgy_name'];
    $stmt->close();
  }
  
  if (!empty($property['district'])) {
    $stmt = $conn->prepare("SELECT description FROM district WHERE district_id = ?");
    $stmt->bind_param("i", $property['district']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) $districtName = $row['description'];
    $stmt->close();
  }
  
  return "House #" . $property['house_no'] . ", " . $barangayName . ", " . $districtName . ", " . $municipalityName;
}

// ----------------------
// Lookup tax declaration via FAAS
// ----------------------
$sql = "SELECT r.dec_id, r.faas_id, r.arp_no, r.tax_year
        FROM rpu_dec r
        JOIN faas f ON f.faas_id = r.faas_id
        WHERE f.pro_id = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$taxdec = $stmt->get_result()->fetch_assoc();
$tax_dec_id = $taxdec['dec_id'] ?? 0;
$faas_id = $taxdec['faas_id'] ?? 0;
$arp_no = $taxdec['arp_no'] ?? 'N/A';
$tax_year = $taxdec['tax_year'] ?? 'N/A';

if (!$tax_dec_id) {
    die("No tax declaration found for property ID $property_id");
}

// ----------------------
// Helper: get owner details as string
// ----------------------
function getOwnerDetails($conn, $oid)
{
    $stmt = $conn->prepare("SELECT own_fname, own_mname, own_surname, street, barangay, city, province
                            FROM owners_tb WHERE own_id = ?");
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    $owner = $stmt->get_result()->fetch_assoc();
    if (!$owner)
        return "Owner ID $oid (not found)";
    $fullname = trim(($owner['own_fname'] ?? '') . ' ' . ($owner['own_mname'] ?? '') . ' ' . ($owner['own_surname'] ?? ''));
    $addressParts = array_filter([$owner['street'] ?? '', $owner['barangay'] ?? '', $owner['city'] ?? '', $owner['province'] ?? ''], fn($p) => trim($p) !== '');
    $address = implode(', ', $addressParts);
    return "$fullname ($address)";
}

/**
 * Helper: get owner name only (no address)
 */
function getOwnerName($conn, $oid)
{
    $stmt = $conn->prepare("SELECT own_fname, own_mname, own_surname FROM owners_tb WHERE own_id = ?");
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    $owner = $stmt->get_result()->fetch_assoc();
    if (!$owner) return "Owner ID $oid";
    return trim(($owner['own_fname'] ?? '') . ' ' . ($owner['own_mname'] ?? '') . ' ' . ($owner['own_surname'] ?? ''));
}

// ----------------------
// Step 1: Confirmation Page
// ----------------------
if (!isset($_POST['confirm'])) {
    $removeDetails = array_map(fn($id) => getOwnerDetails($conn, $id), $remove_ids);
    $addDetails = array_map(fn($id) => getOwnerDetails($conn, $id), $add_ids);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Confirm Ownership Change</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .card-custom {
                max-width: 700px;
                margin: auto;
            }

            .list-group {
                text-align: left;
            }
        </style>
    </head>

    <body class="bg-light">
        <div class="container py-5">
            <div class="card shadow border-0 card-custom">
                <div class="card-header bg-dark text-white text-center">
                    <h5 class="mb-0">Confirm Ownership Change</h5>
                </div>
                <div class="card-body text-center">
                    <p class="small text-muted">
                        You are about to update the official ownership records for this property.
                        Please carefully review the details below before confirming. <br>Once confirmed,
                        the system will update the database and record the changes in the audit log.
                    </p>
                    <hr>
                    <p><strong>Property ID:</strong> <?= htmlspecialchars($property_id) ?></p>
                    <p><strong>ARP Number:</strong> <?= htmlspecialchars($arp_no) ?></p>
                    <p><strong>Tax Year:</strong> <?= htmlspecialchars($tax_year) ?></p>

                    <h6 class="mt-4">Owners to be Removed</h6>
                    <?php if (!empty($removeDetails)): ?>
                        <ul class="list-group mb-3 d-inline-block">
                            <?php foreach ($removeDetails as $d): ?>
                                <li class="list-group-item"><?= htmlspecialchars($d) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-secondary d-inline-block">No owners selected for removal.</div>
                    <?php endif; ?>

                    <h6 class="mt-4">Owners to be Added (New Title Holders)</h6>
                    <?php if (!empty($addDetails)): ?>
                        <ul class="list-group mb-3 d-inline-block">
                            <?php foreach ($addDetails as $d): ?>
                                <li class="list-group-item"><?= htmlspecialchars($d) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-secondary d-inline-block">No new owners selected to add.</div>
                    <?php endif; ?>

                    <div class="alert alert-warning mt-2 text-start">
                        <strong>Note:</strong> This action is permanent and will be recorded in the official audit log
                        together with a snapshot of the current tax declaration (RPU record).
                    </div>

                    <form method="post" class="mt-4">
                        <?php
                        // Preserve POST data
                        foreach ($_POST as $key => $val) {
                            if (is_array($val)) {
                                foreach ($val as $v) {
                                    echo "<input type='hidden' name='{$key}[]' value='" . htmlspecialchars($v, ENT_QUOTES) . "'>";
                                }
                            } else {
                                echo "<input type='hidden' name='$key' value='" . htmlspecialchars($val, ENT_QUOTES) . "'>";
                            }
                        }
                        ?>
                        <input type="hidden" name="confirm" value="1">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                Confirm
                            </button>
                            <a href="FAAS.php?id=<?= $property_id ?>" class="btn btn-outline-secondary btn-sm">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <p class="text-muted small mt-3">
                        By confirming, you acknowledge that the ownership transfer is valid and authorized.
                    </p>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit;
}

// ----------------------
// Step 2: Actual update logic WITH COMPREHENSIVE LOGGING
// ----------------------
$conn->begin_transaction();

try {
    // ✅ Get property location for logging
    $locationDetails = getPropertyLocationDetails($conn, $property_id);
    
    // ✅ Get all current owners before changes
    $current_owners_stmt = $conn->prepare("SELECT owner_id FROM propertyowner WHERE property_id = ? AND is_retained = 1");
    $current_owners_stmt->bind_param("i", $property_id);
    $current_owners_stmt->execute();
    $current_owners_result = $current_owners_stmt->get_result();
    $previous_owner_ids = [];
    while ($row = $current_owners_result->fetch_assoc()) {
        $previous_owner_ids[] = $row['owner_id'];
    }
    $current_owners_stmt->close();
    
    // --- 1. Handle Removed Owners (with audit log) ---
    foreach ($remove_ids as $oid) {
        $stmt = $conn->prepare("UPDATE propertyowner 
                                SET is_retained = 0 
                                WHERE property_id = ? AND owner_id = ?");
        $stmt->bind_param("ii", $property_id, $oid);
        $stmt->execute();

        // Keep your existing owner_audit_log
        $ownerDetails = getOwnerDetails($conn, $oid);
        $details = "Removed: $ownerDetails from property $property_id";
        $stmt2 = $conn->prepare("INSERT INTO owner_audit_log 
            (action, owner_id, property_id, user_id, `tax-dec_id`, details) 
            VALUES ('Removed', ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiiis", $oid, $property_id, $user_id, $tax_dec_id, $details);
        $stmt2->execute();
    }

    // --- 2. Handle Added Owners ---
    foreach ($add_ids as $oid) {
        $stmt = $conn->prepare("INSERT INTO propertyowner 
            (property_id, owner_id, is_retained, created_by) 
            VALUES (?, ?, 1, ?)");
        $stmt->bind_param("iii", $property_id, $oid, $user_id);
        $stmt->execute();
        
        // Keep your existing owner_audit_log
        $ownerDetails = getOwnerDetails($conn, $oid);
        $details = "Added: $ownerDetails to property $property_id";
        $stmt2 = $conn->prepare("INSERT INTO owner_audit_log 
            (action, owner_id, property_id, user_id, `tax-dec_id`, details) 
            VALUES ('Added', ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiiis", $oid, $property_id, $user_id, $tax_dec_id, $details);
        $stmt2->execute();
    }
    
    // ✅ Get new current owners after changes
    $new_owners_stmt = $conn->prepare("SELECT owner_id FROM propertyowner WHERE property_id = ? AND is_retained = 1");
    $new_owners_stmt->bind_param("i", $property_id);
    $new_owners_stmt->execute();
    $new_owners_result = $new_owners_stmt->get_result();
    $new_owner_ids = [];
    while ($row = $new_owners_result->fetch_assoc()) {
        $new_owner_ids[] = $row['owner_id'];
    }
    $new_owners_stmt->close();

    // ✅ LOG COMPREHENSIVE OWNERSHIP TRANSFER TO ACTIVITY LOG
    if ($user_id && (!empty($remove_ids) || !empty($add_ids))) {
        $logMessage  = "Ownership Transfer Completed\n";
        $logMessage .= "Property ID: $property_id\n";
        $logMessage .= "Location: $locationDetails\n";
        $logMessage .= "Tax Declaration ID: $tax_dec_id\n";
        $logMessage .= "ARP Number: $arp_no\n";
        $logMessage .= "Tax Year: $tax_year\n\n";
        
        // Previous owners
        if (!empty($previous_owner_ids)) {
            $logMessage .= "Previous Owners:\n";
            foreach ($previous_owner_ids as $prev_id) {
                $name = getOwnerName($conn, $prev_id);
                $logMessage .= "• $name (ID: $prev_id)\n";
            }
            $logMessage .= "\n";
        }
        
        // Removed owners
        if (!empty($remove_ids)) {
            $logMessage .= "Removed Owners:\n";
            foreach ($remove_ids as $rem_id) {
                $name = getOwnerName($conn, $rem_id);
                $logMessage .= "• $name (ID: $rem_id)\n";
            }
            $logMessage .= "\n";
        }
        
        // Added owners
        if (!empty($add_ids)) {
            $logMessage .= "Added Owners (New Title Holders):\n";
            foreach ($add_ids as $add_id) {
                $name = getOwnerName($conn, $add_id);
                $logMessage .= "• $name (ID: $add_id)\n";
            }
            $logMessage .= "\n";
        }
        
        // Current owners after transfer
        $logMessage .= "Current Owners After Transfer:\n";
        if (!empty($new_owner_ids)) {
            foreach ($new_owner_ids as $curr_id) {
                $name = getOwnerName($conn, $curr_id);
                $logMessage .= "• $name (ID: $curr_id)\n";
            }
        } else {
            $logMessage .= "• None\n";
        }
        
        $logMessage .= "\nTransfer Status: Successfully completed";
        
        logActivity($conn, $user_id, $logMessage);
    }

    $conn->commit();

    header("Location: FAAS.php?id=$property_id&status=success");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    
    // ✅ LOG FAILED TRANSFER
    if ($user_id) {
        $locationDetails = getPropertyLocationDetails($conn, $property_id);
        $logMessage  = "Failed Ownership Transfer\n";
        $logMessage .= "Property ID: $property_id\n";
        $logMessage .= "Location: $locationDetails\n";
        $logMessage .= "Tax Declaration ID: $tax_dec_id\n";
        $logMessage .= "Error: " . $e->getMessage();
        
        logActivity($conn, $user_id, $logMessage);
    }
    
    die("Ownership transfer failed: " . $e->getMessage());
}