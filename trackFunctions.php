<?php
require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// GET ALL
if (isset($_GET['action']) && $_GET['action'] === 'getTransactions') {
    header('Content-Type: application/json; charset=utf-8');
    $result = $conn->query("
        SELECT transaction_id, transaction_code, name, contact_number, description, status, created_at, updated_at
        FROM transactions
        ORDER BY transaction_id DESC
    ");
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    echo json_encode($rows);
    exit;
}

// POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // CREATE
    if ($action === 'saveTransaction') {
        $transaction_code = $_POST['t_code'];
        $name             = $_POST['t_name'];
        $contact          = $_POST['t_contact']; // ✅ maps to contact_number column
        $description      = $_POST['t_description'];
        $status           = $_POST['t_status'];

        $stmt = $conn->prepare("INSERT INTO transactions 
            (transaction_code, name, contact_number, description, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sssss", $transaction_code, $name, $contact, $description, $status);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Transaction added successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
        exit;
    }

    // UPDATE
    if ($action === 'updateTransaction') {
        $transaction_id   = $_POST['transaction_id'];
        $transaction_code = $_POST['t_code'];
        $name             = $_POST['t_name'];
        $contact          = $_POST['t_contact']; // ✅ maps to contact_number column
        $description      = $_POST['t_description'];
        $status           = $_POST['t_status'];

        $stmt = $conn->prepare("UPDATE transactions 
            SET transaction_code=?, name=?, contact_number=?, description=?, status=?, updated_at=NOW() 
            WHERE transaction_id=?");
        $stmt->bind_param("sssssi", $transaction_code, $name, $contact, $description, $status, $transaction_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Transaction updated successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
        exit;
    }

    // DELETE
    if ($action === 'deleteTransaction') {
        $transaction_id = $_POST['transaction_id'];

        $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id=?");
        $stmt->bind_param("i", $transaction_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Transaction deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Invalid request"]);