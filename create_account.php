<?php
session_start();
include('cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $_SESSION['form_data'] = ['username' => $username, 'email' => $email];

    if (!empty($username) && !empty($email) && !empty($password)) {

        $sql = "SELECT username, email FROM userstable WHERE username = '$username' OR email = '$email'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($username == $row['username']) {
                $_SESSION['modalTitle'] = 'Username already exists!';
                $_SESSION['modalMessage'] = '';
                $_SESSION['modalColor'] = '#BD5B5B';
            }

            if ($email == $row['email']) {
                $_SESSION['modalTitle'] = 'Email already exists!';
                $_SESSION['modalMessage'] = '';
                $_SESSION['modalColor'] = '#BD5B5B';
            }
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO userstable (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', 1)";

            if ($connection->query($sql) === TRUE) {
                $_SESSION['modalTitle'] = 'Success';
                $_SESSION['modalMessage'] = 'Account created successfully!';
                $_SESSION['modalColor'] = '#4CAF50';
                $_SESSION['redirect'] = true;

                unset($_SESSION['form_data']);
            } else {
                $_SESSION['modalTitle'] = 'Error';
                $_SESSION['modalMessage'] = 'Error creating account';
                $_SESSION['modalColor'] = '#BD5B5B';
            }
        }
    } else {
        $_SESSION['modalTitle'] = 'Warning';
        $_SESSION['modalMessage'] = 'All fields are required!';
        $_SESSION['modalColor'] = '#FFA500';
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <?php include('header/header.php') ?>
    <!-- styles css, why nasa baba? need muna mag load ang bootstarp at ibang scripts bago ang css custom-->
    <link href="styles/createAccount.css" rel="stylesheet">
</head>

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
                    <input type="text" class="form-control" maxlength="20" id="username" placeholder="Username" form="create-form" name="username" value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" required>
                    <span class="char-counter" style="font-size: .80rem; color: #999;">Max 20 characters</span>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="Email" form="create-form" name="email" value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
                    <span class="char-counter" style="font-size: .80rem; color: #999;">ex: juan@gmail.com</span>
                    <label for="email">Email</label>
                </div>
                <!-- Password Input -->
                <div class="form-floating mb-3 password-container">
                    <input type="password" class="form-control" id="password" placeholder="Password" form="create-form" name="password" minlength="8" maxlength="20" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" required>
                    <label for="password">Password</label>
                </div>

                <!-- Show Password Checkbox -->
                <div class="col-mb-3 pb-3 show-password">
                    <input type="checkbox" id="showpassword" name="showpassword" value="true">
                    <label for="showpassword"> Show Password</label><br>
                    <span class="char-counter" style="font-size: .80rem; color: #999;">
                        8-20 characters, with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.
                    </span>
                </div>
                <button type="submit" class="btn btn-custom w-100" form="create-form">Sign Up</button>
                <!-- <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="true" id="agree" form="create-form" required>
                    <label class="form-check-label" for="agree">I agree to the <a href="#">Privacy Policy</a> and <a>Terms & Conditions</a></label>
                </div> -->
            </div>
        </div>

        <!-- Image Section -->
        <div class="image-section"></div>
    </div>

    <!-- Password toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const showPasswordCheckbox = document.getElementById('showpassword');

            showPasswordCheckbox.addEventListener('change', function() {
                passwordInput.type = this.checked ? 'text' : 'password';
            });
        });

        $(document).ready(function() {
            // iziModal
            $("#modal").iziModal({
                title: <?php echo json_encode($_SESSION['modalTitle'] ?? ''); ?>,
                subtitle: <?php echo json_encode($_SESSION['modalMessage'] ?? ''); ?>,
                headerColor: <?php echo json_encode($_SESSION['modalColor'] ?? ''); ?>,
                width: 400,
                timeoutProgressbar: true,
                transitionIn: 'fadeInDown',
                timeout: 3000,
                radius: 10,
                autoHeight: true,
                bodyOverflow: true,
                onOpening: function(modal) {
                    modal.startLoading();
                },
                onOpened: function(modal) {
                    modal.stopLoading();
                }
            });

            // Open modal if session is set
            <?php if (isset($_SESSION['modalTitle'])): ?>
                $("#modal").iziModal('open');

                <?php if (isset($_SESSION['redirect']) && $_SESSION['redirect']): ?>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 3000);
                <?php endif; ?>

                <?php
                // Clear session data after use
                unset($_SESSION['modalTitle'], $_SESSION['modalMessage'], $_SESSION['modalColor'], $_SESSION['redirect']);
                ?>
            <?php endif; ?>
        });
    </script>

</body>

</html>