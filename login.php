<?php
include('cedric_dbConnection.php');

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT email, password FROM userstable WHERE email = '$email'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            $message = "Login successful";
        } else {
            $message = "Wrong email or password";
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
    <!-- Add jQuery before SweetAlert2 -->
    <script src="assets/jquery-3.6.0.min.js"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="assets/sweetalert.js"></script>
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
                </div>
                <button type="submit" class="btn btn-custom w-100" form="log-form">Sign In</button>
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
                    timer: 1000,
                    timerProgressBar: true,
                    showConfirmButton: true
                }).then(function() {
                    if (message === "Login successful") {
                        // setTimeout(function() {
                        window.location.href = 'menu.php';
                        // }, 1000); 
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