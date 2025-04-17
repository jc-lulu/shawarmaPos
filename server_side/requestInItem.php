<?php
// File: server_side/requestInItem.php
include('check_session.php');
include('../cedric_dbConnection.php');

// Set header to handle AJAX response
header('Content-Type: application/json');

// Initialize response array
$response = array('status' => 'error', 'message' => 'An unknown error occurred');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and validate inputs
        $productName = mysqli_real_escape_string($connection, $_POST['productIn_item']);
        $productQuantity = intval($_POST['productIn_quantity']);
        $productType = mysqli_real_escape_string($connection, $_POST['productIn_type']);
        $dateOfIn = mysqli_real_escape_string($connection, $_POST['dateOfIn']);
        $notes = isset($_POST['requestInNotes']) ? mysqli_real_escape_string($connection, $_POST['requestInNotes']) : '';
        
        // Get user ID from session
        $requestedBy = $_SESSION['user_id'];
        
        // Validation checks
        if (empty($productName)) {
            throw new Exception("Product name is required");
        }
        
        if ($productQuantity <= 0) {
            throw new Exception("Quantity must be greater than zero");
        }
        
        if (empty($productType)) {
            throw new Exception("Product type is required");
        }
        
        if (empty($dateOfIn)) {
            throw new Exception("Request date is required");
        }
        
        // Insert the request with Pending status (0)
        // Transaction type: IN (0)
        $stmt = $connection->prepare("INSERT INTO transaction (requestorId, productName, quantity, productType, transactionType, transactionStatus, dateOfRequest, notes) VALUES (?, ?, ?, ?, 0, 0, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $connection->error);
        }
        
        $stmt->bind_param("isisss", $requestedBy, $productName, $productQuantity, $productType, $dateOfIn, $notes);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        // Success response
        $response = array('status' => 'success', 'message' => 'Request submitted successfully');
        
        $stmt->close();
    } catch (Exception $e) {
        $response = array('status' => 'error', 'message' => $e->getMessage());
    }
}

// Return response
echo json_encode($response);

$connection->close();
?>