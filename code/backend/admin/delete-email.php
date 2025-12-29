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
$emailId = $data['email_id'] ?? 0;

if (empty($emailId)) {
    echo json_encode(['success' => false, 'message' => 'Email ID is required']);
    exit();
}

$conn = getDBConnection();
$emailId = (int)$emailId;

$stmt = $conn->prepare("DELETE FROM emails WHERE id = ?");
$stmt->bind_param("i", $emailId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Email deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete email']);
}

$stmt->close();
closeDBConnection($conn);
?>

