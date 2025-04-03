<?php
include('cedric_dbConnection.php');

$sql = "SELECT * FROM menu ORDER BY menuId DESC";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 product-card">
                <label class="card shadow-sm selectable-product">
                    <input type="checkbox" name="remove_products[]" value="' . $row['menuId'] . '" class="form-check-input d-none">
                    <img src="' . $row['productImage'] . '" class="card-img-top" alt="' . $row['productName'] . '" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h6 class="card-title">' . $row['productName'] . '</h6>
                    </div>
                </label>
            </div>';
    }
} else {
    echo "<p class='text-center'>No products available.</p>";
}
