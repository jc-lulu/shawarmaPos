<?php
include('../cedric_dbConnection.php');
include('check_session.php');

$response = array('success' => false, 'message' => '');

// Debug output - remove in production
error_log('POST data: ' . print_r($_POST, true));

// Check if notification ID was provided
if (!isset($_POST['notificationId']) || empty($_POST['notificationId'])) {
    $response['message'] = 'No notification selected';
    echo json_encode($response);
    exit;
}

try {
    // Get the notification ID from the POST request
    $notificationId = intval($_POST['notificationId']);

    // Debug output - remove in production
    error_log('Notification ID after conversion: ' . $notificationId);

    // The SQL uses notificationId to match your database schema
    $sql = "UPDATE notifications SET notificationFlag = 0 WHERE notificationId = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $notificationId);

    // Execute the query
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        if ($affectedRows > 0) {
            $response['success'] = true;
            $response['message'] = 'Notification successfully unarchived';
        } else {

            $response['message'] = 'Notification not found or already unarchived. ID: ' . $notificationId;

        }
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