<?php
include('check_session.php');
include('../cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productIn_item'];
    $productQuantity = $_POST['productIn_quantity'];
    $productType = $_POST['productIn_type'];
    $dateOfIn = $_POST['dateOfIn'];

    $sql = "INSERT INTO inventory (productName, quantity, productType, type, requestedBy,transactionStatus, dateOfIn) VALUES ('$productName', $productQuantity, '$productType', 0, 0, 1, '$dateOfIn')";
    $query = $connection->query($sql);


    if ($connection) {
        echo "success";
    } else {
        echo "error";
    }
}
