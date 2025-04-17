<?php
// File: server_side/getInventoryItems.php
include('../server_side/check_session.php');
include('../cedric_dbConnection.php');

// Only return IN items with positive quantity
$query = "SELECT productId, productName, quantity FROM inventory WHERE type = 0 AND quantity > 0 AND transactionStatus = 1";
$result = $connection->query($query);

$items = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = array(
            'productId' => $row['productId'],
            'productName' => $row['productName'],
            'quantity' => $row['quantity']
        );
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($items);

$connection->close();
?>