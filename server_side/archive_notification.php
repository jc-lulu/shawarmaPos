<?php
include('../cedric_dbConnection.php');
// include('check_session.php');

$response = array('success' => false, 'message' => '');

// Check if notification IDs were provided
if (!isset($_POST['notificationIds']) || empty($_POST['notificationIds'])) {
    $response['message'] = 'No notifications selected';
    echo json_encode($response);
    exit;
}

try {
    // Get the notification IDs from the POST request
    $notificationIds = $_POST['notificationIds'];

    // Prepare the SQL statement with placeholders
    $placeholders = str_repeat('?,', count($notificationIds) - 1) . '?';
    $sql = "UPDATE notifications SET notificationFlag = 1 WHERE notificationId IN ($placeholders)";

    $stmt = $connection->prepare($sql);

    // Bind all IDs as parameters
    $types = str_repeat('i', count($notificationIds)); // Assuming IDs are integers
    $stmt->bind_param($types, ...$notificationIds);

    // Execute the query
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        $response['success'] = true;
        $response['message'] = $affectedRows . ' notification(s) successfully archived';
    } else {
        $response['message'] = 'Error executing query: ' . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

$connection->close();
echo json_encode($response);
?>