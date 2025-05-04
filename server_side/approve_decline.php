<?php
// filepath: server_side/updateTransactionStatus.php
include('check_session.php');
include('../cedric_dbConnection.php');

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transactionId']) && isset($_POST['status'])) {
    $transactionId = intval($_POST['transactionId']);
    $status = intval($_POST['status']); // 1 for Approved, 2 for Rejected
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($connection, $_POST['notes']) : '';
    
    // Validate status value (1 or 2)
    if ($status !== 1 && $status !== 2) {
        $response['message'] = 'Invalid status value';
        echo json_encode($response);
        exit;
    }
    
    // Start transaction
    $connection->begin_transaction();
    
    try {
        // Update transaction status
        $query = "UPDATE transaction SET transactionStatus = ? WHERE transactionId = ?";
        $stmt = $connection->prepare($query);
        
        if (!$stmt) {
            throw new Exception('Database error: ' . $connection->error);
        }
        
        $stmt->bind_param('ii', $status, $transactionId);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update transaction status: ' . $stmt->error);
        }
        
        $stmt->close();
        
        // Update related notification status to read (1)
        $notifyQuery = "UPDATE notifications SET notificationStatus = 1 WHERE transactionKey = ?";
        $notifyStmt = $connection->prepare($notifyQuery);
        
        if (!$notifyStmt) {
            throw new Exception('Database error: ' . $connection->error);
        }
        
        $notifyStmt->bind_param('i', $transactionId);
        
        if (!$notifyStmt->execute()) {
            throw new Exception('Failed to update notification status: ' . $notifyStmt->error);
        }
        
        $notifyStmt->close();
        
        // If approved (status 1) and it's an IN transaction (type 0), update inventory
        if ($status === 1) {
            // Get transaction details
            $transQuery = "SELECT productName, quantity, transactionType FROM transaction WHERE transactionId = ?";
            $transStmt = $connection->prepare($transQuery);
            
            if (!$transStmt) {
                throw new Exception('Database error: ' . $connection->error);
            }
            
            $transStmt->bind_param('i', $transactionId);
            $transStmt->execute();
            $transResult = $transStmt->get_result();
            
            if ($transResult && $transData = $transResult->fetch_assoc()) {
                $productName = $transData['productName'];
                $quantity = $transData['quantity'];
                $transactionType = $transData['transactionType'];
                
                // Check if it's IN (0) or OUT (1) transaction
                if ($transactionType == 0) {
                    // Add to inventory (IN transaction)
                    $inventoryQuery = "INSERT INTO inventory (productName, quantity) 
                                      VALUES (?, ?) 
                                      ON DUPLICATE KEY UPDATE quantity = quantity + ?";
                    $inventoryStmt = $connection->prepare($inventoryQuery);
                    
                    if (!$inventoryStmt) {
                        throw new Exception('Database error: ' . $connection->error);
                    }
                    
                    $inventoryStmt->bind_param('sii', $productName, $quantity, $quantity);
                    
                    if (!$inventoryStmt->execute()) {
                        throw new Exception('Failed to update inventory: ' . $inventoryStmt->error);
                    }
                    
                    $inventoryStmt->close();
                } elseif ($transactionType == 1) {
                    // Subtract from inventory (OUT transaction)
                    $inventoryQuery = "UPDATE inventory SET quantity = quantity - ? 
                                      WHERE productName = ? AND quantity >= ?";
                    $inventoryStmt = $connection->prepare($inventoryQuery);
                    
                    if (!$inventoryStmt) {
                        throw new Exception('Database error: ' . $connection->error);
                    }
                    
                    $inventoryStmt->bind_param('isi', $quantity, $productName, $quantity);
                    
                    if (!$inventoryStmt->execute()) {
                        throw new Exception('Failed to update inventory: ' . $inventoryStmt->error);
                    }
                    
                    if ($inventoryStmt->affected_rows == 0) {
                        throw new Exception('Not enough inventory available for ' . $productName);
                    }
                    
                    $inventoryStmt->close();
                }
            }
            
            $transStmt->close();
        }
        
        // Commit transaction
        $connection->commit();
        
        $statusText = $status === 1 ? 'approved' : 'rejected';
        $response = [
            'success' => true,
            'message' => 'Transaction ' . $statusText . ' successfully'
        ];
    } catch (Exception $e) {
        // Rollback on error
        $connection->rollback();
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'Missing required parameters';
}

// Send JSON response
echo json_encode($response);

// Close connection
$connection->close();
?>