<?php
session_start();
require_once 'database.php';

$conn = Database::getInstance();

$user_id     = $_SESSION['user_id'] ?? 0;
$property_id = (int)($_POST['property_id'] ?? 0);
$remove_ids  = $_POST['owners_to_remove'] ?? [];
$add_ids     = $_POST['owners_to_add'] ?? [];

// ----------------------
// Lookup tax declaration via FAAS
// ----------------------
$sql = "SELECT r.dec_id, r.faas_id
        FROM rpu_dec r
        JOIN faas f ON f.faas_id = r.faas_id
        WHERE f.pro_id = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$taxdec = $stmt->get_result()->fetch_assoc();
$tax_dec_id = $taxdec['dec_id'] ?? 0;
$faas_id    = $taxdec['faas_id'] ?? 0;

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
    if (!$owner) return "Owner ID $oid (not found)";
    $fullname = trim(($owner['own_fname'] ?? '') . ' ' . ($owner['own_mname'] ?? '') . ' ' . ($owner['own_surname'] ?? ''));
    $addressParts = array_filter([$owner['street'] ?? '', $owner['barangay'] ?? '', $owner['city'] ?? '', $owner['province'] ?? ''], fn($p) => trim($p) !== '');
    $address = implode(', ', $addressParts);
    return "$fullname ($address)";
}

// ----------------------
// Step 1: Confirmation Page
// ----------------------
if (!isset($_POST['confirm'])) {
    $removeDetails = array_map(fn($id) => getOwnerDetails($conn, $id), $remove_ids);
    $addDetails    = array_map(fn($id) => getOwnerDetails($conn, $id), $add_ids);
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
                /* keep items aligned inside */
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
                    <p><strong>Tax Declaration ID:</strong> <?= htmlspecialchars($tax_dec_id) ?></p>

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
// Step 2: Actual update logic
// ----------------------
$conn->begin_transaction();

try {
    // --- 1. Handle Removed Owners ---
    foreach ($remove_ids as $oid) {
        $stmt = $conn->prepare("UPDATE propertyowner 
                                SET is_retained = 0 
                                WHERE property_id = ? AND owner_id = ?");
        $stmt->bind_param("ii", $property_id, $oid);
        $stmt->execute();

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

        $ownerDetails = getOwnerDetails($conn, $oid);
        $details = "Added: $ownerDetails to property $property_id";
        $stmt2 = $conn->prepare("INSERT INTO owner_audit_log 
            (action, owner_id, property_id, user_id, `tax-dec_id`, details) 
            VALUES ('Added', ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiiis", $oid, $property_id, $user_id, $tax_dec_id, $details);
        $stmt2->execute();
    }

    // --- 3. Snapshot of rpu_dec ---
    $sql = "SELECT * FROM rpu_dec WHERE faas_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $faas_id);
    $stmt->execute();
    $snapshot = $stmt->get_result()->fetch_assoc();

    if ($snapshot) {
        $json = json_encode($snapshot);
        $allOwners = array_merge($remove_ids, $add_ids);
        foreach ($allOwners as $oid) {
            $stmt2 = $conn->prepare("INSERT INTO owner_audit_log 
                (action, owner_id, property_id, user_id, `tax-dec_id`, details) 
                VALUES ('Snapshot', ?, ?, ?, ?, ?)");
            $stmt2->bind_param("iiiis", $oid, $property_id, $user_id, $tax_dec_id, $json);
            $stmt2->execute();
        }
    }

    $conn->commit();

    header("Location: FAAS.php?id=$property_id&status=success");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    die("Ownership transfer failed: " . $e->getMessage());
}
