<?php
require_once 'database.php';
$districtId = $_GET['district_id'];
$conn = Database::getInstance();
$result = $conn->query("SELECT id, name FROM barangays WHERE district_id = $districtId");
$barangays = [];
while ($row = $result->fetch_assoc()) {
    $barangays[] = ['id' => $row['id'], 'name' => $row['name']];
}
echo json_encode($barangays);