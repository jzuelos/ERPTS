<?php
include 'database.php'; // Database connection
$conn = Database::getInstance();
// Fetch Barangays
$barangayQuery = "SELECT brgy_id, brgy_name FROM brgy WHERE status = 'Active'";
$barangayResult = $conn->query($barangayQuery);
$barangays = [];
while ($row = $barangayResult->fetch_assoc()) {
    $barangays[] = $row;
}

// Fetch Districts
$districtQuery = "SELECT district_id, description FROM district WHERE status = 'Active'";
$districtResult = $conn->query($districtQuery);
$districts = [];
while ($row = $districtResult->fetch_assoc()) {
    $districts[] = $row;
}

// Fetch Municipalities
$municipalityQuery = "SELECT m_id, m_description FROM municipality WHERE m_status = 'Active'";
$municipalityResult = $conn->query($municipalityQuery);
$municipalities = [];
while ($row = $municipalityResult->fetch_assoc()) {
    $municipalities[] = $row;
}

// Return JSON
echo json_encode([
    'barangays' => $barangays,
    'districts' => $districts,
    'municipalities' => $municipalities
]);
?>
