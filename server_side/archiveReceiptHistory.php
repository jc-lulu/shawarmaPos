<?php

include('../cedric_dbConnection.php');
include('check_session.php');

// Initialize response array
$response = array('success' => false, 'message' => '');

if (!isset($_POST['orderId']) || empty($_POST['orderId'])) {
    $response['message'] = 'No receipt selected';
    echo json_encode($response);
    exit;
}

try {
    // Get the notification IDs from the POST request
    $orderId = $_POST['orderId'];

    // Prepare the SQL statement with placeholders
    $placeholders = str_repeat('?,', count($orderId) - 1) . '?';
    $sql = "UPDATE orderedhistory SET historyStatus = 1 WHERE orderId IN ($placeholders)";

    $stmt = $connection->prepare($sql);

    // Bind all IDs as parameters
    $types = str_repeat('i', count($orderId)); // Assuming IDs are integers
    $stmt->bind_param($types, ...$orderId);

    // Execute the query
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        $response['success'] = true;
        $response['message'] = $affectedRows . ' receipt(s) successfully archived';
    } else {
        $response['message'] = 'Error executing query: ' . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Make sure to output the JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;