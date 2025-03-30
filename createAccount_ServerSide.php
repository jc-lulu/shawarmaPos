<?php
session_start();
include('cedric_dbConnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Prepare SQL to avoid SQL Injection
        $stmt = $connection->prepare("SELECT username, email FROM userstable WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($username == $row['username']) {
                $_SESSION['modalTitle'] = 'Alert';
                $_SESSION['modalMessage'] = 'Username already exists!';
                $_SESSION['modalColor'] = '#BD5B5B';
            } else if ($email == $row['email']) {
                $_SESSION['modalTitle'] = 'Alert';
                $_SESSION['modalMessage'] = 'Email already exists!';
                $_SESSION['modalColor'] = '#BD5B5B';
            }
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare insert query
            $stmt = $connection->prepare("INSERT INTO userstable (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['modalTitle'] = 'Success';
                $_SESSION['modalMessage'] = 'Account created successfully!';
                $_SESSION['modalColor'] = '#4CAF50';
                $_SESSION['redirect'] = true;
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

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
