<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f9f5d7;
        }

        .invoice {
            height: 85vh;
            background-color: #d9ead3;
            border-radius: 5px;
        }

        .menu-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .menu-item button {
            width: 100%;
        }

        .quantity {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .quantity button {
            background-color: #ccc;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card menu-item p-2">
                                <img src="cat.jpg" alt="Food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Siopao</h5>
                                    <p class="card-text">$100</p>
                                    <button class="btn btn-success" onclick="addToInvoice('Siopao', 100)">Add</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card menu-item p-2">
                                <img src="cat.jpg" alt="Food">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Label of Food</h5>
                                    <p class="card-text">$Price</p>
                                    <button class="btn btn-success" onclick="addToInvoice('Label of Food', Price)">Add</button>
                                </div>
                            </div>
                        </div>
                        <!-- Add more menu items as needed -->
                    </div>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
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
    </script>
</body>

</html>
