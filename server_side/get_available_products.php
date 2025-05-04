<?php
include('check_session.php');
include('../cedric_dbConnection.php');

// Get all active IN products with quantity > 0
$query = "SELECT productId, productName, quantity FROM inventory WHERE type = 0 AND quantity > 0 AND transactionStatus = 1 ORDER BY productName ASC";
$result = $connection->query($query);

$products = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($products);

// Close connection
$connection->close();
?>