<?php
session_start();
include('../cedric_dbConnection.php');

// Set response content type to JSON
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Capture data from request
        $productId = $_POST['productId'] ?? '';        
        $productName = $_POST['productName'] ?? '';    
        $quantity = $_POST['quantity'] ?? '';          
        $date = $_POST['date'] ?? '';                  
        $notes = $_POST['notes'] ?? '';                 
        $userId = $_SESSION['user_id'] ?? 0;
        

        if (empty($productId) || empty($productName) || empty($quantity) || empty($date)) {
            echo json_encode(['status' => 'error', 'message' =>  'All required fields must be filled']);
            exit;
        }
        
        // Check if quantity is valid
        if (!is_numeric($quantity) || $quantity <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a valid quantity']);
            exit;
        }
        
        // Check if product exists and has enough quantity
        $checkQuery = "SELECT quantity FROM inventory WHERE productId = ?";
        $checkStmt = $connection->prepare($checkQuery);
        $checkStmt->bind_param("i", $productId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
            exit;
        }
        
        $row = $result->fetch_assoc();
        $availableQuantity = $row['quantity'];
        
        if ($quantity > $availableQuantity) {
            echo json_encode(['status' => 'error', 'message' => 'Requested quantity exceeds available amount']);
            exit;
        }
        
        // Insert transaction record
        $query = "INSERT INTO transaction (productId, productName, transactionType, quantity, dateOfRequest, notes, transactionStatus, requestorId) 
                  VALUES (?, ?, 1, ?, ?, ?,  0, ?)";
        $stmt = $connection->prepare($query);
        $type = 1;
        $status = 0;
        $stmt->bind_param("isissi", $productId, $productName, $quantity, $date, $notes, $userId);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Request submitted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit request: ' . $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$connection->close();
?>