<?php
include('server_side/check_session.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <?php include('header/header.php') ?>
    <link href="styles/report.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content py-3">
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