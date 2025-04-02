<link rel="stylesheet" href="assets/fontAwesome/css/all.min.css">

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
        display: flex;
        align-items: center;
        justify-content: start;
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        border: 2px solid transparent;
        color: #333;
        transition: all 0.3s ease;
    }

    .sidebar a i {
        margin-right: 10px;
        /* Space between icon and text */
        font-size: 18px;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #f1c40f;
        color: white;
        border-color: #f1c40f;
    }

    .signout {
        background-color: #e74c3c !important;
        border: solid 1px #e74c3c !important;
        color: #fff;
    }
</style>

<nav class="col-md-2 col-lg-2 sidebar">
    <img src="assets/logo.avif" alt="Logo">

    <a href="menu.php"><i class="fas fa-utensils"></i> Menu</a>
    <a href="inventory_user.php"><i class="fas fa-box"></i> Inventory</a>
    <a href="ordered_history.php"><i class="fas fa-history"></i> Ordered History</a>
    <a href="transaction.php"><i class="fas fa-cash-register"></i> Transactions</a>
    <a href="menuManagement.php"><i class="fas fa-list-alt"></i> Menu Management</a>
    <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
    <a href="logout.php" class="signout"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
</nav>