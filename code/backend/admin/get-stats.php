<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/admin-session.php';

requireAdminLogin();
header('Content-Type: application/json');

$conn = getDBConnection();

// Get total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$totalUsers = $result->fetch_assoc()['count'];

// Get total emails
$result = $conn->query("SELECT COUNT(*) as count FROM emails");
$totalEmails = $result->fetch_assoc()['count'];

// Get sent emails
$result = $conn->query("SELECT COUNT(*) as count FROM emails WHERE status = 'sent'");
$sentEmails = $result->fetch_assoc()['count'];

// Get scheduled emails
$result = $conn->query("SELECT COUNT(*) as count FROM scheduled_emails WHERE status = 'pending'");
$scheduledEmails = $result->fetch_assoc()['count'];

// Get category distribution
$result = $conn->query("SELECT category, COUNT(*) as count FROM emails GROUP BY category");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[$row['category']] = $row['count'];
}

// Get status distribution
$result = $conn->query("SELECT status, COUNT(*) as count FROM emails GROUP BY status");
$statuses = [];
while ($row = $result->fetch_assoc()) {
    $statuses[$row['status']] = $row['count'];
}

// Get recent users
$result = $conn->query("SELECT id, name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$recentUsers = [];
while ($row = $result->fetch_assoc()) {
    $recentUsers[] = $row;
}

// Get recent emails
$result = $conn->query("SELECT id, sender, receiver, subject, created_at, category FROM emails ORDER BY created_at DESC LIMIT 5");
$recentEmails = [];
while ($row = $result->fetch_assoc()) {
    $recentEmails[] = $row;
}

closeDBConnection($conn);

echo json_encode([
    'success' => true,
    'stats' => [
        'totalUsers' => $totalUsers,
        'totalEmails' => $totalEmails,
        'sentEmails' => $sentEmails,
        'scheduledEmails' => $scheduledEmails,
        'categories' => $categories,
        'statuses' => $statuses,
        'recentUsers' => $recentUsers,
        'recentEmails' => $recentEmails
    ]
]);
?>

