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
 * Send SMS via Semaphore API
 * @param string $to Contact number (with country code)
 * @param string $message SMS content
 * @return array Response with success status and message
 */
function sendSMS($to, $message)
{
    // Replace with your actual Semaphore API key
    $apiKey = '456224d30663f11db6811f48e7924c3b';

    // Validate inputs
    if (empty($to) || empty($message)) {
        error_log("SMS send failed: Missing recipient or message");
        return ['success' => false, 'message' => 'Missing recipient or message'];
    }

    // Format phone number (remove spaces and special characters)
    $to = preg_replace('/[^0-9+]/', '', $to);

    // Prepare API request
    $data = [
        'apikey' => $apiKey,
        'number' => $to,
        'message' => $message,
        'sendername' => 'TrackERPTS' // Optional: customize sender name (max 11 chars)
    ];

    $ch = curl_init('https://api.semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    // Handle cURL errors
    if ($err) {
        error_log("SMS cURL error: $err");
        return ['success' => false, 'message' => 'Network error: ' . $err];
    }

    // Parse response
    $result = json_decode($response, true);

    // Check for successful send
    if ($httpCode === 200 && isset($result[0]['message_id'])) {
        error_log("SMS sent successfully to $to. Message ID: " . $result[0]['message_id']);
        return ['success' => true, 'message' => 'SMS sent successfully', 'data' => $result];
    } else {
        $errorMsg = $result['message'] ?? 'Unknown error';
        error_log("SMS send failed: $errorMsg (HTTP $httpCode)");
        return ['success' => false, 'message' => $errorMsg];
    }
}

/**
 * Format SMS message for received papers
 * @param string $transactionCode Transaction code
 * @param string $clientName Client's name
 * @param string $transactionType Transaction type
 * @return string Formatted SMS message
 */
function formatReceivedPapersSMS($transactionCode, $clientName, $transactionType)
{
    // Shortened transaction type names for SMS
    $typeAbbrev = [
        'Simple Transfer of Ownership' => 'Transfer of Ownership',
        'New Declaration of Real Property' => 'New Property Declaration',
        'Revision/Correction' => 'Property Revision',
        'Consolidation' => 'Property Consolidation'
    ];

    $shortType = $typeAbbrev[$transactionType] ?? $transactionType;
    
    $message = "Dear " . ucwords(strtolower($clientName)) . ",\n\n";
    $message .= "Your {$shortType} #{$transactionCode} papers have been successfully received. ";
    $message .= "Thank you for your transaction.\n\n";
    $message .= "For more info. visit https://erptstrack.erpts.online\n\n";
    $message .= "- ERPTS Team";

    // Ensure message stays within SMS limit
    if (strlen($message) > 1530) {
        $message = substr($message, 0, 1527) . '...';
    }

    return $message;
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

    error_log("DEBUG: Received POST - transaction_id={$transaction_id}, notes={$notes}");

    if ($transaction_id <= 0) {
        throw new Exception("Invalid transaction ID");
    }

    $conn->begin_transaction();
    error_log("DEBUG: Transaction started");

    // Get transaction details
    $stmt = $conn->prepare("SELECT transaction_code, name, contact_number, transaction_type, status FROM transactions WHERE transaction_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed (get transaction): " . $conn->error);
    }
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $transaction = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    error_log("DEBUG: Transaction fetched: " . json_encode($transaction));

    if (!$transaction) {
        throw new Exception("Transaction not found");
    }

    if ($transaction['status'] !== 'Completed') {
        throw new Exception("Only completed transactions can be confirmed for receipt");
    }

    // Check if already received (based on transaction_code instead of ID)
    $checkStmt = $conn->prepare("SELECT received_id FROM received_papers WHERE transaction_code = ?");
    if (!$checkStmt) {
        throw new Exception("Prepare failed (check received): " . $conn->error);
    }
    $checkStmt->bind_param("s", $transaction['transaction_code']);
    $checkStmt->execute();
    $existing = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();

    error_log("DEBUG: Existing received entry check result: " . json_encode($existing));

    if ($existing) {
        throw new Exception("Papers already received for this transaction code");
    }

    // Get user info
    $user_id = intval($_SESSION['user_id']);
    error_log("DEBUG: Logged in user_id={$user_id}");

    $stmtUser = $conn->prepare("SELECT first_name, middle_name, last_name FROM users WHERE user_id = ? LIMIT 1");
    if (!$stmtUser) {
        throw new Exception("Prepare failed (get user): " . $conn->error);
    }
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $userRow = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();

    error_log("DEBUG: User fetched: " . json_encode($userRow));

    if (!$userRow) {
        throw new Exception("User not found");
    }

    // Concatenate name
    $received_by = trim($userRow['first_name'] .
        ' ' . ($userRow['middle_name'] ? substr($userRow['middle_name'], 0, 1) . '. ' : '') .
        $userRow['last_name']);
    error_log("DEBUG: Received by name = {$received_by}");

    // Insert into received_papers
    $insertStmt = $conn->prepare("
        INSERT INTO received_papers 
        (transaction_id, transaction_code, client_name, contact_number, transaction_type, received_by, notes, status, received_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'received', NOW())
    ");
    if (!$insertStmt) {
        throw new Exception("Prepare failed (insert received_papers): " . $conn->error);
    }
    $insertStmt->bind_param(
        "issssss",
        $transaction_id,
        $transaction['transaction_code'],
        $transaction['name'],
        $transaction['contact_number'],
        $transaction['transaction_type'],
        $received_by,
        $notes
    );
    if (!$insertStmt->execute()) {
        throw new Exception("Error confirming transaction: " . $insertStmt->error);
    }
    $insertStmt->close();

    error_log("DEBUG: Inserted into received_papers successfully for code " . $transaction['transaction_code']);

    // Delete the original transaction
    $deleteStmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    if (!$deleteStmt) {
        throw new Exception("Prepare failed (delete transaction): " . $conn->error);
    }
    $deleteStmt->bind_param("i", $transaction_id);
    if (!$deleteStmt->execute()) {
        throw new Exception("Error deleting original transaction: " . $deleteStmt->error);
    }
    $deleteStmt->close();

    error_log("DEBUG: Deleted transaction_id={$transaction_id}");

    $conn->commit();
    error_log("DEBUG: Transaction committed successfully");

    // Delete uploads folder
    deleteTransactionFolder($transaction_id);

    // Log activity
    $details = "Papers received by client" . ($notes ? " - Notes: " . $notes : "");
    logActivity($transaction_id, "Papers Received", $details, $user_id, $transaction['transaction_code']);
    error_log("DEBUG: Activity logged for transaction_code=" . $transaction['transaction_code']);

    // 🔹 Send SMS notification to client
    if (!empty($transaction['contact_number'])) {
        $smsMessage = formatReceivedPapersSMS(
            $transaction['transaction_code'],
            $transaction['name'],
            $transaction['transaction_type']
        );
        
        $smsResult = sendSMS($transaction['contact_number'], $smsMessage);
        
        if ($smsResult['success']) {
            error_log("SMS sent successfully for received papers: " . $transaction['transaction_code']);
            // Log SMS activity
            logActivity(
                $transaction_id, 
                "SMS Sent", 
                "Papers received confirmation sent to " . $transaction['contact_number'], 
                $user_id, 
                $transaction['transaction_code']
            );
        } else {
            error_log("Failed to send SMS for received papers: " . $smsResult['message']);
            // Don't fail the entire operation if SMS fails
        }
    }

    echo json_encode([
        "success" => true,
        "message" => "Transaction confirmed! Papers marked as received.",
        "received_date" => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    $conn->rollback();
    error_log("ERROR: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}


$conn->close();
?>