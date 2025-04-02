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

        <div class="container-fluid p-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <h2>Menu</h2>
                        <div class="input-group">
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
                url: "fetchMenu.php",
                type: "GET",
                dataType: "json",
                success: function(products) {
                    let productHTML = "";

                    if (products.length > 0) {
                        products.forEach(function(product) {
                            productHTML += `
                            <div class="col-md-3 product-container p-3" style="background-color: white; border-radius: 10px;">
                                <div class="image-container">
                                    <img src="${product.productImage}" alt="${product.productName}" style="width: 100%; height: 100%; object-fit: cover;">
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
    <!-- <script>
       let invoice = {};

        function addToInvoice(name, price) {
            if (!invoice[name]) {
                invoice[name] = {
                    price: price,
                    quantity: 1
                };
            } else {
                invoice[name].quantity++;
            }
            updateInvoice();
        }

        function updateInvoice() {
            const invoiceList = document.getElementById('invoice-list');
            invoiceList.innerHTML = '';
            for (const [name, data] of Object.entries(invoice)) {
                const item = document.createElement('div');
                item.classList.add('text-center', 'p-2', 'border-bottom');
                item.innerHTML = `
                    <p>${name} - $${data.price} x <span>${data.quantity}</span></p>
                    <div class='quantity'>
                        <button onclick="changeQuantity('${name}', -1)">-</button>
                        <span>${data.quantity}</span>
                        <button onclick="changeQuantity('${name}', 1)">+</button>
                    </div>
                `;
                invoiceList.appendChild(item);
            }
        }

        function changeQuantity(name, amount) {
            if (invoice[name]) {
                invoice[name].quantity += amount;
                if (invoice[name].quantity <= 0) delete invoice[name];
                updateInvoice();
            }
        }
    </script> -->
</body>

</html>