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
$sendTime = $data['send_time'] ?? '';

if (empty($emailId) || empty($sendTime)) {
    echo json_encode(['success' => false, 'message' => 'Email ID and send time are required']);
    exit();
}

$conn = getDBConnection();
$emailId = (int)$emailId;
$sendTime = $conn->real_escape_string($sendTime);

// Verify email belongs to user
$stmt = $conn->prepare("SELECT id FROM emails WHERE id = ? AND sender = ?");
$sender = getUserEmail();
$stmt->bind_param("is", $emailId, $sender);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Email not found']);
    $stmt->close();
    closeDBConnection($conn);
    exit();
}

$stmt->close();

// Schedule email
$stmt = $conn->prepare("INSERT INTO scheduled_emails (email_id, send_time, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("is", $emailId, $sendTime);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Email scheduled successfully',
        'schedule_id' => $conn->insert_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to schedule email']);
}

$stmt->close();
closeDBConnection($conn);
?>

