<style>
.sidebar {
            width: 15%;
            background-color: #a8d08d;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        .sidebar img {
            width: 120px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .sidebar button {
            width: 100%;
            padding: 20px;
            margin: 10px 0;
            background-color: #f4e04d;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
        .sidebar button:hover {
            background-color: #e5c100;
        }
        .sign-out {
            margin-top: auto;
        }
</style> 
<div class="sidebar">
    <img src="assets/logo.avif" alt="Logo">
    <button ><a href="menu.php">Menu</a></button>
    <button ><a href="inventory_user.php">Inventory</a></button>
    <button ><a href="ordered_history.php">Ordered History</a></button>
    <button ><a href="reports.php">Reports</a></button>
    <button class="sign-out">Sign Out</button>
 </div>