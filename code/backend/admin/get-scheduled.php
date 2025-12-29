<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/admin-session.php';

requireAdminLogin();
header('Content-Type: application/json');

$conn = getDBConnection();

$query = "SELECT id, email_id, send_time, status, created_at FROM scheduled_emails ORDER BY send_time DESC LIMIT 100";
$result = $conn->query($query);
$scheduled = [];

while ($row = $result->fetch_assoc()) {
    $scheduled[] = $row;
}

closeDBConnection($conn);

echo json_encode([
    'success' => true,
    'scheduled' => $scheduled
]);
?>

