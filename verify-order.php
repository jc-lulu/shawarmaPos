<?php
// filepath: c:\xampp\htdocs\shawarmaPos\verify-order.php
// Order verification page that displays order details after scanning QR code

// Include database connection
include('cedric_dbConnection.php');

// Get the order ID from the URL
$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize variables
$orderDetails = null;
$orderItems = [];
$error = '';

// Validate order ID
if ($orderId <= 0) {
    $error = "Invalid order ID.";
} else {
    // Get the order details
    $orderStmt = $connection->prepare("SELECT * FROM orders WHERE orderId = ?");
    $orderStmt->bind_param("i", $orderId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    
    if ($orderResult->num_rows > 0) {
        $orderDetails = $orderResult->fetch_assoc();
        
        // Get the order items
        $itemsStmt = $connection->prepare("SELECT oi.*, m.productName 
                                    FROM order_items oi 
                                    JOIN menu m ON oi.menuId = m.menuId 
                                    WHERE oi.orderId = ?");
        $itemsStmt->bind_param("i", $orderId);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();
        
        while ($item = $itemsResult->fetch_assoc()) {
            $orderItems[] = $item;
        }
        
        $itemsStmt->close();
    } else {
        $error = "Order not found.";
    }
    $orderStmt->close();
}

// Close the connection
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Verification - #<?php echo $orderId; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .order-header {
            background-color: #ff8c00;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }
        .order-details {
            padding: 20px;
        }
        .item-row {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.2rem;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center mt-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <h4>Error</h4>
                        <p><?php echo $error; ?></p>
                    </div>
                <?php else: ?>
                    <div class="card mt-5">
                        <div class="order-header text-center">
                            <h2><i class="fas fa-receipt me-2"></i> Order #<?php echo $orderDetails['orderId']; ?></h2>
                            <p class="mb-0"><?php echo date("F d, Y", strtotime($orderDetails['orderDate'])); ?> at <?php echo date("h:i A", strtotime($orderDetails['orderTime'])); ?></p>
                        </div>
                        
                        <div class="order-details">
                            <h5 class="mb-3">Order Items:</h5>
                            
                            <?php foreach ($orderItems as $item): ?>
                                <div class="item-row d-flex justify-content-between">
                                    <div>
                                        <span class="fw-bold"><?php echo $item['quantity']; ?> × </span>
                                        <?php echo $item['productName']; ?>
                                    </div>
                                    <div>
                                        ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="total-row d-flex justify-content-between mt-3">
                                <div>TOTAL</div>
                                <div>₱<?php echo number_format($orderDetails['total'], 2); ?></div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    This order has been verified
                                </div>
                                <p class="text-muted">Thank you for your order!</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>