<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/admin-session.php';

requireAdminLogin();
header('Content-Type: application/json');

$conn = getDBConnection();
$search = $_GET['search'] ?? '';

$query = "SELECT id, name, email, created_at FROM users";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}
$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

closeDBConnection($conn);

echo json_encode([
    'success' => true,
    'users' => $users
]);
?>

