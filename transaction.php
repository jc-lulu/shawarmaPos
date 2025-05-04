<?php
include('server_side/check_session.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('header/header.php') ?>
    <title>Transactions</title>
    <link href="styles/transaction.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-4 page-container">
            <h1 class="page-title">TRANSACTIONS</h1>

            <div class="row mb-4">
                <div class="col-md-12 d-flex justify-content-end gap-3">
                    <?php
                    if ($_SESSION['user_role'] == 1) {
                        echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionInModal">
                                <i class="fa-solid fa-plus me-2"></i>Request In Item
                              </button>';
                        echo '<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#transactionOutModal">
                                <i class="fa-solid fa-minus me-2"></i>Request Out Item
                              </button>';
                    }

                    ?>
                </div>
            </div>

            <!-- Filters section -->
            <div class="filters-section mb-4">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" id="searchProduct" placeholder="Search products...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select class="form-select" id="filterType">
                            <option value="">All Types</option>
                            <option value="In">In</option>
                            <option value="Out">Out</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select class="form-select" id="filterStatus">
                            <option value="">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-secondary w-100" id="resetFilters">
                            <i class="fa-solid fa-rotate me-1"></i>Reset
                        </button>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary w-100" id="viewArchived">
                            <i class="fa-solid fa-inbox me-1"></i>View Archived
                        </button>
                    </div>
                </div>
            </div>

            <div class="data-table">
                <table id="transactionsTable" class="table table-hover text-center display responsive nowrap"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>PRODUCT NAME</th>
                            <th>TYPE</th>
                            <th>QUANTITY</th>
                            <th>STATUS</th>
                            <th>REQUEST DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaction Modals -->
    <!-- IN Transaction Modal -->
    <div class="modal fade" id="transactionInModal" tabindex="-1" aria-labelledby="transactionInModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="transactionInModalLabel">
                        <i class="fas fa-file-import me-2"></i>Request Inventory Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="requestInItemForm">
                        <div class="mb-4">
                            <label for="productIn_item" class="form-label fw-bold">
                                <i class="fas fa-tag me-2 text-primary"></i>Product Name
                            </label>
                            <input type="text" class="form-control form-control-lg border-0 bg-light"
                                id="productIn_item" name="productIn_item" placeholder="Enter product name"
                                maxlength="20" minlength="5" required>
                        </div>

                        <!-- <div class="mb-4">
                            <label for="productIn_type" class="form-label fw-bold">
                                <i class="fas fa-layer-group me-2 text-primary"></i>Product Type
                            </label>
                            <select class="form-select form-select-lg border-0 bg-light" id="productIn_type"
                                name="productIn_type" required>
                                <option value="" selected disabled>Select product type</option>
                                <option value="1">Product type 1</option>
                                <option value="2">Product type 2</option>
                                <option value="3">Product type 3</option>
                            </select>
                        </div> -->

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="productIn_quantity" class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-2 text-primary"></i>Quantity
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg border-0 bg-light"
                                        id="productIn_quantity" name="productIn_quantity" min="1" placeholder="0"
                                        max="5000" required>
                                    <span class="input-group-text bg-light border-0">units</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="dateOfIn" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Request Date
                                </label>
                                <input type="date" class="form-control form-control-lg border-0 bg-light" id="dateOfIn"
                                    name="dateOfIn" min="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="requestInNotes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-2 text-primary"></i>Notes (Optional)
                            </label>
                            <textarea class="form-control form-control-lg border-0 bg-light" id="requestInNotes"
                                name="requestInNotes" rows="3"
                                placeholder="Add any additional information about this request..."></textarea>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Your request will be reviewed by an administrator before being approved.
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-lg px-4" id="submitRequestInBtn">
                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this modal structure at the end of your body tag -->
    <div class="modal fade" id="archivedModal" tabindex="-1" aria-labelledby="archivedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archivedModalLabel">Archived Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Product Name</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="archivedTransactions">
                                <!-- Data will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- OUT Transaction Modal - Enhanced with Product Selection -->
    <div class="modal fade" id="transactionOutModal" tabindex="-1" aria-labelledby="transactionOutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title" id="transactionOutModalLabel">
                        <i class="fas fa-file-export me-2"></i>Request Out Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Step 1: Select Product Section -->
                    <div id="selectProductSection">
                        <h5 class="mb-3 text-danger"><i class="fas fa-search me-2"></i>Select a Product</h5>

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control form-control-lg" id="productSearchInput"
                                    placeholder="Search products by name..." aria-label="Search products">
                            </div>
                        </div>

                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-hover" id="productSelectionTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Available Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productListBody">
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="noProductsMessage" class="alert alert-info d-none">
                            <i class="fas fa-info-circle me-2"></i>No products found. Try a different search term.
                        </div>
                    </div>

                    <!-- Step 2: Request Form Section -->
                    <div id="requestFormSection" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="backToProductsBtn">
                                <i class="fas fa-arrow-left me-2"></i>Back to Products
                            </button>
                            <h5 class="mb-0 text-danger">Request Form</h5>
                        </div>

                        <form id="outItemForm">
                            <input type="hidden" id="productOut_id" name="productOut_id">

                            <div class="mb-4">
                                <label for="productOut_item" class="form-label fw-bold">
                                    <i class="fas fa-tag me-2 text-danger"></i>Product Name
                                </label>
                                <input type="text" class="form-control form-control-lg border-0 bg-light"
                                    id="productOut_item" name="productOut_item" readonly required>
                            </div>

                            <div class="mb-4" id="quantityContainer">
                                <label for="productOut_quantity" class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-2 text-danger"></i>Quantity
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg border-0 bg-light"
                                        id="productOut_quantity" name="productOut_quantity" min="1" placeholder="0"
                                        required>
                                    <span class="input-group-text bg-light border-0">units</span>
                                </div>
                                <small class="form-text text-muted">Available: <span id="availableQuantity">0</span>
                                    units</small>
                            </div>

                            <div class="mb-4">
                                <label for="dateOfOut" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-2 text-danger"></i>Request Date
                                </label>
                                <input type="date" class="form-control form-control-lg border-0 bg-light" id="dateOfOut"
                                    name="dateOfOut" min="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="requestOutNotes" class="form-label fw-bold">
                                    <i class="fas fa-sticky-note me-2 text-danger"></i>Notes (Optional)
                                </label>
                                <textarea class="form-control form-control-lg border-0 bg-light" id="requestOutNotes"
                                    name="requestOutNotes" rows="3"
                                    placeholder="Add any additional information about this request..."></textarea>
                            </div>

                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Your request to remove items will be reviewed by an administrator.
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger btn-lg px-4 d-none" id="submitRequestOutBtn">
                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT Transaction Modal -->
    <div class="modal fade" id="transactionEditModal" tabindex="-1" aria-labelledby="transactionEditLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #17a2b8; color: white;">
                    <h5 class="modal-title" id="transactionEditLabel">✏️ Edit Transaction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTransactionForm">
                        <div class="mb-3">
                            <label for="edit_transaction_id" class="form-label">Transaction ID</label>
                            <input type="text" class="form-control" id="edit_transaction_id" name="transaction_id"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="edit_product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_quantity" name="quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateTransactionBtn">✔ Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Transaction Details Modal -->
    <div class="modal fade" id="transactionViewModal" tabindex="-1" aria-labelledby="transactionViewLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0dcaf0; color: white;">
                    <h5 class="modal-title" id="transactionViewLabel"><i class="fas fa-info-circle me-2"></i>Transaction
                        Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="transaction-details">
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Transaction ID:</div>
                            <div class="col-7" id="view_transaction_id"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Product Name:</div>
                            <div class="col-7" id="view_product_name"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Type:</div>
                            <div class="col-7" id="view_type"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Quantity:</div>
                            <div class="col-7" id="view_quantity"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Status:</div>
                            <div class="col-7" id="view_status"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Request Date:</div>
                            <div class="col-7" id="view_request_date"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 fw-bold">Notes:</div>
                            <div class="col-7" id="view_notes"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    //Footer
    include("header/footer.php");
    ?>

    <script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $('#viewArchived').on('click', function() {
            loadArchivedTransactions();
            $('#archivedModal').modal('show');
        });

        function loadArchivedTransactions() {
            $.ajax({
                url: 'server_side/get_archived_transactions.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        $.each(data, function(key, transaction) {
                            html += `<tr>
                            <td>${transaction.id}</td>
                             <td>${transaction.productName}</td>
                            <td>${transaction.date}</td>
                            <td>${transaction.type}</td>
                            <td>${parseInt(transaction.total).toFixed(0)}</td>
                            <td>
                                <button class="btn btn-sm btn-success unarchive-btn" 
                                    data-id="${transaction.id}">
                                    <i class="fa-solid fa-box-archive"></i> Unarchive
                                </button>
                            </td>
                        </tr>`;
                        });
                    } else {
                        html =
                            '<tr><td colspan="5" class="text-center">No archived transactions found</td></tr>';
                    }
                    $('#archivedTransactions').html(html);

                    // Handle unarchive button clicks
                    $('.unarchive-btn').on('click', function() {
                        const transactionId = $(this).data('id');
                        unarchiveTransaction(transactionId);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading archived transactions:', error);
                    $('#archivedTransactions').html(
                        '<tr><td colspan="5" class="text-center">Error loading data</td></tr>');
                }
            });
        }

        // Unarchive transaction function
        function unarchiveTransaction(transactionId) {
            Swal.fire({
                title: 'Unarchive Transaction?',
                text: "This will restore the transaction to active status.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unarchive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we unarchive the transaction.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: 'server_side/unarchived_transaction.php',
                        type: 'POST',
                        data: {
                            id: transactionId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Success message
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Transaction has been unarchived successfully.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload the archived transactions list
                                    loadArchivedTransactions();

                                    // Reload the main transaction table if it exists
                                    if (typeof loadTransactions === 'function') {
                                        $('#transactionsTable').DataTable().ajax
                                            .reload();
                                    }
                                });
                            } else {
                                // Error message
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message ||
                                        'Failed to unarchive transaction.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error unarchiving transaction:', error);

                            Swal.fire({
                                title: 'Server Error!',
                                text: 'Could not connect to the server. Please try again later.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }
        $('#transactionsTable').DataTable({
            processing: true,
            responsive: true,
            serverSide: false, // Set to true only if implementing server-side processing
            ajax: {
                url: 'server_side/fetch_transactions.php',
                dataSrc: 'data',
                error: function(xhr, error, thrown) {
                    console.error('DataTables Ajax Error:', error, thrown);
                    console.log('Response Text:', xhr.responseText);
                    $('#transactionsTable_processing').html(
                        'Error loading data. Please refresh the page to try again.');
                }
            },
            columns: [{
                    data: 'transactionId'
                },
                {
                    data: 'productName'
                },
                {
                    data: 'type',
                    render: function(data, type, row) {
                        if (data === 'In') {
                            return '<span class="type-in">' + data + '</span>';
                        } else {
                            return '<span class="type-out">' + data + '</span>';
                        }
                    }
                },
                {
                    data: 'quantity'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data === 'Approved') {
                            return '<span class="status-approved">' + data + '</span>';
                        } else if (data === 'Pending') {
                            return '<span class="status-pending">' + data + '</span>';
                        } else {
                            return '<span class="status-rejected">' + data + '</span>';
                        }
                    }
                },
                {
                    data: 'displayDate'
                },
                {
                    data: 'transactionId',
                    orderable: false,
                    render: function(data, type, row) {
                        return '<div class="action-buttons-container">' +
                            '<button class="btn btn-info btn-sm btn-action view-btn me-1" data-id="' +
                            data + '" data-bs-toggle="tooltip" title="View Details">' +
                            '<i class="fas fa-eye"></i>' +
                            '</button>' +
                            '<button class="btn btn-secondary btn-sm btn-action archive-btn me-1" data-id="' +
                            data + '" data-bs-toggle="tooltip" title="Archive Transaction">' +
                            '<i class="fas fa-archive"></i>' +
                            '</button>' +
                            '<button class="btn btn-danger btn-sm btn-action delete-btn" data-id="' +
                            data + '" data-bs-toggle="tooltip" title="Delete Transaction">' +
                            '<i class="fas fa-trash-alt"></i>' +
                            '</button>' +
                            '</div>';
                    }
                }
            ],
            order: [
                [0, 'desc']
            ],
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            language: {
                emptyTable: "No transactions found",
                zeroRecords: "No matching transactions found",
                info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                infoEmpty: "Showing 0 to 0 of 0 transactions",
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });
        // Search and filter functionality
        $("#searchProduct, #filterType, #filterStatus").on("keyup change", function() {
            let table = $('#transactionsTable').DataTable();
            table.draw();
        });

        // Custom search function for DataTable
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            let search = $("#searchProduct").val().toLowerCase();
            let type = $("#filterType").val();
            let status = $("#filterStatus").val();

            let productName = data[1].toLowerCase();
            let rowType = data[2];
            let rowStatus = data[4];

            let matchSearch = search === "" || productName.includes(search);
            let matchType = type === "" || rowType.includes(type);
            let matchStatus = status === "" || rowStatus.includes(status);

            return matchSearch && matchType && matchStatus;
        });

        // Reset filters
        $("#resetFilters").click(function() {
            $("#searchProduct").val("");
            $("#filterType").val("");
            $("#filterStatus").val("");
            $('#transactionsTable').DataTable().search("").draw();
        });

        // Set default date to today for request in form
        const today = new Date().toISOString().split('T')[0];
        $('#dateOfIn').val(today);

        // Add animation when opening the modal
        $('#transactionInModal').on('show.bs.modal', function() {
            setTimeout(function() {
                $('#productIn_item').focus();
            }, 500);
        });

        // Handle form inputs validation with visual feedback
        $('#requestInItemForm input, #requestInItemForm select, #requestInItemForm textarea').on('input change',
            function() {
                if (this.checkValidity()) {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                } else if ($(this).val()) {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(this).removeClass('is-valid is-invalid');
                }
            });

        // Handle Request In Item submission
        $('#submitRequestInBtn').click(function() {
            // Enhanced validation with visual feedback
            let isValid = true;
            $('#requestInItemForm input:required, #requestInItemForm select:required').each(function() {
                if (!this.checkValidity()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });

            if (!isValid) {
                // Shake effect for validation errors
                $('.modal-content').addClass('animate__animated animate__shakeX');
                setTimeout(function() {
                    $('.modal-content').removeClass('animate__animated animate__shakeX');
                }, 500);
                return;
            }

            // Get form data
            const formData = {
                productIn_item: $('#productIn_item').val(),
                productIn_quantity: $('#productIn_quantity').val(),
                productIn_type: $('#productIn_type').val(),
                dateOfIn: $('#dateOfIn').val(),
                requestInNotes: $('#requestInNotes').val()
            };

            // Show loading state
            const saveBtn = $(this);
            const originalText = saveBtn.html();
            saveBtn.html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...'
            );
            saveBtn.addClass('disabled').prop('disabled', true);

            // Send AJAX request
            $.ajax({
                url: 'server_side/requestInItem.php',
                type: 'POST',
                data: formData,
                dataType: 'json', // Specify that we expect JSON
                success: function(response) {
                    saveBtn.html(originalText);
                    saveBtn.removeClass('disabled').prop('disabled', false);

                    // Check JSON response status property instead of trimming
                    if (response.status === 'success') {
                        // Show success message
                        $('#transactionInModal').modal('hide');

                        Swal.fire({
                            title: 'Request Submitted!',
                            text: 'Your inventory request has been submitted for approval.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(function() {
                            // Reload with fade effect
                            $('body').fadeOut(500, function() {
                                location.reload();
                            });
                        });

                        // Reset form
                        $('#requestInItemForm')[0].reset();
                        $('#requestInItemForm input, #requestInItemForm select, #requestInItemForm textarea')
                            .removeClass('is-valid is-invalid');
                        $('#dateOfIn').val(today);
                    } else {
                        // Show error message
                        Swal.fire({
                            title: 'Error',
                            text: response.message ||
                                'Failed to submit your request. Please try again.',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    saveBtn.html(originalText);
                    saveBtn.removeClass('disabled').prop('disabled', false);

                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);

                    Swal.fire({
                        title: 'Server Error',
                        text: 'Unable to connect to the server. Please check your connection and try again.',
                        icon: 'error'
                    });
                }
            });
        });

        $('#dateOfOut').val(today);

        // Product selection for Request Out
        $('#transactionOutModal').on('show.bs.modal', function() {
            // Reset the modal to show product selection first
            showProductSelectionView();

            // Load products
            loadProducts();
        });

        // Back button functionality
        $('#backToProductsBtn').click(function() {
            showProductSelectionView();
        });

        // Product search functionality
        $('#productSearchInput').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterProductTable(searchTerm);
        });

        // Quantity validation
        $('#productOut_quantity').on('input', function() {
            const availableQty = parseInt($('#availableQuantity').text());
            const requestedQty = parseInt($(this).val());

            if (requestedQty > availableQty) {
                $(this).addClass('is-invalid');
                $('#quantityContainer .invalid-feedback').remove();
                $('#quantityContainer').append(
                    '<div class="invalid-feedback">Quantity cannot exceed available amount</div>'
                );
            } else {
                $(this).removeClass('is-invalid');
                $('#quantityContainer .invalid-feedback').remove();
            }
        });

        $('#submitRequestOutBtn').click(function() {
            // Enhanced validation with visual feedback
            let isValid = true;
            $('#outItemForm input:required').each(function() {
                if (!this.checkValidity()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });

            // Check quantity specifically
            const availableQty = parseInt($('#availableQuantity').text());
            const requestedQty = parseInt($('#productOut_quantity').val());

            if (requestedQty > availableQty) {
                $('#productOut_quantity').addClass('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Show loading state
            const saveBtn = $(this);
            const originalText = saveBtn.html();
            saveBtn.html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...'
            );
            saveBtn.addClass('disabled').prop('disabled', true);

            // Get form data


            const formData = {
                productId: $('#productOut_id').val(), // Changed from product_id
                productName: $('#productOut_item').val(), // Changed from product_name
                quantity: $('#productOut_quantity').val(), // This one is fine
                date: $('#dateOfOut').val(), // This one is fine
                notes: $('#requestOutNotes').val() // This one is fine
            };

            // Send AJAX request
            $.ajax({
                url: 'server_side/requestOutItem.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    saveBtn.html(originalText);
                    saveBtn.removeClass('disabled').prop('disabled', false);

                    if (response.status === 'success') {
                        // Show success message
                        $('#transactionOutModal').modal('hide');

                        Swal.fire({
                            title: 'Request Submitted!',
                            text: 'Your inventory request has been submitted for approval.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(function() {
                            // Reload with fade effect
                            $('body').fadeOut(500, function() {
                                location.reload();
                            });
                        });

                        // Reset form
                        $('#outItemForm')[0].reset();
                        $('#outItemForm input').removeClass('is-valid is-invalid');
                    } else {
                        // Show error message
                        Swal.fire({
                            title: 'Error',
                            text: response.message ||
                                'Failed to submit your request. Please try again.',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    saveBtn.html(originalText);
                    saveBtn.removeClass('disabled').prop('disabled', false);

                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);

                    Swal.fire({
                        title: 'Server Error',
                        text: 'Unable to connect to the server. Please check your connection and try again.',
                        icon: 'error'
                    });
                }
            });
        });

        $(document).on('click', '.view-btn', function() {
            $('.tooltip').hide();
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            const table = $('#transactionsTable').DataTable();
            const rowData = table.row(row).data();

            // Populate the view modal
            $('#view_transaction_id').text(rowData.transactionId);
            $('#view_product_name').text(rowData.productName);
            $('#view_type').text(rowData.type);
            $('#view_quantity').text(rowData.quantity);

            // Format status with colored badge
            let statusHtml = '';
            if (rowData.status === 'Approved') {
                statusHtml = '<span class="badge bg-success">Approved</span>';
            } else if (rowData.status === 'Pending') {
                statusHtml = '<span class="badge bg-warning text-dark">Pending</span>';
            } else {
                statusHtml = '<span class="badge bg-danger">Rejected</span>';
            }
            $('#view_status').html(statusHtml);

            $('#view_request_date').text(rowData.displayDate);
            $('#view_notes').text(rowData.notes || 'No notes available');

            // Show modal
            $('#transactionViewModal').modal('show');
        });

        // Update transaction
        $('#updateTransactionBtn').click(function() {
            // Validate form
            if (!$('#editTransactionForm')[0].checkValidity()) {
                $('#editTransactionForm')[0].reportValidity();
                return;
            }

            // Get form data
            const formData = {
                transaction_id: $('#edit_transaction_id').val(),
                product_name: $('#edit_product_name').val(),
                quantity: $('#edit_quantity').val(),
                status: $('#edit_status').val()
            };

            // Send AJAX request
            $.ajax({
                url: 'server_side/update_transaction.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#transactionEditModal').modal('hide');

                        Swal.fire({
                            title: 'Success',
                            text: response.message ||
                                'Transaction updated successfully',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload(); // Refresh the page
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message || 'Error updating transaction',
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Server error while updating transaction',
                        icon: 'error'
                    });
                }
            });
        });
    });

    $(document).on('click', '.archive-btn', function() {
        $('.tooltip').hide();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Archive Transaction?',
            text: "Archived transactions will be moved to the archive section.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes, archive it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to archive
                $.ajax({
                    url: 'server_side/archive_transaction.php',
                    type: 'POST',
                    data: {
                        transaction_id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Archived!',
                                text: 'Transaction has been archived.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the table
                                $('#transactionsTable').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message ||
                                'Failed to archive transaction', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Server error while archiving transaction',
                            'error');
                    }
                });
            }
        });
    });

    // Delete transaction
    $(document).on('click', '.delete-btn', function() {
        $('.tooltip').hide();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete Transaction?',
            text: "This action cannot be undone!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete
                $.ajax({
                    url: 'server_side/delete_transaction.php',
                    type: 'POST',
                    data: {
                        transaction_id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Transaction has been deleted.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the table
                                $('#transactionsTable').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message ||
                                'Failed to delete transaction', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Server error while deleting transaction',
                            'error');
                    }
                });
            }
        });
    });

    function loadProducts() {
        console.log("Loading products...");
        $.ajax({
            url: 'server_side/fetchRequestOutItem.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("API response:", response);
                if (response.status === 'success') {
                    renderProductList(response.products);
                } else {
                    $('#productListBody').html(`
                    <tr>
                        <td colspan="3" class="text-center text-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>${response.message || 'Error loading products'}
                        </td>
                    </tr>
                `);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.log("Response text:", xhr.responseText);
                $('#productListBody').html(`
                <tr>
                    <td colspan="3" class="text-center text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>Server error. Unable to load products.
                    </td>
                </tr>
            `);
            }
        });
    }

    function renderProductList(products) {
        console.log("Rendering products:", products);
        if (!products || products.length === 0) {
            $('#productListBody').html(`
            <tr>
                <td colspan="3" class="text-center">
                    <i class="fas fa-info-circle me-2"></i>No products available
                </td>
            </tr>
        `);
            return;
        }

        let html = '';
        products.forEach(product => {
            html += `
            <tr>
                <td>${product.name}</td>
                <td>${product.quantity}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary select-product" 
                            data-id="${product.id}" 
                            data-name="${product.name}" 
                            data-quantity="${product.quantity}">
                        <i class="fas fa-check me-1"></i>Select
                    </button>
                </td>
            </tr>
        `;
        });

        $('#productListBody').html(html);

        // Add click event for product selection
        $('.select-product').click(function() {
            const productId = $(this).data('id');
            const productName = $(this).data('name');
            const availableQty = $(this).data('quantity');

            // Fill the form with selected product data
            $('#productOut_id').val(productId);
            $('#productOut_item').val(productName);
            $('#availableQuantity').text(availableQty);

            // Show the request form section
            showRequestFormView();
        });
    }

    function filterProductTable(searchTerm) {
        const rows = $('#productListBody tr');
        let matchFound = false;

        rows.each(function() {
            const productName = $(this).find('td:first').text().toLowerCase();

            if (productName.includes(searchTerm)) {
                $(this).show();
                matchFound = true;
            } else {
                $(this).hide();
            }
        });

        // Show/hide no products message
        if (matchFound) {
            $('#noProductsMessage').addClass('d-none');
        } else {
            $('#noProductsMessage').removeClass('d-none');
        }
    }

    function showProductSelectionView() {
        $('#selectProductSection').removeClass('d-none');
        $('#requestFormSection').addClass('d-none');
        $('#submitRequestOutBtn').addClass('d-none');
    }

    function showRequestFormView() {
        $('#selectProductSection').addClass('d-none');
        $('#requestFormSection').removeClass('d-none');
        $('#submitRequestOutBtn').removeClass('d-none');
    }
    </script>
</body>

</html>