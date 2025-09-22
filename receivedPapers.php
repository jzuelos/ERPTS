<?php
/**
 * receivedPapers.php
 * Handles confirming transactions (receiving papers) only
 */

session_start();
require_once 'database.php';

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit;
}

$conn = Database::getInstance();
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

/**
 * Log activity helper function
 */
function logActivity($transaction_id, $action, $details = null, $user_id = null, $transaction_code = null)
{
    global $conn;

    if ($user_id === null && isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
    }

    // Get transaction code if not provided
    if ($transaction_code === null) {
        $stmtGet = $conn->prepare("SELECT transaction_code FROM transactions WHERE transaction_id = ? LIMIT 1");
        if ($stmtGet) {
            $stmtGet->bind_param("i", $transaction_id);
            $stmtGet->execute();
            $res = $stmtGet->get_result();
            if ($row = $res->fetch_assoc()) {
                $transaction_code = $row['transaction_code'] ?? null;
            }
            $stmtGet->close();
        }
    }

    $stmt = $conn->prepare("INSERT INTO transaction_logs (transaction_id, transaction_code, action, details, user_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isssi", $transaction_id, $transaction_code, $action, $details, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Delete transaction uploads folder
 */
function deleteTransactionFolder($transaction_id)
{
    $folder = __DIR__ . "/uploads/transaction_" . $transaction_id;

    if (!is_dir($folder)) {
        return;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }

    rmdir($folder);
}

// Only handle confirming transaction
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $transaction_id = intval($_POST['transaction_id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if ($transaction_id <= 0) {
        throw new Exception("Invalid transaction ID");
    }

    $conn->begin_transaction();

    // Get transaction details
    $stmt = $conn->prepare("SELECT transaction_code, name, contact_number, transaction_type, status FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $transaction = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$transaction) {
        throw new Exception("Transaction not found");
    }

    if ($transaction['status'] !== 'Completed') {
        throw new Exception("Only completed transactions can be confirmed for receipt");
    }

    // Check if already received
    $checkStmt = $conn->prepare("SELECT received_id FROM received_papers WHERE transaction_id = ?");
    $checkStmt->bind_param("i", $transaction_id);
    $checkStmt->execute();
    $existing = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();

    if ($existing) {
        throw new Exception("Papers already received for this transaction");
    }

    // Insert into received_papers
    $insertStmt = $conn->prepare("
        INSERT INTO received_papers 
        (transaction_id, transaction_code, client_name, contact_number, transaction_type, received_by, notes, status, received_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'received', NOW())
    ");
    $user_id = $_SESSION['user_id'];
    $insertStmt->bind_param(
        "issssis",
        $transaction_id,
        $transaction['transaction_code'],
        $transaction['name'],
        $transaction['contact_number'],
        $transaction['transaction_type'],
        $user_id,
        $notes
    );
    if (!$insertStmt->execute()) {
        throw new Exception("Error confirming transaction: " . $insertStmt->error);
    }
    $insertStmt->close();

    // Delete the original transaction (cascades to transaction_files)
    $deleteStmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $deleteStmt->bind_param("i", $transaction_id);
    if (!$deleteStmt->execute()) {
        throw new Exception("Error deleting original transaction: " . $deleteStmt->error);
    }
    $deleteStmt->close();

    $conn->commit();

    // Delete uploads folder
    deleteTransactionFolder($transaction_id);

    // Log activity
    $details = "Papers received by client" . ($notes ? " - Notes: " . $notes : "");
    logActivity($transaction_id, "Papers Received", $details, $user_id, $transaction['transaction_code']);

    echo json_encode([
        "success" => true,
        "message" => "Transaction confirmed! Papers marked as received.",
        "received_date" => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>
