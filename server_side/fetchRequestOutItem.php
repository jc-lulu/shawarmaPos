<?php
include('../cedric_dbConnection.php');

header('Content-Type: application/json');

try {
    // Query to get all products with inventory > 0
    $query = "SELECT productId, productName, quantity FROM inventory WHERE type = 0 AND quantity > 0 ORDER BY productName ASC";
    $result = $connection->query($query);
    
    $products = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = [ 
                'id' => $row['productId'],
                'name' => $row['productName'],
                'quantity' => $row['quantity']
            ];  
        }
    }
    
    echo json_encode(['status' => 'success', 'products' => $products]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching products: ' . $e->getMessage()]);
}

$connection->close();
?>