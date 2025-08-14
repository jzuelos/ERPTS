<?php
// Configurable location data
$location_data = array(
    "province_city" => "CAMARINES NORTE",
    "municipality" => "DAET",
    "district" => "",
    "barangay" => "ALAVIHAO"
);

// Sample property data
$properties = array(
    array(
        "property_owner" => "APOLINAR LAVARES",
        "property_index_no" => "025-03-009-01-005",
        "arp_no" => "GR-2005-03-009-00006",
        "address" => "ITOMANG, TALISAY, CAMARINES NORTE",
        "tel" => "Tel: 09123456789",
        "kind" => "Land",
        "class" => "AGRICULTURAL",
        "location" => "ALAWIHIAO., DAEI, CAMARINES NORTE",
        "assessed_value" => "10,110.00",
        "taxability" => "Taxable",
        "effectivity" => "2007",
        "cancels" => array(
            "update_code" => "",
            "arp_no" => "",
            "assessed_value" => ""
        ),
        "cancelled_by" => array(
            "update_code" => "",
            "arp_no" => "GR-2008-FF-03-009-00006",
            "page_ref" => ""
        )
    ),
    // Add more properties as needed
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ASSESSMENT ROLL</title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }
        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .location-container {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        .location-item {
            margin: 0 15px;
            white-space: nowrap;
        }
        .location-label {
            font-weight: bold;
        }
        .location-value {
            padding-left: 5px;
        }
        .column-title {
            font-weight: bold;
            text-align: center;
        }
        .sub-header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .address-block {
            display: block;
        }
        .tel-number {
            display: block;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Assessment Roll</div>
        <div class="sub-header">Real Property Assessment</div>
    </div>
    
    <div class="location-container">
        <div class="location-item">
            <span class="location-label">PROVINCE/CITY:</span>
            <span class="location-value"><?php echo $location_data['province_city']; ?></span>
        </div>
        <div class="location-item">
            <span class="location-label">MUNICIPALITY:</span>
            <span class="location-value"><?php echo $location_data['municipality']; ?></span>
        </div>
        <div class="location-item">
            <span class="location-label">DISTRICT:</span>
            <span class="location-value"><?php echo $location_data['district']; ?></span>
        </div>
        <div class="location-item">
            <span class="location-label">BARANGAY:</span>
            <span class="location-value"><?php echo $location_data['barangay']; ?></span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 12%">PROPERTY OWNER</th>
                <th rowspan="2" style="width: 8%">PROPERTY INDEX NO.</th>
                <th rowspan="2" style="width: 8%">ARP NO.</th>
                <th rowspan="2" style="width: 15%">OWNER'S ADDRESS & Tel. Nos.</th>
                <th rowspan="2" style="width: 5%">KIND</th>
                <th rowspan="2" style="width: 8%">CLASS</th>
                <th rowspan="2" style="width: 12%">LOCATION OF PROPERTY</th>
                <th rowspan="2" style="width: 6%">Assessed Value</th>
                <th rowspan="2" style="width: 5%">Taxability</th>
                <th rowspan="2" style="width: 5%">Effectivity</th>
                <th colspan="3" style="width: 12%">CANCELS</th>
                <th colspan="3" style="width: 12%">CANCELLED BY</th>
            </tr>
            <tr>
                <th class="column-title" style="width: 4%">Update Code</th>
                <th class="column-title" style="width: 4%">ARP No.</th>
                <th class="column-title" style="width: 4%">Assessed Value</th>
                <th class="column-title" style="width: 4%">Update Code</th>
                <th class="column-title" style="width: 4%">ARP No.</th>
                <th class="column-title" style="width: 4%">Page REF</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($properties as $property): ?>
            <tr>
                <td><?php echo $property['property_owner']; ?></td>
                <td><?php echo $property['property_index_no']; ?></td>
                <td><?php echo $property['arp_no']; ?></td>
                <td>
                    <span class="address-block"><?php echo $property['address']; ?></span>
                    <span class="tel-number"><?php echo $property['tel']; ?></span>
                </td>
                <td><?php echo $property['kind']; ?></td>
                <td><?php echo $property['class']; ?></td>
                <td><?php echo $property['location']; ?></td>
                <td><?php echo $property['assessed_value']; ?></td>
                <td><?php echo $property['taxability']; ?></td>
                <td><?php echo $property['effectivity']; ?></td>
                <td><?php echo $property['cancels']['update_code']; ?></td>
                <td><?php echo $property['cancels']['arp_no']; ?></td>
                <td><?php echo $property['cancels']['assessed_value']; ?></td>
                <td><?php echo $property['cancelled_by']['update_code']; ?></td>
                <td><?php echo $property['cancelled_by']['arp_no']; ?></td>
                <td><?php echo $property['cancelled_by']['page_ref']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        window.onload = function() {
            // Set print settings
            const style = document.createElement('style');
            style.innerHTML = `
                @page {
                    size: landscape;
                    margin: 0;
                }
                @media print {
                    @page {
                        size: landscape;
                        margin: 0;
                    }
                    body {
                        margin: 0;
                        padding: 10px;
                    }
                }
            `;
            document.head.appendChild(style);
            
            window.print();
        };
    </script>
</body>
</html>