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
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchFood" class="form-control"
                                placeholder="Search by product name...">
                        </div>
                        <div class="btn-group mt-3 filter-buttons">
                            <button id="filter-all" class="btn btn-warning filter-btn active">All</button>
                            <button id="filter-0" class="btn btn-warning filter-btn">Shawarma</button>
                            <button id="filter-1" class="btn btn-warning filter-btn">Burgers</button>
                            <button id="filter-2" class="btn btn-warning filter-btn">Fries</button>
                            <button id="filter-3" class="btn btn-warning filter-btn">Rice</button>
                            <button id="filter-4" class="btn btn-warning filter-btn">Drinks</button>
                        </div>
                    </div>

                    <div class="row p-3 gap-3" id="product-list"></div>
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
        // Global variables
        let allProducts = [];
        let currentFilter = 'all';

        let cart = [];
        let subtotal = 0;

        // Load all products from the server
        function loadProducts() {
            $.ajax({
                url: "server_side/fetchMenu.php",
                type: "GET",
                dataType: "json",
                success: function(products) {
                    // Store all products for filtering
                    allProducts = products;

                    // Apply initial filter (show all)
                    filterProducts(currentFilter);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading products:", error);
                    $("#product-list").html(`
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Failed to load menu items. Please try again later.
                        </div>
                    </div>
                `);
                }
            });
        }

        // Filter products by type and search term
        function filterProducts(filterType) {
            // Update active filter
            currentFilter = filterType;

            // Highlight active filter button
            $('.filter-btn').removeClass('active');
            $(`#filter-${filterType}`).addClass('active');

            // Get current search term
            const searchTerm = $('#searchFood').val().toLowerCase().trim();

            // Apply filters
            let filteredProducts = allProducts;

            // Filter by product type if not "all"
            if (filterType !== 'all') {
                const typeValue = parseInt(filterType);
                filteredProducts = filteredProducts.filter(product =>
                    parseInt(product.productType) === typeValue
                );
            }

            // Apply search filter if there's text
            if (searchTerm) {
                filteredProducts = filteredProducts.filter(product =>
                    product.productName.toLowerCase().includes(searchTerm)
                );
            }

            // Display filtered products
            renderProducts(filteredProducts);
        }

        // Render products to the page
        function renderProducts(products) {
            let productHTML = "";

            if (products.length > 0) {
                products.forEach(function(product) {
                    productHTML += `
                <div class="col-md-3 product-container p-3">
                    <div class="image-container">
                        <img src="${product.productImage}" alt="${product.productName}" 
                             onerror="this.src='images/no-image.png'">
                    </div>
                    <div class="product-name">
                        <strong>${product.productName}</strong>
                    </div>
                    <div class="product-price">
                        <span>₱${parseFloat(product.productPrice).toFixed(2)}</span>
                    </div>
                    <div class="button-container">
                        <button class="btn btn-warning add-to-cart" 
                                data-id="${product.menuId}" 
                                data-name="${product.productName}" 
                                data-price="${product.productPrice}">
                            <i class="fas fa-cart-plus me-2"></i>Add
                        </button>
                    </div>
                </div>`;
                });
            } else {
                productHTML = `
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-search me-2"></i>
                    No products found matching your criteria.
                </div>
            </div>`;
            }

            $("#product-list").html(productHTML);
        }

        // Function to add items to cart
        function addToCart(id, name, price) {
            // Check if item is already in cart
            const existingItemIndex = cart.findIndex(item => item.id === id);

            if (existingItemIndex !== -1) {
                // Increment quantity if already in cart
                cart[existingItemIndex].quantity++;
                cart[existingItemIndex].totalPrice = cart[existingItemIndex].quantity * cart[existingItemIndex].price;
            } else {
                // Add new item to cart
                cart.push({
                    id: id,
                    name: name,
                    price: parseFloat(price),
                    quantity: 1,
                    totalPrice: parseFloat(price)
                });
            }

            // Update the invoice display
            updateInvoice();

            // Return the updated cart for potential further use
            return cart;
        }

        // Function to remove items from cart
        function removeFromCart(id) {
            const itemIndex = cart.findIndex(item => item.id === id);

            if (itemIndex !== -1) {
                if (cart[itemIndex].quantity > 1) {
                    // Decrease quantity if more than 1
                    cart[itemIndex].quantity--;
                    cart[itemIndex].totalPrice = cart[itemIndex].quantity * cart[itemIndex].price;
                } else {
                    // Remove item if quantity is 1
                    cart.splice(itemIndex, 1);
                }

                // Update the invoice display
                updateInvoice();
            }
        }

        // Function to clear the entire cart
        function clearCart() {
            cart = [];
            updateInvoice();
        }

        // Function to update the invoice display
        function updateInvoice() {
            // Calculate subtotal
            subtotal = cart.reduce((sum, item) => sum + item.totalPrice, 0);

            // If cart is empty, show empty message
            if (cart.length === 0) {
                $("#invoice-list").html(`
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Your cart is empty</p>
                    </div>
                `);

                // Update place order button
                $(".invoice .btn-warning").html(`
                    <i class="fas fa-check-circle me-2"></i>Place Order
                `);
                return;
            }

            // Build receipt HTML
            let invoiceHTML = `
                <div class="receipt">
                    <div class="receipt-header mb-3">
                        <p class="text-center mb-1">${new Date().toLocaleDateString('en-PH', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })}</p>
                        <p class="text-center mb-0">${new Date().toLocaleTimeString('en-PH')}</p>
                    </div>
                    
                    <table class="receipt-items w-100">
                        <thead>
                            <tr>
                                <th class="text-start">Item</th>
                                <th>Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            // Add cart items
            cart.forEach(item => {
                invoiceHTML += `
                    <tr>
                        <td class="text-start item-name">${item.name}</td>
                        <td class="text-center">
                            <div class="qty-control">
                                <button class="qty-btn minus" data-id="${item.id}">-</button>
                                <span>${item.quantity}</span>
                                <button class="qty-btn plus" data-id="${item.id}">+</button>
                            </div>
                        </td>
                        <td class="text-end">₱${item.price.toFixed(2)}</td>
                        <td class="text-end">₱${item.totalPrice.toFixed(2)}</td>
                    </tr>
                `;
            });

            // Add receipt footer with totals
            invoiceHTML += `
                        </tbody>
                    </table>
                    
                    <div class="receipt-divider my-3"></div>
                    
                    <div class="receipt-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₱${subtotal.toFixed(2)}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between total-row mb-3">
                            <span><strong>TOTAL</strong></span>
                            <span><strong>₱${subtotal.toFixed(2)}</strong></span>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button id="clear-cart" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash me-1"></i> Clear All
                        </button>
                    </div>
                </div>
            `;

            // update the invoice list
            $("#invoice-list").html(invoiceHTML);

            // Update the place order button text
            $(".invoice .btn-warning").html(`
                <i class="fas fa-check-circle me-2"></i>Place Order (₱${subtotal.toFixed(2)})
            `);

            $(".qty-btn.minus").on("click", function() {
                const id = $(this).data("id");
                removeFromCart(id);
            });

            $(".qty-btn.plus").on("click", function() {
                const id = $(this).data("id");
                const item = cart.find(item => item.id === id);
                if (item) {
                    addToCart(id, item.name, item.price);
                }
            });

            // clear cart button
            $("#clear-cart").on("click", function() {
                Swal.fire({
                    title: 'Clear cart?',
                    text: "All items will be removed from your order.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearCart();
                        Swal.fire(
                            'Cleared!',
                            'Your cart has been cleared.',
                            'success'
                        );
                    }
                });
            });
        }

        // Document ready
        $(document).ready(function() {
            // Initial load
            loadProducts();

            // Initialize empty invoice
            updateInvoice();

            // Filter button click events
            $('#filter-all').on('click', function() {
                filterProducts('all');
            });

            $('#filter-0').on('click', function() {
                filterProducts('0');
            });

            $('#filter-1').on('click', function() {
                filterProducts('1');
            });

            $('#filter-2').on('click', function() {
                filterProducts('2');
            });

            $('#filter-3').on('click', function() {
                filterProducts('3');
            });

            $('#filter-4').on('click', function() {
                filterProducts('4');
            });

            // Search input event
            $('#searchFood').on('input', function() {
                filterProducts(currentFilter);
            });

            // Add to cart click handler
            $(document).on('click', '.add-to-cart', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = $(this).data('price');

                // Add the item to cart
                addToCart(productId, productName, productPrice);

                // Show notification
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart',
                    text: `${productName} has been added to your order.`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            });

            // Place order function
            $(".invoice .btn-warning").on("click", function() {
                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Empty Cart',
                        text: 'Please add some items to your cart first.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirm Order',
                    text: `Place order for ₱${subtotal.toFixed(2)}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ff8c00',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, place order!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Processing Order...',
                            text: 'Please wait while we process your order.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // send to server via ajax
                        $.ajax({
                            url: "server_side/place_order.php",
                            type: "POST",
                            contentType: "application/json",
                            data: JSON.stringify({
                                items: cart,
                                subtotal: subtotal
                            }),
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    // Show receipt with order ID and print button
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Order Placed!',
                                        html: `
                <div class="text-center">
                    <p>Your order has been placed successfully.</p>
                    <div class="alert alert-success mt-3">
                        <strong>Order ID:</strong> ${response.orderId}<br>
                        <strong>Date:</strong> ${response.dateOfOrder}<br>
                        <strong>Time:</strong> ${response.timeOfOrder}
                    </div>
                    <button id="print-receipt" class="btn btn-sm btn-outline-primary mt-3">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            `,
                                        didOpen: () => {
                                            document.getElementById('print-receipt').addEventListener('click', function() {
                                                // Generate printable receipt
                                                const receiptWindow = window.open('', '_blank');

                                                receiptWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Receipt #${response.orderId}</title>
                            <style>
                                body {
                                    font-family: 'Courier New', monospace;
                                    width: 300px;
                                    margin: 0 auto;
                                    padding: 10px;
                                }
                                .receipt-header, .receipt-footer {
                                    text-align: center;
                                    margin-bottom: 10px;
                                }
                                .divider {
                                    border-top: 1px dashed #000;
                                    margin: 10px 0;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }
                                th, td {
                                    text-align: left;
                                    padding: 3px 0;
                                }
                                .amount {
                                    text-align: right;
                                }
                                .total {
                                    font-weight: bold;
                                    border-top: 1px solid #000;
                                    padding-top: 5px;
                                }
                                @media print {
                                    .no-print {
                                        display: none;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="receipt-header">
                                <h2>SHAWARMA POS</h2>
                                <p>Receipt #${response.orderId}</p>
                                <p>${response.dateOfOrder} - ${response.timeOfOrder}</p>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <table>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th class="amount">Price</th>
                                    <th class="amount">Total</th>
                                </tr>
                    `);

                                                // Add items to receipt
                                                cart.forEach(item => {
                                                    receiptWindow.document.write(`
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td class="amount">₱${item.price.toFixed(2)}</td>
                                <td class="amount">₱${item.totalPrice.toFixed(2)}</td>
                            </tr>
                        `);
                                                });

                                                // Add totals and footer
                                                receiptWindow.document.write(`
                                <tr class="total">
                                    <td colspan="2">Total:</td>
                                    <td colspan="2" class="amount">₱${subtotal.toFixed(2)}</td>
                                </tr>
                            </table>
                            
                            <div class="divider"></div>
                            
                            <div class="receipt-footer">
                                <p>Thank you for your order!</p>
                                <p>Please come again</p>
                            </div>
                            
                            <div class="no-print" style="text-align: center; margin-top: 20px;">
                                <button onclick="window.print();" style="padding: 8px 16px; cursor: pointer;">
                                    Print Receipt
                                </button>
                            </div>
                        </body>
                        </html>
                    `);

                                                // Trigger the print dialog
                                                receiptWindow.document.close();
                                                receiptWindow.focus();
                                            });
                                        }
                                    }).then(() => {
                                        // Clear cart after order is placed
                                        clearCart();
                                    });
                                } else {
                                    // Show error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Order Failed',
                                        text: response.message || 'There was an error processing your order. Please try again.',
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error placing order:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Server Error',
                                    text: 'There was a problem connecting to the server. Please try again later.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>