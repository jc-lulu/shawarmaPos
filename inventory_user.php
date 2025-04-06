<?php
include('server_side/check_session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <?php include("header/header.php"); ?>
    <link href="styles/inventory.css" rel="stylesheet">
    <!-- <style>
        /* Enhanced styling */
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .page-container {
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .page-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 3px solid #ff8c00;
            display: inline-block;
        }

        .data-table {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        /* Button styling */
        .action-btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-add {
            background: linear-gradient(to right, #4e73df, #224abe);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
        }

        .btn-add:hover {
            background: linear-gradient(to right, #3a5bc7, #1a3a9c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
            color: white;
        }

        .btn-out {
            background: linear-gradient(to right, #ff8c00, #e67e00);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
        }

        .btn-out:hover {
            background: linear-gradient(to right, #e67e00, #cc6e00);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3);
            color: white;
        }

        /* Table styling */
        #inventoryTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #inventoryTable thead th {
            background: linear-gradient(to right, #ff8c00, #e67e00);
            color: white;
            font-weight: 500;
            padding: 12px;
            border: none;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        #inventoryTable tbody tr {
            transition: all 0.2s;
        }

        #inventoryTable tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        #inventoryTable td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .type-in {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            min-width: 80px;
        }

        .type-out {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            min-width: 80px;
        }

        /* Action buttons */
        .btn-action {
            margin: 0 3px;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-view {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-view:hover {
            background-color: #218838;
            transform: scale(1.1);
        }

        .btn-edit {
            background-color: #17a2b8;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #138496;
            transform: scale(1.1);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        /* Receipt modal styling */
        .receipt-modal .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .receipt-modal .modal-header {
            background-color: #ff8c00;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 1.2rem;
        }

        .receipt-modal .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #ddd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .receipt-header h4 {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .receipt-body {
            padding: 0 10px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .receipt-item-label {
            font-weight: 600;
            color: #555;
        }

        .receipt-footer {
            border-top: 2px dashed #ddd;
            margin-top: 20px;
            padding-top: 15px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }

        /* Edit modal styling */
        #editItemModal .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        #editItemModal .modal-header {
            background-color: #17a2b8;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        #editItemModal .modal-footer {
            border-top: 1px solid #f0f0f0;
        }

        #editItemForm .form-label {
            font-weight: 500;
            color: #555;
        }

        #editItemForm .form-control,
        #editItemForm .form-select {
            border-radius: 6px;
            padding: 0.6rem 0.75rem;
            border: 1px solid #ddd;
            transition: all 0.2s;
        }

        #editItemForm .form-control:focus,
        #editItemForm .form-select:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
        }

        /* DataTable buttons styling */
        .dt-buttons .btn {
            margin-right: 8px;
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dt-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            border: 1px solid #ddd;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #ff8c00;
            box-shadow: 0 0 0 0.25rem rgba(255, 140, 0, 0.25);
            outline: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .action-buttons-container {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .data-table {
                padding: 1rem;
            }
        }
    </style> -->
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-4 page-container">
            <h1 class="page-title">INVENTORY MANAGEMENT</h1>

            <div class="row mb-4">
                <?php
                $role = $_SESSION['user_role'];

                if ($role == 0) {
                    echo '<div class="col-md-12 d-flex justify-content-end gap-3 mb-3">
                    <button class="btn btn-add action-btn" data-bs-toggle="modal" data-bs-target="#transactionInModal">
                        <i class="fa-solid fa-plus me-2"></i>Add New Item
                    </button>
                    <button class="btn btn-out action-btn" data-bs-toggle="modal" data-bs-target="#transactionOutModal">
                        <i class="fa-solid fa-minus me-2"></i>Out Item
                    </button>
                    </div>';
                }
                ?>
            </div>
            <div class="data-table">
                <table id="inventoryTable" class="table table-hover text-center display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('cedric_dbConnection.php');

                        $query = "SELECT productId, productName, type, quantity, transactionStatus, dateOfIn, dateOfOut FROM inventory";
                        $result = $connection->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {


                                echo "<tr>";
                                echo "<td>" . $row['productId'] . "</td>";
                                echo "<td>" . $row['productName'] . "</td>";

                                // Display type
                                if ($row['type'] == 0) {
                                    echo "<td><span class='type-in'>IN</span></td>";
                                } else {
                                    echo "<td><span class='type-out'>OUT</span></td>";
                                }

                                echo "<td>" . $row['quantity'] . "</td>";

                                // Store dates in data attributes for the modal
                                $dateIn = $row['dateOfIn'] ? date('Y-m-d', strtotime($row['dateOfIn'])) : 'N/A';
                                $dateOut = ($row['dateOfOut'] && $row['dateOfOut'] != '0000-00-00') ?
                                    date('Y-m-d', strtotime($row['dateOfOut'])) : 'N/A';
                                $status = $row['transactionStatus'];

                                // Action buttons
                                echo "<td class='action-buttons-container'>";
                                echo "<button class='btn btn-view btn-action view-details-btn' 
                                    data-id='" . $row['productId'] . "'
                                    data-name='" . $row['productName'] . "'
                                    data-type='" . ($row['type'] == 0 ? 'IN' : 'OUT') . "'
                                    data-quantity='" . $row['quantity'] . "'
                                    data-status='" . $status . "'
                                    data-date-in='" . $dateIn . "'
                                    data-date-out='" . $dateOut . "'
                                    data-bs-toggle='tooltip' 
                                    title='View Details'>
                                    <i class='fas fa-receipt'></i>
                                </button>";

                                // Add edit and delete buttons for admins
                                $role = $_SESSION['user_role'];

                                if ($role == 0) {
                                    echo " <button class='btn btn-edit btn-action edit-btn' data-id='" . $row['productId'] . "' data-bs-toggle='tooltip' title='Edit'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='btn btn-delete btn-action delete-btn' data-id='" . $row['productId'] . "' data-bs-toggle='tooltip' title='Delete'>
                                        <i class='fas fa-trash'></i>
                                    </button>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No inventory data found</td></tr>";
                        }

                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal fade receipt-modal" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="receipt-header">
                        <h4>SHAWARMA POS SYSTEM</h4>
                        <p>Inventory Item Receipt</p>
                    </div>
                    <div class="receipt-body">
                        <div class="receipt-item">
                            <span class="receipt-item-label">Product ID:</span>
                            <span id="modal-product-id"></span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-item-label">Product Name:</span>
                            <span id="modal-product-name"></span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-item-label">Type:</span>
                            <span id="modal-product-type"></span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-item-label">Quantity:</span>
                            <span id="modal-product-quantity"></span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-item-label">Status:</span>
                            <span id="modal-product-status"></span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-item-label">Date In:</span>
                            <span id="modal-date-in"></span>
                        </div>
                        <div class="receipt-item">
                            <span class="receipt-item-label">Date Out:</span>
                            <span id="modal-date-out"></span>
                        </div>
                    </div>
                    <div class="receipt-footer">
                        <p>Generated on: <span id="modal-generated-date"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="print-receipt"><i class="fas fa-print me-2"></i> Print Receipt</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Inventory Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editItemForm">
                        <input type="hidden" id="edit-product-id" name="productId">

                        <div class="mb-3">
                            <label for="edit-product-name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit-product-name" name="productName" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit-product-type" class="form-label">Transaction Type</label>
                            <select class="form-select" id="edit-product-type" name="type" required>
                                <option value="0">IN</option>
                                <option value="1">OUT</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit-product-quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit-product-quantity" name="quantity" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit-product-status" class="form-label">Status</label>
                            <select class="form-select" id="edit-product-status" name="status" required>
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit-date-in" class="form-label">Date In</label>
                            <input type="date" class="form-control" id="edit-date-in" name="dateIn">
                        </div>

                        <div class="mb-3" id="edit-date-out-container">
                            <label for="edit-date-out" class="form-label">Date Out</label>
                            <input type="date" class="form-control" id="edit-date-out" name="dateOut">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
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

            // Initialize DataTable with custom buttons
            $('#inventoryTable').DataTable({
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

            // Add custom styling for the buttons container
            $('.dt-buttons').addClass('mb-3');

            // Handle View Details button click
            $(document).on('click', '.view-details-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const type = $(this).data('type');
                const quantity = $(this).data('quantity');
                const status = $(this).data('status');
                const dateIn = $(this).data('date-in');
                const dateOut = $(this).data('date-out');

                // Format current date for receipt
                const now = new Date();
                const formattedDate = now.toLocaleString();

                // Set modal values
                $('#modal-product-id').text(id);
                $('#modal-product-name').text(name);
                $('#modal-product-type').text(type);
                $('#modal-product-quantity').text(quantity);
                $('#modal-product-status').text(status);
                $('#modal-date-in').text(dateIn);
                $('#modal-date-out').text(dateOut);
                $('#modal-generated-date').text(formattedDate);

                // Highlight the product type
                if (type === 'IN') {
                    $('#modal-product-type').removeClass('type-out').addClass('type-in');
                } else {
                    $('#modal-product-type').removeClass('type-in').addClass('type-out');
                }

                // Open modal
                $('#productDetailsModal').modal('show');
            });

            // Handle print button click
            $('#print-receipt').on('click', function() {
                const modalContent = document.querySelector('.receipt-modal .modal-content').innerHTML;
                const printWindow = window.open('', '_blank');

                printWindow.document.write(`
                <html>
                <head>
                    <title>Inventory Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
                        .receipt-header { text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 10px; margin-bottom: 15px; }
                        .receipt-body { padding: 0 15px; }
                        .receipt-item { display: flex; justify-content: space-between; margin-bottom: 8px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px; }
                        .receipt-item-label { font-weight: bold; color: #555; }
                        .receipt-footer { border-top: 2px dashed #ddd; margin-top: 15px; padding-top: 10px; text-align: center; font-size: 0.9rem; }
                        .type-in { color: #28a745; font-weight: bold; }
                        .type-out { color: #dc3545; font-weight: bold; }
                        .modal-header, .modal-footer, .btn-close { display: none; }
                    </style>
                </head>
                <body>
                    ${modalContent}
                </body>
                </html>
            `);

                printWindow.document.close();
                printWindow.focus();

                // Give time for styles to load
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            });

            // Handle edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                // Hide any active tooltips
                $('.tooltip').hide();

                // Fetch item details for editing
                $.ajax({
                    url: 'server_side/get_inventory_item.php',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            const item = data.item;

                            // Populate the edit form
                            $('#edit-product-id').val(item.productId);
                            $('#edit-product-name').val(item.productName);
                            $('#edit-product-type').val(item.type);
                            $('#edit-product-quantity').val(item.quantity);
                            $('#edit-product-status').val(item.transactionStatus);

                            // Format dates for form inputs
                            if (item.dateOfIn) {
                                $('#edit-date-in').val(item.dateOfIn.substring(0, 10)); // YYYY-MM-DD format
                            }

                            if (item.dateOfOut && item.dateOfOut !== '0000-00-00') {
                                $('#edit-date-out').val(item.dateOfOut.substring(0, 10));
                            } else {
                                $('#edit-date-out').val('');
                            }

                            // Show/hide date out field based on type
                            if (item.type == 0) { // IN
                                $('#edit-date-out-container').hide();
                            } else { // OUT
                                $('#edit-date-out-container').show();
                            }

                            // Open the modal
                            $('#editItemModal').modal('show');
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'Failed to get item details',
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Server error while retrieving item details',
                            icon: 'error'
                        });
                    }
                });
            });

            // Toggle date out field visibility when type changes
            $('#edit-product-type').change(function() {
                if ($(this).val() == 0) { // IN
                    $('#edit-date-out-container').hide();
                } else { // OUT
                    $('#edit-date-out-container').show();
                }
            });

            // Handle save button click
            $('#saveEditBtn').click(function() {
                // Validate form
                if (!$('#editItemForm')[0].checkValidity()) {
                    $('#editItemForm')[0].reportValidity();
                    return;
                }

                // Get form data
                const formData = {
                    productId: $('#edit-product-id').val(),
                    productName: $('#edit-product-name').val(),
                    type: $('#edit-product-type').val(),
                    quantity: $('#edit-product-quantity').val(),
                    status: $('#edit-product-status').val(),
                    dateIn: $('#edit-date-in').val(),
                    dateOut: $('#edit-date-out').val()
                };

                // Send AJAX request
                $.ajax({
                    url: 'server_side/update_inventory.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#editItemModal').modal('hide');

                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload(); // Refresh the page to show updated data
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Server error while updating inventory',
                            icon: 'error'
                        });
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                // Hide any active tooltips
                $('.tooltip').hide();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX delete request
                        $.ajax({
                            url: 'server_side/delete_inventory.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload(); // Refresh the page
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Server error while deleting inventory item',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>