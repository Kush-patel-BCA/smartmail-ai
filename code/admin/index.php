<?php
// Redirect to login if not logged in
require_once __DIR__ . '/../backend/auth/admin-session.php';
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>

