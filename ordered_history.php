<?php
include('server_side/check_session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <?php include("header/header.php"); ?>
    <link href="styles/inventory.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-4 page-container">
            <h1 class="page-title">ORDER HISTORY</h1>

            <div class="data-table">
                <table id="orderHistoryTable" class="table table-hover text-center display responsive nowrap"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>History ID</th>
                            <th>Order ID</th>
                            <th>Total Cost</th>
                            <th>Date of Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('cedric_dbConnection.php');

                        $query = "SELECT historyId, orderId, totalCost, dateOfOrder, timeOfOrder FROM orderedHistory ORDER BY dateOfOrder DESC, timeOfOrder DESC";
                        $result = $connection->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['historyId'] . "</td>";
                                echo "<td>" . $row['orderId'] . "</td>";
                                echo "<td>₱" . number_format($row['totalCost'], 2) . "</td>";
                                echo "<td>" . $row['dateOfOrder'] . "</td>";

                                // Action buttons
                                echo "<td class='action-buttons-container'>";
                                echo "<button class='btn btn-view btn-action view-details-btn' 
                                    data-id='" . $row['orderId'] . "'
                                    data-bs-toggle='tooltip' 
                                    title='View Order Details'>
                                    <i class='fas fa-eye'></i>
                                </button>";

                                echo "<button class='btn btn-print btn-action print-receipt-btn' 
                                    data-id='" . $row['orderId'] . "'
                                    data-bs-toggle='tooltip' 
                                    title='Print Receipt'>
                                    <i class='fas fa-print'></i>
                                </button>";

                                echo "<button class='btn btn-print btn-action archive-btn' 
                                    data-id='" . $row['orderId'] . "'
                                    data-bs-toggle='tooltip' 
                                    title='Archive Receipt'>
                                    <i class='fas fa-box-archive'></i>
                                </button>";

                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No order history data found</td></tr>";
                        }

                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="order-items-container">
                        <!-- Order items will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Print Modal -->
    <div class="modal fade receipt-modal" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel"
        aria-hidden="true">
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

    <?php
    //Footer
    include("header/footer.php");
    ?>

    <script>
        $(document).ready(function () {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize DataTable
            $('#orderHistoryTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                order: [
                    [3, 'desc'],
                    [4, 'desc'] // Order by date then time
                ],
            });

            // Custom styling for the buttons container
            $('.dt-buttons').addClass('mb-3');

            // View order details
            $(document).on('click', '.view-details-btn', function () {
                const orderId = $(this).data('id');

                // Fetch order items from orderedItemHistory
                $.ajax({
                    url: 'server_side/fetchOrderItems.php',
                    type: 'GET',
                    data: {
                        orderId: orderId
                    },
                    dataType: 'json',
                    success: function (items) {
                        if (items.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Items Found',
                                text: `No items found for Order #${orderId}`
                            });
                            return;
                        }

                        // Get order details from the table
                        const orderRow = $(`button[data-id="${orderId}"]`).closest('tr');
                        const date = orderRow.find('td:eq(3)').text();
                        const time = orderRow.find('td:eq(4)').text();
                        const totalCost = orderRow.find('td:eq(2)').text();

                        // Create receipt-style HTML for the modal
                        let receiptHTML = `
                        <div class="receipt-print">
                            <div class="receipt-header">
                                <h2 class="text-center">SHAWARMA POS</h2>
                                <p class="text-center mb-1">Order #${orderId}</p>
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

                        let totalAmount = 0;
                        items.forEach(item => {
                            const itemTotal = parseFloat(item.productPrice) * parseInt(
                                item.Quantity);
                            totalAmount += itemTotal;

                            receiptHTML += `
                            <tr>
                                <td class="text-start">${item.productName}</td>
                                <td class="text-center">${item.Quantity}</td>
                                <td class="text-end">₱${parseFloat(item.productPrice).toFixed(2)}</td>
                                <td class="text-end">₱${itemTotal.toFixed(2)}</td>
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
                                    <span><strong>${totalCost}</strong></span>
                                </div>
                            </div>
                        </div>
                    `;

                        // Update modal content and show
                        $('.order-items-container').html(receiptHTML);
                        $('#orderDetailsModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.error("Error loading order details:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Load Details',
                            text: 'There was a problem retrieving order details. Please try again later.'
                        });
                    }
                });
            });

            // Print receipt
            $(document).on('click', '.print-receipt-btn', function () {
                const orderId = $(this).data('id');

                // Fetch order details
                $.ajax({
                    url: 'server_side/fetchOrderItems.php',
                    type: 'GET',
                    data: {
                        orderId: orderId
                    },
                    dataType: 'json',
                    success: function (items) {
                        if (items.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Items Found',
                                text: `No items found for Order #${orderId}`
                            });
                            return;
                        }

                        // Get order details from table
                        const orderRow = $(`button[data-id="${orderId}"]`).closest('tr');
                        const date = orderRow.find('td:eq(3)').text();
                        const time = orderRow.find('td:eq(4)').text();

                        // Calculate total
                        let totalAmount = 0;
                        items.forEach(item => {
                            totalAmount += parseFloat(item.productPrice) * parseInt(item
                                .Quantity);
                        });

                        // Create receipt HTML
                        let receiptHTML = `
                            <div class="receipt-print">
                            <div class="row">
                                <div class="col-md-3 image-container mb-3 d-flex justify-content-center align-items-center">
                                    <img src="assets/logo.avif" alt="Logo" class="img-fluid" style="max-width: 100px; border-radius: 100%;">
                                </div>
                                <div class="col-md-9 d-flex justify-content-center align-items-center">
                                     <div class="receipt-header">
                                        <h2 class="text-center">SHAWARMA POS</h2>
                                        <p class="text-center mb-1">Receipt #${orderId}</p>
                                        <p class="text-center mb-0">${date} - ${time}</p>
                                    </div>
                                </div>
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
                            const itemTotal = parseFloat(item.productPrice) * parseInt(
                                item.Quantity);
                            receiptHTML += `
                                <tr>
                                    <td class="text-start">${item.productName}</td>
                                    <td class="text-center">${item.Quantity}</td>
                                    <td class="text-end">₱${parseFloat(item.productPrice).toFixed(2)}</td>
                                    <td class="text-end">₱${itemTotal.toFixed(2)}</td>
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
                    error: function (xhr, status, error) {
                        console.error("Error loading receipt:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Load Receipt',
                            text: 'There was a problem retrieving the receipt. Please try again later.'
                        });
                    }
                });
            });

            // Print receipt button
            $('#printReceipt').on('click', function () {
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
                $(document).ready(function () {
                    location.reload();
                });
            });
        });
    </script>

    <!-- style for erceipt -->
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

        .action-buttons-container {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .btn-action {
            padding: 5px 10px;
            border-radius: 4px;
        }

        .btn-view {
            background-color: #007bff;
            color: #fff;
        }

        .btn-print {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
</body>

</html>