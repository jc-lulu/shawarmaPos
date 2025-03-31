<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
        .search-bar button, .filter-button {
            padding: 10px;
            background-color: #f4e04d;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button:hover, .filter-button:hover {
            background-color: #ff8c00;
        }
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filter-dropdown {
            position: relative;
            display: inline-block;
        }
        .filter-dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 150px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }
        .filter-dropdown-content button {
            width: 100%;
            padding: 10px;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }
        .filter-dropdown-content button:hover {
            background-color: #f4e04d;
        }
        .filter-dropdown:hover .filter-dropdown-content {
            display: block;
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
        th, td {
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
            <h1>REPORTS</h1>
            
            <div class="search-bar">
                <input type="text" placeholder="Search Reports">
            </div>

            <div class="filters">
                <!-- Date Range Filter -->
                <div class="filter-dropdown">
                    <button class="filter-button">Date Range ⬇</button>
                    <div class="filter-dropdown-content">
                        <button>Today</button>
                        <button>7 days</button>
                        <button>Month</button>
                        <button>Custom Date</button>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="filter-dropdown">
                    <button class="filter-button">Category ⬇</button>
                    <div class="filter-dropdown-content">
                        <button>Sales</button>
                        <button>Expenses</button>
                        <button>Inventory</button>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="filter-dropdown">
                    <button class="filter-button">Status ⬇</button>
                    <div class="filter-dropdown-content">
                        <button>Pending</button>
                        <button>Approved</button>
                        <button>Disapproved</button>
                    </div>
                </div>
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">Report ID</th>
                            <th style="width: 30%;">Category</th>
                            <th style="width: 20%;">Total Amount</th>
                            <th style="width: 20%;">Status</th>
                            <th style="width: 10%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>201</td>
                            <td>Sales</td>
                            <td>$1,200</td>
                            <td>Approved</td>
                            <td>2025-03-31</td>
                        </tr>
                        <tr>
                            <td>202</td>
                            <td>Expenses</td>
                            <td>$400</td>
                            <td>Pending</td>
                            <td>2025-03-30</td>
                        </tr>
                        <tr>
                            <td>203</td>
                            <td>Inventory</td>
                            <td>$800</td>
                            <td>Disapproved</td>
                            <td>2025-03-29</td>
                        </tr>
                        <tr>
                            <td>204</td>
                            <td>Sales</td>
                            <td>$950</td>
                            <td>Approved</td>
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
