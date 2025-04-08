<?php
session_start();
include('../cedric_dbConnection.php');

// Check if user is logged in and has admin permissions
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] != 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Get product ID
    $productId = $_POST['id'];

    // Delete the record
    $sql = "DELETE FROM inventory WHERE productId = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $productId);

    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Inventory item deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting inventory item: ' . $connection->error]);
    }

    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing item ID']);
}
