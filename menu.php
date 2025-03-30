<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/sweetalert.js"></script>
    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- iziModal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/css/iziModal.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/js/iziModal.min.js"></script>
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
            display: flex;
            flex: 1;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .menu-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            padding: 10px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .category-buttons {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .category-buttons button {
            padding: 10px;
            background-color: #ffa500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .category-buttons button:hover {
            background-color: #ff8c00;
        }

        .menu-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .menu-item {
            text-align: center;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        .menu-item button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .menu-item button:hover {
            background-color: #45a049;
        }

        .invoice {
            width: 25%;
            padding: 20px;
            background-color: #d9ead3;
            border-radius: 5px;
            margin-left: auto;
            height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .invoice h3 {
            text-align: center;
        }

        .invoice-list {
            flex-grow: 1;
            overflow-y: auto;
        }

        .place-order {
            padding: 15px;
            background-color: #f4e04d;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            align-self: center;
        }

        .quantity {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 5px;
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
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="menu-header">
                <h2>Menu</h2>
                <div class="search-bar">
                    <input type="text" placeholder="Search Food">
                    <button>Search</button>
                </div>
                <div class="category-buttons">
                    <button>All</button>
                    <button>Shawarma</button>
                    <button>Burgers</button>
                    <button>Fries</button>
                    <button>Drinks</button>
                </div>
            </div>
            <div class="menu-items">
                <div class="menu-item">
                    <img src="cat.jpg" alt="Food">
                    <p>Siopao</p>
                    <p>$100</p>
                    <button onclick="addToInvoice('Siopao', 100)">Add</button>
                </div>
                <div class="menu-item">
                    <img src="cat.jpg" alt="Food">
                    <p>Label of Food</p>
                    <p>$Price</p>
                    <button onclick="addToInvoice('Label of Food', Price)">Add</button>
                </div>
            </div>
        </div>
        <div class="invoice">
            <h3>Invoice</h3>
            <div class="invoice-list" id="invoice-list"></div>
            <button class="place-order">Place Order</button>
        </div>
    </div>
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