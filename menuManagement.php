<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu_management</title>
    <?php include('header/header.php') ?>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        background-color: #f9f5d7;
    }

    .container {
        max-width: 100%;
        display: flex;
        flex: 1;
    }

    .main-content {
        flex: 1;
        padding: 20px;
    }

    .product-container {
        height: 200px;
        width: 200px;
        background-color: #ffff;
        padding: 5px;
    }

    .image-container {
        width: 100%;
        height: 60%;
    }

    .product-name {
        height: 20%;
        width: 100%;
    }

    .product-price {
        height: 20%;
        width: 100%;
    }
</style>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="col">
                <h1>Menu Management</h1>
            </div>
            <div class="row p-3">
                <div class="col gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionInModal">Add Product</button>
                    <button class="btn btn-warning">Remove Product</button>
                </div>
                <div class="row p-3 gap-2">
                    <div class="col-md-3 product-container">
                        <div class="image-container"></div>
                        <div class="product-name"></div>
                        <div class="product-price"></div>
                    </div>
                </div>
            </div>
        </div>

        <!---Forms-->
        <form method="Post" id="addProduct"></form>

        <!-- modals-->
        <div class="modal fade" id="transactionInModal" tabindex="-1" aria-labelledby="transactionInLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionInLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control mb-2" placeholder="Product Name" form="addProduct">
                        <input type="number" class="form-control mb-2" placeholder="Price" form="addProduct">
                        <button class="btn btn-primary w-100" form="addProduct">✔ Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</html>