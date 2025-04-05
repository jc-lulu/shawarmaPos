<?php
include('server_side/check_session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <?php include('header/header.php') ?>
    <link href="styles/menu.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container-fluid py-3 p-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <h1>Menu</h1>
                        <div class="input-group mt-3">
                            <input type="text" class="form-control" placeholder="Search Food">
                        </div>
                        <div class="btn-group mt-3">
                            <button class="btn btn-warning">All</button>
                            <button class="btn btn-warning">Shawarma</button>
                            <button class="btn btn-warning">Burgers</button>
                            <button class="btn btn-warning">Fries</button>
                            <button class="btn btn-warning">Drinks</button>
                        </div>
                    </div>

                    <div class="row p-3 gap-2" id="product-list"></div>
                </div>

                <div class="col-md-4">
                    <div class="invoice p-3 d-flex flex-column">
                        <h3 class="text-center">Invoice</h3>
                        <div id="invoice-list" class="flex-grow-1 overflow-auto"></div>
                        <button class="btn btn-warning w-100">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadProducts() {
            $.ajax({
                url: "server_side/fetchMenu.php",
                type: "GET",
                dataType: "json",
                success: function(products) {
                    let productHTML = "";

                    if (products.length > 0) {
                        products.forEach(function(product) {
                            productHTML += `
                            <div class="col-md-3 product-container p-3" style="background-color: white; border-radius: 10px;">
                                <div class="image-container">
                                    <img src="server_side/${product.productImage}" alt="${product.productName}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="product-name text-center">
                                    <strong>${product.productName}</strong>
                                </div>
                                <div class="product-price text-center">
                                    <span>₱${parseFloat(product.productPrice).toFixed(2)}</span>
                                </div>
                                <div class="button-container text-center">
                                    <button class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        `;
                        });
                    } else {
                        productHTML = "<p>No products found.</p>";
                    }

                    $("#product-list").html(productHTML);
                },
                error: function() {
                    console.error("Error loading products.");
                }
            });
        }

        $(document).ready(function() {
            loadProducts();
        });
    </script>
</body>

</html>