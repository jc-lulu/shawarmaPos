<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f9f5d7;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .main-content {
            width: 80%;
            padding: 20px;
        }

        .main-content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button,
        .search-bar select {
            padding: 10px;
            background-color: #f4e04d;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover,
        .search-bar select:hover {
            background-color: #ff8c00;
        }

        .data-table {
            width: 100%;
            height: 400px;
            background-color: #d9d9d9;
            border-radius: 5px;
            overflow: auto;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            text-align: center;
            table-layout: fixed;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #ff8c00;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <h1>INVENTORY</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search Product">
                <button>🔍</button>
                <select>
                    <option value="">Filter</option>
                    <option value="category1">Category 1</option>
                    <option value="category2">Category 2</option>
                </select>
            </div>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 15%;">Quantity</th>
                            <th style="width: 15%;">Gross Income</th>
                            <th style="width: 15%;">Net Income</th>
                            <th style="width: 20%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Sample Product</td>
                            <td>50</td>
                            <td>$500</td>
                            <td>$400</td>
                            <td>2025-03-29</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Another Product</td>
                            <td>30</td>
                            <td>$300</td>
                            <td>$250</td>
                            <td>2025-03-28</td>
                        </tr>
                        <!-- More rows can be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>