<?php
session_start();
include('../cedric_dbConnection.php');

// Check if user is logged in and has admin permissions
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] != 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    // Delete the record
    $sql = "DELETE FROM userstable WHERE usersId = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);

    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'account deleted succesfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting account: ' . $connection->error]);
    }

    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or missing user ID']);
}