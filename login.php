<?php
include('cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT email, password FROM userstable WHERE email = '$email'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            echo "Login successful";
            header('Location: menu.php');
        } else {
            echo "Wrong password";
        }
    } else {
        echo "Email not found";
    }
    $connection->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    /* Reset styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Full height layout */
    body,
    html {
        height: 100%;
        font-family: 'Arial', sans-serif;
    }

    /* Form Section */
    .form-section {
        width: 50%;
        background-color: #fff;
        padding: 40px;
    }

    .form-container {
        max-width: 400px;
        width: 100%;
    }

    .form-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Image Section */
    .image-section {
        width: 50%;
        background: url('assets/logo.avif') no-repeat center center;
        background-size: cover;
    }

    /* Buttons */
    .btn-custom {
        background: #000;
        color: #fff;
        padding: 12px;
        font-size: 1rem;
        border-radius: 8px;
        transition: 0.3s;
        width: 100%;
    }

    .btn-custom:hover {
        background: white;
        color: black;
        border: 1px solid black;
    }

    /* Password Input */
    .password-container {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
    }

    /* Checkbox Agreement */
    .form-check {
        font-size: 0.9rem;
        text-align: left;
    }

    /* Mobile View */
    @media (max-width: 768px) {
        .image-section {
            display: none;
        }

        .form-section {
            width: 100%;
        }
    }
</style>

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
                <button type="submit" class="btn btn-custom w-100" form="log-form">Sign Up</button>
            </div>
        </div>
    </div>

    <!-- <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        }
    </script> -->
</body>

</html>