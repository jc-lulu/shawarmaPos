<?php
include('../cedric_dbConnection.php');
include('check_session.php');

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'];

if ($userRole == '0') {
    // Admin role
    $query = "SELECT * FROM transaction WHERE is_archived = 1 ORDER BY dateOfRequest DESC";
} else {
    // Staff role
    $query = "SELECT * FROM transaction WHERE is_archived = 1 AND requestorId = $userId ORDER BY dateOfRequest DESC";
}
// Get archived transactions (changed from is_archived = 0 to is_archived = 1)
$result = mysqli_query($connection, $query);

// Initialize empty array for transactions
$transactions = [];

// Fetch all rows into the array
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        $transactiontype = $row['transactionType'];
        if ($transactiontype == 0) {
            $row['transactionType'] = 'In';
        } else if ($transactiontype == 1) {
            $row['transactionType'] = 'Out';
        }
        // Add each transaction to the array
        $transactions[] = [
            'id' => $row['transactionId'],
            'date' => date('M d, Y', strtotime($row['dateOfRequest'])),
            'customer' => $row['requestorId'],
            'productName' => $row['productName'],
            'status' => $row['transactionStatus'],
            'type' => $row['transactionType'],
            'quantity' => $row['quantity'],
            'total' => $row['quantity'] // You might want to adjust this if you store price information
        ];
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($transactions);
?>