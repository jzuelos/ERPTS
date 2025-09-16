<?php 
include 'database.php'; 
$conn = Database::getInstance();

$response = [];

if (isset($_GET['type'])) {
    $type = $_GET['type'];

    if ($type === 'municipalities') {
        // Fetch Municipalities
        $stmt = $conn->prepare("SELECT m_id, m_description FROM municipality WHERE m_status = 'Active'");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    } elseif ($type === 'barangays' && isset($_GET['m_id'])) {
        // Fetch Barangays by Municipality
        $m_id = intval($_GET['m_id']);
        $stmt = $conn->prepare("SELECT brgy_id, brgy_name FROM brgy WHERE m_id = ? AND status = 'Active'");
        $stmt->bind_param("i", $m_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    } elseif ($type === 'district' && isset($_GET['m_id'])) {
        // Fetch District by Municipality
        $m_id = intval($_GET['m_id']);
        $stmt = $conn->prepare("SELECT district_id, description FROM district WHERE m_id = ? AND status = 'Active' LIMIT 1");
        $stmt->bind_param("i", $m_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = $result->fetch_assoc();
    }
}

echo json_encode($response);
?>