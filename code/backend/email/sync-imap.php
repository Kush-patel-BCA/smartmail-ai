<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/imap.php';
require_once __DIR__ . '/../auth/session.php';

requireLogin();
header('Content-Type: application/json');

if (!function_exists('imap_open')) {
    echo json_encode([
        'success' => false,
        'message' => 'IMAP extension is not enabled. Please enable php_imap in your php.ini configuration.'
    ]);
    exit;
}

$conn = getDBConnection();
$userEmail = getUserEmail(); // In a real app, we might use the logged-in user's email if it matches IMAP

try {
    // 1. Connect to IMAP
    $inbox = @imap_open(IMAP_HOST, IMAP_USERNAME, IMAP_PASSWORD);

    if (!$inbox) {
        throw new Exception('Cannot connect to Gmail: ' . imap_last_error());
    }

    // 2. Search for emails (e.g., recent ones)
    // ALL, UNSEEN, RECENT, etc.
    // Limiting to last 10 for performance in this demo
    $emails = imap_search($inbox, 'ALL');

    if ($emails) {
        rsort($emails); // Newest first
        $emails = array_slice($emails, 0, 10); // Limit to 10

        $count = 0;

        foreach ($emails as $email_number) {
            // 3. Fetch Overview
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $message = imap_fetchbody($inbox, $email_number, 1); // 1 is usually plain text, 2 is HTML depending on structure
            
            // If body is empty, try section 2 (often HTML part)
            if (empty($message)) {
                $message = imap_fetchbody($inbox, $email_number, 2);
            }

            // Decode message if quoted-printable or base64
            $structure = imap_fetchstructure($inbox, $email_number);
            if (isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
                $part = $structure->parts[1];
                if ($part->encoding == 3) { // Base64
                    $message = base64_decode($message);
                } elseif ($part->encoding == 4) { // Quoted-Printable
                    $message = quoted_printable_decode($message);
                }
            } else {
                 // Simple structure
                if (isset($structure->encoding)) {
                    if ($structure->encoding == 3) {
                         $message = base64_decode($message);
                    } elseif ($structure->encoding == 4) {
                         $message = quoted_printable_decode($message);
                    }
                }
            }

            $subject = isset($overview[0]->subject) ? $overview[0]->subject : '(No Subject)';
            $from = isset($overview[0]->from) ? $overview[0]->from : '(Unknown)';
            $date = isset($overview[0]->date) ? date('Y-m-d H:i:s', strtotime($overview[0]->date)) : date('Y-m-d H:i:s');
            
            // Extract email address from "Name <email@example.com>"
            preg_match('/<([^>]+)>/', $from, $matches);
            $senderEmail = isset($matches[1]) ? $matches[1] : $from;

            // 4. Check if exists in DB to avoid duplicates
            // We'll assume if subject + sender + created_at matches, it's the same.
            // A better way is to store Message-ID.
            $checkSql = "SELECT id FROM emails WHERE sender = ? AND subject = ? AND created_at = ? AND receiver = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ssss", $senderEmail, $subject, $date, $userEmail);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows == 0) {
                // 5. Insert into DB
                $insertSql = "INSERT INTO emails (sender, receiver, subject, body, category, status, created_at) VALUES (?, ?, ?, ?, 'General', 'unread', ?)";
                $insertStmt = $conn->prepare($insertSql);
                // Truncate body if too long for TEXT (65k chars), though usually fine
                $bodyToSave = substr($message, 0, 60000); 
                
                $insertStmt->bind_param("sssss", $senderEmail, $userEmail, $subject, $bodyToSave, $date);
                if ($insertStmt->execute()) {
                    $count++;
                }
                $insertStmt->close();
            }
            $checkStmt->close();
        }
    }

    imap_close($inbox);

    echo json_encode([
        'success' => true,
        'message' => "Synced successfully. Found $count new emails."
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

closeDBConnection($conn);
?>
