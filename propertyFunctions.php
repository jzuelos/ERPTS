<?php
include 'database.php';

$conn = Database::getInstance();

// Activity Logging Function with error check
function logActivity($user_id, $action) {
    global $conn;
    $log_time = date("Y-m-d H:i:s"); // Current timestamp

    // Prepare the statement
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, log_time) VALUES (?, ?, ?)");
    
    // Check if prepare statement was successful
    if ($stmt === false) {
        error_log("Failed to prepare log statement: " . $conn->error);
        return false;
    }

    // Bind parameters
    $stmt->bind_param("iss", $user_id, $action, $log_time);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Failed to execute log statement: " . $stmt->error);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Simulating user_id retrieval from session (replace with actual session variable)
    session_start();
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Default to 1 for testing

    // ✅ Handle Deletion (common for all tables)
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id']) && isset($_POST['table'])) {
        $id = intval($_POST['id']); // ensure numeric
        $table = $_POST['table'];

        // Map table to primary key column (use real PKs, not codes)
        $primaryKey = [
            "classification" => "c_id",
            "land_use"       => "lu_id",
            "subclass"       => "sc_id"
        ];

        if (!array_key_exists($table, $primaryKey)) {
            echo json_encode(["success" => false, "message" => "Invalid table"]);
            exit;
        }

        $col = $primaryKey[$table];

        $stmt = $conn->prepare("DELETE FROM $table WHERE $col = ?");
        $stmt->bind_param("i", $id); // ✅ primary key is integer
        $success = $stmt->execute();

        // Log the deletion action
        if ($success) {
            $action = "Deleted record from $table";
            $log_success = logActivity($user_id, $action);  // Log action
            if (!$log_success) {
                echo "Error logging the action.";
            }
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        exit; // ✅ Stop further insert logic after deletion
    }

    // ✅ Handle Classification Form
    if (isset($_POST['c_code']) && isset($_POST['c_description']) && isset($_POST['c_uv']) && isset($_POST['c_status'])) {
        $c_code = $_POST['c_code'];
        $c_description = $_POST['c_description'];
        $c_uv = $_POST['c_uv'];
        $c_status = $_POST['c_status'];

        $query = "INSERT INTO classification (c_code, c_description, c_uv, c_status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssds", $c_code, $c_description, $c_uv, $c_status);

        if ($stmt->execute()) {
            // Log the insertion action
            $action = "Added new classification";
            $log_success = logActivity($user_id, $action);  // Log action
            if (!$log_success) {
                echo "Error logging the action.";
            }

            echo "Classification details added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // ✅ Handle Land Use Form
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
            // Log the insertion action
            $action = "Added new land use";
            $log_success = logActivity($user_id, $action);  // Log action
            if (!$log_success) {
                echo "Error logging the action.";
            }

            echo "Land Use added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // ✅ Handle Sub-Classes Form
    elseif (isset($_POST['sc_code']) && isset($_POST['sc_description']) && isset($_POST['sc_uv']) && isset($_POST['sc_status'])) {
        $sc_code = $_POST['sc_code'];
        $sc_description = $_POST['sc_description'];
        $sc_uv = $_POST['sc_uv'];
        $sc_status = $_POST['sc_status'];

        $query = "INSERT INTO subclass (sc_code, sc_description, sc_uv, sc_status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssds", $sc_code, $sc_description, $sc_uv, $sc_status);

        if ($stmt->execute()) {
            // Log the insertion action
            $action = "Added new subclass";
            $log_success = logActivity($user_id, $action);  // Log action
            if (!$log_success) {
                echo "Error logging the action.";
            }

            echo "Sub-Class added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
