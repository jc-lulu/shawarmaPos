<?php
include('../cedric_dbConnection.php');

$postData = json_decode(file_get_contents('php://input'), true);

if (!$postData || empty($postData['items'])) {
    echo json_encode(['success' => false, 'message' => 'No items in order']);
    exit;
}

// Get the order data
$items = $postData['items'];
$subtotal = $postData['subtotal'];
$dateOfOrder = date('Y-m-d');
$timeOfOrder = date('H:i:s');

$connection->begin_transaction();

try {
    // Step 1: Generate the new order ID
    $query = "SELECT MAX(CAST(itemKey AS UNSIGNED)) as maxId FROM ordereditemhistory";
    $result = $connection->query($query);
    $row = $result->fetch_assoc();

    $orderIdNum = 1; // Default starting ID
    if ($row && $row['maxId']) {
        $orderIdNum = intval($row['maxId']) + 1;
    }

    $orderId = sprintf('%06d', $orderIdNum); // Format to 6 digits with leading zeros

    // Step 2: Insert each item into orderitemhistory
    $stmt = $connection->prepare("INSERT INTO ordereditemhistory (itemKey, productName, productPrice, Quantity, totalPrice, dateOfOrder) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($items as $item) {
        $stmt->bind_param(
            "ssddds",
            $orderId,
            $item['name'],
            $item['price'],
            $item['quantity'],
            $item['totalPrice'],
            $dateOfOrder
        );

        if (!$stmt->execute()) {
            throw new Exception("Error inserting order item: " . $stmt->error);
        }
    }
    $stmt->close();

    // Calculate week information
    $orderDate = new DateTime($dateOfOrder);
    $weekNumber = intval($orderDate->format('W')); // ISO week number (1-53)
    $weekYear = intval($orderDate->format('Y'));   // Year
    $weekLabel = "Week $weekNumber, $weekYear";

    // Step 3: Insert order summary into orderhistory with week information
    $orderStmt = $connection->prepare("INSERT INTO orderedhistory (orderId, totalCost, dateOfOrder, timeOfOrder, weekNumber, weekYear, weekLabel) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $orderStmt->bind_param("sdssiis", $orderId, $subtotal, $dateOfOrder, $timeOfOrder, $weekNumber, $weekYear, $weekLabel);

    if (!$orderStmt->execute()) {
        throw new Exception("Error inserting order summary: " . $orderStmt->error);
    }
    $orderStmt->close();

    // Commit the transaction
    $connection->commit();

    // Send success response with the order ID
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'orderId' => $orderId,
        'dateOfOrder' => $dateOfOrder,
        'timeOfOrder' => $timeOfOrder
    ]);
} catch (Exception $e) {
    // Roll back the transaction in case of error
    $connection->rollback();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$connection->close();
