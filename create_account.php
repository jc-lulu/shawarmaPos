<?php
include('cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $sqlemail = "SELECT username, email FROM userstable WHERE email = '$email'";
        $emailresult = $connection->query($sqlemail);

        if ($emailresult->num_rows > 0) {
            $row = $emailresult->fetch_assoc();

            if ($username == $row['username']) {
                echo 'Username already exists!';
            } else if ($email == $row['email']) {
                echo 'Email already exists!';
            }
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO userstable (username, email, password) VALUES ('$username', '$email', '$password')";
            $result = $connection->query($sql);
            if ($result) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error creating account";
            }
        }
    } else {
        echo "All fields are required!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <!-- Styles -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--- scripts -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/sweetalert.js"></script>
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

    input[type="checkbox"] {
        transform: scale(1.2);
        cursor: pointer;
    }

    /* Mobile View */
    @media (max-width: 768px) {
        .image-section {
            display: block;
            /* border-radius: 100%;
            max-height: 100px;
            width: 100px; */
        }

        .form-section {
            width: 100%;
        }
    }
</style>

<body>
    <form method="Post" id="create-form"></form>
    <div class="container-fluid vh-100 d-flex">
        <!-- Form Section -->
        <div class="form-section d-flex align-items-center justify-content-center">
            <div class="form-container">
                <h2 class="form-title">Create an account</h2>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" placeholder="Username" form="create-form" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="Email" form="create-form" name="email" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3 password-container">
                    <input type="password" class="form-control" id="password" placeholder="Password" form="create-form" name="password" required>
                    <label for="password">Password</label>
                </div>
                <div class="col-mb-3 pb-3 show-password">
                    <input type="checkbox" id="showpassword" name="showpassword" value="true">
                    <label for="vehicle1"> Show Password</label><br>
                </div>
                <button type="submit" class="btn btn-custom w-100" form="create-form">Sign Up</button>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="true" id="agree" form="create-form" required>
                    <label class="form-check-label" for="agree">
                        I agree to the <a href="#">Privacy Policy</a> and <a href="login.php">Terms & Conditions</a>
                    </label>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div class="image-section"></div>
    </div>

    <script>
        document.getElementById("showpassword").onclick = function() {
            var passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        };
    </script>
</body>

</html>