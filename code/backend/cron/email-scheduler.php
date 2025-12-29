<?php
/**
 * Email Scheduler Cron Job
 * Run this script every minute using cron: * * * * * php /path/to/email-scheduler.php
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/mail.php';

$conn = getDBConnection();

// Get pending scheduled emails that should be sent now
$currentTime = date('Y-m-d H:i:s');
$query = "SELECT se.id, se.email_id, e.receiver, e.subject, e.body 
          FROM scheduled_emails se 
          JOIN emails e ON se.email_id = e.id 
          WHERE se.status = 'pending' 
          AND se.send_time <= ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $currentTime);
$stmt->execute();
$result = $stmt->get_result();

$sentCount = 0;
$failedCount = 0;

while ($row = $result->fetch_assoc()) {
    $emailResult = sendEmail($row['receiver'], $row['subject'], $row['body']);
    
    if ($emailResult['success']) {
        // Update scheduled email status
        $updateStmt = $conn->prepare("UPDATE scheduled_emails SET status = 'sent' WHERE id = ?");
        $updateStmt->bind_param("i", $row['id']);
        $updateStmt->execute();
        $updateStmt->close();
        
        // Update email status
        $updateEmailStmt = $conn->prepare("UPDATE emails SET status = 'sent' WHERE id = ?");
        $updateEmailStmt->bind_param("i", $row['email_id']);
        $updateEmailStmt->execute();
        $updateEmailStmt->close();
        
        $sentCount++;
    } else {
        // Mark as failed
        $updateStmt = $conn->prepare("UPDATE scheduled_emails SET status = 'failed' WHERE id = ?");
        $updateStmt->bind_param("i", $row['id']);
        $updateStmt->execute();
        $updateStmt->close();
        
        $failedCount++;
    }
}

$stmt->close();
closeDBConnection($conn);

// Log results (optional)
echo "Scheduled emails processed: $sentCount sent, $failedCount failed\n";
?>

