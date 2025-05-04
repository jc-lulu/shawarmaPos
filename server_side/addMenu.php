<?php
include('../cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $category = $_POST['category']; // Changed from productType to match the form field name
    $image = $_FILES['product_image'];

    // Check if the file was uploaded
    if ($image['error'] === 0) {
        $uploadDir = "../uploads/"; // Define upload directory

        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($image['name']);
        $imagePath = "uploads/" . $imageName;
        $fullImagePath = "../" . $imagePath;

        // Move the uploaded file
        if (move_uploaded_file($image['tmp_name'], $fullImagePath)) {
            $stmt = $connection->prepare("INSERT INTO menu (productName, productPrice, productImage, productType) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdsi", $productName, $price, $imagePath, $category);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload image. Error code: " . $image['error'];
        }
    } else {
        echo "Error uploading image. Error code: " . $image['error'];
    }
} else {
    echo "Invalid request method";
}