<?php
session_start();
include('../cedric_dbConnection.php');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Prepare and execute the query
    $sql = "SELECT * FROM inventory WHERE productId = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        echo json_encode(['success' => true, 'item' => $item]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
    }

    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No item ID provided']);
}
