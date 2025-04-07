<?php
include 'database.php';

$conn = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle Classification Form
    if (isset($_POST['c_code']) && isset($_POST['c_description']) && isset($_POST['c_uv']) && isset($_POST['c_status'])) {
        $c_code = $_POST['c_code'];
        $c_description = $_POST['c_description'];
        $c_uv = $_POST['c_uv'];
        $c_status = $_POST['c_status'];

        $query = "INSERT INTO classification (c_code, c_description, c_uv, c_status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssds", $c_code, $c_description, $c_uv, $c_status); // Fix: "ssds"

        if ($stmt->execute()) {
            echo "Classification details added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle Land Use Form
    elseif (isset($_POST['report_code']) && isset($_POST['lu_code']) && isset($_POST['lu_description']) && isset($_POST['lu_al']) && isset($_POST['lu_status'])) {
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

    // Handle Sub-Classes Form
    elseif (isset($_POST['sc_code']) && isset($_POST['sc_description']) && isset($_POST['sc_uv']) && isset($_POST['sc_status'])) {
        $sc_code = $_POST['sc_code'];
        $sc_description = $_POST['sc_description'];
        $sc_uv = $_POST['sc_uv'];
        $sc_status = $_POST['sc_status'];

        $query = "INSERT INTO subclass (sc_code, sc_description, sc_uv, sc_status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssds", $sc_code, $sc_description, $sc_uv, $sc_status);

        if ($stmt->execute()) {
            echo "Sub-Class added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
