<?php
session_start();
include('../cedric_dbConnection.php');

// Check if user is logged in and if role is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] != 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];
    $dateIn = $_POST['dateIn'] ?: null;
    $dateOut = $_POST['dateOut'] ?: null;


    if ($dateIn) $dateIn = date('Y-m-d', strtotime($dateIn));
    if ($dateOut) $dateOut = date('Y-m-d', strtotime($dateOut));
    else $dateOut = '0000-00-00'; // Default value

    $sql = "UPDATE inventory SET 
            productName = ?, 
            type = ?, 
            quantity = ?, 
            transactionStatus = ?, 
            dateOfIn = ?, 
            dateOfOut = ? 
            WHERE productId = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("siisssi", $productName, $type, $quantity, $status, $dateIn, $dateOut, $productId);

    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Inventory item updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating inventory: ' . $connection->error]);
    }

    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
