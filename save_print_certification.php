<?php
/**
 * Save Print Certification Details
 * Handles saving certification information before printing tax declaration
 */

session_start();
require_once 'database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$conn = Database::getInstance();
$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate required fields
        $required_fields = ['property_id', 'faas_id', 'owner_admin', 'certification_date', 
                           'certification_fee', 'or_number', 'date_paid'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Sanitize and validate input
        $property_id = intval($_POST['property_id']);
        $faas_id = intval($_POST['faas_id']);
        $owner_admin = trim($_POST['owner_admin']);
        $certification_date = $_POST['certification_date'];
        $certification_fee = floatval($_POST['certification_fee']);
        $or_number = trim($_POST['or_number']);
        $date_paid = $_POST['date_paid'];

        // Validate property exists
        $check_property = $conn->prepare("SELECT p_id FROM p_info WHERE p_id = ?");
        $check_property->bind_param("i", $property_id);
        $check_property->execute();
        if ($check_property->get_result()->num_rows === 0) {
            throw new Exception("Invalid property ID");
        }
        $check_property->close();

        // Validate FAAS exists
        $check_faas = $conn->prepare("SELECT faas_id FROM faas WHERE faas_id = ? AND pro_id = ?");
        $check_faas->bind_param("ii", $faas_id, $property_id);
        $check_faas->execute();
        if ($check_faas->get_result()->num_rows === 0) {
            throw new Exception("Invalid FAAS ID or mismatch with property");
        }
        $check_faas->close();

        // Validate dates
        if (!strtotime($certification_date) || !strtotime($date_paid)) {
            throw new Exception("Invalid date format");
        }

        // Validate certification fee
        if ($certification_fee < 0) {
            throw new Exception("Certification fee cannot be negative");
        }

        // Begin transaction
        $conn->begin_transaction();

        // Insert certification record
        $insert_sql = "INSERT INTO print_certifications 
                       (property_id, faas_id, owner_admin, certification_date, 
                        certification_fee, or_number, date_paid, created_by, created_at)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("iissdssi", 
            $property_id, 
            $faas_id, 
            $owner_admin, 
            $certification_date, 
            $certification_fee, 
            $or_number, 
            $date_paid, 
            $user_id
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to save certification: " . $stmt->error);
        }

        $cert_id = $conn->insert_id;
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Certification details saved successfully',
            'cert_id' => $cert_id,
            'property_id' => $property_id
        ]);

    } else {
        throw new Exception("Invalid request method");
    }

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollback();
    }
    
    error_log("Print Certification Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>