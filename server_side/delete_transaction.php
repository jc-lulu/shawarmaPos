<?php
// Include database connection
include('../cedric_dbConnection.php');
include('check_session.php');

// Check if post request contains transaction_id
if (!isset($_POST['transaction_id'])) {
    echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
    exit;
}

$transactionId = $_POST['transaction_id'];

try {
    // Delete the transaction
    $query = "DELETE FROM transaction WHERE transactionId = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $transactionId);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete transaction']);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

$connection->close();
?>