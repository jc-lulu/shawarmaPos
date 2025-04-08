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

            <!-- Stats Summary -->
            <!-- <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-label">Total Orders</div>
                        <div class="stats-value">125</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="border-left-color: #20c997;">
                        <div class="stats-label">Total Revenue</div>
                        <div class="stats-value">$12,450</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card" style="border-left-color: #ffc107;">
                        <div class="stats-label">Avg. Order Value</div>
                        <div class="stats-value">$99.60</div>
                    </div>
                </div>
            </div> -->

            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-export">
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
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="minAmount" placeholder="Min">
                            <span class="input-group-text">to</span>
                            <span class="input-group-text">$</span>
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
                            <th>ITEMS</th>
                            <th>RECEIPT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>105</td>
                            <td><span class="amount-value">$700</span></td>
                            <td>2025-03-31</td>
                            <td>8 items</td>
                            <td><span class="download-badge"><i class="fas fa-download me-1"></i> PDF</span></td>
                            <td>
                                <button class="action-btn btn-view toggle-details" data-order="105" data-bs-toggle="tooltip" title="View Order Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Order">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="order-details-row" id="details-105">
                            <td colspan="6">
                                <div class="order-details">
                                    <div class="order-detail-header">Order Items</div>
                                    <div class="item-row">
                                        <div>Shawarma - Regular</div>
                                        <div>3 × $15</div>
                                        <div>$45</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Shawarma - Large</div>
                                        <div>5 × $25</div>
                                        <div>$125</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Fries - Large</div>
                                        <div>2 × $8</div>
                                        <div>$16</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Soft Drinks</div>
                                        <div>8 × $3</div>
                                        <div>$24</div>
                                    </div>
                                    <div class="item-row" style="font-weight: bold;">
                                        <div>Total</div>
                                        <div></div>
                                        <div>$700</div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>104</td>
                            <td><span class="amount-value">$520</span></td>
                            <td>2025-03-30</td>
                            <td>5 items</td>
                            <td><span class="download-badge"><i class="fas fa-download me-1"></i> PDF</span></td>
                            <td>
                                <button class="action-btn btn-view toggle-details" data-order="104" data-bs-toggle="tooltip" title="View Order Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Order">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="order-details-row" id="details-104">
                            <td colspan="6">
                                <div class="order-details">
                                    <div class="order-detail-header">Order Items</div>
                                    <div class="item-row">
                                        <div>Shawarma - Regular</div>
                                        <div>2 × $15</div>
                                        <div>$30</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Shawarma - Large</div>
                                        <div>4 × $25</div>
                                        <div>$100</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Combo Meal - Premium</div>
                                        <div>3 × $130</div>
                                        <div>$390</div>
                                    </div>
                                    <div class="item-row" style="font-weight: bold;">
                                        <div>Total</div>
                                        <div></div>
                                        <div>$520</div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>103</td>
                            <td><span class="amount-value">$620</span></td>
                            <td>2025-03-27</td>
                            <td>6 items</td>
                            <td><span class="download-badge"><i class="fas fa-download me-1"></i> PDF</span></td>
                            <td>
                                <button class="action-btn btn-view toggle-details" data-order="103" data-bs-toggle="tooltip" title="View Order Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Order">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="order-details-row" id="details-103">
                            <td colspan="6">
                                <div class="order-details">
                                    <div class="order-detail-header">Order Items</div>
                                    <div class="item-row">
                                        <div>Shawarma - Regular</div>
                                        <div>4 × $15</div>
                                        <div>$60</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Shawarma - Large</div>
                                        <div>7 × $25</div>
                                        <div>$175</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Combo Meal - Family</div>
                                        <div>3 × $95</div>
                                        <div>$285</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Soft Drinks - Family Size</div>
                                        <div>2 × $50</div>
                                        <div>$100</div>
                                    </div>
                                    <div class="item-row" style="font-weight: bold;">
                                        <div>Total</div>
                                        <div></div>
                                        <div>$620</div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>102</td>
                            <td><span class="amount-value">$300</span></td>
                            <td>2025-03-28</td>
                            <td>3 items</td>
                            <td><span class="download-badge"><i class="fas fa-download me-1"></i> PDF</span></td>
                            <td>
                                <button class="action-btn btn-view toggle-details" data-order="102" data-bs-toggle="tooltip" title="View Order Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Order">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="order-details-row" id="details-102">
                            <td colspan="6">
                                <div class="order-details">
                                    <div class="order-detail-header">Order Items</div>
                                    <div class="item-row">
                                        <div>Party Package - Small</div>
                                        <div>1 × $300</div>
                                        <div>$300</div>
                                    </div>
                                    <div class="item-row" style="font-weight: bold;">
                                        <div>Total</div>
                                        <div></div>
                                        <div>$300</div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>101</td>
                            <td><span class="amount-value">$450</span></td>
                            <td>2025-03-29</td>
                            <td>4 items</td>
                            <td><span class="download-badge"><i class="fas fa-download me-1"></i> PDF</span></td>
                            <td>
                                <button class="action-btn btn-view toggle-details" data-order="101" data-bs-toggle="tooltip" title="View Order Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Order">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="order-details-row" id="details-101">
                            <td colspan="6">
                                <div class="order-details">
                                    <div class="order-detail-header">Order Items</div>
                                    <div class="item-row">
                                        <div>Shawarma - Premium</div>
                                        <div>6 × $35</div>
                                        <div>$210</div>
                                    </div>
                                    <div class="item-row">
                                        <div>Side Dishes - Assorted</div>
                                        <div>4 × $60</div>
                                        <div>$240</div>
                                    </div>
                                    <div class="item-row" style="font-weight: bold;">
                                        <div>Total</div>
                                        <div></div>
                                        <div>$450</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Hide all order details initially
            $(".order-details").hide();
            $(".order-details-row").hide();

            // Toggle order details
            $(".toggle-details").click(function() {
                // Hide tooltips when clicking
                $('.tooltip').hide();
                
                let orderId = $(this).data("order");
                $("#details-" + orderId).toggle();
                $("#details-" + orderId + " .order-details").slideToggle(300);
                
                // Change icon based on visibility
                if ($("#details-" + orderId).is(":visible")) {
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                    $(this).attr('title', 'Hide Order Details');
                } else {
                    $(this).html('<i class="fas fa-eye"></i>');
                    $(this).attr('title', 'View Order Details');
                }
                
                // Update tooltip
                $(this).tooltip('dispose');
                new bootstrap.Tooltip($(this)[0]);
            });

            // Initialize DataTable
            $('#orderHistoryTable').DataTable({
                responsive: true,
                dom: '<"dt-buttons"B><"clear">lfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-outline-secondary btn-sm me-1',
                        text: '<i class="fas fa-copy me-1"></i> Copy',
                        titleAttr: 'Copy to clipboard'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-outline-success btn-sm me-1',
                        text: '<i class="fas fa-file-csv me-1"></i> CSV',
                        titleAttr: 'Export as CSV'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-outline-primary btn-sm me-1',
                        text: '<i class="fas fa-file-excel me-1"></i> Excel',
                        titleAttr: 'Export as Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-outline-danger btn-sm me-1',
                        text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                        titleAttr: 'Export as PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-dark btn-sm',
                        text: '<i class="fas fa-print me-1"></i> Print',
                        titleAttr: 'Print table'
                    }
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [
                    { targets: [5], orderable: false },
                    { targets: '_all', orderable: true }
                ],
            });

            // Custom styling for the buttons container
            $('.dt-buttons').addClass('mb-3');

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

            // Filter functionality
            $("#applyFilters").click(function() {
                let startDate = $("#startDate").val();
                let endDate = $("#endDate").val();
                let minAmount = $("#minAmount").val();
                let maxAmount = $("#maxAmount").val();
                let search = $("#searchOrders").val().toLowerCase();
                
                let table = $('#orderHistoryTable').DataTable();
                
                // Clear existing search
                table.search('').columns().search('').draw();
                
                // Apply general search if provided
                if (search) {
                    table.search(search).draw();
                }
                
                // Custom filtering for date and amount range
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    // Format: data[0] = Order ID, data[1] = Total Cost, data[2] = Date
                    
                    let rowDate = new Date(data[2]);
                    let rowAmount = parseFloat(data[1].replace(/[^\d.-]/g, ''));
                    
                    // Default match state
                    let matchDate = true;
                    let matchAmount = true;
                    
                    // Date filtering if both dates are provided
                    if (startDate && endDate) {
                        let start = new Date(startDate);
                        let end = new Date(endDate);
                        // Adjust end date to include the entire day
                        end.setHours(23, 59, 59, 999);
                        matchDate = rowDate >= start && rowDate <= end;
                    }
                    
                    // Amount filtering
                    if (minAmount && !isNaN(parseFloat(minAmount))) {
                        matchAmount = matchAmount && rowAmount >= parseFloat(minAmount);
                    }
                    
                    if (maxAmount && !isNaN(parseFloat(maxAmount))) {
                        matchAmount = matchAmount && rowAmount <= parseFloat(maxAmount);
                    }
                    
                    return matchDate && matchAmount;
                });
                
                table.draw();
                
                // Remove the custom search function after drawing
                $.fn.dataTable.ext.search.pop();
            });
            
            // Reset filters
            $("#resetFilters").click(function() {
                setDefaultDates();
                $("#minAmount").val("");
                $("#maxAmount").val("");
                $("#searchOrders").val("");
                $('#orderHistoryTable').DataTable().search("").columns().search('').draw();
            });
            
            // Apply filters on page load
            $("#applyFilters").trigger("click");
            
            // Handle download badge
            $(".download-badge").click(function() {
                // Here you would implement the actual download functionality
                let orderId = $(this).closest('tr').find('td:first').text();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Receipt Downloaded',
                    text: `Receipt for Order #${orderId} has been downloaded`,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
            
            // Print functionality
            $(".btn-print").click(function() {
                // Hide tooltips
                $('.tooltip').hide();
                
                let orderId = $(this).closest('tr').find('td:first').text();
                
                Swal.fire({
                    icon: 'info',
                    title: 'Printing Order',
                    text: `Preparing Order #${orderId} for printing...`,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>
</body>

</html>