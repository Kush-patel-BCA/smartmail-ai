<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/mail.php';
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
$sendNow = $data['sendNow'] ?? true;
$draftId = $data['draftId'] ?? null;

if (empty($to) || empty($subject) || empty($body)) {
    echo json_encode(['success' => false, 'message' => 'To, subject, and body are required']);
    exit();
}

$conn = getDBConnection();
$sender = getUserEmail();
$receiver = $to;
$subjectEscaped = $subject;
$bodyEscaped = $body;

// Categorize email
$category = categorizeEmail($subject, $body, $receiver);

// Check if updating existing draft or creating new email
if ($draftId && $sendNow) {
    // Update existing draft and send it
    $stmt = $conn->prepare("UPDATE emails SET receiver = ?, subject = ?, body = ?, category = ?, status = 'sent' WHERE id = ? AND sender = ? AND status = 'draft'");
    $stmt->bind_param("ssssis", $receiver, $subjectEscaped, $bodyEscaped, $category, $draftId, $sender);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $emailId = $draftId;
        $stmt->close();
        
        // Send email immediately
        $result = sendEmail($to, $subject, $body);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Email sent successfully',
                'email_id' => $emailId
            ]);
        } else {
            // Revert to draft if sending failed
            $stmt2 = $conn->prepare("UPDATE emails SET status = 'draft' WHERE id = ?");
            $stmt2->bind_param("i", $emailId);
            $stmt2->execute();
            $stmt2->close();
            
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        closeDBConnection($conn);
        exit();
    } else {
        $stmt->close();
        // If update failed, continue to create new email
    }
}

// Save email to database (new email or draft update failed)
$status = $sendNow ? 'sent' : 'draft';
$stmt = $conn->prepare("INSERT INTO emails (sender, receiver, subject, body, category, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $sender, $receiver, $subjectEscaped, $bodyEscaped, $category, $status);

if ($stmt->execute()) {
    $emailId = $conn->insert_id;
    
    if ($sendNow) {
        // Send email immediately
        $result = sendEmail($to, $subject, $body);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Email sent successfully',
                'email_id' => $emailId
            ]);
        } else {
            // Update status if sending failed
            $stmt2 = $conn->prepare("UPDATE emails SET status = 'draft' WHERE id = ?");
            $stmt2->bind_param("i", $emailId);
            $stmt2->execute();
            $stmt2->close();
            
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Email saved as draft',
            'email_id' => $emailId
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save email']);
}

$stmt->close();
closeDBConnection($conn);
?>

