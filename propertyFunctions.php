<?php
// Include the database connection file
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $form_type = $_POST['form_type']; // Identify which form was submitted

        if ($form_type === "classification") {
            // Process Classification Form
            $c_code = $_POST['c_code'];
            $c_description = $_POST['c_description'];
            $c_uv = $_POST['c_uv'];
            $c_status = $_POST['c_status'];

            $query = "INSERT INTO classification (c_code, c_description, c_uv, c_status) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $c_code, $c_description, $c_uv, $c_status);

            if ($stmt->execute()) {
                echo "Classification details added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } elseif ($form_type === "land_use") {
            // Process Land Use Form
            $report_code = $_POST['report_code'];
            $lu_code = $_POST['lu_code'];
            $lu_description = $_POST['lu_description'];
            $lu_al = $_POST['lu_al'];
            $lu_status = $_POST['lu_status'];

            $query = "INSERT INTO land_use (report_code, lu_code, lu_description, lu_al, lu_status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssds", $report_code, $lu_code, $lu_description, $lu_al, $lu_status);

            if ($stmt->execute()) {
                echo "Land Use added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>
