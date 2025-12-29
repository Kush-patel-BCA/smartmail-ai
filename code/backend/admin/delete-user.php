<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/admin-session.php';

requireAdminLogin();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? 0;

if (empty($userId)) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit();
}

$conn = getDBConnection();
$userId = (int)$userId;

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
}

$stmt->close();
closeDBConnection($conn);
?>

