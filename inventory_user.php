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
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-3 mb-3">
            <h1>INVENTORY</h1>

            <div class="row mb-3">
                <?php
                $role = $_SESSION['user_role'];

                if ($role == 0) {
                    echo '<div class="col-md-12 d-flex justify-content-end gap-2 mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionInModal"><i class="fa-solid fa-plus m-1"></i>In Item</button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#transactionOutModal"><i class="fa-solid fa-minus m-1"></i>Out Item</button>
                    </div>';
                }
                ?>
            </div>
            <div class="data-table">
                <table id="inventoryTable" class="table table-bordered table-hover text-center display responsive nowrap" style="width:100%">
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
                                echo "<td>
                                    <button class='btn btn-sm btn-success view-details-btn' 
                                        data-id='" . $row['productId'] . "'
                                        data-name='" . $row['productName'] . "'
                                        data-type='" . ($row['type'] == 0 ? 'IN' : 'OUT') . "'
                                        data-quantity='" . $row['quantity'] . "'
                                        data-status='" . $status . "'
                                        data-date-in='" . $dateIn . "'
                                        data-date-out='" . $dateOut . "'>
                                        <i class='fas fa-receipt me-1'></i> View Details
                                    </button>";

                                // Add edit and delete buttons for admins

                                $role = $_SESSION['user_role'];

                                if ($role == 0) {
                                    echo " <button class='btn btn-sm btn-info edit-btn' data-id='" . $row['productId'] . "'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button class='btn btn-sm btn-danger delete-btn' data-id='" . $row['productId'] . "'>
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
                <div class="modal-header bg-light">
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
                    <button type="button" class="btn btn-primary" id="print-receipt"><i class="fas fa-print me-1"></i> Print</button>
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
                // Add modal open or redirect logic here
                alert('Edit transaction ID: ' + id);
            });

            // Handle delete button click
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this inventory transaction?')) {
                    // Add AJAX delete logic here
                    $.ajax({
                        url: 'delete_inventory.php',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            alert('Transaction deleted successfully');
                            location.reload();
                        },
                        error: function() {
                            alert('Error deleting transaction');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>