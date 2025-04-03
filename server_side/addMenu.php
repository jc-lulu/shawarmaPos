<?php
include('../cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $image = $_FILES['product_image'];

    // Check if the file was uploaded
    if ($image['error'] === 0) {
        $imageName = time() . "_" . basename($image['name']);
        $imagePath = "../uploads/" . $imageName;

        // Move the uploaded file
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $stmt = $connection->prepare("INSERT INTO menu (productName, productPrice, productImage) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $productName, $price, $imagePath);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Error uploading image.";
    }
}
