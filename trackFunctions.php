<?php
session_start();
require_once 'database.php';
header('Content-Type: application/json; charset=utf-8');

$conn = Database::getInstance();
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

/**
 * Log activity helper
 * @param int $transaction_id
 * @param string $action
 * @param string|null $details
 * @param int|null $user_id
 * @param string|null $transaction_code  // optional, will try to fetch if null
 * @return bool
 */
function logActivity($transaction_id, $action, $details = null, $user_id = null, $transaction_code = null)
{
    global $conn;

    // try session user if not provided
    if ($user_id === null && isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
    }

    // If transaction_code not supplied, attempt to fetch it (best-effort)
    if ($transaction_code === null) {
        $stmtGet = $conn->prepare("SELECT transaction_code FROM transactions WHERE transaction_id = ? LIMIT 1");
        if ($stmtGet) {
            $stmtGet->bind_param("i", $transaction_id);
            if ($stmtGet->execute()) {
                $res = $stmtGet->get_result();
                if ($row = $res->fetch_assoc()) {
                    $transaction_code = $row['transaction_code'] ?? null;
                }
            }
            $stmtGet->close();
        }
    }

    // Insert with/without user_id (handle nullable user_id cleanly)
    if ($user_id === null) {
        $stmt = $conn->prepare("
            INSERT INTO transaction_logs (transaction_id, transaction_code, action, details)
            VALUES (?, ?, ?, ?)
        ");
        if (!$stmt) {
            error_log("logActivity prepare failed (no user): " . $conn->error);
            return false;
        }
        $stmt->bind_param("isss", $transaction_id, $transaction_code, $action, $details);
    } else {
        $user_id = intval($user_id);
        $stmt = $conn->prepare("
            INSERT INTO transaction_logs (transaction_id, transaction_code, action, details, user_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            error_log("logActivity prepare failed (with user): " . $conn->error);
            return false;
        }
        $stmt->bind_param("isssi", $transaction_id, $transaction_code, $action, $details, $user_id);
    }

    $ok = $stmt->execute();
    if (!$ok) {
        error_log("logActivity execute failed: " . $stmt->error);
    }
    $stmt->close();
    return $ok;
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
    $stmt = $conn->prepare("SELECT file_id, file_path, uploaded_at FROM transaction_files WHERE transaction_id=?");
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
        mkdir($uploadDir, 0755, true);

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
            if ($stmt->execute()) {
                logActivity($transaction_id, "Document Uploaded", $relativePath, $_SESSION['user_id'], $transaction_code);
            }
        }
    }
}

function sendSMS($to, $message)
{
    $apiKey = 'YOUR_SEMAPHORE_API_KEY'; //to do

    $data = [
        'apikey' => $apiKey,
        'number' => $to,
        'message' => $message
    ];

    $ch = curl_init('https://api.semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        error_log("SMS send error: $err");
        return false;
    }

    return $response; // Returns API response
}

// ---------- POST actions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    header('Content-Type: application/json');

    try {
        // ------------------ SAVE TRANSACTION ------------------
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

            $stmt = $conn->prepare("INSERT INTO transactions 
                (transaction_code, name, contact_number, description, transaction_type, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("ssssss", $transaction_code, $name, $contact, $description, $transaction_type, $status);

            if ($stmt->execute()) {
                $transaction_id = $stmt->insert_id;
                handleMultipleUploads($transaction_id);
                logActivity($transaction_id, "Created", "Transaction created", $_SESSION['user_id'], $transaction_code);
                // SEND SMS via Semaphore
                if (!empty($contact) && !empty($description)) {
                    sendSMS($contact, $description);
                }
                
                echo json_encode(["success" => true, "message" => "Transaction saved successfully!", "transaction_id" => $transaction_id]);
            } else {
                echo json_encode(["success" => false, "message" => $stmt->error]);
            }
            exit;
        }

        // ------------------ UPDATE TRANSACTION ------------------
        elseif ($action === 'updateTransaction') {
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
                logActivity($transaction_id, "Updated", "Transaction updated", $_SESSION['user_id'], $transaction_code);
                // SEND SMS via Semaphore
                if (!empty($contact) && !empty($description)) {
                    sendSMS($contact, $description);
                }

                echo json_encode(["success" => true, "message" => "Transaction updated successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => $stmt->error]);
            }
            exit;
        }

        // ------------------ DELETE DOCUMENT ------------------
        elseif ($action === 'deleteDocument') {
            $file_id = intval($_POST['file_id'] ?? 0);

            $stmt = $conn->prepare("SELECT file_path, transaction_id FROM transaction_files WHERE file_id=?");
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
                    $details = "Deleted document: " . $file['file_path'];
                    logActivity(intval($file['transaction_id']), "Document Deleted", $details, $_SESSION['user_id']);
                    echo json_encode(["success" => true, "message" => "Document deleted successfully!"]);
                } else {
                    echo json_encode(["success" => false, "message" => $stmt->error]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "File not found"]);
            }
            exit;
        } elseif ($action === 'deleteTransaction') {
            $transaction_id = isset($_POST['transaction_id']) ? (int) $_POST['transaction_id'] : 0;

            if ($transaction_id > 0) {
                // Delete associated files from DB and filesystem
                $stmtFiles = $conn->prepare("SELECT file_path FROM transaction_files WHERE transaction_id=?");
                $stmtFiles->bind_param("i", $transaction_id);
                $stmtFiles->execute();
                $resultFiles = $stmtFiles->get_result();
                $folderPath = __DIR__ . "/uploads/transaction_" . $transaction_id . "/";

                while ($file = $resultFiles->fetch_assoc()) {
                    $filePath = __DIR__ . "/" . $file['file_path'];
                    if (file_exists($filePath))
                        unlink($filePath);
                }

                // Delete file records
                $stmtDelFiles = $conn->prepare("DELETE FROM transaction_files WHERE transaction_id=?");
                $stmtDelFiles->bind_param("i", $transaction_id);
                $stmtDelFiles->execute();

                // Delete transaction record
                $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id=?");
                $stmt->bind_param("i", $transaction_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    logActivity($transaction_id, "Deleted", "Transaction deleted", $_SESSION['user_id']);

                    // Delete folder if empty
                    if (is_dir($folderPath)) {
                        $filesInFolder = array_diff(scandir($folderPath), ['.', '..']);
                        if (empty($filesInFolder)) {
                            rmdir($folderPath); // remove folder if empty
                        }
                    }

                    echo json_encode(["success" => true, "message" => "Transaction deleted successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Transaction not found"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Invalid transaction ID"]);
            }
            exit;
        }

        // ------------------ INVALID ACTION ------------------
        else {
            echo json_encode(["success" => false, "message" => "Invalid action"]);
            exit;
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        exit;
    }
}

// ---------- GET RECENT ACTIVITY ----------
if (isset($_GET['action']) && $_GET['action'] === 'getActivity') {
    $sql = "SELECT 
                l.log_id,
                l.transaction_id,
                l.action,
                l.details,
                l.created_at,
                COALESCE(l.transaction_code, t.transaction_code, CONCAT('#', l.transaction_id)) AS t_code,
                COALESCE(u.username, 'System') AS user
            FROM transaction_logs l
            LEFT JOIN transactions t ON l.transaction_id = t.transaction_id
            LEFT JOIN users u ON l.user_id = u.user_id
            ORDER BY l.created_at DESC";

    $result = $conn->query($sql);
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
    }

    echo json_encode($rows);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request"]);
