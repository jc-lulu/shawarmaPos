<?php
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
$transactionId = isset($_POST['transactionId']) ? intval($_POST['transactionId']) : 0;
$notificationId = isset($_POST['notificationId']) ? intval($_POST['notificationId']) : 0;

error_log("Received transactionId: $transactionId, notificationId: $notificationId");

if ($transactionId <= 0 || $notificationId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid transaction or notification ID'
    ]);
    exit;
}

// Start transaction to ensure data consistency
$connection->begin_transaction();

try {
    // Step 1: Get transaction details
    $query = "SELECT * FROM transaction WHERE transactionId = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $transactionId);
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
    $stmt->bind_param('i', $transactionId);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update transaction status: " . $stmt->error);
    }
    
    // Step 3: Update notification status to Finished (1)
    $updateNotificationQuery = "UPDATE notifications SET notificationStatus = 1 WHERE notificationId = ?";
    $stmt = $connection->prepare($updateNotificationQuery);
    $stmt->bind_param('i', $notificationId);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update notification status: " . $stmt->error);
    }
    
    // Step 4: Process inventory update based on transaction type
    $productName = $transaction['productName'];
    $quantity = $transaction['quantity'];
    $productType = $transaction['productType'];
    $transactionType = $transaction['transactionType'];
    
    // Check if product exists in inventory
    $checkInventoryQuery = "SELECT * FROM inventory WHERE productName = ?";
    $stmt = $connection->prepare($checkInventoryQuery);
    $stmt->bind_param('s', $productName);
    $stmt->execute();
    $inventoryResult = $stmt->get_result();
    
    if ($inventoryResult->num_rows > 0) {
        // Update existing inventory item
        $inventoryItem = $inventoryResult->fetch_assoc();
        $inventoryId = $inventoryItem['inventoryId'];
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
        $updateInventoryQuery = "UPDATE inventory SET quantity = ? WHERE inventoryId = ?";
        $stmt = $connection->prepare($updateInventoryQuery);
        $stmt->bind_param('ii', $newQuantity, $inventoryId);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update inventory: " . $stmt->error);
        }
    } else if ($transactionType == 0) {
        // Only create new inventory item if it's an "In" transaction
        $type = 0; // In type
        $insertInventoryQuery = "INSERT INTO inventory (productName, quantity, productType, type) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insertInventoryQuery);
        $stmt->bind_param('sii', $productName, $quantity, $productType, $type);
        
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
    
    $createNotificationQuery = "INSERT INTO notifications (notificationTarget notificationMessage, notificationType, notificationStatus) VALUES (?, ?, ?, 0)";
    $stmt = $connection->prepare($createNotificationQuery);
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
?>