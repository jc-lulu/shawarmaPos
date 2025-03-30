<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <?php include('header/header.php') ?>
    <!-- styles css, why nasa baba? need muna mag load ang bootstarp at ibang scripts bago ang css custom-->
    <link href="styles/menu.css" rel="stylesheet">
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