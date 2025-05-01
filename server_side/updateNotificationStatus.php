<?php
// filepath: server_side/updateNotificationStatus.php
include('check_session.php');
include('../cedric_dbConnection.php');

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $notificationId = intval($_POST['id']);
    $status = intval($_POST['status']);
    
    // Validate status value (0 or 1)
    if ($status !== 0 && $status !== 1) {
        $response['message'] = 'Invalid status value';
        echo json_encode($response);
        exit;
    }
    
    // Update notification status
    $query = "UPDATE notifications SET notificationStatus = ? WHERE notificationId = ?";
    $stmt = $connection->prepare($query);
    
    if (!$stmt) {
        $response['message'] = 'Database error: ' . $connection->error;
        echo json_encode($response);
        exit;
    }
    
    $stmt->bind_param('ii', $status, $notificationId);
    
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Notification status updated successfully'
        ];
    } else {
        $response['message'] = 'Failed to update notification status: ' . $stmt->error;
    }
    
    $stmt->close();
} else {
    $response['message'] = 'Missing required parameters';
}

// Send JSON response
echo json_encode($response);

// Close connection
$connection->close();
?>