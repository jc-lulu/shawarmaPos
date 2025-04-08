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
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container-fluid py-4 page-container">
            <h1 class="page-title">REPORTS</h1>

            <!-- Stats Summary Cards -->
            <div class="stats-cards mb-4">
                <div class="stat-card card-sales">
                    <div class="stat-title">Total Sales</div>
                    <div class="stat-value">$3,950</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </div>
                </div>
                <div class="stat-card card-expenses">
                    <div class="stat-title">Total Expenses</div>
                    <div class="stat-value">$1,200</div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-arrow-down"></i> 5% from last month
                    </div>
                </div>
                <div class="stat-card card-profit">
                    <div class="stat-title">Net Profit</div>
                    <div class="stat-value">$2,750</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i> 18% from last month
                    </div>
                </div>
                <div class="stat-card card-inventory">
                    <div class="stat-title">Inventory Value</div>
                    <div class="stat-value">$8,400</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i> 3% from last month
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-export">
                        <i class="fa-solid fa-file-export me-2"></i>Export Report
                    </button>
                </div>
            </div>

            <!-- Filters section -->
            <div class="filters-section mb-4">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="date-range-picker">
                            <input type="date" class="form-control" id="startDate">
                            <span>to</span>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="filterCategory">
                            <option value="">All Categories</option>
                            <option value="Sales">Sales</option>
                            <option value="Expenses">Expenses</option>
                            <option value="Inventory">Inventory</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">All Status</option>
                            <option value="Approved">Approved</option>
                            <option value="Pending">Pending</option>
                            <option value="Disapproved">Disapproved</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchReports" placeholder="Search reports...">
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
                <table id="reportsTable" class="table table-hover text-center display responsive nowrap">
                    <thead>
                        <tr>
                            <th>REPORT ID</th>
                            <th>CATEGORY</th>
                            <th>TOTAL AMOUNT</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>201</td>
                            <td><span class="category-badge category-sales">Sales</span></td>
                            <td><span class="amount-value">$1,200</span></td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td>2025-03-31</td>
                            <td>
                                <button class="action-btn btn-view" data-bs-toggle="tooltip" title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>202</td>
                            <td><span class="category-badge category-expenses">Expenses</span></td>
                            <td><span class="amount-value">$400</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>2025-03-30</td>
                            <td>
                                <button class="action-btn btn-view" data-bs-toggle="tooltip" title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>203</td>
                            <td><span class="category-badge category-inventory">Inventory</span></td>
                            <td><span class="amount-value">$800</span></td>
                            <td><span class="status-badge status-disapproved">Disapproved</span></td>
                            <td>2025-03-29</td>
                            <td>
                                <button class="action-btn btn-view" data-bs-toggle="tooltip" title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>204</td>
                            <td><span class="category-badge category-sales">Sales</span></td>
                            <td><span class="amount-value">$950</span></td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td>2025-03-28</td>
                            <td>
                                <button class="action-btn btn-view" data-bs-toggle="tooltip" title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Add more dummy rows for testing scrolling -->
                        <tr>
                            <td>205</td>
                            <td><span class="category-badge category-inventory">Inventory</span></td>
                            <td><span class="amount-value">$1,250</span></td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td>2025-03-27</td>
                            <td>
                                <button class="action-btn btn-view" data-bs-toggle="tooltip" title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>206</td>
                            <td><span class="category-badge category-expenses">Expenses</span></td>
                            <td><span class="amount-value">$550</span></td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>2025-03-26</td>
                            <td>
                                <button class="action-btn btn-view" data-bs-toggle="tooltip" title="View Report">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-print" data-bs-toggle="tooltip" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
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

            // Initialize DataTable
            $('#reportsTable').DataTable({
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
            });

            // Custom styling for the buttons container
            $('.dt-buttons').addClass('mb-3');

            // Set default dates (current month)
            function setDefaultDates() {
                let today = new Date();
                let firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                
                let formattedFirstDay = firstDay.toISOString().split('T')[0];
                let formattedToday = today.toISOString().split('T')[0];
                
                $("#startDate").val(formattedFirstDay);
                $("#endDate").val(formattedToday);
            }
            
            setDefaultDates();

            // Filter functionality
            $("#applyFilters").click(function() {
                let startDate = $("#startDate").val();
                let endDate = $("#endDate").val();
                let category = $("#filterCategory").val().toLowerCase();
                let status = $("#filterStatus").val().toLowerCase();
                let search = $("#searchReports").val().toLowerCase();
                
                let table = $('#reportsTable').DataTable();
                
                // Clear existing search
                table.search('').columns().search('').draw();
                
                // Apply general search if provided
                if (search) {
                    table.search(search).draw();
                }
                
                // Custom filtering for date, category and status
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    let rowCategory = data[1].toLowerCase();
                    let rowStatus = data[3].toLowerCase();
                    let rowDate = new Date(data[4]);
                    
                    // Default match state
                    let matchCategory = true;
                    let matchStatus = true;
                    let matchDate = true;
                    
                    // Only apply category filter if one is selected
                    if (category) {
                        matchCategory = rowCategory.includes(category);
                    }
                    
                    // Only apply status filter if one is selected
                    if (status) {
                        matchStatus = rowStatus.includes(status);
                    }
                    
                    // Date filtering if both dates are provided
                    if (startDate && endDate) {
                        let start = new Date(startDate);
                        let end = new Date(endDate);
                        // Adjust end date to include the entire day
                        end.setHours(23, 59, 59, 999);
                        matchDate = rowDate >= start && rowDate <= end;
                    }
                    
                    return matchCategory && matchStatus && matchDate;
                });
                
                table.draw();
                
                // Remove the custom search function after drawing
                $.fn.dataTable.ext.search.pop();
            });
            
            // Reset filters
            $("#resetFilters").click(function() {
                setDefaultDates();
                $("#filterCategory").val("");
                $("#filterStatus").val("");
                $("#searchReports").val("");
                $('#reportsTable').DataTable().search("").columns().search('').draw();
            });
            
            // Apply filters on page load
            $("#applyFilters").trigger("click");
            
            // Handle tooltip hiding when clicking action buttons
            $('.action-btn').click(function() {
                $('.tooltip').hide();
            });
        });
    </script>
</body>

</html>