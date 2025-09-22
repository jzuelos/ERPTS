<?php
require_once '../database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$p_id = isset($_GET['p_id']) ? (int) $_GET['p_id'] : 0;

function convertNumberToWords($number)
{
    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    return ucwords($f->format($number));
}

// Fetch p_info by p_id
function getPInfo($conn, $p_id)
{
    $p_info = null;
    if ($stmt = $conn->prepare("SELECT * FROM p_info WHERE p_id = ?")) {
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $p_info = $result->fetch_assoc();
        $stmt->close();

        if (!$p_info) {
            die("p_info record not found.");
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }

    return $p_info;
}

// Fetch faas by pro_id (same as p_id)
function getFaasInfo($conn, $p_id)
{
    $faas_info = null;
    if ($stmt = $conn->prepare("SELECT * FROM faas WHERE pro_id = ?")) {
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $faas_info = $result->fetch_assoc();
        $stmt->close();

        if (!$faas_info) {
            die("FAAS record not found.");
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }

    return $faas_info;
}

// Fetch owner full name(s) by p_id using propertyowner + owners_tb
function getOwnerFullNameByPInfo($conn, $p_id)
{
    $owners = [];
    $sql = "
        SELECT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname) AS full_name
        FROM propertyowner po
        INNER JOIN owners_tb o ON po.owner_id = o.own_id
        WHERE po.property_id = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $owners[] = trim($row['full_name']);
        }
        $stmt->close();

        if (empty($owners)) {
            return "N/A";
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }

    // Return as a single string (comma separated)
    return implode(", ", $owners);
}


// Fetch all land properties from 'land' table using faas_id
function getLandProperties($conn, $faas_id)
{
    $sql = "SELECT * FROM land WHERE faas_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt)
        die("Prepare failed: " . $conn->error);

    $stmt->bind_param("i", $faas_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $land_data = [];
    while ($row = $result->fetch_assoc()) {
        $land_data[] = $row;
    }
    return $land_data;
}

// Fetch RPU data using faas_id
function getRpuDataByFaasId($conn, $faas_id)
{
    $rpu_data = null;
    if ($stmt = $conn->prepare("SELECT * FROM rpu_idnum WHERE faas_id = ?")) {
        $stmt->bind_param("i", $faas_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rpu_data = $result->fetch_assoc();
        $stmt->close();

        if (!$rpu_data) {
            die("RPU record not found for faas_id: $faas_id.");
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }
    return $rpu_data;
}

// Execute and store data
$p_info = getPInfo($conn, $p_id);
$faas_info = getFaasInfo($conn, $p_id);
$full_name = getOwnerFullNameByPInfo($conn, $p_id);

$faas_id = $faas_info['faas_id']; // define this before using in RPU or land
$rpu_data = getRpuDataByFaasId($conn, $faas_id);
$land_properties = getLandProperties($conn, $faas_id);

// Now $p_info, $faas_info, $rpu_data, and $land_properties are ready to use

// helper: format PIN as 000-00-000-00-000
function formatPin($value)
{
    $digits = preg_replace('/\D/', '', $value); // keep digits only
    if (strlen($digits) !== 13) {
        return $value; // return as-is if not 13 digits
    }
    return implode('-', [
        substr($digits, 0, 3),
        substr($digits, 3, 2),
        substr($digits, 5, 3),
        substr($digits, 8, 2),
        substr($digits, 10, 3),
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RPA Form No. 1A</title>

</head>
<link rel="stylesheet" href="DRP.css">
<body>

<div class="border">

<!-- Header -->
<div class="section">
    <p class="bold">RPA Form NO. 1A</p>
    <p>
        <span class="bold">Assessment of Real Property No.:</span>
        <u>_________<?= htmlspecialchars($rpu_data['arp'] ?? 'N/A') ?>_______________</u>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="bold">Property Index No.:</span>
        <u>________<?= isset($rpu_data['pin']) ? formatPin($rpu_data['pin']) : 'N/A' ?>___________</u>
</div>

<div class="section center" style="text-align: center;">
    <p class="bold" style="font-size: 20px; margin: 0;">DECLARATION OF REAL PROPERTY</p>
    <p style="margin: 0; font-size: 14px;">(Filed Under Republic Act No. 7160)</p>
</div>


<div class="section">
    <p><span class="bold">Owner: </span><u> _________<?= htmlspecialchars($full_name ?? 'N/A') ?>__________________________</u>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="bold">Address:</span> ___________________________________________</p>
    <p><span class="bold">Administration:</span> _______________________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span
            class="bold">Address:</span> ___________________________________________</p>
</div>

<div class="section">
    <p><span class="bold">Location of Property:</span></p>
    <div class="location">
        <div>
            <u>________<?= htmlspecialchars(($p_info['house_no'] ?? '') . ' ' . ($p_info['street'] ?? '')) ?: 'N/A' ?>________</u>
            <span class="label">(Number and Street)</span>
        </div>
        <div>
            <u>____<?= htmlspecialchars($p_info['barangay'] ?? 'N/A') ?>____</u>
            <span class="label">(Barangay)</span>
        </div>
        <div>
            <u>____<?= htmlspecialchars($p_info['province'] ?? 'N/A') ?>____</u>
            <span class="label">(City)</span>
        </div>
    </div>
</div>

<div class="section">
    <p><span class="bold">OCT/TCT No.:</span> <u>_________________</u> &nbsp;&nbsp; <span class="bold">Survey No.:</span>
        <u>_________________</u> &nbsp;&nbsp; <span class="bold">Lot No.:</span> <u>_________________</u> &nbsp;&nbsp; <span
            class="bold">Blk No.:</span> <u>_________________</u>
    </p>
</div>

<div class="section" style="display: flex; align-items: center; gap: 20px;">
    <p class="bold" style="margin: 0;">Boundaries:</p>
    <p style="margin: 0;">North: ____________________</p>
    <p style="margin: 0;">East: ____________________</p>
    <p style="margin: 0;">South: ____________________</p>
    <p style="margin: 0;">West: ____________________</p>
</div>
<p style="font-size: 12px; text-align: center; margin-top: 5px;">
    (State streets, streams or PIN by which bounded, or names of the owner of adjacent lands)
</p>
<div style="width: 100%; border-bottom: 1px solid black; margin: 5px 0;"></div>
<div style="width: 100%; border-bottom: 1px solid black; margin: 5px 0;"></div>

<div class="section">
        <table class="underline-table">
            <tr>
                <td class="bold">Kind of Property</td>
                <td class="bold">Actual Use</td>
                <td class="bold">Area</td>
                <td class="bold">Market Value</td>
                <td class="bold">Assessment Level</td>
                <td class="bold">Assessed Value</td>
            </tr>
             <?php
            $total_assessed = 0;
            $total_market = 0;
            if (!empty($land_properties)) {
                foreach ($land_properties as $land) {
                    $total_assessed += $land['assess_value'];
                    $total_market += $land['market_value'];
                    echo "<tr>";
                    echo "<td>Land</td>"; // Static text instead of dynamic
                    echo "<td>" . htmlspecialchars($land['actual_use']) . "</td>";
                    echo "<td>" . htmlspecialchars($land['area']) . "</td>";
                    echo "<td>₱" . number_format($land['market_value'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($land['assess_lvl']) . "%</td>";
                    echo "<td>₱" . number_format($land['assess_value'], 2) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No land data available.</td></tr>";
            }
            ?>
        </table>


    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <div style="flex: 2; text-align: center;">
            <p><span class="bold">TOTAL</span><br>₱<?= number_format($total_market, 2) ?></p>
        </div>

        <div style="flex: 0.2;"></div>

        <div style="flex: 2; text-align: center;">
            <p><span class="bold">TOTAL</span><br>₱<?= number_format($total_assessed, 2) ?></p>
        </div>
    </div>

    <div style="margin-top: 5px;">
        <p style="text-align: left; display: inline-block;">
            <span class="bold">Total Assessed Value:</span>
        </p>
        <p style="display: inline-block; border-bottom: 1px solid black; width: 75%;">
            <?= ucwords(convertNumberToWords($total_assessed)) ?> Pesos Only
        </p>
        <div style="clear: both; text-align: center; font-style: italic; font-size: 12px;">
            (Amount in Words)
        </div>
    </div>
</div>

<div class="section">
    <p style="display: flex; justify-content: space-between; align-items: flex-end; margin: 0;">
        <span class="bold" style="margin-right: 5px;">Taxable:</span>
        <span class="bold" style="margin-right: 5px;">{ }</span>
        <span class="bold" style="margin-right: 15px;">Exempt:</span>
        <span class="bold" style="margin-right: 20px;">{ }</span>

        <span class="bold" style="margin-left: auto;">Tax Effectivity:</span>
        <span><u>____<?= htmlspecialchars($rpu_data['effectivity'] ?? 'N/A') ?>____</u></span>
    </p>
</div>

<div class="section">
    <p><span class="bold">Area:</span> <u>____<?= htmlspecialchars($p_info['land_area'] ?? 'N/A') ?>____</u></p>
    <p style="display: flex; justify-content: space-between; margin: 0; ">
        <span class="bold">Verified By: MA. SALOME A. BERTILLO</span>
        <span class="bold" style="text-align: right; margin-right: 20%;">Approved By:</span>
    </p>
<div style="text-align: right; margin-top: 5px; margin-right: 10px;">
    <u>MAXIMO P. MAGANA, JR., REA</u><br>
    <div style="margin-top: 2px; text-align: right; margin-right: 30px;">
    <span class="bold">Provincial Assessor</span>
</div>
</div>

</div>

<div class="section">
    <p style="display: flex; justify-content: space-between; margin: 0;">
        <span class="bold">Previous Assessed Value:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
        <span class="bold">By:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
    </p>
    <p style="display: flex; justify-content: space-between; margin: 0;">
        <span>This declaration cancels No.:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
        <span>Date:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
    </p>
    <p style="font-size: 14px; margin-top: 20px;">
        <b>Memoranda:</b> REVISED TO CORRECT THE TAXABILITY FROM TAXABLE TO EXEMPT PURSUANT TO SECTION 234 OF
        RA 7160. LETTER REQUEST, SEC CERTIFICATE OF INCORPORATION, ARTICLES OF INCORPORATION
        AND BY-LAWS, ALL PHOTOCOPY SUBMITTED.
    </p>
</div>

<div style="border: 1px solid black; padding: 15px; width: 92%; margin: auto; margin-top: 25p; min-height: 100px; ">
    <div style="display: flex; justify-content: space-between;">
        <div>
            <p style="margin: 0; font-weight: bold;">Acknowledgement:</p>
            <div style="margin-top: 15px; display: flex; justify-content: space-between; width: 100%; min-height: 50px;">
                <div style="margin-right: 20px;">
                    <span style="display: inline-block; border-bottom: 1px solid black; width: 250px; height: 25px;"></span><br>
                    <span style="font-size: 12px; font-weight: bold; margin-left: 25%;">Owner/Administrator</span>
                </div>
                <div>
                    <span style="display: inline-block; border-bottom: 1px solid black; width: 150px; height: 25px;"></span><br>
                    <span style="font-size: 12px; font-weight: bold; margin-left: 25%;">Date</span>
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; margin-bottom: 5px;"><span style="font-weight: bold;">Certification Fee:₱</span> <span
                    style="border-bottom: 1px solid black; display: inline-block; width: 120px; height: 20px;"></span></p>
            <p style="margin: 0; margin-bottom: 5px;"><span style="font-weight: bold;">O.R. No.:</span> <span
                    style="border-bottom: 1px solid black; display: inline-block; width: 180px; height: 20px;"></span></p>
            <p style="margin: 0;"><span style="font-weight: bold;">Date Paid:</span> <span
                    style="border-bottom: 1px solid black; display: inline-block; width: 180px; height: 20px;"></span></p>
        </div>
    </div>
</div>


<div class="footer" style="padding: 10px; font-size: 15px; margin-top: -5px;">
    <p><span class="bold">IMPORTANT:</span> This declaration is issued only in connection with real property
                taxation and the validation herein is based on a schedule of market values prepared for the purpose. It
                should not be considered as title to the property.</p>
</div>

</div>

<script>
    setTimeout(() => { window.print(); }, 500);
</script>

</body>
</html>
