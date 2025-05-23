<?php
require_once 'database.php';

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

function getOwnerFullNameByPInfo($conn, $p_id) {
    $full_name = null;
    $sql = "
        SELECT CONCAT(own_fname, ' ', own_mname, ' ', own_surname) AS full_name
        FROM p_info
        INNER JOIN owners_tb ON p_info.ownID_Fk = owners_tb.own_id
        WHERE p_info.p_id = ?
        LIMIT 1
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $stmt->bind_result($full_name);
        $stmt->fetch();
        $stmt->close();

        if (!$full_name) {
            die("Owner full name not found for p_id: $p_id.");
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }
    return $full_name;
}

// Fetch all land properties from 'land' table using faas_id
function getLandProperties($conn, $faas_id)
{
    $sql = "SELECT * FROM land WHERE faas_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPA Form No. 1A</title>
    <link rel="stylesheet" href="DRP.css">
</head>

<body>
    <div class="section">
        <p class="bold">RPA Form NO. 1A</p>
        <p><span class="bold">Assessment of Real Property No.:</span> <u>____<?= htmlspecialchars($rpu_data['arp'] ?? 'N/A') ?>____</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="bold">Property Index No.:</span> <u>____<?= htmlspecialchars($rpu_data['pin'] ?? 'N/A') ?>____</u></p>
    </div>

    <div class="section center">
        <p class="bold" style="font-size: 20px;">DECLARATION OF REAL PROPERTY</p>
        <p>(Filed Under Republic Act No. 7160)</p>
    </div>

    <div class="section">
        <p><span class="bold">Owner:</span><u>_______<?= htmlspecialchars($full_name ?? 'N/A') ?>_______</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="bold">Address:</span> _____________________________</p>
        <p><span class="bold">Administration:</span> _______________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="bold">Address:</span> _____________________________</p>
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
        <p><span class="bold">OCT/TCT No.:</span> <u>________</u> &nbsp;&nbsp; <span class="bold">Survey No.:</span> <u>_________</u> &nbsp;&nbsp; <span class="bold">Lot No.:</span> <u>_________</u> &nbsp;&nbsp; <span class="bold">Blk No.:</span> <u>_________</u></p>
    </div>

    <div class="section">
        <p class="bold">Boundaries:</p>
        <p>North: __________________________________ &nbsp;&nbsp; South: ___________________________________</p>
        <p>East: ___________________________________ &nbsp;&nbsp; West: ___________________________________</p>
        <p style="font-size: 12px; text-align: center;">(State streets, streams or PIN by which bounded, or names of the owner of adjacent lands)</p>
    </div>
    <div class="section">
        <table style="width: 100%;">
            <tr>
                <td class="bold" style="text-align: center;">Kind of Property</td>
                <td class="bold" style="text-align: center;">Actual Use</td>
                <td class="bold" style="text-align: center;">Area</td>
                <td class="bold" style="text-align: center;">Market Value</td>
                <td class="bold" style="text-align: center;">Assessment Level</td>
                <td class="bold" style="text-align: center;">Assessed Value</td>
            </tr>
            <?php
            $total_assessed = 0;
            $total_market = 0;
            if (!empty($land_properties)) {
                foreach ($land_properties as $land) {
                    $total_assessed += $land['assess_value'];
                    $total_market += $land['market_value'];
                    echo "<tr>";
                    echo "<td style='text-align: center;'>Land</td>"; // Static text instead of dynamic
                    echo "<td style='text-align: center;'>" . htmlspecialchars($land['actual_use']) . "</td>";
                    echo "<td style='text-align: center;'>" . htmlspecialchars($land['area']) . "</td>";
                    echo "<td style='text-align: center;'>₱" . number_format($land['market_value'], 2) . "</td>";
                    echo "<td style='text-align: center;'>" . htmlspecialchars($land['assess_lvl']) . "%</td>";
                    echo "<td style='text-align: center;'>₱" . number_format($land['assess_value'], 2) . "</td>";
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

            <div style="flex: 0.2;"></div> <!-- Smaller spacer -->

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

            <!-- Tax Effectivity  -->
            <span class="bold" style="margin-left: auto;">Tax Effectivity:</span>
            <span><u>____<?= htmlspecialchars($rpu_data['effectivity'] ?? 'N/A') ?>____</u></span>
        </p>
    </div>

    <div class="section">
        <!-- Area section -->
        <p><span class="bold">Area:</span> <u>____<?= htmlspecialchars($p_info['land_area'] ?? 'N/A') ?>____</u></p>
        <p style="display: flex; justify-content: space-between; margin: 0;">
            <span class="bold">Verified By: MA. SALOME A. BERTILLO</span>
            <span class="bold" style="text-align: right; margin-right: 20%;">Approved By:</span>
        </p>
        <div style="text-align: left; margin-top: 10px;">
        </div>
        <div style="text-align: right;">
            <u>MAXIMO P. MAGANA, JR., REA</u>
            <br>
            <span class="bold">Provincial Assessor</span>
        </div>

        <!-- Municipal Assessor section -->
        <div style="text-align: left; margin-top: 5px;">
            <span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>

            <p style="text-align: left; margin-top: 5px; margin-left: 40px;">
                <span class="bold">Municipal Assessor</span>
            </p>
        </div>

        <!-- Previous Assessed Value and Cancellation part -->
        <div class="section">
            <p style="display: flex; justify-content: space-between; margin: 0;">
                <span class="bold">Previous Assessed Value:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
                <span class="bold" style="text-align: left;">By:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
            </p>
            <p style="display: flex; justify-content: space-between; margin: 0;">
                <span>This declaration cancels No.:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
                <span>Date:</span><span style="border-bottom: 1px solid black; width: 30%; display: inline-block;"></span>
            </p>
            <p><b>Memoranda:</b>REVISED TO CORRECT THE TAXABILITY FROM TAXABLE TO EXEMPT PURSUANT TO SECTION 234 OF
                RA 7160. LETTER REQUEST, SEC CERTIFICATE OF INCORPORATION, ARTICLES OF INCORPORATION
                AND BY-LAWS, ALL PHOTOCOPY SUBMITTED. </p>
        </div>

        <div style="border: 1px solid black; padding: 10px; width: 95%; margin-left: auto; margin-right: auto; margin-bottom: 0;">
            <!-- Acknowledgement -->
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p style="margin: 0; font-weight: bold;">Acknowledgement:</p>
                    <div style="margin-top: 10px; display: flex; justify-content: space-between; width: 100%;">
                        <div style="margin-right: 20px;">
                            <span style="display: inline-block; border-bottom: 1px solid black; width: 200px;"></span>
                            <br>
                            <span style="font-size: 12px; font-weight: bold; margin-left: 25%;">Owner/Administrator</span>
                        </div>
                        <div>
                            <span style="display: inline-block; border-bottom: 1px solid black; width: 100px;"></span>
                            <br>
                            <span style="font-size: 12px; font-weight: bold; margin-left: 30%;">Date</span>
                        </div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0;"><span style="font-weight: bold;">Certification Fee:₱</span> <span style="border-bottom: 1px solid black; display: inline-block; width: 100px;"></span></p>
                    <p style="margin: 0;"><span style="font-weight: bold;">O.R. No.:</span> <span style="border-bottom: 1px solid black; display: inline-block; width: 150px;"></span></p>
                    <p style="margin: 0;"><span style="font-weight: bold;">Date Paid:</span> <span style="border-bottom: 1px solid black; display: inline-block; width: 150px;"></span></p>
                </div>
            </div>
        </div>
        <div class="footer" style="padding: 10px; font-size: 15px; margin-top: -5px;">
            <p><span class="bold">IMPORTANT:</span> This declaration is issued only in connection with real property taxation and the validation herein is based on a schedule of market values prepared for the purpose. It should not be considered as title to the property.</p>
        </div>
</body>
<script>
    //Automatically print after populating the fields
    setTimeout(() => {
        window.print();
    }, 500); // Adjust delay if needed
</script>
</html>