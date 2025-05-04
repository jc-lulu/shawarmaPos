<?php
include('../cedric_dbConnection.php');
include('check_session.php');

$response = [];
$userId = $_SESSION['user_id'];


try {
    $sqlSelect = "SELECT role FROM userstable WHERE usersId = $userId";
    $result = mysqli_query($connection, $sqlSelect);
    $row = mysqli_fetch_assoc($result);
    $userRole = $row['role'];
    // Query to get archived notifications


    if ($userRole != 0) {
        //AND notificationTarget = $userId OR notificationType = 1  //include message type
        $sql = "SELECT * FROM notifications 
            WHERE notificationFlag = 1 AND (notificationTarget = $userId OR notificationTarget = 0) AND notificationType != 0 ORDER BY notificationId DESC"; //include message type
    } else {
        $sql = "SELECT * FROM notifications 
            WHERE notificationFlag = 1 AND notificationType != 2"; //exclude message type
    }

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