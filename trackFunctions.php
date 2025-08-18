<?php
require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// GET ALL
if (isset($_GET['action']) && $_GET['action'] === 'getTransactions') {
    $result = $conn->query("SELECT * FROM transactions ORDER BY transaction_id DESC");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    exit;
}

// POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // CREATE
    if ($action === 'saveTransaction') {
        $transaction_code = $_POST['transaction_code'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO transactions (transaction_code, name, description, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $transaction_code, $name, $description, $status);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Transaction added successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
        exit;
    }

    // UPDATE
    if ($action === 'updateTransaction') {
        $transaction_id = $_POST['transaction_id'];
        $transaction_code = $_POST['transaction_code'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE transactions SET transaction_code=?, name=?, description=?, status=? WHERE transaction_id=?");
        $stmt->bind_param("ssssi", $transaction_code, $name, $description, $status, $transaction_id);

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
?>
