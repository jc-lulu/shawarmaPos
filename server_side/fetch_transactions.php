<?php
// File: server_side/fetch_transactions.php
// Prevent any output before JSON response
ob_start();

// Include required files
include('check_session.php');
include('../cedric_dbConnection.php');

// Set up response array
$response = array('data' => array());

try {
    // Query to get all transactions with proper ordering
    $query = "SELECT 
                transactionId,
                requestorId, 
                productName, 
                quantity,
                productType,
                transactionType, 
                transactionStatus, 
                dateOfRequest,
                notes
              FROM transaction 
              ORDER BY transactionId DESC";

    $result = $connection->query($query);
    
    if ($result === false) {
        throw new Exception("Database query error: " . $connection->error);
    }

    // Process results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format date for display
            $requestDate = !empty($row['dateOfRequest']) ? date('Y-m-d', strtotime($row['dateOfRequest'])) : 'N/A';
            
            // Determine status text
            $statusText = 'Unknown';
            if ($row['transactionStatus'] == 0) {
                $statusText = 'Pending';
            } else if ($row['transactionStatus'] == 1) {
                $statusText = 'Approved';
            } else if ($row['transactionStatus'] == 2) {
                $statusText = 'Rejected';
            }
            
            // Determine transaction type text
            $typeText = $row['transactionType'] == 0 ? 'In' : 'Out';
            
            // Build data row
            $response['data'][] = array(
                'transactionId' => $row['transactionId'],
                'requestorId' => $row['requestorId'],
                'productName' => htmlspecialchars($row['productName']),
                'productType' => $row['productType'],
                'type' => $typeText,
                'quantity' => $row['quantity'],
                'status' => $statusText,
                'displayDate' => $requestDate,
                'notes' => htmlspecialchars($row['notes']),
                'transactionId_raw' => $row['transactionId'] // For sorting purposes
            );
        }
    }
    
    // Clear any output buffer and output clean JSON
    ob_end_clean();
    
    // Set headers and return JSON
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    echo json_encode($response);
    
} catch (Exception $e) {
    // Clear buffer and return error JSON
    ob_end_clean();
    
    // Log error
    error_log("Transaction fetch error: " . $e->getMessage());
    
    // Return JSON with error info
    header('Content-Type: application/json');
    echo json_encode(array(
        'error' => true,
        'message' => $e->getMessage(),
        'data' => array()
    ));
}

// Close connection
$connection->close();
?>