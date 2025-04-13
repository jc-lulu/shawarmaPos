<?php
include('../cedric_dbConnection.php');

if (isset($_POST['remove_products'])) {
    $idsToDelete = implode(",", $_POST['remove_products']);
    $sql = "DELETE FROM menu WHERE menuId IN ($idsToDelete)";

    if ($connection->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $connection->error;
    }
} else {
    echo "No products selected.";
}