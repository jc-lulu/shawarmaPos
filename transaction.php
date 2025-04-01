<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9f5d7;
        }

        .data-table {
            max-height: 400px;
            overflow-y: auto;
            background-color: #d9d9d9;
            border-radius: 5px;
            padding: 10px;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #ff8c00;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">TRANSACTIONS</h1>

                <!-- Transaction Button with Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        🔄 Transactions
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transactionInModal">📥 In</button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transactionOutModal">📤 Out</button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transactionEditModal">✏️ Edit</button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transactionApprovalModal">✅ Approval</button></li>
                    </ul>
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
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <tr>
                            <td>1</td>
                            <td>Product A</td>
                            <td>In</td>
                            <td>50</td>
                            <td>Approved</td>
                            <td>2025-04-01</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Product B</td>
                            <td>Out</td>
                            <td>20</td>
                            <td>Pending</td>
                            <td>2025-03-30</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                    <input type="text" class="form-control mb-2" placeholder="Product Name">
                    <input type="number" class="form-control mb-2" placeholder="Quantity">
                    <button class="btn btn-success w-100">✔ Confirm</button>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
