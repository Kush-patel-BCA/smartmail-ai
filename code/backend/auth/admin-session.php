<?php
// Admin Session Management
session_start();

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        $basePath = dirname(dirname(dirname($_SERVER['PHP_SELF'])));
        header('Location: ' . $basePath . '/admin/login.php');
        exit();
    }
}

function getAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

function getAdminUsername() {
    return $_SESSION['admin_username'] ?? null;
}

function getAdminName() {
    return $_SESSION['admin_name'] ?? null;
}

function getAdminRole() {
    return $_SESSION['admin_role'] ?? null;
}

function isSuperAdmin() {
    return getAdminRole() === 'super_admin';
}

function adminLogout() {
    session_unset();
    session_destroy();
    $basePath = dirname(dirname(dirname($_SERVER['PHP_SELF'])));
    header('Location: ' . $basePath . '/admin/login.php');
    exit();
}
?>

