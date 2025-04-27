<?php
// Include database connection
require_once '../cedric_dbConnection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $response = ['success' => true];

    // Get count of IN items
    $inItemsQuery = "SELECT COUNT(*) as count FROM inventory WHERE type = 0 AND transactionStatus = 1";
    $result = $connection->query($inItemsQuery);
    if ($result && $row = $result->fetch_assoc()) {
        $response['inItemCount'] = $row['count'];
    }

    // Get latest IN date
    $latestDateQuery = "SELECT MAX(dateOfIn) as latestDate FROM inventory WHERE type = 0 AND transactionStatus = 1";
    $result = $connection->query($latestDateQuery);
    if ($result && $row = $result->fetch_assoc()) {
        $response['latestInDate'] = $row['latestDate'];
    }

    // Get total stock (sum of quantities)
    $totalStockQuery = "SELECT SUM(quantity) as total FROM inventory WHERE type = 0 AND transactionStatus = 1";
    $result = $connection->query($totalStockQuery);
    if ($result && $row = $result->fetch_assoc()) {
        $response['totalStock'] = $row['total'] ? $row['total'] : 0;
    }

    // Get count of low stock items (less than 10 units)
    $lowStockQuery = "SELECT COUNT(*) as count FROM inventory WHERE type = 0 AND transactionStatus = 1 AND quantity < 20";
    $result = $connection->query($lowStockQuery);
    if ($result && $row = $result->fetch_assoc()) {
        $response['lowStockCount'] = $row['count'];
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (Exception $e) {
    // Return error response
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Close connection
$connection->close();
