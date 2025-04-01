<style>
    body {
        background-color: #FFF3B0;
    }

    .sidebar {
        background-color: #a0ce4e;
        min-height: 100vh;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .sidebar img {
        border-radius: 100%;
        width: 100px;
        margin-bottom: 20px;
    }

    .sidebar a {
        background-color: #f1c40f;
        display: block;
        width: 100%;
        text-align: center;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
        border: 2px solid #f1c40f;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #f1c40f;
        color: white;
    }

    .sidebar a:hover {
        background-color: #f1c40f;
        transition: .5s;
        color: white;
    }

    .signout {
        background-color: #e74c3c !important;
        border: solid 1px #e74c3c !important;
        color: #fff;
    }
</style>
<nav class="col-md-2 col-lg-2 sidebar">
    <img src="assets/logo.avif" alt="Logo">
    <a href="menu.php">Menu</a>
    <a href="inventory_user.php">Inventory</a>
    <a href="ordered_history.php">Ordered History</a>
    <a href="transaction.php">Transactions</a>
    <a href="menuManagement.php">Menu Management</a>
    <a href="reports.php">Reports</a>
    <a href="logout.php" class="signout">Sign Out</a>
</nav>