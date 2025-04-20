<link rel="stylesheet" href="assets/fontAwesome/css/all.min.css">

<style>
body {
    background-color: #FFF3B0;
    font-family: 'Poppins', sans-serif;
}

.sidebar {
    background: linear-gradient(145deg, #a0ce4e, #8ab542);
    min-height: 100vh;
    padding: 20px 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 100;
}

.logo-container {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    width: 100%;
}

.sidebar img {
    border-radius: 50%;
    width: 90px;
    height: 90px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.sidebar img:hover {
    transform: scale(1.05);
}

.menu-label {
    color: #fff;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    font-weight: 600;
    margin: 10px 0;
    align-self: flex-start;
    opacity: 0.8;
}

.nav-links {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar a {
    background-color: rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px 15px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    color: #fff;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.sidebar a i {
    margin-right: 12px;
    font-size: 18px;
    width: 22px;
    text-align: center;
    transition: all 0.3s;
}

.sidebar a:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateX(5px);
}

.sidebar a.active {
    background-color: #f1c40f;
    color: #333;
    border-left-color: #e67e00;
    font-weight: 600;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.sidebar a.active i {
    color: #e67e00;
}

.app-title {
    color: #fff;
    font-weight: 700;
    margin-top: 10px;
    font-size: 18px;
    text-align: center;
}

.user-info {
    margin-top: 10px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 15px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 15px;
}

.user-name {
    color: #fff;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 14px;
}

.user-role {
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
    margin-bottom: 10px;
}

.signout {
    background-color: rgba(231, 76, 60, 0.2) !important;
    color: #fff !important;
    border-left: none !important;
    font-weight: 500;
    margin-top: 5px;
}

.signout:hover {
    background-color: #e74c3c !important;
}

.signout i {
    color: #e74c3c;
}

.signout:hover i {
    color: #fff;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .sidebar {
        padding: 15px 10px;
    }

    .sidebar a {
        padding: 10px;
    }

    .sidebar img {
        width: 70px;
        height: 70px;
    }
}

@media (max-width: 768px) {
    .app-title {
        display: none;
    }

    .sidebar {
        min-width: 80px;
        padding: 15px 5px;
    }

    .sidebar a span {
        display: none;
    }

    .sidebar a i {
        margin-right: 0;
        font-size: 20px;
    }

    .sidebar img {
        width: 60px;
        height: 60px;
    }

    .user-info {
        display: none;
    }

    /* a.btn-notification {
        } */
}
</style>

<nav class="col-md-2 col-lg-2 sidebar">
    <div class="logo-container">
        <img src="assets/logo.avif" alt="Shawarma POS">
        <div class="app-title">Shawarma POS</div>
    </div>

    <div class="menu-label">Notification</div>

    <div class="nav-links">
        <a href="notification.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'notification.php' ? 'active' : ''; ?> btn-notification">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
        </a>
    </div>

    <div class="menu-label">Main Menu</div>

    <div class="nav-links">
        <a href="menu.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?> btn-notification">
            <i class="fas fa-utensils"></i> <span>Menu</span>
        </a>
        <a href="inventory_user.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'inventory_user.php' ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> <span>Inventory</span>
        </a>
        <a href="ordered_history.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'ordered_history.php' ? 'active' : ''; ?>">
            <i class="fas fa-history"></i> <span>Ordered History</span>
        </a>
        <a href="transaction.php"
            class="<?php echo basename($_SERVER['PHP_SELF']) == 'transaction.php' ? 'active' : ''; ?>">
            <i class="fas fa-cash-register"></i> <span>Transactions</span>
        </a>
    </div>

    <div class="menu-label">Administration</div>

    <div class="nav-links">
        <?php
        if ($_SESSION['user_role'] == 0) {
            $activeClass = (basename($_SERVER['PHP_SELF']) == 'menuManagement.php') ? 'active' : '';
            echo '<a href="menuManagement.php" class="' . $activeClass . '">
            <i class="fas fa-list-alt"></i> <span>Menu Management</span>
        </a>';
            $activeClass = (basename($_SERVER['PHP_SELF']) == 'manageAccounts.php') ? 'active' : '';
            echo '<a href="manageAccounts.php" class="' . $activeClass . '">
                <i class="fas fa-users-cog"></i> <span>Manage Accounts</span>
            </a>';
        }
        ?>
        <a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i> <span>Reports</span>
        </a>
    </div>

    <div class="user-info">
        <div class="user-name"><?php echo $role = ($_SESSION["user_role"] == 0) ? "ADMIN" : "STAFF" ; ?></div>
        <div class="user-role"><?php echo "Hello, " . $_SESSION['user_name']; ?></div>
        <a onclick="confirmLogout()" class="signout">
            <i class="fas fa-sign-out-alt"></i> <span>Sign Out</span>
        </a>
    </div>
</nav>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Ready to Leave?',
        text: "Are you sure you want to log out?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff8c00',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, log me out!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show a brief "logging out" message
            Swal.fire({
                title: 'Logging Out...',
                text: 'You will be redirected to the login page.',
                icon: 'info',
                timer: 1200,
                timerProgressBar: true,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = 'server_side/logout.php';
                }
            });
        }
    });
}
</script>