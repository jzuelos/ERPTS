<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Property Report</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      font-size: 12px;
      margin: 1cm;
    }

    h1 {
      text-align: center;
      font-size: 22px;
      margin-bottom: 20px;
      line-height: 1.4;
    }

    .header {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      margin-bottom: 10px;
      flex-wrap: wrap;
      gap: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 4px;
      text-align: center;
    }

    th {
      background: #686868ff;
      color: #fff;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    .footer {
      position: fixed;
      bottom: 20px;
      right: 20px;
      font-size: 14px;
      text-align: right;
    }

    @media print {
      @page {
        size: landscape;
        margin: 1cm;
      }

      body::before {
        content: "";
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: url('images/Seal.png') no-repeat center;
        background-size: 500px;
        opacity: 0.08;
        width: 100%;
        height: 100%;
        z-index: -1;
        pointer-events: none;
      }

      .footer {
        position: fixed;
        bottom: 1cm;
        right: 1cm;
      }
    }
  </style>
</head>

<body>
  <?php
  session_start();
  require_once "database.php";
  $conn = Database::getInstance();
  date_default_timezone_set('Asia/Manila');

  // Fetch username of the logged-in user
  $username = 'Guest';
  $user_id = null;
  if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $query = "SELECT username FROM users WHERE user_id = $user_id LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $username = $row['username'];
    }
  }

  // Capture filters
  $classification = $_GET['classification'] ?? '';
  $province = $_GET['province'] ?? '';
  $municipality = $_GET['municipality'] ?? '';
  $district = $_GET['district'] ?? '';
  $barangay = $_GET['barangay'] ?? '';
  $from_date = $_GET['from_date'] ?? '';
  $to_date = $_GET['to_date'] ?? '';
  $print_all = $_GET['print_all'] ?? '';

  // Format date
  $date_display = '';
  if ($from_date && $to_date) {
    $date_display = date("F d, Y", strtotime($from_date)) . " - " . date("F d, Y", strtotime($to_date));
  } elseif ($from_date) {
    $date_display = "From " . date("F d, Y", strtotime($from_date));
  } elseif ($to_date) {
    $date_display = "Until " . date("F d, Y", strtotime($to_date));
  }

  // Get classification name if ID is provided
  $classification_name = '';
  if (!empty($classification)) {
    $stmt = $conn->prepare("SELECT c_description FROM classification WHERE c_id = ?");
    $stmt->bind_param("i", $classification);
    $stmt->execute();
    $result_class = $stmt->get_result();
    if ($row = $result_class->fetch_assoc()) {
      $classification_name = $row['c_description'];
    }
    $stmt->close();
  }

  // FIXED QUERY - Using GROUP_CONCAT to combine multiple owners
  $sql = "
SELECT 
  r.pin AS property_index_no,
  d.arp_no AS tax_declaration_no,
  GROUP_CONCAT(
    DISTINCT CONCAT(o.own_fname, ' ', o.own_mname, ' ', o.own_surname) 
    SEPARATOR ', '
  ) AS owner_name,
  GROUP_CONCAT(
    DISTINCT CONCAT(o.house_no, ' ', o.street, ', ', o.barangay, ', ', o.city, ', ', o.province)
    SEPARATOR ' | '
  ) AS owner_address,
  CONCAT(p.street, ', ', p.barangay, ', ', p.city, ', ', p.province) AS property_location,
  p.land_area AS area,
  'Land' AS kind,
  l.adjust_mv AS market_value,
  l.assess_value AS assessed_value
FROM faas f
LEFT JOIN p_info p ON f.pro_id = p.p_id
LEFT JOIN rpu_idnum r ON f.rpu_idno = r.rpu_id
LEFT JOIN rpu_dec d ON f.faas_id = d.faas_id
LEFT JOIN land l ON f.faas_id = l.faas_id
LEFT JOIN propertyowner po ON po.property_id = p.p_id AND po.is_retained = 1
LEFT JOIN owners_tb o ON o.own_id = po.owner_id
WHERE d.arp_no IS NOT NULL 
  AND d.arp_no != '' 
  AND d.arp_no != '0'
";

  // Apply filters if not printing all
  if (!$print_all) {
    if (!empty($classification) && !empty($classification_name)) {
      $sql .= " AND l.classification = '" . $conn->real_escape_string($classification_name) . "'";
    }

    if (!empty($province)) {
      $stmt = $conn->prepare("SELECT province_name FROM province WHERE province_id = ?");
      $stmt->bind_param("i", $province);
      $stmt->execute();
      $result_prov = $stmt->get_result();
      if ($row = $result_prov->fetch_assoc()) {
        $sql .= " AND p.province = '" . $conn->real_escape_string($row['province_name']) . "'";
      }
      $stmt->close();
    }

    if (!empty($municipality)) {
      $stmt = $conn->prepare("SELECT m_description FROM municipality WHERE m_id = ?");
      $stmt->bind_param("i", $municipality);
      $stmt->execute();
      $result_mun = $stmt->get_result();
      if ($row = $result_mun->fetch_assoc()) {
        $sql .= " AND p.city = '" . $conn->real_escape_string($row['m_description']) . "'";
      }
      $stmt->close();
    }

    if (!empty($district)) {
      $stmt = $conn->prepare("SELECT description FROM district WHERE district_id = ?");
      $stmt->bind_param("i", $district);
      $stmt->execute();
      $result_dist = $stmt->get_result();
      if ($row = $result_dist->fetch_assoc()) {
        $sql .= " AND p.district = '" . $conn->real_escape_string($row['description']) . "'";
      }
      $stmt->close();
    }

    if (!empty($barangay)) {
      $stmt = $conn->prepare("SELECT brgy_name FROM brgy WHERE brgy_id = ?");
      $stmt->bind_param("i", $barangay);
      $stmt->execute();
      $result_brgy = $stmt->get_result();
      if ($row = $result_brgy->fetch_assoc()) {
        $sql .= " AND p.barangay = '" . $conn->real_escape_string($row['brgy_name']) . "'";
      }
      $stmt->close();
    }

    if (!empty($from_date)) {
      $sql .= " AND DATE(p.created_at) >= '" . $conn->real_escape_string($from_date) . "'";
    }
    if (!empty($to_date)) {
      $sql .= " AND DATE(p.created_at) <= '" . $conn->real_escape_string($to_date) . "'";
    }
  }

  // GROUP BY to prevent duplicates
  $sql .= " GROUP BY f.faas_id, r.pin, d.arp_no, p.street, p.barangay, p.city, p.province, p.land_area, l.adjust_mv, l.assess_value";
  $sql .= " ORDER BY d.arp_no ASC";

  $result = $conn->query($sql);

  // ACTIVITY LOGGING
  if ($user_id) {
    function getValue($conn, $table, $column, $id_column, $id)
    {
      if (!$id) return null;
      $stmt = $conn->prepare("SELECT $column FROM $table WHERE $id_column = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $stmt->close();
      return $row[$column] ?? null;
    }

    $classification_display = getValue($conn, "classification", "c_description", "c_id", $classification);
    $province_display       = getValue($conn, "province", "province_name", "province_id", $province);
    $municipality_display   = getValue($conn, "municipality", "m_description", "m_id", $municipality);
    $district_display       = getValue($conn, "district", "description", "district_id", $district);
    $barangay_display       = getValue($conn, "brgy", "brgy_name", "brgy_id", $barangay);

    $activity = "Printed Property Report\n" .
      "• Classification: " . ($classification_display ?: 'All') . "\n" .
      "• Province: " . ($province_display ?: 'All') . "\n" .
      "• Municipality/City: " . ($municipality_display ?: 'All') . "\n" .
      "• District: " . ($district_display ?: 'All') . "\n" .
      "• Barangay: " . ($barangay_display ?: 'All') . "\n" .
      "• Date Range: " . ($date_display ?: 'All');

    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $activity);
    $stmt->execute();
    $stmt->close();
  }
  ?>

  <h1>
    PROVINCE OF CAMARINES NORTE <br>
    <span style="font-size:18px;">(PROVINCIAL ASSESSOR'S OFFICE)</span><br>
    <span style="font-size:15px;">(Property By: Classification <?= htmlspecialchars($classification_display ?? 'All') ?>)</span>
  </h1>

  <div class="header">
    <span><b>Classification:</b> <?= htmlspecialchars($classification_display ?? 'All') ?></span>
    <span><b>Province:</b> <?= htmlspecialchars($province_display ?? 'Camarines Norte') ?></span>
    <span><b>Municipality/City:</b> <?= htmlspecialchars($municipality_display ?? 'All') ?></span>
    <span><b>District:</b> <?= htmlspecialchars($district_display ?? 'All') ?></span>
    <span><b>Barangay:</b> <?= htmlspecialchars($barangay_display ?? 'All') ?></span>
    <span><b>Date:</b> <?= $date_display ?: date("F d, Y") ?></span>
  </div>

  <table>
    <thead>
      <tr>
        <th>PROPERTY INDEX NO.</th>
        <th>TAX DECLARATION NO.</th>
        <th>NAME OF OWNER</th>
        <th>OWNER ADDRESS</th>
        <th>PROPERTY LOCATION</th>
        <th>AREA</th>
        <th>KIND</th>
        <th>MARKET VALUE</th>
        <th>ASSESSED VALUE</th>
        <th>REMARKS</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['property_index_no']) ?></td>
            <td><?= htmlspecialchars($row['tax_declaration_no']) ?></td>
            <td><?= htmlspecialchars($row['owner_name'] ?: 'N/A') ?></td>
            <td><?= htmlspecialchars($row['owner_address'] ?: 'N/A') ?></td>
            <td><?= htmlspecialchars($row['property_location']) ?></td>
            <td><?= htmlspecialchars($row['area']) ?></td>
            <td><?= htmlspecialchars($row['kind']) ?></td>
            <td><?= number_format($row['market_value'], 2) ?></td>
            <td><?= number_format($row['assessed_value'], 2) ?></td>
            <td></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="10">No records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <div class="footer">
    <b>PROCESSED BY:</b> <?= htmlspecialchars($username) ?><br>
    <b>Date & Time:</b> <?= date("F d, Y h:i A") ?>
  </div>

  <script>
    window.onload = function() {
      setTimeout(() => window.print(), 500);
    };
  </script>
</body>

</html>