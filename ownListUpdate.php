<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = Database::getInstance();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data and set defaults if not present
    $own_id = $_POST['own_id'] ?? null;
    $own_fname = $_POST['own_fname'] ?? '';
    $own_mname = $_POST['own_mname'] ?? '';
    $own_surname = $_POST['own_surname'] ?? '';
    $tin_no = $_POST['tin_no'] ?? '';
    $house_no = $_POST['house_no'] ?? '';
    $street = $_POST['street'] ?? '';
    $barangay = $_POST['barangay'] ?? '';
    $district = $_POST['district'] ?? '';
    $city = $_POST['city'] ?? '';
    $province = $_POST['province'] ?? '';

    // Update SQL query
    $sql = "UPDATE owners_tb SET 
            own_fname = ?, 
            own_mname = ?, 
            own_surname = ?, 
            tin_no = ?, 
            house_no = ?, 
            street = ?, 
            barangay = ?, 
            district = ?, 
            city = ?, 
            province = ? 
            WHERE own_id = ?";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssssi', $own_fname, $own_mname, $own_surname, $tin_no, $house_no, $street, $barangay, $district, $city, $province, $own_id);

    // Execute and check
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>