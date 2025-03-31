<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordered History</title>
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
        .search-bar button {
            padding: 10px;
            background-color: #f4e04d;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button:hover {
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
            <h1>ORDERED HISTORY</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search Order">
            </div>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">Order ID</th>
                            <th style="width: 40%;">Total Cost</th>
                            <th style="width: 40%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>105</td>
                            <td>$700</td>
                            <td>2025-03-31</td>
                        </tr>
                        <tr>
                            <td>104</td>
                            <td>$520</td>
                            <td>2025-03-30</td>
                        </tr>
                        <tr>
                            <td>103</td>
                            <td>$620</td>
                            <td>2025-03-27</td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>$300</td>
                            <td>2025-03-28</td>
                        </tr>
                        <tr>
                            <td>101</td>
                            <td>$450</td>
                            <td>2025-03-29</td>
                        </tr>
                        <!-- More rows can be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
