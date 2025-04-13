<?php
include('../cedric_dbConnection.php');

// Get order history from database
$query = "SELECT oh.historyId, oh.orderId, oh.totalCost, oh.dateOfOrder, oh.timeOfOrder, 
          COUNT(oi.id) as itemCount
          FROM orderedhistory oh
          LEFT JOIN ordereditemhistory oi ON oh.orderId = oi.itemKey
          GROUP BY oh.historyId, oh.orderId, oh.totalCost, oh.dateOfOrder, oh.timeOfOrder
          ORDER BY oh.dateOfOrder DESC, oh.timeOfOrder DESC";

$result = $connection->query($query);

$orders = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = array(
            'orderId' => $row['orderId'],
            'totalCost' => $row['totalCost'],
            'dateOfOrder' => $row['dateOfOrder'],
            'timeOfOrder' => $row['timeOfOrder'],
            'itemCount' => $row['itemCount']
        );
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($orders);
$connection->close();
?>