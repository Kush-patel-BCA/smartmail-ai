<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/session.php';

requireLogin();
header('Content-Type: application/json');

$conn = getDBConnection();
$userEmail = getUserEmail();
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$params = [];
$types = "";
$whereClauses = [];

// Determine query based on status (folder)
if ($status === 'sent') {
    // Sent folder: Emails sent by user
    $whereClauses[] = "sender = ?";
    $whereClauses[] = "status = 'sent'";
    $params[] = $userEmail;
    $types .= "s";
} elseif ($status === 'draft') {
    // Drafts folder: Drafts by user
    $whereClauses[] = "sender = ?";
    $whereClauses[] = "status = 'draft'";
    $params[] = $userEmail;
    $types .= "s";
} elseif ($status === 'trash') {
    // Trash folder: Emails sent OR received by user that are in trash
    $whereClauses[] = "(sender = ? OR receiver = ?)";
    $whereClauses[] = "status = 'trash'";
    $params[] = $userEmail;
    $params[] = $userEmail;
    $types .= "ss";
} else {
    // Inbox (default): Emails received by user, not in trash
    $whereClauses[] = "receiver = ?";
    $whereClauses[] = "status NOT IN ('trash', 'draft', 'sent')";
    $params[] = $userEmail;
    $types .= "s";
}

// Additional filters
if (!empty($category)) {
    $whereClauses[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($search)) {
    $whereClauses[] = "(subject LIKE ? OR body LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

// Build final query
$query = "SELECT * FROM emails";
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$emails = [];
while ($row = $result->fetch_assoc()) {
    $emails[] = [
        'id' => $row['id'],
        'sender' => $row['sender'],
        'receiver' => $row['receiver'],
        'subject' => $row['subject'],
        'body' => $row['body'],
        'category' => $row['category'],
        'status' => $row['status'],
        'created_at' => $row['created_at']
    ];
}

echo json_encode([
    'success' => true,
    'emails' => $emails,
    'count' => count($emails)
]);

$stmt->close();
closeDBConnection($conn);
?>
