<?php
require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$p_id = isset($_GET['p_id']) ? (int) $_GET['p_id'] : 0;
$land_id = isset($_GET['land_id']) ? (int) $_GET['land_id'] : 0;

$rpu_idno = 0;
if ($stmt = $conn->prepare("SELECT rpu_idno FROM faas WHERE pro_id = ?")) {
    $stmt->bind_param("i", $p_id);
    $stmt->execute();
    $stmt->bind_result($rpu_idno);
    $stmt->fetch();
    $stmt->close();
}

if (!$rpu_idno) {
    die("Error: No RPU ID found for this property.");
}

$rpu_Data = null;
if ($stmt = $conn->prepare("SELECT * FROM rpu_idnum WHERE rpu_id = ?")) {
    $stmt->bind_param("i", $rpu_idno);
    $stmt->execute();
    $result = $stmt->get_result();
    $rpu_Data = $result->fetch_assoc();
    $stmt->close();

    if (!$rpu_Data) {
        die("RPU record not found.");
    }
}

$landRecord = null;
if ($land_id > 0) {
    if ($stmt = $conn->prepare("SELECT * FROM land WHERE land_id = ?")) {
        $stmt->bind_param("i", $land_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $landRecord = $result->fetch_assoc();
        $stmt->close();
    }
}

$certificationData = null;
if ($land_id > 0) {
    // Prepare the SQL query to fetch the certification data
    if ($stmt = $conn->prepare("SELECT * FROM certification WHERE land_id = ?")) {
        $stmt->bind_param("i", $land_id);  // Bind land_id from URL or previous query
        $stmt->execute();
        $result = $stmt->get_result();
        $certificationData = $result->fetch_assoc();  // Fetch the certification data
        $stmt->close();
    }

    // Optionally, handle cases where no certification is found
    if (!$certificationData) {
        echo "No certification data found for this land.";
    }
}

$propertyData = null;
if ($p_id > 0) {
    if ($stmt = $conn->prepare("SELECT * FROM p_info WHERE p_id = ?")) {
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $propertyData = $result->fetch_assoc();
        $stmt->close();
    }
}

$owners = [];
if ($p_id > 0) {
    if (
        $stmt = $conn->prepare("
        SELECT 
            o.own_id,
            o.own_fname,
            o.own_mname,
            o.own_surname,
            o.house_no,
            o.street,
            o.barangay,
            o.district,
            o.city,
            o.province,
            o.own_info
        FROM owners_tb o
        INNER JOIN propertyowner po ON o.own_id = po.owner_id
        WHERE po.property_id = ? AND po.is_retained = 1
    ")
    ) {
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $owners[] = $row;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Appraisal Sheet</title>
    <link rel="stylesheet" href="print-layout.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .front-page {
            padding: 20px;
        }

        .back-page {
            padding: 20px;
        }

        /* Ensure separate pages for front and back */
        @media print {
            .front-page {
                break-after: page;
                /* Add a page break after the front page */
            }

            .back-page {
                break-before: page;
                /* Ensure the back page starts on a new page */
            }

            @page {
                size: legal;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 12px;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        .section-title {
            font-weight: bold;
            text-align: center;
            padding: 10px 0;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .signature-block {
            width: 45%;
            margin-bottom: 20px;
            /* Add space between rows */
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 5px;
            width: 100%;
            text-align: center;
            position: relative;
        }

        .signature-text {
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
        }

        .signature-text div {
            text-align: right;
            /* Align content to the right */
            width: 100%;
            /* Ensure it spans the full width */
        }
    </style>
</head>

<body>
    <!-- Front Page -->
    <div class="front-page">
        <div class="header">
            REAL PROPERTY FIELD APPRAISAL AND ASSESSMENT SHEET - LAND/PLANTS & TREES
        </div>
        <table>
            <tr>
                <td><strong>ARP NO.:</strong> <?= htmlspecialchars($rpu_Data['arp'] ?? 'N/A') ?></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>PIN:</strong> <?= htmlspecialchars($rpu_Data['pin'] ?? 'N/A') ?></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>OCT/TCT NO.:</strong> <?= htmlspecialchars($landRecord['oct_no'] ?? 'N/A') ?></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Survey No.:</strong> <?= htmlspecialchars($landRecord['survey_no'] ?? 'N/A') ?></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <strong>OWNER:</strong><br>
                    <?php
                    $ownerNames = [];
                    foreach ($owners as $owner) {
                        $ownerNames[] = '&nbsp;&nbsp;' . htmlspecialchars($owner['own_fname'] . ' ' . $owner['own_mname'] . ' ' . $owner['own_surname']);
                    }
                    echo implode('<br>', $ownerNames);
                    ?>
                </td>
                <td>
                    <strong>ADDRESS:</strong><br>
                    <?php
                    $ownerAddresses = [];
                    foreach ($owners as $owner) {
                        $fullAddress = '&nbsp;&nbsp;&nbsp;&nbsp;' . htmlspecialchars(
                            $owner['house_no'] . ' ' . $owner['street'] . ', ' . $owner['barangay'] . ', ' . $owner['city']
                        );
                        $ownerAddresses[] = $fullAddress;
                    }
                    echo implode('<br>', $ownerAddresses);
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Administrator/Occupant:</strong>
                    <?= htmlspecialchars(
                        ($landRecord['first_name'] ?? 'N/A') . ' ' .
                        ($landRecord['middle_name'] ?? '') . ' ' .
                        ($landRecord['last_name'] ?? '')
                    ) ?>
                </td>
                <td><strong>ADDRESS:</strong>
                    <?= htmlspecialchars(
                        trim(
                            implode(', ', array_filter([
                                $landRecord['house_street'] ?? '',
                                $landRecord['barangay'] ?? '',
                                $landRecord['district'] ?? '',
                                $landRecord['municipality'] ?? '',
                                $landRecord['province'] ?? ''
                            ]))
                        )
                    ) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Tel No.:</strong><br>
                    <?php
                    $ownerPhones = [];
                    foreach ($owners as $owner) {
                        $own_info = $owner['own_info'];
                        preg_match('/Telephone:\s*(\d{11})/', $own_info, $matches);
                        if (isset($matches[1])) {
                            $ownerPhones[] = '&nbsp;&nbsp;&nbsp;&nbsp;' . htmlspecialchars($matches[1]);
                        } else {
                            $ownerPhones[] = "N/A";
                        }
                    }
                    echo implode('<br>', $ownerPhones);
                    ?>
                </td>
                <td></td>
            </tr>
        </table>

        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tr>
                <th colspan="2" class="section-title">PROPERTY LOCATION</th>
            </tr>
            <tr>
                <td><strong>No./Street:</strong>
                    <?= htmlspecialchars(($propertyData['house_no'] ?? 'N/A') . ' ' . ($propertyData['street'] ?? '')) ?>
                </td>
                <td><strong>Brgy./Dist:</strong>
                    <?= htmlspecialchars(
                        ($propertyData['barangay'] ?? 'N/A') .
                        (($propertyData['barangay'] && $propertyData['district']) ? ', ' : '') .
                        ($propertyData['district'] ?? 'N/A')
                    ) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Municipality:</strong> <?= htmlspecialchars($propertyData['city'] ?? 'N/A') ?></td>
                <td><strong>Province:</strong> <?= htmlspecialchars($propertyData['province'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td><strong>North:</strong> <?= htmlspecialchars($landRecord['north'] ?? 'N/A') ?></td>
                <td><strong>East:</strong> <?= htmlspecialchars($landRecord['east'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td><strong>West:</strong> <?= htmlspecialchars($landRecord['south'] ?? 'N/A') ?></td>
                <td><strong>South:</strong> <?= htmlspecialchars($landRecord['west'] ?? 'N/A') ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="6" class="section-title">LAND APPRAISAL:</th>
            </tr>
            <tr>
                <th>Classification</th>
                <th>Sub-Class</th>
                <th>Area</th>
                <th>Actual Use</th>
                <th>Unit Value</th>
                <th>Market Value</th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($landRecord['classification'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($landRecord['sub_class'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($landRecord['area'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($landRecord['actual_use'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($landRecord['unit_value'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($landRecord['market_value'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th colspan="5">TOTAL:</th>
                <td><b><?= htmlspecialchars($landRecord['market_value'] ?? 'N/A') ?></b></td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="8" class="section-title">PLANTS AND TREES APPRAISAL:</th>
            </tr>
            <tr>
                <th>Product Class</th>
                <th>Area Planted</th>
                <th>Total Number</th>
                <th>Non-fruit Bearing</th>
                <th>Fruit Bearing</th>
                <th>Age</th>
                <th>Unit Price</th>
                <th>Market Value</th>
            </tr>
            <tr>
                <td id="print-productClass"></td>
                <td id="print-areaPlanted"></td>
                <td id="print-totalNumber"></td>
                <td id="print-nonFruitBearing"></td>
                <td id="print-fruitBearing"></td>
                <td id="print-age"></td>
                <td id="print-unitPrice"></td>
                <td id="print-marketValue"></td>
            </tr>
            <!-- Additional rows can be added here if needed -->
            <tr>
                <th colspan="7">TOTAL:</th>
                <td id="print-totalMarketValue"></td>
            </tr>
        </table>

        <!-- Back Page Content -->
        <div class="back-page">
            <table>
                <tr>
                    <th colspan="5" class="section-title">VALUE ADJUSTMENTS FACTOR</th>
                </tr>
                <tr>
                    <th>Market Value</th>
                    <th>Adjustment Factors</th>
                    <th>% Adjustment</th>
                    <th>Value Adjustment</th>
                    <th>Adjustment Market Value</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($landRecord['market_value'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['adjust_factor'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['adjust_percent'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['adjust_value'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['adjust_mv'] ?? 'N/A') ?></td>
                </tr>
                <!-- Additional rows can be added here if needed -->
                <tr>
                    <td colspan="4"><b>TOTAL</b></td>
                    <td><b><?= htmlspecialchars($landRecord['adjust_mv'] ?? 'N/A') ?></b>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="5" class="section-title">VALUE ADJUSTMENTS FACTOR (SUMMARY)</th>
                </tr>
                <tr>
                    <th>Kind</th>
                    <th>Actual Use</th>
                    <th>Adjustment Market Value</th>
                    <th>Assessment Level (%)</th>
                    <th>Assessed Value</th>
                </tr>
                <tr>
                    <td>Land</td>
                    <td><?= htmlspecialchars($landRecord['actual_use'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['adjust_mv'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['assess_lvl'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($landRecord['assess_value'] ?? 'N/A') ?></td>
                </tr>
                <!-- Additional rows can be added here if needed -->
                <tr>
                    <td colspan="4"><b>TOTAL</b></td>
                    <td><b><?= htmlspecialchars($landRecord['assess_value'] ?? 'N/A') ?></b></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>Previous Owner: <span id="print-previousOwner"></span></td>
                    <td>Taxability: <?= htmlspecialchars($rpu_Data['taxability'] ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Previous Assessed Value: <span id="print-previousAssessedValue"></span></td>
                    <td>Effectivity: <?= htmlspecialchars($rpu_Data['effectivity'] ?? 'N/A') ?></span></td>
                </tr>
            </table>

            <div class="signature-section">
                <div class="signature-block">
                    <div class="signature-title">OWNER'S SIGNATURE:</div>
                    <br>
                    <div class="signature-line"></div>
                    <div class="signature-text">
                        <div>Print Name and Signature</div>
                        <div>Date</div>
                    </div>
                </div>

                <div class="signature-block">
                    <div class="signature-title">APPROVED:</div>
                    <br>
                    <div class="signature-text">
                        <b><?= htmlspecialchars($certificationData['approved'] ?? 'N/A') ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            (<?= htmlspecialchars($certificationData['approved_date'] ?? 'N/A') ?>)</b>
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-text">
                        <div>Provincial Assessor</div>
                        <div>Date</div>
                    </div>
                </div>
            </div>

            <div class="signature-section">
                <div class="signature-block">
                    <div class="signature-title">MA, SALOME A. BERTILLO</div>
                    <div class="signature-line"></div>
                    <div class="signature-text">
                        <div>Name</div>
                        <div>Date</div>
                    </div>
                </div>

                <div class="signature-block">
                    <div class="signature-title">RECOMMENDING APPROVAL:</div>
                    <br>
                    <div class="signature-text">
                        <b><?= htmlspecialchars($certificationData['recom_approval'] ?? 'N/A') ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            (<?= htmlspecialchars($certificationData['recom_date'] ?? 'N/A') ?>)
                        </b>
                    </div>
                    <div class="signature-line"></div>
                    <div class="signature-text">
                        <div>Date</div>
                    </div>
                </div>
            </div>

            <div class="signature-section">
                <div class="signature-block">
                    <div class="signature-title">By:</div>
                    <div class="signature-line"></div>
                </div>
            </div>


            <table>
                <tr>
                    <td colspan="2">MEMORANDA:</td>
                </tr>
                <tr>
                    <td class="memorandatxt" colspan="1">TRANSFERRED BY VIRTUE OF TRANSFER CERTIFICATE OF TITLE
                        NO.079-2024001759. AUTHENTICATED
                        COPY OF TITLE, DEED OF ABSOLUTE SALE NOTARIZED BY ATTY. DON H. CULVERA UNDER DOC. NO. 043;
                        PAGE NO. 10; BOOK NO.7; SERIES OF 2024, BIR CAR NO. eCR202300793135 DATED MAY 24, 2024
                        SIGNED BY RDO 064 DAISY B. DE LEON, CERT. OF TAX PAYMENT AND TRANSFER TAX RECEIPT,
                        ALL PHOTOCOPY SUBMITTED.</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="4" class="section-title">REFERENCE AND POSTING SUMMARY</th>
                </tr>
                <tr>
                    <th>Pre and Post Inspection</th>
                    <th>Previous</th>
                    <th>Present</th>
                    <th>Posting</th>
                </tr>
                <tr>
                    <td>Pin</td>
                    <td id="print-previousPin"></td>
                    <td id="print-presentPin"></td>
                    <td id="print-postingPin"></td>
                </tr>
                <tr>
                    <td>TDN/ARP NO.</td>
                    <td id="print-previousArpNo"></td>
                    <td id="print-presentArpNo"></td>
                    <td id="print-postingArpNo"></td>
                </tr>
                <tr>
                    <td>Ass. Roll Page No.</td>
                    <td id="print-previousRollPageNo"></td>
                    <td id="print-presentRollPageNo"></td>
                    <td id="print-postingRollPageNo"></td>
                </tr>
            </table>
        </div>

</body>
<script>
    //Automatically print after populating the fields
    setTimeout(() => {
        window.print();
    }, 500); // Adjust delay if needed
</script>

</html>