<?php
include 'database.php'; // Include your database connection

$conn = Database::getInstance();

// Fetch classification
$classifications = $conn->query("SELECT c_id, c_code, c_description FROM classification WHERE c_status = 'Active'");

// Fetch subclass
$subclasses = $conn->query("SELECT sc_id, sc_code, sc_description FROM subclass WHERE sc_status = 'Active'");

// Fetch land use
$land_uses = $conn->query("SELECT lu_id, lu_description FROM land_use WHERE lu_status = 'Active'");

$data = [
    'classifications' => [],
    'subclasses' => [],
    'land_uses' => []
];

while ($row = $classifications->fetch_assoc()) {
    $data['classifications'][] = [
        'id' => $row['c_id'],
        'text' => "{$row['c_description']} ({$row['c_code']})"
    ];
}

while ($row = $subclasses->fetch_assoc()) {
    $data['subclasses'][] = [
        'id' => $row['sc_id'],
        'text' => "{$row['sc_description']} ({$row['sc_code']})"
    ];
}

while ($row = $land_uses->fetch_assoc()) {
    $data['land_uses'][] = [
        'id' => $row['lu_id'],
        'text' => $row['lu_description']
    ];
}

echo json_encode($data);
?>
