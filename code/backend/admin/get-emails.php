<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/admin-session.php';

requireAdminLogin();
header('Content-Type: application/json');

$conn = getDBConnection();
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT id, sender, receiver, subject, body, category, status, created_at FROM emails WHERE 1=1";

if (!empty($category)) {
    $category = $conn->real_escape_string($category);
    $query .= " AND category = '$category'";
}

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (subject LIKE '%$search%' OR body LIKE '%$search%' OR sender LIKE '%$search%' OR receiver LIKE '%$search%')";
}

$query .= " ORDER BY created_at DESC LIMIT 100";

$result = $conn->query($query);
$emails = [];

while ($row = $result->fetch_assoc()) {
    $emails[] = $row;
}

closeDBConnection($conn);

echo json_encode([
    'success' => true,
    'emails' => $emails
]);
?>

