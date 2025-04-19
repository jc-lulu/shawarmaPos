<?php
// filepath: server_side/fetchNotifications.php
include('check_session.php');
include('../cedric_dbConnection.php');

// Set headers for JSON response
header('Content-Type: application/json');

// Get filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($connection, $_GET['search']) : '';
$type = isset($_GET['type']) && $_GET['type'] !== '' ? intval($_GET['type']) : null;
$status = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null;

// Build the query
$query = "SELECT * FROM notifications WHERE 1=1";

// Add search filter if provided
if (!empty($search)) {
    $query .= " AND (notificationMessage LIKE '%$search%' OR notes LIKE '%$search%')";
}

// Add type filter if provided
if ($type !== null) {
    $query .= " AND notificationType = $type";
}

// Add status filter if provided
if ($status !== null) {
    $query .= " AND notificationStatus = $status";
}

// Order by most recent first
$query .= " ORDER BY notificationId DESC";

// Execute query
$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode([]);
    exit;
}

// Fetch all notifications
$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Format createdAt if it exists, otherwise use current time
    $row['createdAt'] = isset($row['createdAt']) ? $row['createdAt'] : date('Y-m-d H:i:s');
    
    // Use notificationId as id for frontend
    $row['id'] = $row['notificationId'];
    
    $notifications[] = $row;
}

// Send JSON response
echo json_encode($notifications);

// Close connection
mysqli_close($connection);
?>