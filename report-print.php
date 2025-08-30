<?php
// Include the database connection
include 'database.php';
$conn = Database::getInstance();

// Retrieve the filters from the URL query string
$print_all = isset($_GET['print_all']) ? $_GET['print_all'] : null;
$classification = isset($_GET['classification']) ? $_GET['classification'] : null;
$province = isset($_GET['province']) ? $_GET['province'] : null;
$municipality = isset($_GET['municipality']) ? $_GET['municipality'] : null;
$district = isset($_GET['district']) ? $_GET['district'] : null;
$barangay = isset($_GET['barangay']) ? $_GET['barangay'] : null;
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;

// Build the SQL query based on the filters
$sql = "SELECT * FROM land WHERE 1=1"; // Base query

// Apply filters if present
if ($classification) {
    $sql .= " AND classification = ?";
}
if ($province) {
    $sql .= " AND province = ?";
}
if ($municipality) {
    $sql .= " AND municipality = ?";
}
if ($district) {
    $sql .= " AND district = ?";
}
if ($barangay) {
    $sql .= " AND barangay = ?";
}
if ($from_date && $to_date) {
    $sql .= " AND created_at BETWEEN ? AND ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$types = '';
$params = [];

if ($classification) {
    $types .= 's';
    $params[] = $classification;
}
if ($province) {
    $types .= 's';
    $params[] = $province;
}
if ($municipality) {
    $types .= 's';
    $params[] = $municipality;
}
if ($district) {
    $types .= 's';
    $params[] = $district;
}
if ($barangay) {
    $types .= 's';
    $params[] = $barangay;
}
if ($from_date && $to_date) {
    $types .= 'ss';
    $params[] = $from_date;
    $params[] = $to_date;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
if ($result->num_rows > 0) {
    // Fetch data and generate the dynamic table
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Assessment Roll</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          margin: 30px;
        }

        h1 {
          text-align: center;
          margin-bottom: 10px;
          font-size: 24px;
        }

        table {
          width: 100%;
          border-collapse: collapse;
          font-size: 12px;
        }

        th, td {
          border: 1px solid #000;
          padding: 6px;
          text-align: left;
          vertical-align: top;
        }

        th {
          background-color: #f0f0f0;
        }

        .sub-header th {
          text-align: center;
        }

        .nowrap {
          white-space: nowrap;
        }

        .location-header td {
          border: none;
          padding: 4px 6px;
          font-weight: bold;
        }

        .location-header {
          margin-bottom: 5px;
        }
      </style>
    </head>
    <body>
    
    <h1>ASSESSMENT ROLL</h1>

    <table class="location-header">
      <tr>
        <td><strong>PROVINCE/CITY:</strong> ' . htmlspecialchars($province) . '</td>
        <td><strong>MUNICIPALITY:</strong> ' . htmlspecialchars($municipality) . '</td>
        <td><strong>DISTRICT:</strong> ' . htmlspecialchars($district) . '</td>
        <td style="text-align: right;"><strong>BARANGAY:</strong> ' . htmlspecialchars($barangay) . '</td>
      </tr>
    </table>

    <table>
      <thead>
        <tr class="sub-header">
          <th rowspan="2">PROPERTY OWNER</th>
          <th rowspan="2">PROPERTY INDEX NO.</th>
          <th rowspan="2">ARP NO.</th>
          <th rowspan="2">OWNER\'S ADDRESS & TEL. NOS.</th>
          <th rowspan="2">KIND</th>
          <th rowspan="2">CLASS</th>
          <th rowspan="2">LOCATION OF PROPERTY</th>
          <th rowspan="2">ASSESSED VALUE</th>
          <th rowspan="2">TAXABILITY</th>
          <th rowspan="2">EFFECTIVITY</th>
          <th colspan="3">CANCELS</th>
          <th colspan="4">CANCELLED BY</th>
        </tr>
        <tr class="sub-header">
          <th class="nowrap">UPDATE CODE</th>
          <th class="nowrap">ARP NO.</th>
          <th class="nowrap">ASSESSED VALUE</th>
          <th class="nowrap">UPDATE CODE</th>
          <th class="nowrap">ARP NO.</th>
          <th class="nowrap">ASSESSED VALUE</th>
          <th class="nowrap">REF NO.</th>
        </tr>
      </thead>
      <tbody>';

    // Loop through each row and display the data dynamically
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['owner_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['property_index']) . '</td>';
        echo '<td>' . htmlspecialchars($row['arp_no']) . '</td>';
        echo '<td>' . htmlspecialchars($row['owner_address']) . '</td>';
        echo '<td>' . htmlspecialchars($row['kind']) . '</td>';
        echo '<td>' . htmlspecialchars($row['classification']) . '</td>';
        echo '<td>' . htmlspecialchars($row['location']) . '</td>';
        echo '<td>' . htmlspecialchars($row['assessed_value']) . '</td>';
        echo '<td>' . htmlspecialchars($row['taxability']) . '</td>';
        echo '<td>' . htmlspecialchars($row['effectivity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_update_code']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_arp_no']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_assessed_value']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_update_code_by']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_arp_no_by']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_assessed_value_by']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cancel_ref_no']) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></body></html>';
} else {
    echo "<p>No records found for the given filters.</p>";
}
?>
