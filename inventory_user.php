<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>

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
            <h1 class="mb-4">INVENTORY</h1>

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Product">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">Filter</option>
                        <option value="category1">Category 1</option>
                        <option value="category2">Category 2</option>
                    </select>
                </div>
            </div>

            <div class="data-table">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-warning">
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 15%;">Quantity</th>
                            <th style="width: 15%;">Gross Income</th>
                            <th style="width: 15%;">Net Income</th>
                            <th style="width: 20%;">Date</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <tr>
                            <td>1</td>
                            <td>Sample Product</td>
                            <td>50</td>
                            <td>$500</td>
                            <td>$400</td>
                            <td>2025-03-29</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Another Product</td>
                            <td>30</td>
                            <td>$300</td>
                            <td>$250</td>
                            <td>2025-03-28</td>
                        </tr>
                        <!-- More rows can be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>