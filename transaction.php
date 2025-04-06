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

        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
                <h1>TRANSACTIONS</h1>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionInModal"><i class="fa-solid fa-plus m-1"></i>In Item</button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#transactionInModal"><i class="fa-solid fa-minus m-1"></i>Out Item</button>
                </div>
            </div>

            <div class="data-table">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <tr>
                            <td>2</td>
                            <td>Product B</td>
                            <td>Out</td>
                            <td>20</td>
                            <td>Pending</td>
                            <td>2025-03-30</td>
                            <td>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transactionEditModal">✏️ Edit</button>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionInLabel">📥 Add Stock (In Transaction)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" placeholder="Product Name" form="inItemForm" name="productIn_item">
                    <input type="number" class="form-control mb-2" placeholder="Quantity" form="inItemForm" name="productIn_quantity">
                    <input type="date" class="form-control mb-2 DateIn" id="dateIn" form="inItemForm" name="dateOfIn" value="<?php echo date('Y-m-d'); ?>" style="display: none;" placeholder="<?php echo date('Y-m-d'); ?>">
                    <select class="form-control mb-2" form="inItemForm" name="productIn_type">
                        <option value="" selected="true" disabled="disabled" style="font-style: italic;">Select Type</option>
                        <option value="type 1">type 1</option>
                        <option value="type 2">type 2</option>
                        <option value="type 3">type 3</option>
                    </select>
                    <button class="btn btn-success w-100" type="submit" form="inItemForm">✔ Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- OUT Transaction Modal -->
    <div class="modal fade" id="transactionOutModal" tabindex="-1" aria-labelledby="transactionOutLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionOutLabel">📤 Remove Stock (Out Transaction)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" placeholder="Product Name">
                    <input type="number" class="form-control mb-2" placeholder="Quantity">
                    <button class="btn btn-danger w-100">✔ Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT Transaction Modal -->
    <div class="modal fade" id="transactionEditModal" tabindex="-1" aria-labelledby="transactionEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionEditLabel">✏️ Edit Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" placeholder="Transaction ID">
                    <input type="text" class="form-control mb-2" placeholder="New Product Name">
                    <input type="number" class="form-control mb-2" placeholder="New Quantity">
                    <button class="btn btn-warning w-100">✔ Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- APPROVAL Transaction Modal -->
    <div class="modal fade" id="transactionApprovalModal" tabindex="-1" aria-labelledby="transactionApprovalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionApprovalLabel">✅ Approve Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-2" placeholder="Transaction ID">
                    <select class="form-select mb-2">
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                    <button class="btn btn-primary w-100">✔ Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

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
                                text: "The product has been successfully In",
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $("#transactionInModal").modal("hide");
                            $("#inItemForm")[0].reset();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response
                            });
                        }

                    }
                })
            })
        });
    </script>
</body>

</html>