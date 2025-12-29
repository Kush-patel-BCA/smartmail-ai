<?php
// Session Management
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        $basePath = dirname(dirname(dirname($_SERVER['PHP_SELF'])));
        header('Location: ' . $basePath . '/login.php');
        exit();
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserEmail() {
    return $_SESSION['user_email'] ?? null;
}

function getUserName() {
    return $_SESSION['user_name'] ?? null;
}

function logout() {
    session_unset();
    session_destroy();
    $basePath = dirname(dirname(dirname($_SERVER['PHP_SELF'])));
    header('Location: ' . $basePath . '/login.php');
    exit();
}
?>

