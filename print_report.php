<?php
include 'database.php';
$conn = Database::getInstance();

// Base query (select all if no filters)
$query = "SELECT * FROM land WHERE 1=1";

// Apply filters
if (!empty($_GET['classification'])) {
    $classification = $conn->real_escape_string($_GET['classification']);
    $query .= " AND classification = '$classification'";
}
if (!empty($_GET['province'])) {
    $province = $conn->real_escape_string($_GET['province']);
    $query .= " AND province_name = '$province'";
}
if (!empty($_GET['municipality'])) {
    $municipality = $conn->real_escape_string($_GET['municipality']);
    $query .= " AND municipality_name = '$municipality'";
}
if (!empty($_GET['district'])) {
    $district = $conn->real_escape_string($_GET['district']);
    $query .= " AND district_name = '$district'";
}
if (!empty($_GET['barangay'])) {
    $barangay = $conn->real_escape_string($_GET['barangay']);
    $query .= " AND barangay_name = '$barangay'";
}
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $conn->real_escape_string($_GET['from_date']);
    $to = $conn->real_escape_string($_GET['to_date']);
    $query .= " AND DATE(created_at) BETWEEN '$from' AND '$to'";
}

// If "Print ALL" is checked, ignore filters
if (!empty($_GET['print_all'])) {
    $query = "SELECT * FROM land";
}

$result = $conn->query($query);

// Simple display
echo "<h2>Report</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
if ($result && $result->num_rows > 0) {
    $fields = $result->fetch_fields();
    echo "<tr>";
    foreach ($fields as $field) {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
    }
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='100%'>No records found</td></tr>";
}
echo "</table>";
