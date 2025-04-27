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

        <div class="container py-4 page-container">
            <h1 class="page-title">INVENTORY MANAGEMENT</h1>

            <!-- Add this after your page title and before the action buttons row -->

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-boxes fa-2x text-primary mb-3"></i>
                            <h5 class="card-title">Total IN Items</h5>
                            <h2 class="card-text mb-0" id="inItemCount">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary mb-3"></i>
                            <h5 class="card-title">Latest IN Date</h5>
                            <h2 class="card-text mb-0" id="latestInDate">-</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-hashtag fa-2x text-primary mb-3"></i>
                            <h5 class="card-title">Total Stock</h5>
                            <h2 class="card-text mb-0" id="totalStockCount">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x text-danger mb-3"></i>
                            <h5 class="card-title">Low Stock Items</h5>
                            <h2 class="card-text mb-0" id="lowStockCount">0</h2>
                        </div>
                    </div>
                </div>
            </div>

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
                    <button class="btn btn-danger action-btn view-out-items">
                        <i class="fa-solid fa-eye me-2"></i>View OUT Items
                    </button>
                    </div>';
                } else {
                    echo '<div class="col-md-12 d-flex justify-content-end gap-3 mb-3">
                    <button class="btn btn-danger action-btn view-out-items">
                        <i class="fa-solid fa-eye me-2"></i>View OUT Items
                    </button>
                    </div>';
                }
                ?>
            </div>
            <div class="data-table">
                <table id="inventoryTable" class="table table-hover text-center display responsive nowrap"
                    style="width:100%">
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

                        $query = "SELECT productId, productName, type, transactionStatus,quantity, dateOfIn, dateOfOut FROM inventory WHERE transactionStatus = 1 AND type = 0 ORDER BY  quantity ASC";
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
                                    echo " 
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

    <!-- Out Item Modal -->
    <div class="modal fade" id="transactionOutModal" tabindex="-1" aria-labelledby="transactionOutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title" id="transactionOutModalLabel">
                        <i class="fas fa-minus-circle me-2"></i>Out Items from Inventory
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Search Bar -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg border-0 bg-light"
                                    id="productIdSearch" placeholder="Search by Product ID...">
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <span class="badge bg-info py-2 px-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Select items to mark as "Out"
                            </span>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="table-responsive inventory-table">
                        <table class="table table-hover" id="outItemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="15%">Product ID</th>
                                    <th width="50%">Product Name</th>
                                    <th width="15%">Available</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="outItemsTableBody">
                                <!-- Products will be loaded here via AJAX -->
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading inventory items...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Out Item Quantity Modal -->
    <div class="modal fade" id="outItemQuantityModal" tabindex="-1" aria-labelledby="outItemQuantityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title" id="outItemQuantityModalLabel">
                        <i class="fas fa-minus-circle me-2"></i>Remove Item from Inventory
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="product-icon mb-3">
                            <i class="fas fa-box-open fa-3x text-danger"></i>
                        </div>
                        <h4 class="product-name fw-bold">Product Name</h4>
                        <p class="product-id text-muted">Product ID: <span id="selectedProductId"></span></p>
                    </div>

                    <form id="outItemForm">
                        <input type="hidden" id="outProductId" name="productId">
                        <input type="hidden" id="outProductName" name="productName">

                        <div class="mb-4">
                            <label for="outQuantity" class="form-label fw-bold">
                                <i class="fas fa-hashtag me-2 text-danger"></i>Out Quantity
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg border-0 bg-light"
                                    id="outQuantity" name="quantity" min="1" placeholder="0" required>
                                <span class="input-group-text bg-light border-0">units</span>
                            </div>
                            <div class="form-text mt-2">
                                Available: <span class="fw-bold text-success" id="availableQuantity">0</span> units
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="dateOfOut" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-2 text-danger"></i>Date
                            </label>
                            <input type="date" class="form-control form-control-lg border-0 bg-light" id="dateOfOut"
                                name="dateOfOut" required>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This action will mark items as "OUT" in your inventory.
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" id="backToListBtn">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </button>
                    <button type="button" class="btn btn-danger btn-lg px-4" id="confirmOutBtn">
                        <i class="fas fa-minus-circle me-2"></i>Mark as Out
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Item Modal - Enhanced Design -->
    <div class="modal fade" id="transactionInModal" tabindex="-1" aria-labelledby="transactionInModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="transactionInModalLabel">
                        <i class="fas fa-box-open me-2"></i>Add New Inventory Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="addItemForm">
                        <div class="mb-4">
                            <label for="productIn_item" class="form-label fw-bold">
                                <i class="fas fa-tag me-2 text-primary"></i>Product Name
                            </label>
                            <input type="text" class="form-control form-control-lg border-0 bg-light"
                                id="productIn_item" name="productIn_item" placeholder="Enter product name" required>
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
                                        required>
                                    <span class="input-group-text bg-light border-0">units</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="dateOfIn" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Date
                                </label>
                                <input type="date" class="form-control form-control-lg border-0 bg-light" id="dateOfIn"
                                    name="dateOfIn" required>
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            New items will be added with "IN" transaction type and approved status.
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-lg px-4" id="saveNewItemBtn">
                        <i class="fas fa-plus me-2"></i>Add Item
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Details Modal -->
    <div class="modal fade receipt-modal" id="productDetailsModal" tabindex="-1"
        aria-labelledby="productDetailsModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-primary" id="print-receipt"><i class="fas fa-print me-2"></i>
                        Print Receipt</button>
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
                            <input type="number" class="form-control" id="edit-product-quantity" name="quantity" min="1"
                                required>
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

    <!-- Replace the current View OUT Items Modal with this enhanced version -->

    <!-- View OUT Items Modal - Enhanced UI -->
    <div class="modal fade" id="viewOutItemsModal" tabindex="-1" aria-labelledby="viewOutItemsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title" id="viewOutItemsModalLabel">
                        <i class="fas fa-box-open me-2"></i>OUT Items Inventory
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-boxes fa-2x text-danger mb-3"></i>
                                    <h5 class="card-title">Total OUT Items</h5>
                                    <h2 class="card-text mb-0" id="outItemCount">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-2x text-danger mb-3"></i>
                                    <h5 class="card-title">Latest OUT Date</h5>
                                    <h2 class="card-text mb-0" id="latestOutDate">-</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-hashtag fa-2x text-danger mb-3"></i>
                                    <h5 class="card-title">Total Quantity OUT</h5>
                                    <h2 class="card-text mb-0" id="totalQuantityOut">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg border-0 bg-light"
                                    id="outItemsSearch" placeholder="Search by name or ID...">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-2">
                            <!-- <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort me-1"></i> Sort By
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item sort-items" data-sort="id-asc" href="#">ID
                                            (Ascending)</a></li>
                                    <li><a class="dropdown-item sort-items" data-sort="id-desc" href="#">ID
                                            (Descending)</a></li>
                                    <li><a class="dropdown-item sort-items" data-sort="name-asc" href="#">Name (A-Z)</a>
                                    </li>
                                    <li><a class="dropdown-item sort-items" data-sort="name-desc" href="#">Name
                                            (Z-A)</a></li>
                                    <li><a class="dropdown-item sort-items" data-sort="date-asc" href="#">Date (Oldest
                                            First)</a></li>
                                    <li><a class="dropdown-item sort-items" data-sort="date-desc" href="#">Date (Newest
                                            First)</a></li>
                                    <li><a class="dropdown-item sort-items" data-sort="qty-asc" href="#">Quantity (Low
                                            to High)</a></li>
                                    <li><a class="dropdown-item sort-items" data-sort="qty-desc" href="#">Quantity (High
                                            to Low)</a></li>
                                </ul>
                            </div> -->
                            <button class="btn btn-primary" id="refreshOutItems">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Table Container with Custom Styling -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-danger">
                                    <i class="fas fa-clipboard-list me-2"></i>OUT Items List
                                </h6>
                                <!-- <div>
                                    <button class="btn btn-sm btn-outline-secondary export-excel">
                                        <i class="fas fa-file-excel me-1"></i> Export to Excel
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger export-pdf ms-2">
                                        <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                    </button>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="outItemsListTable" class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Date OUT</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="outItemsListBody">
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="spinner-border text-danger" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mt-2 text-muted">Loading OUT items...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">Showing <span id="visibleItemCount">0</span> of <span
                                    id="totalItemCount">0</span> items</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
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


            const today = new Date().toISOString().split('T')[0];

            $('#transactionOutModal').on('show.bs.modal', function() {
                loadInventoryItems();
            });

            // Handle search by product ID
            $('#productIdSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();

                $('#outItemsTableBody tr').each(function() {
                    const productId = $(this).find('td:first').text().toLowerCase();

                    if (productId.includes(searchTerm) || searchTerm === '') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Handle "Out" button click
            $(document).on('click', '.btn-mark-out', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const availableQty = $(this).data('quantity');

                // Set values in the quantity modal
                $('#selectedProductId').text(productId);
                $('.product-name').text(productName);
                $('#outProductId').val(productId);
                $('#outProductName').val(productName);
                $('#availableQuantity').text(availableQty);

                // Set max quantity
                $('#outQuantity').attr('max', availableQty);

                // Set default date to today
                const today = new Date().toISOString().split('T')[0];
                $('#dateOfOut').val(today);

                // Hide the list modal and show the quantity modal
                $('#transactionOutModal').modal('hide');
                $('#outItemQuantityModal').modal('show');
            });

            // Handle Back button click
            $('#backToListBtn').on('click', function() {
                $('#outItemQuantityModal').modal('hide');
                $('#transactionOutModal').modal('show');
            });

            // Handle Out Quantity validation
            $('#outQuantity').on('input', function() {
                const maxQty = parseInt($('#availableQuantity').text());
                const enteredQty = parseInt($(this).val());

                if (enteredQty > maxQty) {
                    $(this).addClass('is-invalid');
                    $(this).val(maxQty);
                } else if (enteredQty < 1) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Handle Confirm Out button click
            // Update the Confirm Out button click handler
            $('#confirmOutBtn').on('click', function() {
                // Validate form
                if (!$('#outItemForm')[0].checkValidity()) {
                    $('#outItemForm')[0].reportValidity();
                    return;
                }

                const outQty = parseInt($('#outQuantity').val());
                const maxQty = parseInt($('#availableQuantity').text());

                if (outQty > maxQty || outQty < 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Quantity',
                        text: `Please enter a quantity between 1 and ${maxQty}.`
                    });
                    return;
                }

                // Show loading state
                const confirmBtn = $(this);
                const originalText = confirmBtn.html();
                confirmBtn.html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...'
                );
                confirmBtn.prop('disabled', true);

                // Prepare data for submission
                const formData = {
                    productId: $('#outProductId').val(),
                    productName: $('#outProductName').val(),
                    quantity: $('#outQuantity').val(),
                    dateOfOut: $('#dateOfOut').val(),
                    isConfirmation: false,
                    deleteZeroStock: false
                };

                processOutItem(formData, confirmBtn, originalText);
            });

            // Function to process the Out Item request
            function processOutItem(formData, button, originalBtnText) {
                $.ajax({
                    url: 'server_side/OutItem.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.trim() === 'zero_stock_confirmation') {
                            // Reset button state
                            button.html(originalBtnText);
                            button.prop('disabled', false);

                            // Show zero stock confirmation dialog
                            Swal.fire({
                                title: 'Zero Stock Warning!',
                                html: `<div class="text-center mb-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p>This action will reduce <strong>${formData.productName}</strong> stock to zero.</p>
                        <p>Do you want to also remove this product from inventory?</p>
                    </div>`,
                                showCancelButton: false,
                                showDenyButton: true,
                                confirmButtonColor: '#dc3545',
                                cancelButtonColor: '#6c757d',
                                denyButtonColor: '#28a745',
                                confirmButtonText: 'Cancel Operation',
                                denyButtonText: 'Yes, Remove Item',
                                cancelButtonText: 'No, Keep Item'
                            }).then((result) => {
                                if (result.isDenied) { // Yes, Remove Item
                                    // Send request again with confirmation and delete flag
                                    const updatedFormData = {
                                        ...formData,
                                        isConfirmation: true,
                                        deleteZeroStock: true
                                    };

                                    button.html(
                                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...'
                                    );
                                    button.prop('disabled', true);
                                    processOutItem(updatedFormData, button, originalBtnText);

                                } else if (result.dismiss === Swal.DismissReason
                                    .cancel) { // No, Keep Item
                                    // Send request again with confirmation but no delete
                                    const updatedFormData = {
                                        ...formData,
                                        isConfirmation: true,
                                        deleteZeroStock: false
                                    };

                                    button.html(
                                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...'
                                    );
                                    button.prop('disabled', true);
                                    processOutItem(updatedFormData, button, originalBtnText);
                                }
                                // If clicked "Cancel Operation", do nothing
                            });

                        } else if (response.trim() === 'success') {
                            // Reset button state
                            button.html(originalBtnText);
                            button.prop('disabled', false);

                            // Show success message
                            $('#outItemQuantityModal').modal('hide');

                            Swal.fire({
                                title: 'Item Marked as Out!',
                                html: `<strong>${formData.productName}</strong> has been marked as out successfully.
                           ${formData.deleteZeroStock ? '<br>The item has been removed from inventory.' : ''}`,
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

                        } else if (response.trim() === 'invalid_quantity') {
                            button.html(originalBtnText);
                            button.prop('disabled', false);

                            Swal.fire({
                                title: 'Invalid Quantity',
                                text: 'Please enter a valid quantity.',
                                icon: 'error'
                            });

                        } else if (response.trim() === 'product_not_found') {
                            button.html(originalBtnText);
                            button.prop('disabled', false);

                            Swal.fire({
                                title: 'Product Not Found',
                                text: 'This product no longer exists in inventory.',
                                icon: 'error'
                            });

                        } else {
                            // Handle other errors
                            button.html(originalBtnText);
                            button.prop('disabled', false);

                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to process the request. Please try again.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        button.html(originalBtnText);
                        button.prop('disabled', false);

                        Swal.fire({
                            title: 'Server Error',
                            text: 'Unable to connect to the server. Please check your connection and try again.',
                            icon: 'error'
                        });
                    }
                });
            }

            // Function to load inventory items
            function loadInventoryItems() {
                $.ajax({
                    url: 'server_side/get_items_outmodal.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            let tableRows = '';

                            // Create table rows for each item
                            data.forEach(function(item) {
                                tableRows += `
                            <tr>
                                <td>${item.productId}</td>
                                <td>${item.productName}</td>
                                <td>${item.quantity} units</td>
                                <td>
                                    <button class="btn btn-danger btn-sm btn-mark-out" 
                                        data-id="${item.productId}" 
                                        data-name="${item.productName}" 
                                        data-quantity="${item.quantity}">
                                        <i class="fas fa-minus-circle me-1"></i> Out
                                    </button>
                                </td>
                            </tr>
                        `;
                            });

                            // Update table content
                            $('#outItemsTableBody').html(tableRows);
                        } else {
                            $('#outItemsTableBody').html(`
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p>No inventory items available</p>
                            </td>
                        </tr>
                    `);
                        }
                    },
                    error: function() {
                        $('#outItemsTableBody').html(`
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                            <p>Error loading inventory items. Please try again.</p>
                        </td>
                    </tr>
                `);
                    }
                });
            }
            $('#dateOfIn').val(today);

            // Add animation when opening the modal
            $('#transactionInModal').on('show.bs.modal', function() {
                setTimeout(function() {
                    $('#productIn_item').focus();
                }, 500);
            });

            // Handle form inputs validation with visual feedback
            $('#addItemForm input, #addItemForm select').on('input change', function() {
                if ($(this).val()) {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                } else {
                    $(this).removeClass('is-valid');
                }
            });

            // Handle Add Item submission with enhanced UX
            $('#saveNewItemBtn').click(function() {
                // Enhanced validation with visual feedback
                let isValid = true;
                $('#addItemForm input, #addItemForm select').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).addClass('is-valid').removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    // Shake the modal slightly to indicate validation error
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
                    dateOfIn: $('#dateOfIn').val()
                };

                // Show loading state with enhanced animation
                const saveBtn = $(this);
                const originalText = saveBtn.html();
                saveBtn.html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Adding Item...'
                );
                saveBtn.addClass('disabled').prop('disabled', true);

                // Add overlay to indicate processing
                $('<div class="modal-backdrop show loading-overlay"><div class="spinner-grow text-light" role="status"></div></div>')
                    .css({
                        'opacity': '0.3',
                        'z-index': '1051'
                    })
                    .appendTo('body');

                // Send AJAX request
                $.ajax({
                    url: 'server_side/InItem.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        saveBtn.html(originalText);
                        saveBtn.removeClass('disabled').prop('disabled', false);
                        $('.loading-overlay').remove();

                        if (response.trim() === 'success') {
                            // Show success message with animation
                            $('#transactionInModal').modal('hide');

                            Swal.fire({
                                title: 'Item Added Successfully!',
                                text: 'The new inventory item has been added.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal
                                        .stopTimer)
                                    toast.addEventListener('mouseleave', Swal
                                        .resumeTimer)
                                }
                            }).then(function() {
                                // Reload with fade effect
                                $('body').fadeOut(500, function() {
                                    location.reload();
                                });
                            });

                            // Reset form
                            $('#addItemForm')[0].reset();
                            $('#addItemForm input, #addItemForm select').removeClass(
                                'is-valid is-invalid');
                            $('#dateOfIn').val(today);
                        } else {
                            // Show error message
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to add the item. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#4e73df'
                            });
                        }
                    },
                    error: function() {
                        saveBtn.html(originalText);
                        saveBtn.removeClass('disabled').prop('disabled', false);
                        $('.loading-overlay').remove();

                        Swal.fire({
                            title: 'Server Error',
                            text: 'Unable to connect to the server. Please check your connection and try again.',
                            icon: 'error',
                            confirmButtonColor: '#4e73df'
                        });
                    }
                });
            });

            // Add keyboard shortcuts
            $(document).on('keydown', function(e) {
                if ($('#transactionInModal').hasClass('show')) {
                    if (e.key === "Enter" && !e.shiftKey) {
                        // Prevent default form submission
                        e.preventDefault();
                        // Trigger save button click
                        $('#saveNewItemBtn').click();
                    }
                }
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
                    // {
                    //     extend: 'csv',
                    //     className: 'btn btn-outline-success btn-sm me-1',
                    //     text: '<i class="fas fa-file-csv me-1"></i> CSV',
                    //     titleAttr: 'Export as CSV'
                    // },
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

            //custom styling for the buttons container
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

                if (status == 1) {
                    $('#modal-product-status').text('Approved');
                } else {
                    $('#modal-product-status').text('Rejected');
                }

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


                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            });

            //edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                // Hide any active tooltips
                $('.tooltip').hide();

                //Fetch item details for editing
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


                            if (item.dateOfIn) {
                                $('#edit-date-in').val(item.dateOfIn.substring(0,
                                    10)); // YYYY-MM-DD format
                            }

                            if (item.dateOfOut && item.dateOfOut !== '0000-00-00') {
                                $('#edit-date-out').val(item.dateOfOut.substring(0, 10));
                            } else {
                                $('#edit-date-out').val('');
                            }


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
            $('#edit-product-type').change(function() {
                if ($(this).val() == 0) { // IN
                    $('#edit-date-out-container').hide();
                } else { // OUT
                    $('#edit-date-out-container').show();
                }
            });

            //save button click
            $('#saveEditBtn').click(function() {
                // Validate form
                if (!$('#editItemForm')[0].checkValidity()) {
                    $('#editItemForm')[0].reportValidity();
                    return;
                }

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
                                location
                                    .reload(); // Refresh the page to show updated data
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

            //delete button click
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

            // View OUT Items Button click
            $('.view-out-items').on('click', function() {
                $('#viewOutItemsModal').modal('show');
                loadOutItems();
            });

            // Search functionality for OUT items list
            $('#outItemsSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();

                $('#outItemsListBody tr').each(function() {
                    const productId = $(this).find('td:eq(0)').text().toLowerCase();
                    const productName = $(this).find('td:eq(1)').text().toLowerCase();

                    if (productId.includes(searchTerm) || productName.includes(searchTerm) ||
                        searchTerm === '') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Refresh button for OUT items list
            $('#refreshOutItems').on('click', function() {
                loadOutItems();
            });

            function loadOutItems() {
                // Show loading spinner
                $('#outItemsListBody').html(`
        <tr>
            <td colspan="5" class="text-center py-4">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading OUT items...</p>
            </td>
        </tr>
    `);

                $.ajax({
                    url: 'server_side/getOutItems_inventory.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('AJAX Success:', data);

                        if (data && data.length > 0) {
                            let tableRows = '';
                            let totalQuantity = 0;
                            let latestDate = null;

                            // Process each item
                            data.forEach(function(item) {
                                // Safely handle dateOut
                                let dateOut = 'N/A';
                                let dateOutObj = null;

                                if (item.dateOfOut && item.dateOfOut !== '0000-00-00') {
                                    try {
                                        dateOutObj = new Date(item.dateOfOut);
                                        dateOut = dateOutObj.toLocaleDateString();

                                        // Track latest date
                                        if (!latestDate || dateOutObj > latestDate) {
                                            latestDate = dateOutObj;
                                        }
                                    } catch (e) {
                                        dateOut = item.dateOfOut;
                                    }
                                }

                                // Track total quantity
                                totalQuantity += parseInt(item.quantity) || 0;

                                // Safe handling of date attributes for data attributes
                                let dateInAttr = 'N/A';
                                let dateOutAttr = 'N/A';

                                if (item.dateOfIn && item.dateOfIn !== '0000-00-00') {
                                    try {
                                        dateInAttr = new Date(item.dateOfIn).toISOString()
                                            .split('T')[0];
                                    } catch (e) {
                                        dateInAttr = 'N/A';
                                    }
                                }

                                if (item.dateOfOut && item.dateOfOut !== '0000-00-00') {
                                    try {
                                        dateOutAttr = new Date(item.dateOfOut).toISOString()
                                            .split('T')[0];
                                    } catch (e) {
                                        dateOutAttr = 'N/A';
                                    }
                                }

                                // Build table row with enhanced styling
                                tableRows += `
                    <tr data-id="${item.productId}" data-name="${item.productName}" data-quantity="${item.quantity}" data-date="${dateOutAttr}">
                        <td>
                            <span class="badge bg-light text-dark">#${item.productId}</span>
                        </td>
                        <td>
                            <div class="fw-bold">${item.productName}</div>
                        </td>
                        <td>
                            <span class="badge ${parseInt(item.quantity) > 0 ? 'bg-success' : 'bg-danger'}">${item.quantity}</span>
                        </td>
                        <td>
                            <i class="far fa-calendar-alt me-1 text-muted"></i> ${dateOut}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-danger delete-btn" data-id="${item.productId}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    `;
                            });

                            // Update table content
                            $('#outItemsListBody').html(tableRows);

                            // Update summary cards
                            $('#outItemCount').text(data.length);
                            $('#totalQuantityOut').text(totalQuantity);
                            $('#latestOutDate').text(latestDate ? latestDate.toLocaleDateString() :
                                '-');

                            // Update counters in footer
                            $('#totalItemCount').text(data.length);
                            $('#visibleItemCount').text(data.length);

                            // Initialize tooltips for the new buttons
                            $('[title]').tooltip();

                        } else {
                            $('#outItemsListBody').html(`
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="lead">No OUT items found in inventory</p>
                            <p class="text-muted">Items marked as OUT will appear here</p>
                        </td>
                    </tr>
                `);

                            // Update summary cards with zeros
                            $('#outItemCount').text('0');
                            $('#totalQuantityOut').text('0');
                            $('#latestOutDate').text('-');
                            $('#totalItemCount').text('0');
                            $('#visibleItemCount').text('0');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.log('Response:', xhr.responseText);

                        $('#outItemsListBody').html(`
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                        <p>Error loading OUT items: ${error}</p>
                        <p>Please check console for details.</p>
                        <button class="btn btn-sm btn-outline-primary mt-3" id="retry-load">
                            <i class="fas fa-redo me-1"></i> Try Again
                        </button>
                    </td>
                </tr>
            `);

                        // Update summary cards with zeros
                        $('#outItemCount').text('0');
                        $('#totalQuantityOut').text('0');
                        $('#latestOutDate').text('-');
                    }
                });
            }

            $(document).on('click', '.sort-items', function(e) {
                e.preventDefault();
                const sortType = $(this).data('sort');

                const rows = $('#outItemsListBody tr').toArray();

                rows.sort(function(a, b) {
                    let comparison = 0;

                    if (sortType === 'id-asc') {
                        comparison = $(a).data('id') - $(b).data('id');
                    } else if (sortType === 'id-desc') {
                        comparison = $(b).data('id') - $(a).data('id');
                    } else if (sortType === 'name-asc') {
                        comparison = $(a).data('name').localeCompare($(b).data('name'));
                    } else if (sortType === 'name-desc') {
                        comparison = $(b).data('name').localeCompare($(a).data('name'));
                    } else if (sortType === 'qty-asc') {
                        comparison = parseInt($(a).data('quantity')) - parseInt($(b).data(
                            'quantity'));
                    } else if (sortType === 'qty-desc') {
                        comparison = parseInt($(b).data('quantity')) - parseInt($(a).data(
                            'quantity'));
                    } else if (sortType === 'date-asc' || sortType === 'date-desc') {
                        const dateA = $(a).data('date');
                        const dateB = $(b).data('date');

                        // Handle N/A values
                        if (dateA === 'N/A' && dateB === 'N/A') {
                            comparison = 0;
                        } else if (dateA === 'N/A') {
                            comparison = 1; // N/A is "greater" (comes last)
                        } else if (dateB === 'N/A') {
                            comparison = -1; // N/A is "greater" (comes last)
                        } else {
                            comparison = new Date(dateA) - new Date(dateB);
                        }

                        if (sortType === 'date-desc') {
                            comparison = -comparison;
                        }
                    }

                    return comparison;
                });

                // Re-add the sorted rows
                $('#outItemsListBody').empty();
                $.each(rows, function(index, row) {
                    $('#outItemsListBody').append(row);
                });
            });

            $(document).on('click', '.export-excel', function() {
                // Create a workbook
                const data = [];

                // Add header row
                data.push(['Product ID', 'Product Name', 'Quantity', 'Date OUT']);

                // Add data rows
                $('#outItemsListBody tr').each(function() {
                    const id = $(this).find('td:eq(0)').text().trim();
                    const name = $(this).find('td:eq(1)').text().trim();
                    const qty = $(this).find('td:eq(2)').text().trim();
                    const date = $(this).find('td:eq(3)').text().trim();

                    if (id && name) { // Skip empty rows
                        data.push([id, name, qty, date]);
                    }
                });

                if (data.length <= 1) {
                    Swal.fire({
                        title: 'No Data',
                        text: 'There is no data to export',
                        icon: 'warning'
                    });
                    return;
                }

                // Generate Excel file
                const workbook = XLSX.utils.book_new();
                const worksheet = XLSX.utils.aoa_to_sheet(data);
                XLSX.utils.book_append_sheet(workbook, worksheet, 'OUT Items');

                // Generate file and trigger download
                XLSX.writeFile(workbook, 'OutItems_Export_' + new Date().toISOString().slice(0, 10) +
                    '.xlsx');
            });

            $(document).on('click', '.export-pdf', function() {
                // Check if there's data to export
                if ($('#outItemsListBody tr').length === 0 || $('#outItemsListBody tr:first td').length ===
                    1) {
                    Swal.fire({
                        title: 'No Data',
                        text: 'There is no data to export',
                        icon: 'warning'
                    });
                    return;
                }

                // Define PDF document
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                // Add title
                doc.setFontSize(16);
                doc.text('OUT Items Inventory Report', 14, 15);

                // Add date
                doc.setFontSize(10);
                doc.text('Generated on: ' + new Date().toLocaleString(), 14, 22);

                // Add table using autoTable plugin if available
                if (typeof doc.autoTable === 'function') {
                    doc.autoTable({
                        startY: 30,
                        head: [
                            ['Product ID', 'Product Name', 'Quantity', 'Date OUT']
                        ],
                        body: Array.from($('#outItemsListBody tr')).map(row => {
                            const $row = $(row);
                            return [
                                $row.find('td:eq(0)').text().trim(),
                                $row.find('td:eq(1)').text().trim(),
                                $row.find('td:eq(2)').text().trim(),
                                $row.find('td:eq(3)').text().trim()
                            ];
                        }),
                        theme: 'striped',
                        headStyles: {
                            fillColor: [220, 53, 69],
                            textColor: 255
                        }
                    });
                } else {
                    // Fallback if autoTable not available
                    doc.text('PDF export requires the jspdf-autotable plugin', 14, 30);
                }

                // Save PDF
                doc.save('OutItems_Report_' + new Date().toISOString().slice(0, 10) + '.pdf');
            });

            // Retry loading on button click
            $(document).on('click', '#retry-load', function() {
                loadOutItems();
            });

            // Add this to your $(document).ready() function

            // Load inventory summary for dashboard cards
            function loadInventorySummary() {
                $.ajax({
                    url: 'server_side/get_inventory_summary.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            // Update IN items card
                            $('#inItemCount').text(data.inItemCount || 0);

                            // Format latest date
                            if (data.latestInDate) {
                                try {
                                    const dateObj = new Date(data.latestInDate);
                                    $('#latestInDate').text(dateObj.toLocaleDateString());
                                } catch (e) {
                                    $('#latestInDate').text(data.latestInDate);
                                }
                            } else {
                                $('#latestInDate').text('-');
                            }

                            // Update total stock and low stock
                            $('#totalStockCount').text(data.totalStock || 0);
                            $('#lowStockCount').text(data.lowStockCount || 0);

                            // Add animations
                            $('.card-text').each(function() {
                                $(this).addClass('animate__animated animate__fadeIn');
                            });
                        }
                    },
                    error: function() {
                        // Show error indicators
                        $('#inItemCount, #totalStockCount, #lowStockCount').html(
                            '<i class="fas fa-exclamation-circle text-danger"></i>');
                        $('#latestInDate').text('-');
                    }
                });
            }

            // Call this on page load
            loadInventorySummary();

        });
    </script>
</body>

</html>