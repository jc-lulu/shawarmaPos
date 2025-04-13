<?php
include('../cedric_dbConnection.php');

if (!isset($_GET['orderId']) || empty($_GET['orderId'])) {
    echo json_encode(['error' => 'No order ID provided']);
    exit;
}

$orderId = $_GET['orderId'];

// Sanitize input to prevent SQL injection
$orderId = $connection->real_escape_string($orderId);

$query = "SELECT id, productName, productPrice, Quantity as Quantity, totalPrice
          FROM ordereditemhistory
          WHERE itemKey = '$orderId'
          ORDER BY id";

$result = $connection->query($query);

$items = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($items);
$connection->close();
?>