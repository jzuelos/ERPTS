<?php
include 'database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (empty($_POST['property_id'])) {
    die("Property ID is missing.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get posted data and sanitize it
    $propertyId = $_POST['property_id'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $houseNumber = $_POST['houseNumber'];
    $landArea = $_POST['landArea'];
    $zoneNumber = $_POST['zoneNumber'];
    $ardNumber = $_POST['ardNumber'];
    $taxability = $_POST['taxability'];
    $effectivity = $_POST['effectivity'];

    // Debugging: Check if data is received correctly
    error_log("Updating property ID: $propertyId with street: $street");

    // Prepare and execute the SQL update query
    $stmt = $conn->prepare("UPDATE p_info SET street = ?, barangay = ?, city = ?, province = ?, house_no = ?, land_area = ? WHERE p_id = ?");
    $stmt->bind_param("ssssssi", $street, $barangay, $municipality, $province, $houseNumber, $landArea, $propertyId);

    error_log("property_id: $propertyId, street: $street, barangay: $barangay, municipality: $municipality, province: $province, house_no: $houseNumber, land_area: $landArea, zone_no: $zoneNumber, ard_no: $ardNumber, taxability: $taxability, effectivity: $effectivity");

    if ($stmt->affected_rows > 0) {
        echo "Property information updated successfully!";
    } else {
        echo "No rows were updated.";
    }    

    if ($stmt->execute()) {
        echo "Property information updated successfully!";
    } else {
        echo "Error updating property information: " . $stmt->error;
        error_log("Error: " . $stmt->error); // Log error to server logs
    }

    $stmt->close();
    $conn->close();
}
?>