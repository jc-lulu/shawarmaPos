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
            <div class="col">
                <h1>Menu Management</h1>
            </div>
            <div class="row p-3">
                <div class="col gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionInModal"><i class="fa-solid fa-plus m-1"></i>Add Product</button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#removeProductModal"><i class="fa-solid fa-xmark m-1"></i>Remove Product</button>
                </div>
                <div class="row p-3 gap-2" id="product-list">
                    <!-- <div class="col-md-3 product-container">
                        <div class="image-container"></div>
                        <div class="product-name"></div>
                        <div class="product-price"></div>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div class="modal fade" id="transactionInModal" tabindex="-1" aria-labelledby="transactionInLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionInLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadMenu" method="POST" enctype="multipart/form-data">
                            <input type="text" class="form-control mb-2" name="product_name" placeholder="Product Name" required>
                            <input type="number" class="form-control mb-2" name="price" placeholder="Price" required>
                            <input type="file" class="form-control mb-2" name="product_image" accept="image/*" required>
                            <button class="btn btn-primary w-100" type="submit">✔ Add</button>
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
                        <input type="text" id="searchProduct" class="form-control mb-3" placeholder="Search product name">

                        <!-- Product List -->
                        <form id="removeProductForm">
                            <div id="remove-product-list" class="row g-3">
                                <!-- Product list  -->
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-danger w-100 mt-3">🗑 Remove Selected</button>
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
                            <div class="col-md-3 product-container">
                                <div class="image-container">
                                    <img src="${product.productImage}" alt="${product.productName}" style="width: 100%; height: 100%; object-fit: cover;">
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
                console.log('burger ka sakin');
                $.ajax({
                    url: "server_side/removeMenu.php",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.trim() === "success") {
                            console.log('pasok kana');
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