<?php
// Include database connection
require_once '../cedric_dbConnection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Query to get all OUT items (type = 1)
    $query = "SELECT productId, productName, quantity, dateOfIn, dateOfOut, transactionStatus 
              FROM inventory 
              WHERE type = 1 AND transactionStatus = 1 ORDER BY  dateOfOut DESC";

    $result = $connection->query($query);

    if (!$result) {
        throw new Exception("Database query error: " . $connection->error);
    }

    $outItems = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $outItems[] = $row;
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($outItems);
} catch (Exception $e) {
    // Return error response
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Close connection
$connection->close();