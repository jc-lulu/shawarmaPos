<?php
include('../cedric_dbConnection.php');

$query = "SELECT menuId, productName, productPrice, productImage, productType FROM menu ORDER BY productType ASC, productName ASC";
$result = $connection->query($query);

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($products);
$connection->close();