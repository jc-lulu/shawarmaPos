<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordered History</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <?php include('header/header.php') ?>
    <link href="styles/orderedHistory.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content py-3">
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