<?php
include('server_side/check_session.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <?php include('header/header.php') ?>
    <link href="styles/orderedHistory.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container-fluid py-4 page-container">
            <h1 class="page-title">ORDER HISTORY</h1>

            <!-- Stats Summary -Will be populated dynamically -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-label">Total Orders</div>
                        <div class="stats-value" id="totalOrders">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="border-left-color: #20c997;">
                        <div class="stats-label">Total Revenue</div>
                        <div class="stats-value" id="totalRevenue">₱0.00</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="border-left-color: #ffc107;">
                        <div class="stats-label">Avg. Order Value</div>
                        <div class="stats-value" id="avgOrderValue">₱0.00</div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-export" id="exportOrders">
                        <i class="fa-solid fa-file-export me-2"></i>Export Orders
                    </button>
                </div>
            </div>

            <!-- Filters section -->
            <div class="filters-section mb-4">
                <div class="row">
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="date-range-picker">
                            <input type="date" class="form-control" id="startDate">
                            <span>to</span>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label">Amount Range</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="minAmount" placeholder="Min">
                            <span class="input-group-text">to</span>
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="maxAmount" placeholder="Max">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchOrders" placeholder="Search by order ID...">
                    </div>
                    <div class="col-12 mt-2">
                        <button class="btn btn-filter me-2" id="applyFilters">
                            <i class="fa-solid fa-filter me-1"></i>Apply Filters
                        </button>
                        <button class="btn btn-secondary" id="resetFilters">
                            <i class="fa-solid fa-rotate me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="data-table">
                <table id="orderHistoryTable" class="table table-hover text-center display responsive nowrap">
                    <thead>
                        <tr>
                            <th>ORDER ID</th>
                            <th>TOTAL COST</th>
                            <th>DATE</th>
                            <th>TIME</th>
                            <th>ITEMS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Order data will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Receipt Print Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Order Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="receiptContent" class="p-3">
                        <!-- Receipt content will be inserted here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printReceipt">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            const initTooltips = () => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            };

            initTooltips();

            // Load order history data

            function loadOrderHistory() {
                $.ajax({
                    url: 'server_side/fetchOrderHistory.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // First destroy any existing table
                        if ($.fn.dataTable.isDataTable('#orderHistoryTable')) {
                            $('#orderHistoryTable').DataTable().destroy();
                        }

                        // Initialize DataTable
                        const table = $('#orderHistoryTable').DataTable({
                            data: data,
                            columns: [{
                                    data: 'orderId'
                                },
                                {
                                    data: 'totalCost',
                                    render: function(data) {
                                        return '₱' + parseFloat(data).toFixed(2);
                                    }
                                },
                                {
                                    data: 'dateOfOrder'
                                },
                                {
                                    data: 'timeOfOrder'
                                },
                                {
                                    data: 'itemCount',
                                    render: function(data) {
                                        return data + ' item' + (data != 1 ? 's' : '');
                                    }
                                },
                                {
                                    data: 'orderId',
                                    render: function(data) {
                                        return `
                                    <button class="action-btn btn-view view-details" data-order="${data}" data-bs-toggle="tooltip" title="View Order Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn btn-print print-receipt" data-order="${data}" data-bs-toggle="tooltip" title="Print Receipt">
                                        <i class="fas fa-print"></i>
                                    </button>
                                `;
                                    }
                                }
                            ],
                            responsive: true,
                            dom: '<"dt-buttons"B><"clear">lfrtip',
                            buttons: [
                                // Leave your buttons as they are
                            ],
                            pageLength: 10,
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            order: [
                                [2, 'desc'],
                                [3, 'desc'] // Order by date then time
                            ],
                            columnDefs: [{
                                    targets: [5],
                                    orderable: false
                                },
                                {
                                    targets: '_all',
                                    orderable: true
                                }
                            ],
                            drawCallback: function() {
                                initTooltips();

                                // Calculate stats
                                if (data.length > 0) {
                                    const totalOrders = data.length;
                                    const totalRevenue = data.reduce((sum, order) => sum + parseFloat(order.totalCost), 0);
                                    const avgOrderValue = totalRevenue / totalOrders;

                                    $("#totalOrders").text(totalOrders);
                                    $("#totalRevenue").text('₱' + totalRevenue.toFixed(2));
                                    $("#avgOrderValue").text('₱' + avgOrderValue.toFixed(2));
                                }
                            }
                        });

                        // Custom styling for the buttons container
                        $('.dt-buttons').addClass('mb-3');

                        // View order details
                        $('#orderHistoryTable tbody').on('click', '.view-details', function() {
                            const orderId = $(this).data('order');
                            viewOrderDetails(orderId);
                        });

                        // Print receipt
                        $('#orderHistoryTable tbody').on('click', '.print-receipt', function() {
                            const orderId = $(this).data('order');
                            showReceiptModal(orderId);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading order history:", error);
                        console.log(xhr.responseText); // Log the actual error response
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Load Orders',
                            text: 'There was a problem retrieving order history. Please try again later.'
                        });
                    }
                });
            }

            $("#applyFilters").click(function() {
                loadOrderHistory();
            });

            // Reset filters button click
            $("#resetFilters").click(function() {
                setDefaultDates();
                $("#minAmount").val("");
                $("#maxAmount").val("");
                $("#searchOrders").val("");
                loadOrderHistory();
            });

            // View order details
            function viewOrderDetails(orderId) {
                // First check if details row already exists
                const existingRow = $('#details-' + orderId);
                if (existingRow.length > 0) {
                    existingRow.toggle();
                    const btn = $(`button.view-details[data-order="${orderId}"]`);

                    if (existingRow.is(":visible")) {
                        btn.html('<i class="fas fa-eye-slash"></i>');
                        btn.attr('title', 'Hide Order Details').tooltip('dispose').tooltip();
                    } else {
                        btn.html('<i class="fas fa-eye"></i>');
                        btn.attr('title', 'View Order Details').tooltip('dispose').tooltip();
                    }
                    return;
                }

                // If not, fetch the details
                $.ajax({
                    url: 'server_side/fetchOrderItems.php',
                    type: 'GET',
                    data: {
                        orderId: orderId
                    },
                    dataType: 'json',
                    success: function(items) {
                        if (items.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Items Found',
                                text: `No items found for Order #${orderId}`
                            });
                            return;
                        }

                        // Create details HTML
                        let detailsHTML = `
                            <tr class="order-details-row" id="details-${orderId}">
                                <td colspan="6">
                                    <div class="order-details">
                                        <div class="order-detail-header">Order Items</div>
                        `;

                        let totalAmount = 0;
                        items.forEach(item => {
                            detailsHTML += `
                                <div class="item-row">
                                    <div>${item.productName}</div>
                                    <div>${item.Quantity} × ₱${parseFloat(item.productPrice).toFixed(2)}</div>
                                    <div>₱${parseFloat(item.totalPrice).toFixed(2)}</div>
                                </div>
                            `;
                            totalAmount += parseFloat(item.totalPrice);
                        });

                        detailsHTML += `
                                        <div class="item-row" style="font-weight: bold;">
                                            <div>Total</div>
                                            <div></div>
                                            <div>₱${totalAmount.toFixed(2)}</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;

                        // Insert after the current row
                        $(detailsHTML).insertAfter($(`#orderHistoryTable tr:has(button[data-order="${orderId}"])`));

                        // Update button
                        const btn = $(`button.view-details[data-order="${orderId}"]`);
                        btn.html('<i class="fas fa-eye-slash"></i>');
                        btn.attr('title', 'Hide Order Details').tooltip('dispose').tooltip();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading order details:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Load Details',
                            text: 'There was a problem retrieving order details. Please try again later.'
                        });
                    }
                });
            }

            // Show receipt modal
            function showReceiptModal(orderId) {
                // Fetch order data
                $.ajax({
                    url: 'server_side/fetchOrderItems.php',
                    type: 'GET',
                    data: {
                        orderId: orderId
                    },
                    dataType: 'json',
                    success: function(items) {
                        if (items.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Items Found',
                                text: `No items found for Order #${orderId}`
                            });
                            return;
                        }

                        // Get order details from table
                        const orderRow = $(`#orderHistoryTable button[data-order="${orderId}"]`).closest('tr');
                        const date = orderRow.find('td:eq(2)').text();
                        const time = orderRow.find('td:eq(3)').text();

                        // Calculate total
                        let totalAmount = 0;
                        items.forEach(item => {
                            totalAmount += parseFloat(item.totalPrice);
                        });

                        // Create receipt HTML
                        let receiptHTML = `
                            <div class="receipt-print">
                                <div class="receipt-header">
                                    <h2 class="text-center">SHAWARMA POS</h2>
                                    <p class="text-center mb-1">Receipt #${orderId}</p>
                                    <p class="text-center mb-0">${date} - ${time}</p>
                                </div>
                                
                                <div class="receipt-divider my-3"></div>
                                
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

                        items.forEach(item => {
                            receiptHTML += `
                                <tr>
                                    <td class="text-start">${item.productName}</td>
                                    <td class="text-center">${item.Quantity}</td>
                                    <td class="text-end">₱${parseFloat(item.productPrice).toFixed(2)}</td>
                                    <td class="text-end">₱${parseFloat(item.totalPrice).toFixed(2)}</td>
                                </tr>
                            `;
                        });

                        receiptHTML += `
                                    </tbody>
                                </table>
                                
                                <div class="receipt-divider my-3"></div>
                                
                                <div class="receipt-summary">
                                    <div class="d-flex justify-content-between total-row mb-3">
                                        <span><strong>TOTAL</strong></span>
                                        <span><strong>₱${totalAmount.toFixed(2)}</strong></span>
                                    </div>
                                </div>
                                
                                <div class="receipt-footer text-center mt-4">
                                    <p>Thank you for your order!</p>
                                    <p class="mb-0">Please come again</p>
                                </div>
                            </div>
                        `;

                        // Set modal content and show
                        $('#receiptModalLabel').text(`Receipt #${orderId}`);
                        $('#receiptContent').html(receiptHTML);
                        $('#receiptModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading receipt:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Load Receipt',
                            text: 'There was a problem retrieving the receipt. Please try again later.'
                        });
                    }
                });
            }

            // Print receipt
            $('#printReceipt').on('click', function() {
                const printContent = document.getElementById('receiptContent').innerHTML;
                const originalContent = document.body.innerHTML;

                document.body.innerHTML = `
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
                        .receipt-divider {
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
                        .text-end {
                            text-align: right;
                        }
                        .text-center {
                            text-align: center;
                        }
                        .total-row {
                            font-weight: bold;
                            border-top: 1px solid #000;
                            padding-top: 5px;
                        }
                    </style>
                    ${printContent}
                `;

                window.print();
                document.body.innerHTML = originalContent;

                // Reattach event handlers after restoring content
                $(document).ready(function() {
                    initTooltips();
                    loadOrderHistory();
                });
            });

            // Set default dates (last 30 days)
            function setDefaultDates() {
                let today = new Date();
                let thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);

                let formattedOldDate = thirtyDaysAgo.toISOString().split('T')[0];
                let formattedToday = today.toISOString().split('T')[0];

                $("#startDate").val(formattedOldDate);
                $("#endDate").val(formattedToday);
            }

            setDefaultDates();

            // Initialize the order history table
            loadOrderHistory();
        });
    </script>

    <!-- Add Receipt Styling -->
    <style>
        .receipt-print {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #333;
            max-width: 400px;
            margin: 0 auto;
        }

        .receipt-header h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .receipt-divider {
            border-top: 1px dashed #aaa;
        }

        .receipt-items th {
            font-size: 12px;
            color: #666;
            font-weight: normal;
            border-bottom: 1px solid #eee;
            padding: 5px 2px;
        }

        .receipt-items td {
            padding: 8px 2px;
            vertical-align: top;
            font-size: 13px;
        }

        .total-row {
            font-size: 16px;
            font-weight: bold;
            padding-top: 8px;
        }

        .receipt-footer {
            font-size: 13px;
            color: #666;
        }
    </style>
</body>

</html>