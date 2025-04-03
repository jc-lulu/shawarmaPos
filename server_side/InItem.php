<?php
include('../cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productIn_item'];
    $productQuantity = $_POST['productIn_quantity'];
    $productType = $_POST['productIn_type'];

    $sql = "INSERT INTO inventory (productName, quantity,type, transactionStatus) VALUES ('$productName', $productQuantity, '$productType', 0)";
    $query = $connection->query($sql);

    if ($connection) {
        echo "success";
    } else {
        echo "error";
    }
}
