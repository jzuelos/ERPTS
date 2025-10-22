<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Property Report</title>
  <style>
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

    @media print {
      @page {
        size: landscape;
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

  // Base query
  $sql = "
  SELECT 
    r.pin AS property_index_no,
    d.arp_no AS tax_declaration_no,
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
  WHERE 1=1
  ";

  // Apply filters if not printing all
  if (!$print_all) {
    if (!empty($classification)) {
      $sql .= " AND l.classification = '" . $conn->real_escape_string($classification) . "'";
    }
    if (!empty($province)) {
      $sql .= " AND p.province = '" . $conn->real_escape_string($province) . "'";
    }
    if (!empty($municipality)) {
      $sql .= " AND p.city = '" . $conn->real_escape_string($municipality) . "'";
    }
    if (!empty($district)) {
      $sql .= " AND p.district = '" . $conn->real_escape_string($district) . "'";
    }
    if (!empty($barangay)) {
      $sql .= " AND p.barangay = '" . $conn->real_escape_string($barangay) . "'";
    }
    if (!empty($from_date)) {
      $sql .= " AND DATE(p.created_at) >= '" . $conn->real_escape_string($from_date) . "'";
    }
    if (!empty($to_date)) {
      $sql .= " AND DATE(p.created_at) <= '" . $conn->real_escape_string($to_date) . "'";
    }
  }

  $result = $conn->query($sql);

  // 🔹 ACTIVITY LOGGING SECTION
  if ($user_id) {

    // Helper function to get readable names
    function getValue($conn, $table, $column, $id_column, $id)
    {
      if (!$id) return null;

      $stmt = $conn->prepare("SELECT $column FROM $table WHERE $id_column = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();

      $result = $stmt->get_result();
      $row = $result->fetch_assoc();

      $stmt->close();

      return $row[$column] ?? null; // safely returns null if not found
    }

    // Fetch readable values
    $classification_name = getValue($conn, "classification", "c_description", "c_id", $classification);
    $province_name       = getValue($conn, "province", "province_name", "province_id", $province);
    $municipality_name   = getValue($conn, "municipality", "m_description", "m_id", $municipality);
    $district_name       = getValue($conn, "district", "description", "district_id", $district);
    $barangay_name       = getValue($conn, "brgy", "brgy_name", "brgy_id", $barangay);

    // Build readable activity message
    $activity = "Printed Property Report\n" .
      "• Classification: " . ($classification_name ?: 'All') . "\n" .
      "• Province: " . ($province_name ?: 'All') . "\n" .
      "• Municipality/City: " . ($municipality_name ?: 'All') . "\n" .
      "• District: " . ($district_name ?: 'All') . "\n" .
      "• Barangay: " . ($barangay_name ?: 'All') . "\n" .
      "• Date Range: " . ($date_display ?: 'All');

    // Insert into activity log
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $activity);
    $stmt->execute();
    $stmt->close();
  }
  ?>

  <h1 style="text-align:center; font-size:22px; margin-bottom:20px;">
    PROVINCE OF CAMARINES NORTE <br>
    <span style="font-size:18px;">(PROVINCIAL ASSESSOR'S OFFICE)</span><br>
    <span style="font-size:15px;">(Property By: Classification <?= htmlspecialchars($classification ?: 'All') ?>)</span>
  </h1>

  <div class="header">
    <span><b>Classification:</b> <?= htmlspecialchars($classification) ?></span>
    <span><b>Province:</b> <?= htmlspecialchars($province) ?></span>
    <span><b>Municipality/City:</b> <?= htmlspecialchars($municipality) ?></span>
    <span><b>District:</b> <?= htmlspecialchars($district) ?></span>
    <span><b>Barangay:</b> <?= htmlspecialchars($barangay) ?></span>
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
            <td></td>
            <td></td>
            <td><?= htmlspecialchars($row['property_location']) ?></td>
            <td><?= htmlspecialchars($row['area']) ?></td>
            <td><?= htmlspecialchars($row['kind']) ?></td>
            <td><?= htmlspecialchars($row['market_value']) ?></td>
            <td><?= htmlspecialchars($row['assessed_value']) ?></td>
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

  <div style="position:fixed; bottom:20px; right:20px; font-size:14px; text-align:right;">
    <b>PROCESSED BY:</b> <?= htmlspecialchars($username) ?><br>
    <b>Date & Time:</b> <?= date("F d, Y h:i A") ?>
  </div>

  <script>
    window.onload = function() {
      window.print();
    };
  </script>
</body>

</html>