<?php
include('../cedric_dbConnection.php');
include('check_session.php');

$response = array('success' => false, 'message' => '');

try {
    // Delete all archived notifications for this user
    $sql = "DELETE FROM notifications WHERE notificationId AND notificationFlag = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        $response['success'] = true;
        $response['message'] = $affectedRows . ' archived notification(s) successfully deleted';
    } else {
        $response['message'] = 'Error executing query: ' . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>