<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/categorize-email.php';

requireLogin();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$to = $data['to'] ?? '';
$subject = $data['subject'] ?? '';
$body = $data['body'] ?? '';
$draftId = $data['draftId'] ?? null;

// Allow saving draft with minimal content
if (empty($subject) && empty($body)) {
    echo json_encode(['success' => false, 'message' => 'Please add some content to save as draft']);
    exit();
}

$conn = getDBConnection();
$sender = getUserEmail();
$receiver = $to ?: $sender; // Default to sender if no recipient

// Categorize email if we have content
$category = 'General';
if (!empty($subject) || !empty($body)) {
    $category = categorizeEmail($subject, $body, $receiver);
}

if ($draftId) {
    // Update existing draft
    $stmt = $conn->prepare("UPDATE emails SET receiver = ?, subject = ?, body = ?, category = ? WHERE id = ? AND sender = ? AND status = 'draft'");
    $stmt->bind_param("ssssis", $receiver, $subject, $body, $category, $draftId, $sender);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Draft updated successfully',
            'draft_id' => $draftId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update draft']);
    }
    $stmt->close();
} else {
    // Create new draft
    $status = 'draft';
    $stmt = $conn->prepare("INSERT INTO emails (sender, receiver, subject, body, category, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $sender, $receiver, $subject, $body, $category, $status);
    
    if ($stmt->execute()) {
        $emailId = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Draft saved successfully',
            'draft_id' => $emailId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save draft']);
    }
    $stmt->close();
}

closeDBConnection($conn);
?>

