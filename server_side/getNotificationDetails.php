<?php
include('check_session.php');
include('../cedric_dbConnection.php');

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $notificationId = intval($_GET['id']);
    
    // Get notification details
    $query = "SELECT * FROM notifications WHERE notificationId = ?";
    $stmt = $connection->prepare($query);
    
    if (!$stmt) {
        $response['message'] = 'Database error: ' . $connection->error;
        echo json_encode($response);
        exit;
    }
    
    $stmt->bind_param('i', $notificationId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $row = $result->fetch_assoc()) {
        // Format createdAt if it exists, otherwise use current time
        $row['createdAt'] = isset($row['createdAt']) ? $row['createdAt'] : date('Y-m-d H:i:s');
        
        // Get transaction details if transactionKey exists
        if (!empty($row['transactionKey'])) {
            $transQuery = "SELECT requestorId, productName, quantity, productType, transactionType, transactionStatus, dateOfRequest 
                          FROM transaction 
                          WHERE transactionId = ?";
            $transStmt = $connection->prepare($transQuery);
            
            if ($transStmt) {
                $transStmt->bind_param('i', $row['transactionKey']);
                $transStmt->execute();
                $transResult = $transStmt->get_result();
                
                if ($transResult && $transData = $transResult->fetch_assoc()) {
                    // Get requestor name
                    $userQuery = "SELECT username as requestorName 
                                 FROM userstable WHERE usersId = ?";
                    $userStmt = $connection->prepare($userQuery);
                    
                    if ($userStmt) {
                        $userStmt->bind_param('i', $transData['requestorId']);
                        $userStmt->execute();
                        $userResult = $userStmt->get_result();
                        
                        if ($userResult && $userData = $userResult->fetch_assoc()) {
                            $transData['requestorName'] = $userData['requestorName'];
                        } else {
                            $transData['requestorName'] = 'Unknown';
                        }
                        
                        $userStmt->close();
                    }
                    
                    // Merge transaction data with notification
                    $row['transaction'] = $transData;
                }
                
                $transStmt->close();
            }
        }
        
        $response = [
            'success' => true,
            'notification' => $row
        ];
    } else {
        $response['message'] = 'Notification not found';
    }
    
    $stmt->close();
} else {
    $response['message'] = 'Invalid request parameters';
}

// Send JSON response
echo json_encode($response);

// Close connection
$connection->close();
?>