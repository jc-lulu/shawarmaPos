<?php
include('../cedric_dbConnection.php');
include('check_session.php');

$response = ['success' => false, 'message' => ''];

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $transactionId = mysqli_real_escape_string($connection, $_POST['id']);

    // Update transaction to set is_archived = 0
    $query = "UPDATE transaction SET is_archived = 0 WHERE transactionId = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $transactionId);

    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Transaction unarchived successfully';
        } else {
            $response['message'] = 'Transaction not found or already unarchived';
        }
    } else {
        $response['message'] = 'Error unarchiving transaction: ' . $connection->error;
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid transaction ID';
}

header('Content-Type: application/json');
echo json_encode($response);
?>