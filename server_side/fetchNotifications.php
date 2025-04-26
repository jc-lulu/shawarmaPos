<?php
include('check_session.php');
include('../cedric_dbConnection.php');

header('Content-Type: application/json');

// Get user role from session
$userRole = $_SESSION['user_role'];

// Get filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($connection, $_GET['search']) : '';
$type = isset($_GET['type']) && $_GET['type'] !== '' ? intval($_GET['type']) : null;
$status = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null;

$query = "SELECT * FROM notifications WHERE 1=1";

// If not admin (role != 1), exclude request notifications (type 0)
if ($userRole != 0) {
    $query .= " AND (notificationType != 0)";
}

// search filter if provided
if (!empty($search)) {
    $query .= " AND (notificationMessage LIKE '%$search%' OR notes LIKE '%$search%')";
}

// type filter if provided
if ($type !== null) {
    $query .= " AND notificationType = $type";
}

// status filter if provided
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
    $row['transactionId'] = $row['transactionKey'];

    $notifications[] = $row;
}

// Send JSON response
echo json_encode($notifications);

// Close connection
mysqli_close($connection);
