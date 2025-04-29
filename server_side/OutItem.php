<?php
// File: server_side/OutItem.php
include('check_session.php');
include('../cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $productId = mysqli_real_escape_string($connection, $_POST['productId']);
    $productName = mysqli_real_escape_string($connection, $_POST['productName']);
    $quantity = intval($_POST['quantity']);
    $dateOfOut = mysqli_real_escape_string($connection, $_POST['dateOfOut']);
    $deleteZeroStock = isset($_POST['deleteZeroStock']) ? $_POST['deleteZeroStock'] === 'true' : false;

    // Check if this is a confirmation request or initial request
    $isConfirmation = isset($_POST['isConfirmation']) ? $_POST['isConfirmation'] === 'true' : false;

    // Get the current product details to check available quantity
    $checkQuery = "SELECT quantity FROM inventory WHERE productId = ? AND type = 0";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $availableQty = $row['quantity'];

        // Validate quantity
        if ($quantity <= 0 || $quantity > $availableQty) {
            echo "invalid_quantity";
            exit;
        }

        // Check if this operation will make stock zero and need confirmation
        if ($quantity == $availableQty && !$isConfirmation) {
            // Return special response to trigger confirmation dialog
            echo "zero_stock_confirmation";
            exit;
        }

        // Begin transaction
        $connection->begin_transaction();

        try {
            // 1. Reduce quantity from the IN record
            $updateQuery = "UPDATE inventory SET quantity = quantity - ? WHERE productId = ? AND type = 0";
            $updateStmt = $connection->prepare($updateQuery);
            $updateStmt->bind_param("is", $quantity, $productId);
            $updateStmt->execute();

            // 2. Insert new OUT record
            $insertQuery = "INSERT INTO inventory ( productName, quantity, type, requestedBy, transactionStatus, dateOfOut) 
                           VALUES ( ?, ?, 1, 0, 1, ?)";
            $insertStmt = $connection->prepare($insertQuery);
            $insertStmt->bind_param("sis", $productName, $quantity, $dateOfOut);
            $insertStmt->execute();
            // 3. If stock becomes zero and delete option is enabled, delete the item
            if ($quantity == $availableQty && $deleteZeroStock) {
                $deleteQuery = "DELETE FROM inventory WHERE productId = ? AND type = 0";
                $deleteStmt = $connection->prepare($deleteQuery);
                $deleteStmt->bind_param("s", $productId);
                $deleteStmt->execute();
            }

            // Commit transaction
            $connection->commit();
            echo "success";

        } catch (Exception $e) {
            // Rollback on error
            $connection->rollback();
            echo "error: " . $e->getMessage();
        }
    } else {
        echo "product_not_found";
    }

    // Close statement and connection
    $stmt->close();
    $connection->close();
}
?>