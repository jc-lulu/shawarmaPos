<?php
session_start();

// Unset all session var
$_SESSION = array();
// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

session_destroy(); // Destroy the session

header("Location: ../index.php");
exit;
