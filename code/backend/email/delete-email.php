<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/session.php';

requireLogin();
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
$userEmail = getUserEmail();
$emailId = (int)$emailId;

// Verify email belongs to user (either as sender or receiver)
$stmt = $conn->prepare("SELECT id FROM emails WHERE id = ? AND (receiver = ? OR sender = ?)");
$stmt->bind_param("iss", $emailId, $userEmail, $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Email not found or access denied']);
    $stmt->close();
    closeDBConnection($conn);
    exit();
}

$stmt->close();

// Update status to trash (soft delete) or hard delete
$action = $data['permanent'] ?? false;
if ($action) {
    $stmt = $conn->prepare("DELETE FROM emails WHERE id = ?");
} else {
    $stmt = $conn->prepare("UPDATE emails SET status = 'trash' WHERE id = ?");
}
$stmt->bind_param("i", $emailId);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => $action ? 'Email deleted permanently' : 'Email moved to trash'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete email']);
}

$stmt->close();
closeDBConnection($conn);
?>
