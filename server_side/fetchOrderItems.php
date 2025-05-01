<?php
include('check_session.php');
include('../cedric_dbConnection.php');

// Get the order ID from the request
$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;

// Initialize response array
$response = array();

if ($orderId) {
    // Query to get order items where itemKey matches the orderId
    $query = "SELECT * FROM orderedItemHistory WHERE itemKey = ?";
    
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add each item to the response array
            $response[] = array(
                'itemKey' => $row['itemKey'],
                'productName' => $row['productName'],
                'Quantity' => $row['quantity'],
                'productPrice' => $row['productPrice'],
                // Include any other fields you need
            );
        }
    }
    
    $stmt->close();
} else {
    // If no orderId was provided
    $response = array(
        'error' => true,
        'message' => 'No order ID provided'
    );
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($response);

$connection->close();
?>