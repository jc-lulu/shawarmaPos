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
                    if ($_SESSION['user_role'] == 1){
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
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filterType">
                            <option value="">All Types</option>
                            <option value="In">In</option>
                            <option value="Out">Out</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
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
                </div>
            </div>

            <div class="data-table">
                <table id="transactionsTable" class="table table-hover text-center display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>PRODUCT NAME</th>
                            <th>TYPE</th>
                            <th>QUANTITY</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example rows with enhanced styling -->
                        <tr>
                            <td>1</td>
                            <td>Product A</td>
                            <td><span class="type-in">In</span></td>
                            <td>10</td>
                            <td><span class="status-approved">Approved</span></td>
                            <td>2025-03-29</td>
                            <td class="action-buttons-container">
                                <button class="btn btn-edit btn-action edit-btn" data-bs-toggle="modal" data-bs-target="#transactionEditModal" data-bs-toggle="tooltip" title="Edit Transaction">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Product B</td>
                            <td><span class="type-out">Out</span></td>
                            <td>20</td>
                            <td><span class="status-pending">Pending</span></td>
                            <td>2025-03-30</td>
                            <td class="action-buttons-container">
                                <button class="btn btn-edit btn-action edit-btn" data-bs-toggle="modal" data-bs-target="#transactionEditModal" data-bs-toggle="tooltip" title="Edit Transaction">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Product C</td>
                            <td><span class="type-out">Out</span></td>
                            <td>5</td>
                            <td><span class="status-rejected">Rejected</span></td>
                            <td>2025-04-01</td>
                            <td class="action-buttons-container">
                                <button class="btn btn-edit btn-action edit-btn" data-bs-toggle="modal" data-bs-target="#transactionEditModal" data-bs-toggle="tooltip" title="Edit Transaction">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Forms -->
    <form method="Post" id="inItemForm"></form>
    <form method="Post" id="outItemForm"></form>

    <!-- Transaction Modals -->
    <!-- IN Transaction Modal -->
    <div class="modal fade" id="transactionInModal" tabindex="-1" aria-labelledby="transactionInLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #28a745; color: white;">
                    <h5 class="modal-title" id="transactionInLabel">📥 Add Stock (In Transaction)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="inItemForm">
                        <div class="mb-3">
                            <label for="productIn_item" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productIn_item" name="productIn_item" required>
                        </div>

                        <div class="mb-3">
                            <label for="productIn_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="productIn_quantity" name="productIn_quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="productIn_type" class="form-label">Type</label>
                            <select class="form-select" id="productIn_type" name="productIn_type" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="type 1">type 1</option>
                                <option value="type 2">type 2</option>
                                <option value="type 3">type 3</option>
                            </select>
                        </div>
                        
                        <input type="hidden" class="form-control DateIn" id="dateIn" name="dateOfIn" value="<?php echo date('Y-m-d'); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="inItemForm" class="btn btn-success">✔ Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- OUT Transaction Modal -->
    <div class="modal fade" id="transactionOutModal" tabindex="-1" aria-labelledby="transactionOutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #fd7e14; color: white;">
                    <h5 class="modal-title" id="transactionOutLabel">📤 Remove Stock (Out Transaction)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="outItemForm">
                        <div class="mb-3">
                            <label for="productOut_item" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productOut_item" name="productOut_item" required>
                        </div>

                        <div class="mb-3">
                            <label for="productOut_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="productOut_quantity" name="productOut_quantity" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="outItemForm" class="btn btn-danger">✔ Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT Transaction Modal -->
    <div class="modal fade" id="transactionEditModal" tabindex="-1" aria-labelledby="transactionEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #17a2b8; color: white;">
                    <h5 class="modal-title" id="transactionEditLabel">✏️ Edit Transaction</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTransactionForm">
                        <div class="mb-3">
                            <label for="edit_transaction_id" class="form-label">Transaction ID</label>
                            <input type="text" class="form-control" id="edit_transaction_id" name="transaction_id" readonly>
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

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize DataTable
            $('#transactionsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                order: [
                    [0, 'desc']
                ],
            });

            // Search and filter functionality
            $("#searchProduct, #filterType, #filterStatus").on("keyup change", function() {
                let search = $("#searchProduct").val().toLowerCase();
                let type = $("#filterType").val();
                let status = $("#filterStatus").val();
                
                let table = $('#transactionsTable').DataTable();
                
                // Custom search function
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    let productName = data[1].toLowerCase();
                    let rowType = data[2];
                    let rowStatus = data[4];
                    
                    let matchSearch = productName.includes(search);
                    let matchType = type === "" || rowType.includes(type);
                    let matchStatus = status === "" || rowStatus.includes(status);
                    
                    return matchSearch && matchType && matchStatus;
                });
                
                table.draw();
                
                // Remove the custom search function after drawing
                $.fn.dataTable.ext.search.pop();
            });
            
            // Reset filters
            $("#resetFilters").click(function() {
                $("#searchProduct").val("");
                $("#filterType").val("");
                $("#filterStatus").val("");
                $('#transactionsTable').DataTable().search("").draw();
            });

            $('#inItemForm').submit(function(event) {
                event.preventDefault();
                var inFormData = new FormData(this);

                $.ajax({
                    url: "server_side/inItem.php",
                    type: "POST",
                    data: inFormData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Product In",
                                text: "The product has been successfully added",
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $("#transactionInModal").modal("hide");
                            $("#inItemForm")[0].reset();
                            // Reload the page after success
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response
                            });
                        }
                    }
                })
            });

            $('#outItemForm').submit(function(event) {
                event.preventDefault();
                var outFormData = new FormData(this);

                $.ajax({
                    url: "server_side/outItem.php",
                    type: "POST",
                    data: outFormData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Product Out",
                                text: "The product has been successfully removed",
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $("#transactionOutModal").modal("hide");
                            $("#outItemForm")[0].reset();
                            // Reload the page after success
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response
                            });
                        }
                    }
                })
            });

            // Edit transaction - populate modal
            $(document).on('click', '.edit-btn', function() {
                $('.tooltip').hide();

                const id = $(this).closest('tr').find('td:first').text();
                const productName = $(this).closest('tr').find('td:eq(1)').text();
                const quantity = $(this).closest('tr').find('td:eq(3)').text();
                const status = $(this).closest('tr').find('td:eq(4)').text().trim();

                $('#edit_transaction_id').val(id);
                $('#edit_product_name').val(productName);
                $('#edit_quantity').val(quantity);
                $('#edit_status').val(status.includes('Pending') ? 'Pending' : 
                                     (status.includes('Approved') ? 'Approved' : 'Rejected'));

                // Open modal
                $('#transactionEditModal').modal('show');
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
                                text: response.message || 'Transaction updated successfully',
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
    </script>
</body>

</html>