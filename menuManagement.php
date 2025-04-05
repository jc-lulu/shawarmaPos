<?php
include('server_side/check_session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management</title>
    <?php include('header/header.php') ?>
    <link href="styles/menuManagement.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <h1>Menu Management</h1>

            <div class="action-row mb-4">
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#transactionInModal">
                    <i class="fa-solid fa-plus me-2"></i>Add Product
                </button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#removeProductModal">
                    <i class="fa-solid fa-xmark me-2"></i>Remove Product
                </button>
            </div>

            <div id="product-list" class="product-list-container">
                <!-- Products will be loaded here -->
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="transactionInModal" tabindex="-1" aria-labelledby="transactionInLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionInLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadMenu" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price (₱)</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label for="product_image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*" required>
                                <div class="form-text">Recommended size: 500x500px</div>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-check me-2"></i> Add Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remove Product Modal -->
        <div class="modal fade" id="removeProductModal" tabindex="-1" aria-labelledby="removeProductLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeProductLabel">Remove Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Search Product -->
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchProduct" class="form-control" placeholder="Search product name">
                        </div>

                        <!-- Product List -->
                        <form id="removeProductForm">
                            <div id="remove-product-list" class="row g-3">
                                <!-- Product list will be loaded dynamically -->
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-danger w-100 mt-3">
                                <i class="fas fa-trash me-2"></i> Remove Selected
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadRemoveProductList() {
            $.ajax({
                url: "server_side/fetchRemove.php",
                type: "GET",
                success: function(response) {
                    $("#remove-product-list").html(response);

                    //click functionality to select product
                    $(".selectable-product").click(function() {
                        var checkbox = $(this).find("input[type='checkbox']");
                        checkbox.prop("checked", !checkbox.prop("checked"));
                        $(this).toggleClass("selected", checkbox.prop("checked"));
                    });
                },
                error: function() {
                    console.error("Error loading products.");
                }
            });
        }

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
                            <div class="product-container">
                                <div class="image-container">
                                    <img src="server_side/${product.productImage}" alt="${product.productName}">
                                </div>
                                <div class="product-name text-center">
                                    <strong>${product.productName}</strong>
                                </div>
                                <div class="product-price text-center">
                                    <span>₱${parseFloat(product.productPrice).toFixed(2)}</span>
                                </div>
                            </div>
                            `;
                        });
                    } else {
                        productHTML = "<div class='w-100 text-center p-5'><p class='text-muted'>No products found. Add some products to get started!</p></div>";
                    }

                    $("#product-list").html(productHTML);
                },
                error: function() {
                    console.error("Error loading products.");
                }
            });
        }

        $(document).ready(function() {
            loadProducts(); // Load products

            $("#uploadMenu").submit(function(event) {
                event.preventDefault(); // Prevent page reload
                var formData = new FormData(this);

                $.ajax({
                    url: "server_side/addMenu.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Product Added!",
                                text: "The product has been successfully added.",
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $("#transactionInModal").modal("hide");
                            $("#uploadMenu")[0].reset();
                            loadProducts(); // Reload products after adding
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response
                            });
                        }
                    }
                });
            });

            //search function sa Remove Modal
            $("#searchProduct").on("keyup", function() {
                var searchText = $(this).val().toLowerCase();
                $(".product-card").each(function() {
                    var productName = $(this).find(".card-title").text().toLowerCase();
                    $(this).toggle(productName.includes(searchText));
                });
            });

            $("#removeProductModal").on("show.bs.modal", function() {
                loadRemoveProductList();
            });

            $("#removeProductForm").submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: "server_side/removeMenu.php",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.trim() === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Products Removed!",
                                text: "Selected products have been successfully removed.",
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $("#removeProductModal").modal("hide");
                            loadProducts();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>