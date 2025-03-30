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
                $modalTitle = 'Alert.';
                $modalMessage = 'Username already exists!';
                $modalColor = '#BD5B5B';
            } else if ($email == $row['email']) {
                $modalTitle = 'Alert';
                $modalMessage = 'Email already exists!';
                $modalColor = '#BD5B5B';
            }
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO userstable (username, email, password) VALUES ('$username', '$email', '$password')";
            $result = $connection->query($sql);

            if ($result) {
                $modalTitle = 'Success';
                $modalMessage = 'Account created successfully!';
                $modalColor = '#4CAF50';
                $redirect = true;
            } else {
                $modalTitle = 'Error';
                $modalMessage = 'Error creating account';
                $modalColor = '#BD5B5B';
            }
        }
    } else {
        $modalTitle = 'Warning';
        $modalMessage = 'All fields are required!';
        $modalColor = '#FFA500';
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
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/sweetalert.js"></script>
    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- iziModal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/css/iziModal.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/js/iziModal.min.js"></script>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body,
    html {
        height: 100%;
        font-family: 'Arial', sans-serif;
    }

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

    .image-section {
        width: 50%;
        background: url('assets/logo.avif') no-repeat center center;
        background-size: cover;
    }

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
        }

        .form-section {
            width: 100%;
        }
    }
</style>

<body>
    <!-- izimodal -->
    <div id="modal" class="iziModal"></div>

    <form method="Post" id="create-form"></form>
    <div class="container-fluid vh-100 d-flex">
        <!-- Form Section -->
        <div class="form-section d-flex align-items-center justify-content-center">
            <div class="form-container">
                <h2 class="form-title">Create an account</h2>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" maxlength="20" id="username" placeholder="Username" form="create-form" name="username" required>
                    <span class="char-counter" style="font-size: .80rem; color: #999;">Max 20 characters</span>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="Email" form="create-form" name="email" required>
                    <span class="char-counter" style="font-size: .80rem; color: #999;">ex: juan@gmail.com</span>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3 password-container">
                    <input type="password" class="form-control" id="password" placeholder="Password" form="create-form" name="password" minlength="8" maxlength="20" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" required>
                    <label for="password">Password</label>
                </div>
                <div class="col-mb-3 pb-3 show-password">
                    <input type="checkbox" id="showpassword" name="showpassword" value="true">
                    <label for="showpassword"> Show Password</label><br>
                    <span class="char-counter" style="font-size: .80rem; color: #999;">8-20 characters, with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.</span>
                </div>
                <button type="submit" class="btn btn-custom w-100" form="create-form">Sign Up</button>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="true" id="agree" form="create-form" required>
                    <label class="form-check-label" for="agree">I agree to the <a href="#">Privacy Policy</a> and <a>Terms & Conditions</a></label>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div class="image-section"></div>
    </div>
    <script>
        $(document).ready(function() {
            // iziModal
            $("#modal").iziModal({
                title: '<?php echo $modalTitle; ?>',
                subtitle: '<?php echo $modalMessage; ?>',
                headerColor: '<?php echo $modalColor; ?>',
                width: 400,
                timeoutProgressbar: true,
                transitionIn: 'fadeInDown',
                timeout: 3000,
                radius: 10,
                // padding: 10,
                autoHeight: true,
                bodyOverflow: true,
                onOpening: function(modal) {
                    modal.startLoading();
                },
                onOpened: function(modal) {
                    modal.stopLoading();
                }
            });

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
                $("#modal").iziModal('open');


                <?php if (isset($redirect) && $redirect) { ?>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 3000);
                <?php } ?>
            <?php } ?>
        });
    </script>
</body>

</html>