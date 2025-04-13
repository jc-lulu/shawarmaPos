<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include('../cedric_dbConnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    $check_username_sql = "SELECT * FROM userstable WHERE username = '$username'";
    $result = $connection->query($check_username_sql);
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }
    
    $check_email_sql = "SELECT * FROM userstable WHERE email = '$email'";
    $result = $connection->query($check_email_sql);
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO userstable (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
    
    if ($connection->query($insert_sql)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Account created successfully',
            'user_id' => $connection->insert_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $connection->error]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$connection->close();
?>