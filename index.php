<?php
session_start();

include('cedric_dbConnection.php');

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT usersId, username, role, email, password FROM userstable WHERE email = '$email'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            //session variables
            $_SESSION['user_id'] = $row['usersId'];
            $_SESSION['user_name'] = $row['username'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();

            $stockValue = 20;
            // Join with products table to get product names and order by quantity ASC
            $sql = "SELECT i.productId, i.quantity, p.productName 
                    FROM inventory i 
                    INNER JOIN inventory p ON i.productId = p.productId 
                    WHERE i.transactionStatus = 1 AND i.type = 0 AND i.quantity <= $stockValue 
                    ORDER BY i.quantity ASC";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                // Create notifications for all low stock products
                while ($row = $result->fetch_assoc()) {
                    $productId = $row["productId"];
                    $productName = $row["productName"];
                    $quantity = $row["quantity"];

                    // Check if notification already exists for this product
                    $checkSql = "SELECT * FROM notifications 
                                WHERE productId = $productId 
                                AND notificationType = 1 
                                AND notificationStatus = 0";
                    $checkResult = $connection->query($checkSql);

                    // Only insert if no active notification exists for this product
                    if ($checkResult->num_rows == 0) {
                        $messageNotification = "Low stock alert! $productName is running low on stock (only $quantity remaining). Please restock it.";
                        $sqlInsert = "INSERT INTO notifications (productId, notificationMessage, notificationType, notificationStatus) 
                                    VALUES ($productId, '$messageNotification', 1, 0)";
                        $connection->query($sqlInsert);
                    }
                }

                $lowStockAlert = false;
                $notificationCount = 0;

                if ($result->num_rows > 0) {
                    $lowStockAlert = true;

                    // Get count of all active notifications
                    $notifCountSql = "SELECT COUNT(*) as count FROM notifications WHERE notificationStatus = 0";
                    $notifCountResult = $connection->query($notifCountSql);
                    if ($notifCountResult && $notifCountRow = $notifCountResult->fetch_assoc()) {
                        $notificationCount = $notifCountRow['count'];
                    }
                }

                $message = "Login successful";
            } else {
                $message = "Login successful"; //set login message
            }

            //set login message
        } else {
            $message = "Wrong email or password";
        }
        //check if account status
        // if ($accountStatus == 0) {
        //     $message = "Account is not verified. Waiting for approval.";
        // } else {
        //     if (password_verify($password, $hashed_password)) {
        //         //session variables
        //         $_SESSION['user_id'] = $row['usersId'];
        //         $_SESSION['user_name'] = $row['username'];
        //         $_SESSION['user_email'] = $row['email'];
        //         $_SESSION['user_role'] = $row['role'];
        //         $_SESSION['logged_in'] = true;
        //         $_SESSION['last_activity'] = time();

        //         $message = "Login successful"; //set login message
        //     } else {
        //         $message = "Wrong email or password";
        //     }
        // }
    } else {
        $message = "Wrong email or password";
    }
    $connection->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <?php include('header/header.php') ?>
    <!-- custom css -->
    <link href="styles/login.css" rel="stylesheet">
</head>

<body>
    <form method="Post" id="log-form"></form>
    <div class="container-fluid vh-100 d-flex">
        <!-- Image Section -->
        <div class="image-section"></div>
        <!-- Form Section -->
        <div class="form-section d-flex align-items-center justify-content-center">
            <div class="form-container">
                <h2 class="form-title">Log In</h2>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="Email" form="log-form" name="email"
                        required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3 password-container">
                    <input type="password" class="form-control" id="password" placeholder="Password" form="log-form"
                        name="password" required>
                    <label for="password">Password</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-password"
                        onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <button type="submit" class="btn btn-custom w-100" form="log-form">Sign In</button>
                <!-- <div class="create-account-container text-center p-2">
                    <p>Don't have an account? <a href="create_account.php" class="create-account-link">Sign Up</a></p>
                </div> -->
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        var message = "<?php echo $message; ?>";
        var lowStockAlert = <?php echo $lowStockAlert ? 'true' : 'false'; ?>;
        var notificationCount = <?php echo $notificationCount; ?>;

        if (message !== '') {
            Swal.fire({
                title: message === "Login successful" ? 'Success!' : 'Error',
                text: message,
                icon: message === "Login successful" ? 'success' : 'error',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(function() {
                if (message === "Login successful") {
                    if (lowStockAlert) {
                        showLowStockAlert();
                    } else {
                        window.location.href = 'menu.php';
                    }
                }
            });
        }

        function showLowStockAlert() {
            Swal.fire({
                title: 'Low Stock Alert!',
                text: 'Some products are running low on stock. Please check notifications.',
                icon: 'warning',
                allowOutsideClick: false,
                confirmButtonText: 'OK'
            }).then(function() {
                if (notificationCount > 0) {
                    showNotificationAlert();
                } else {
                    window.location.href = 'menu.php';
                }
            });
        }

        function showNotificationAlert() {
            Swal.fire({
                title: 'Notifications',
                text: 'You have ' + notificationCount + ' unread notification' + (notificationCount >
                    1 ? 's' : '') + '.',
                icon: 'info',
                allowOutsideClick: false,
                confirmButtonText: 'View Notifications'
            }).then(function() {
                // Redirect to notifications page or menu with notifications tab active
                window.location.href = 'notification.php';
            });
        }
    });

    function togglePassword() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
    </script>
</body>

</html>