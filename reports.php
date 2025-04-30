<?php
include('server_side/check_session.php');

// Database connection
require_once 'cedric_dbConnection.php';

// Get current month and previous month dates
$currentMonth = date('Y-m');
$previousMonth = date('Y-m', strtotime('-1 month'));

// Calculate total sales for current month
$salesQuery = "SELECT SUM(totalCost) as totalSales 
               FROM orderedhistory 
               WHERE DATE_FORMAT(dateOfOrder, '%Y-%m') = '$currentMonth'";
$salesResult = $connection->query($salesQuery);
$currentMonthSales = $salesResult->fetch_assoc()['totalSales'] ?: 0;

// Calculate total sales for previous month
$prevSalesQuery = "SELECT SUM(totalCost) as totalSales 
                   FROM orderedhistory 
                   WHERE DATE_FORMAT(dateOfOrder, '%Y-%m') = '$previousMonth'";
$prevSalesResult = $connection->query($prevSalesQuery);
$previousMonthSales = $prevSalesResult->fetch_assoc()['totalSales'] ?: 0;

// Calculate sales percentage change
$salesPercentChange = 0;
if ($previousMonthSales > 0) {
    $salesPercentChange = (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100;
}
$salesTrend = $salesPercentChange >= 0 ? 'trend-up' : 'trend-down';
$salesIcon = $salesPercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

// For expenses, assume expenses are 70% of sales 
$currentMonthExpenses = $currentMonthSales * 0.7;
$previousMonthExpenses = $previousMonthSales * 0.7;

// Calculate expenses percentage change
$expensesPercentChange = 0;
if ($previousMonthExpenses > 0) {
    $expensesPercentChange = (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100;
}
$expensesTrend = $expensesPercentChange <= 0 ? 'trend-up' : 'trend-down'; // Note: for expenses, down is good
$expensesIcon = $expensesPercentChange <= 0 ? 'fa-arrow-down' : 'fa-arrow-up';

// Calculate profit
$currentMonthProfit = $currentMonthSales - $currentMonthExpenses;
$previousMonthProfit = $previousMonthSales - $previousMonthExpenses;

// Calculate profit percentage change
$profitPercentChange = 0;
if ($previousMonthProfit > 0) {
    $profitPercentChange = (($currentMonthProfit - $previousMonthProfit) / $previousMonthProfit) * 100;
}
$profitTrend = $profitPercentChange >= 0 ? 'trend-up' : 'trend-down';
$profitIcon = $profitPercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

// Get inventory value
$inventoryQuery = "SELECT SUM(quantity * (SELECT AVG(totalCost/quantity) FROM orderedhistory)) as inventoryValue 
                  FROM inventory";
$inventoryResult = $connection->query($inventoryQuery);
$inventoryValue = $inventoryResult->fetch_assoc()['inventoryValue'] ?: 0;

// For inventory trend, we would need histo
// For now, let's just use a placeholder value of 3%
// $inventoryPercentChange = 3;
// $inventoryTrend = $inventoryPercentChange >= 0 ? 'trend-up' : 'trend-down';
// $inventoryIcon = $inventoryPercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

// Format numbers for display
$salesDisplay = '₱' . number_format($currentMonthSales, 2);
$expensesDisplay = '₱' . number_format($currentMonthExpenses, 2);
$profitDisplay = '₱' . number_format($currentMonthProfit, 2);
$inventoryDisplay = '₱' . number_format($inventoryValue, 2);

// Close connection
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <?php include('header/header.php') ?>
    <link href="styles/report.css" rel="stylesheet">
    <style>
        .chart-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 20px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            color: #0A3981;
        }

        .chart-iframe {
            width: 100%;
            height: 450px;
            border: none;
            overflow: hidden;
        }

        .stat-period {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .charts-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-col {
            flex: 1;
            min-width: 300px;
        }

        @media (max-width: 992px) {
            .chart-col {
                flex: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container-fluid py-4 page-container">
            <h1 class="page-title">REPORTS</h1>

            <!-- Stats Summary Cards -->
            <div class="stats-cards mb-4">
                <div class="stat-card card-sales">
                    <div class="stat-title">Total Sales</div>
                    <div class="stat-value"><?php echo $salesDisplay; ?></div>
                    <div class="stat-period">This Month (<?php echo date('F Y'); ?>)</div>
                </div>
                <div class="stat-card card-expenses">
                    <div class="stat-title">Total Expenses</div>
                    <div class="stat-value"><?php echo $expensesDisplay; ?></div>
                    <div class="stat-period">This Month (<?php echo date('F Y'); ?>)</div>
                </div>
                <div class="stat-card card-profit">
                    <div class="stat-title">Net Profit</div>
                    <div class="stat-value"><?php echo $profitDisplay; ?></div>
                    <div class="stat-period">This Month (<?php echo date('F Y'); ?>)</div>
                </div>
                <!-- <div class="stat-card card-inventory">
                    <div class="stat-title">Inventory Value</div>
                    <div class="stat-value"><?php //echo $inventoryDisplay; ?></div>
                    <div class="stat-period">Current Inventory</div>
                </div> -->
            </div>

            <!-- Revenue Analysis Section Header -->
            <h2 class="section-title mb-3" style="color: #0A3981;">Revenue Analysis</h2>

            <!-- Charts Row - Both Charts Side by Side -->
            <div class="charts-row">
                <!-- Weekly Chart Column -->
                <div class="chart-col">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Weekly Revenue</h3>
                            <div class="chart-actions">
                                <button class="btn btn-sm btn-view view-chart" data-chart="weekly">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="refresh-weekly">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <iframe src="graphs/chartWeekly.php" class="chart-iframe" id="weekly-chart-iframe"></iframe>
                    </div>
                </div>

                <!-- Monthly Chart Column -->
                <div class="chart-col">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Monthly Revenue</h3>
                            <div class="chart-actions">
                                <button class="btn btn-sm btn-view view-chart" data-chart="monthly">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="refresh-monthly">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <iframe src="graphs/chart.php" class="chart-iframe" id="monthly-chart-iframe"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Handle refresh buttons
        document.getElementById('refresh-weekly').addEventListener('click', function () {
            document.getElementById('weekly-chart-iframe').src = 'graphs/chartWeekly.php?t=' + new Date().getTime();
        });

        document.getElementById('refresh-monthly').addEventListener('click', function () {
            document.getElementById('monthly-chart-iframe').src = 'graphs/chart.php?t=' + new Date().getTime();
        });

        // Improved view chart function
        document.querySelectorAll('.view-chart').forEach(function (button) {
            button.addEventListener('click', function () {
                const chartType = this.getAttribute('data-chart');

                // Instead of opening the raw PHP file, create a wrapper HTML page
                let url = '';
                let title = '';

                if (chartType === 'weekly') {
                    url = 'graphs/chartWeekly.php';
                    title = 'Weekly Revenue Chart';
                } else if (chartType === 'monthly') {
                    url = 'graphs/chart.php';
                    title = 'Monthly Revenue Chart';
                }

                if (url) {
                    // Create a wrapper HTML page that properly includes the chart
                    const wrapperHTML = `
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>${title}</title>
                    <style>
                        body { 
                            margin: 0; 
                            padding: 20px; 
                            font-family: Arial, sans-serif; 
                            background-color: #f8f9fa;
                            text-align: center;
                        }
                        .chart-container {
                            width: 90%;
                            height: 85vh;
                            margin: 0 auto;
                            background-color: white;
                            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                            border-radius: 8px;
                            padding: 20px;
                            overflow: hidden;
                        }
                        h2 {
                            color: #0A3981;
                            margin-bottom: 20px;
                            font-size: 24px;
                        }
                        iframe {
                            width: 100%;
                            height: 100%;
                            border: none;
                        }
                        .print-btn {
                            margin-top: 20px;
                            padding: 8px 15px;
                            background-color: #0A3981;
                            color: white;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 14px;
                        }
                        .print-btn:hover {
                            background-color: #072a5f;
                        }
                    </style>
                </head>
                <body>
                    <h2>${title}</h2>
                    <div class="chart-container">
                        <iframe src="${url}?t=${new Date().getTime()}" frameborder="0"></iframe>
                    </div>
                    <button class="print-btn" onclick="window.print()">Print Chart</button>
                </body>
                </html>
                `;

                    // Open a new window and write the wrapper HTML to it
                    const newWindow = window.open('', '_blank', 'width=1000,height=800,resizable=yes');
                    if (newWindow) {
                        newWindow.document.write(wrapperHTML);
                        newWindow.document.close(); // Important to finish the document
                        newWindow.focus();
                    } else {
                        alert('Unable to open new window. Please check your popup blocker settings.');
                    }
                }
            });
        });
    </script>
</body>

</html>