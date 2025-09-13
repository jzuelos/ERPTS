<?php
require_once 'database.php';
header('Content-Type: application/json; charset=utf-8');

$conn = Database::getInstance();
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

// ---------- GET ALL ----------
if (isset($_GET['action']) && $_GET['action'] === 'getTransactions') {
    $sql = "SELECT transaction_id, transaction_code, name, contact_number, description, 
                   transaction_type, status, created_at, updated_at
            FROM transactions
            ORDER BY transaction_id DESC";
    $result = $conn->query($sql);
    if (!$result) {
        echo json_encode(["success" => false, "message" => $conn->error]);
        exit;
    }

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    exit;
}

// ---------- GET NEXT TRANSACTION CODE ----------
if (isset($_GET['action']) && $_GET['action'] === 'getNextTransactionCode') {
    do {
        $nextCode = str_pad(rand(0, 99999), 5, "0", STR_PAD_LEFT);
        $check = $conn->query("SELECT 1 FROM transactions WHERE transaction_code='$nextCode' LIMIT 1");
    } while ($check && $check->num_rows > 0);
    echo json_encode(["next_code" => $nextCode]);
    exit;
}

// ---------- GET DOCUMENTS ----------
if (isset($_GET['action']) && $_GET['action'] === 'getDocuments') {
    $transaction_id = intval($_GET['transaction_id']);
    $stmt = $conn->prepare("SELECT file_id, file_path FROM transaction_files WHERE transaction_id=?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $files = [];
    while ($row = $result->fetch_assoc()) {
        $files[] = $row;
    }
    echo json_encode($files);
    exit;
}

// ---------- Helper: handle multiple uploads ----------
function handleMultipleUploads($transaction_id, $fieldName = 't_file')
{
    global $conn;
    if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName]['name']))
        return;

    $uploadDir = __DIR__ . "/uploads/transaction_" . $transaction_id . "/";
    if (!is_dir($uploadDir))
        mkdir($uploadDir, 0777, true);

    foreach ($_FILES[$fieldName]['name'] as $key => $name) {
        if ($_FILES[$fieldName]['error'][$key] !== UPLOAD_ERR_OK)
            continue;

        $safeName = preg_replace("/[^A-Za-z0-9_\.-]/", "_", basename($name));
        $fileName = uniqid("tx_") . "_" . $safeName;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'][$key], $filePath)) {
            $relativePath = "uploads/transaction_" . $transaction_id . "/" . $fileName;
            $stmt = $conn->prepare("INSERT INTO transaction_files (transaction_id, file_path) VALUES (?, ?)");
            $stmt->bind_param("is", $transaction_id, $relativePath);
            $stmt->execute();
        }
    }
}

// ---------- POST actions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // --- CREATE / SAVE ---
    if ($action === 'saveTransaction') {
        $name = trim($_POST['t_name'] ?? '');
        $contact = trim($_POST['t_contact'] ?? '');
        $description = trim($_POST['t_description'] ?? '');
        $transaction_type = trim($_POST['transactionType'] ?? '');
        $status = trim($_POST['t_status'] ?? '');

        // Generate unique transaction code
        do {
            $transaction_code = str_pad(rand(0, 99999), 5, "0", STR_PAD_LEFT);
            $check = $conn->query("SELECT 1 FROM transactions WHERE transaction_code='$transaction_code' LIMIT 1");
        } while ($check && $check->num_rows > 0);

        $stmt = $conn->prepare("INSERT INTO transactions (transaction_code, name, contact_number, description, transaction_type, status, created_at, updated_at)
                                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssss", $transaction_code, $name, $contact, $description, $transaction_type, $status);

        if ($stmt->execute()) {
            $transaction_id = $stmt->insert_id;
            handleMultipleUploads($transaction_id);
            echo json_encode(["success" => true, "message" => "Transaction saved successfully!", "transaction_id" => $transaction_id]);
        } else {
            error_log("Save transaction failed: " . $stmt->error);
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
        exit;
    }

    // --- UPDATE ---
    if ($action === 'updateTransaction') {
        $transaction_id = intval($_POST['transaction_id'] ?? 0);
        $stmt = $conn->prepare("SELECT transaction_code FROM transactions WHERE transaction_id=?");
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $transaction_code = $row['transaction_code'] ?? '';

        $name = trim($_POST['t_name'] ?? '');
        $contact = trim($_POST['t_contact'] ?? '');
        $description = trim($_POST['t_description'] ?? '');
        $transaction_type = trim($_POST['transactionType'] ?? '');
        $status = trim($_POST['t_status'] ?? '');

        $stmt = $conn->prepare("UPDATE transactions 
            SET transaction_code=?, name=?, contact_number=?, description=?, transaction_type=?, status=?, updated_at=NOW() 
            WHERE transaction_id=?");
        $stmt->bind_param("ssssssi", $transaction_code, $name, $contact, $description, $transaction_type, $status, $transaction_id);

        if ($stmt->execute()) {
            handleMultipleUploads($transaction_id);
            echo json_encode(["success" => true, "message" => "Transaction updated successfully!"]);
        } else {
            error_log("Update transaction failed: " . $stmt->error);
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
        exit;
    }

    if ($action === 'deleteTransaction') {
        $transaction_id = intval($_POST['transaction_id'] ?? 0);

        // Get transaction code
        $stmt = $conn->prepare("SELECT transaction_code FROM transactions WHERE transaction_id=?");
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $transaction_code = $row['transaction_code'] ?? null;

        if (!$transaction_code) {
            echo json_encode(["success" => false, "message" => "Transaction not found"]);
            exit;
        }

        // Delete transaction
        $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id=?");
        $stmt->bind_param("i", $transaction_id);

        if ($stmt->execute()) {
            // Delete files from DB
            $conn->query("DELETE FROM transaction_files WHERE transaction_id=" . $transaction_id);

            // Delete folder & files safely
            $folderPath = __DIR__ . "/uploads/transaction_" . $transaction_code;
            if (is_dir($folderPath)) {
                foreach (glob("$folderPath/*") as $file) {
                    @unlink($file); // suppress errors
                }
                @rmdir($folderPath); // suppress errors
            }

            echo json_encode(["success" => true, "message" => "Transaction and images deleted!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Delete failed"]);
        }
        exit;
    }

    // --- DELETE SINGLE DOCUMENT ---
    if ($action === 'deleteDocument') {
        $file_id = intval($_POST['file_id'] ?? 0);
        $stmt = $conn->prepare("SELECT file_path FROM transaction_files WHERE file_id=?");
        $stmt->bind_param("i", $file_id);
        $stmt->execute();
        $file = $stmt->get_result()->fetch_assoc();

        if ($file) {
            $filePath = __DIR__ . "/" . $file['file_path'];
            if (file_exists($filePath))
                unlink($filePath);

            $stmt = $conn->prepare("DELETE FROM transaction_files WHERE file_id=?");
            $stmt->bind_param("i", $file_id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Document deleted successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => $stmt->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "File not found"]);
        }
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Invalid request"]);
