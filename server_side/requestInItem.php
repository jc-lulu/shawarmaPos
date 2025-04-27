<?php
// File: server_side/requestInItem.php
include('check_session.php');
include('../cedric_dbConnection.php');

// Set header to handle AJAX response
header('Content-Type: application/json');

// Initialize response array
$response = array('status' => 'error', 'message' => 'An unknown error occurred');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and validate inputs
        $productName = mysqli_real_escape_string($connection, $_POST['productIn_item']);
        $productQuantity = intval($_POST['productIn_quantity']);
        $dateOfIn = mysqli_real_escape_string($connection, $_POST['dateOfIn']);
        $notes = isset($_POST['requestInNotes']) ? mysqli_real_escape_string($connection, $_POST['requestInNotes']) : '';

        // Get user ID from session                                                 
        $requestedBy = $_SESSION['user_id'];

        // Validation checks
        if (empty($productName)) {
            throw new Exception("Product name is required");
        }

        if ($productQuantity <= 0) {
            throw new Exception("Quantity must be greater than zero");
        }

        if (empty($dateOfIn)) {
            throw new Exception("Request date is required");
        }

        // Insert the request with Pending status (0)
        // Transaction type: IN (0)
        $stmt = $connection->prepare("INSERT INTO transaction (requestorId, productName, quantity, transactionType, transactionStatus, dateOfRequest, notes) VALUES (?, ?, ?, 0, 0, ?, ?)");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $connection->error);
        }

        $stmt->bind_param("isiss", $requestedBy, $productName, $productQuantity, $dateOfIn, $notes);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        //step 2: insert into notifications table
        $transactionId = $stmt->insert_id; // Get the last inserted transaction ID
        $notificationType = 0;
        $notificationStatus = 0;
        $notificationMessage = "You have a request for In item that need your approval";
        $notes = ($notes) ? $notes : 'No notes provided';

        $notifyStmt = $connection->prepare("INSERT INTO notifications (transactionKey, notificationType, notificationStatus, notificationMessage, notes) VALUES (?, ?, ?, ?, ?)");

        if (!$notifyStmt) {
            throw new Exception("Prepare failed: " . $connection->error);
        }

        $notifyStmt->bind_param("iiiss", $transactionId, $notificationType, $notificationStatus, $notificationMessage, $notes);

        if (!$notifyStmt->execute()) {
            throw new Exception("Execute failed: " . $notifyStmt->error);
        }

        $notifyStmt->close();

        $response = array('status' => 'success', 'message' => 'Request submitted successfully', 'transactionId' => $transactionId);

        $stmt->close();
    } catch (Exception $e) {
        $response = array('status' => 'error', 'message' => $e->getMessage());
    }
}

// Return response
echo json_encode($response);

$connection->close();
