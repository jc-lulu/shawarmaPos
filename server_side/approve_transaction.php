<?php
// Turn off PHP notices and warnings to prevent HTML in output
error_reporting(E_ERROR);
ini_set('display_errors', 0);

// Include required files
include('check_session.php');
include('../cedric_dbConnection.php');

// Set header for JSON response
header('Content-Type: application/json');

// Check if user is admin
if ($_SESSION['user_role'] != 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Permission denied. Only administrators can approve transactions.'
    ]);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get transaction and notification IDs
$transactionId = isset($_POST['transactionId']) ? $_POST['transactionId'] : 0;
$notificationId = isset($_POST['notificationId']) ? intval($_POST['notificationId']) : 0;

error_log("Received transactionId: $transactionId, notificationId: $notificationId");

if (empty($transactionId) || $notificationId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid transaction or notification ID'
    ]);
    exit;
}

// Start transaction to ensure data consistency
$connection->begin_transaction();

try {
    // Step 1: Get trans    action details
    $query = "SELECT * FROM transaction WHERE transactionId = ?";
    $stmt = $connection->prepare($query);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param('s', $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Transaction not found");
    }

    $transaction = $result->fetch_assoc(); // Fetch transaction details

    // Check if transaction is already approved or rejected
    if ($transaction['transactionStatus'] != 0) {
        throw new Exception("This transaction is already processed");
    }

    // Step 2: Update transaction status to Approved (1)
    $updateTransactionQuery = "UPDATE transaction SET transactionStatus = 1 WHERE transactionId = ?";
    $stmt = $connection->prepare($updateTransactionQuery);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param('s', $transactionId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update transaction status: " . $stmt->error);
    }

    // Step 3: Update notification status to Finished (1)
    $updateNotificationQuery = "UPDATE notifications SET requestStatus = 1 WHERE notificationId = ?";
    $stmt = $connection->prepare($updateNotificationQuery);

    if (!$stmt) {
        throw new Exception("Prepare failed for notification update: " . $connection->error);
    }

    //debug logging
    error_log("Updating notification ID: $notificationId to status 1");

    $stmt->bind_param('i', $notificationId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update notification status: " . $stmt->error . " - Query: $updateNotificationQuery");
    }

    $affectedRows = $stmt->affected_rows;
    if ($affectedRows == 0) {
        error_log("Warning: No rows affected when updating notification status for ID: $notificationId");
    }

    // Step 4: Process inventory update based on transaction type
    $productName = $transaction['productName'];
    $quantity = $transaction['quantity'];
    $productType = $transaction['productType'];
    $transactionType = $transaction['transactionType'];

    // Check if product exists in inventory
    $checkInventoryQuery = "SELECT * FROM inventory WHERE productName = ?";
    $stmt = $connection->prepare($checkInventoryQuery);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param('s', $productName);
    $stmt->execute();
    $inventoryResult = $stmt->get_result();

    if ($inventoryResult->num_rows > 0) {
        // Update existing inventory item
        $inventoryItem = $inventoryResult->fetch_assoc();
        $productId = $inventoryItem['productId'];
        $currentQuantity = $inventoryItem['quantity'];

        // Calculate new quantity based on transaction type
        // 0 = In (add), 1 = Out (subtract)
        $newQuantity = ($transactionType == 0)
            ? $currentQuantity + $quantity
            : $currentQuantity - $quantity;

        // Ensure quantity doesn't go negative
        if ($newQuantity < 0) {
            throw new Exception("Cannot approve: Transaction would result in negative inventory");
        }

        // Update inventory quantity
        $updateInventoryQuery = "UPDATE inventory SET quantity = ? WHERE productId = ?";
        $stmt = $connection->prepare($updateInventoryQuery);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param('ii', $newQuantity, $productId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update inventory: " . $stmt->error);
        }
    } else if ($transactionType == 0) {

        $type = 0; // In type
        $insertInventoryQuery = "INSERT INTO inventory (productName, quantity, productType, type) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insertInventoryQuery);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param('siii', $productName, $quantity, $productType, $type);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add new inventory item: " . $stmt->error);
        }
    } else {
        // Cannot approve "Out" transaction for non-existent inventory
        throw new Exception("Cannot approve: Product does not exist in inventory");
    }

    //Final Step: Create a new notification for the requestor
    $requestorId = $transaction['requestorId'];
    $message = "Your request for " . $productName . " has been approved";
    $notificationType = 2; // Response notification

    // Fix the query syntax - missing comma between columns
    $createNotificationQuery = "INSERT INTO notifications (notificationTarget, notificationMessage, notificationType, notificationStatus) VALUES (?, ?, ?, 0)";
    $stmt = $connection->prepare($createNotificationQuery);

    if (!$stmt) {
        throw new Exception("Prepare failed for notification creation: " . $connection->error);
    }

    $stmt->bind_param('isi', $requestorId, $message, $notificationType);

    if (!$stmt->execute()) {
        throw new Exception("Failed to create notification for requestor: " . $stmt->error);
    }

    // All operations successful, commit transaction
    $connection->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Transaction approved successfully'
    ]);
} catch (Exception $e) {
    // Roll back on any error
    $connection->rollback();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $connection->close();
}
