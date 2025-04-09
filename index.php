<?php
session_start();

include('cedric_dbConnection.php');

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT usersId, username, role, email, password, accountStatus FROM userstable WHERE email = '$email'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $accountStatus = $row['accountStatus'];
        //check if account status
        if ($accountStatus == 0) {
            $message = "Account is not verified. Waiting for approval.";
        } else {
            if (password_verify($password, $hashed_password)) {
                //session variables
                $_SESSION['user_id'] = $row['usersId'];
                $_SESSION['user_name'] = $row['username'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();

                $message = "Login successful"; //set login message
            } else {
                $message = "Wrong email or password";
            }
        }
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
                    <input type="email" class="form-control" id="email" placeholder="Email" form="log-form" name="email" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3 password-container">
                    <input type="password" class="form-control" id="password" placeholder="Password" form="log-form" name="password" required>
                    <label for="password">Password</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <button type="submit" class="btn btn-custom w-100" form="log-form">Sign In</button>
                <div class="create-account-container text-center p-2">
                    <p>Don't have an account? <a href="create_account.php" class="create-account-link">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var message = "<?php echo $message; ?>";
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
                        console.log("Login successful, redirecting to menu.php");
                        window.location.href = 'menu.php';
                    }
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