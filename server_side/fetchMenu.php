<?php
include('../cedric_dbConnection.php');

$sql = "SELECT * FROM menu ORDER BY menuId DESC";
$result = $connection->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
