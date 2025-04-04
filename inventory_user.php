<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <?php include("header/header.php"); ?>
    <style>
        body {
            background-color: #f9f5d7;
        }

        .data-table {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            margin-left: 5px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            margin: 0 5px;
        }

        table.dataTable thead th {
            background-color: #ff8c00;
            color: white;
            position: sticky;
            top: 0;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }

        .type-in {
            color: #28a745;
            font-weight: bold;
        }

        .type-out {
            color: #dc3545;
            font-weight: bold;
        }

        .dt-buttons button {
            font-weight: bold;
            margin-right: 5px;
        }

        .dt-buttons .buttons-copy {
            background-color: #ff8c00 !important;
            color: white !important;
            border: none !important;

        }

        .dt-buttons .buttons-csv,
        .buttons-excel {
            background-color: #28a745 !important;
            color: white !important;
            border: none !important;

        }

        .dt-buttons .buttons-pdf {
            background-color: #dc3545 !important;
            color: white !important;
            border: none !important;

        }

        .dt-buttons .buttons-print {
            background-color: #17a2b8 !important;
            color: white !important;
            border: none !important;

        }

        div.dt-buttons {
            float: none !important;
        }

        label {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-3 mb-3">
            <h1>INVENTORY</h1>

            <div class="row mb-3">
                <?php
                $role = 0;
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
                            <th>Transaction ID</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <!-- <th>Transaction Status</th> -->
                            <th>Date In</th>
                            <th>Date Out</th>
                            <?php if ($role == 0) echo '<th>Actions</th>'; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('cedric_dbConnection.php');

                        $query = "SELECT transactionId, productName, type, quantity, transactionStatus, dateOfIn, dateOfOut FROM inventory";
                        $result = $connection->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['transactionId'] . "</td>";
                                echo "<td>" . $row['productName'] . "</td>";

                                // Display type
                                if ($row['type'] == 0) {
                                    echo "<td><span class='type-in'>IN</span></td>";
                                } else {
                                    echo "<td><span class='type-out'>OUT</span></td>";
                                }

                                echo "<td>" . $row['quantity'] . "</td>";

                                // Format transaction status with badges
                                $status = $row['transactionStatus'];
                                $statusClass = '';

                                if ($status == 'Completed') {
                                    $statusClass = 'badge-success';
                                } else if ($status == 'Pending') {
                                    $statusClass = 'badge-warning';
                                } else if ($status == 'Cancelled') {
                                    $statusClass = 'badge-danger';
                                } else {
                                    $statusClass = 'badge-info';
                                }

                                // echo "<td><span class='badge {$statusClass}'>{$status}</span></td>";

                                // Format dates or show placeholder if NULL
                                echo "<td>" . ($row['dateOfIn'] ? date('Y-m-d', strtotime($row['dateOfIn'])) : 'N/A') . "</td>";
                                echo "<td>" . ($row['dateOfOut'] ? date('Y-m-d', strtotime($row['dateOfOut'])) : 'N/A') . "</td>";

                                // Add action buttons for admin
                                if ($role == 0) {
                                    echo "<td>
                                        <button class='btn btn-sm btn-info edit-btn' data-id='" . $row['transactionId'] . "'><i class='fas fa-edit'></i></button>
                                        <button class='btn btn-sm btn-danger delete-btn' data-id='" . $row['transactionId'] . "'><i class='fas fa-trash'></i></button>
                                    </td>";
                                }

                                echo "</tr>";
                            }
                        } else {
                            $colspan = $role == 0 ? 8 : 7;
                            echo "<tr><td colspan='{$colspan}'>No inventory data found</td></tr>";
                        }

                        $connection->close();
                        ?>
                    </tbody>
                </table>
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
                    [4, 'desc'],
                    [5, 'desc']
                ], // Order by dateOfIn and dateOfOut descending
                columnDefs: [{
                    targets: [4, 5], // Date columns
                    render: function(data, type, row) {
                        // For sorting purposes, return the original data
                        if (type === 'sort' && data !== 'N/A') {
                            return data;
                        }
                        return data;
                    }
                }]
            });

            // Add custom styling for the buttons container
            $('.dt-buttons').addClass('mb-3');

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