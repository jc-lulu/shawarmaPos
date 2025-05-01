<?php
include('../cedric_dbConnection.php');
include('check_session.php');

$response = [];

try {
    // Query to get archived notifications
    $sql = "SELECT * FROM notifications 
            WHERE notificationFlag = 1";

    $stmt = $connection->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Prepare failed: ' . htmlspecialchars($connection->error));
    }

    // No parameters to bind since we're not filtering by userId
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all notifications as an associative array
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    echo json_encode($notifications);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$connection->close();
?>