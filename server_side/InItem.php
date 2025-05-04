<?php
include('check_session.php');
include('../cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productIn_item'];
    $productQuantity = $_POST['productIn_quantity'];
    $dateOfIn = $_POST['dateOfIn'];

    $select = "SELECT productName FROM inventory WHERE productName = '$productName' AND type = 0 AND transactionStatus = 1";
    $result = $connection->query($select);
    if ($result->num_rows > 0) {
        // Product already exists, update the quantity
        $row = $result->fetch_assoc();
        $existingQuantity = $row['quantity'];
        $newQuantity = $existingQuantity + $productQuantity;

        $update = "UPDATE inventory SET quantity = $newQuantity WHERE productName = '$productName' AND type = 0 AND transactionStatus = 1";
        $connection->query($update);
    } else {
        // Product does not exist, insert a new record
        $insert = "INSERT INTO inventory (productName, quantity, type, requestedBy, transactionStatus, dateOfIn) VALUES ('$productName', $productQuantity, 0, 0, 1, '$dateOfIn')";
        $connection->query($insert);
    }

    if ($connection) {
        echo "success";
    } else {
        echo "error";
    }
}