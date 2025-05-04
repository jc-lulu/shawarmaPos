<?php
include('check_session.php');
include('../cedric_dbConnection.php');  // Fixed path and typo in filename

// Query to get order history data
$query = "SELECT historyId, orderId, totalCost, dateOfOrder, timeOfOrder  FROM orderedhistory WHERE historyStatus = 0 ORDER BY dateOfOrder DESC, timeOfOrder DESC";
$result = mysqli_query($connection, $query);

$orderHistory = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Query to get item count for this order
        $itemQuery = "SELECT COUNT(*) as item_count FROM ordereditems WHERE orderId = '{$row['orderId']}' AND historyStatus = 1";
        $itemResult = mysqli_query($connection, $itemQuery);
        $itemCount = 0;

        if ($itemResult && mysqli_num_rows($itemResult) > 0) {
            $itemRow = mysqli_fetch_assoc($itemResult);
            $itemCount = $itemRow['item_count'];
        }

        $orderHistory[] = array(
            'order_id' => $row['orderId'],
            'total_cost' => $row['totalCost'],
            'order_date' => $row['dateOfOrder'],
            'order_time' => $row['timeOfOrder'],
            'items' => $itemCount
        );
    }
}

// Add error logging for debugging
if (empty($orderHistory)) {
    error_log("No order history found in database");
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($orderHistory);

mysqli_close($connection);
?>