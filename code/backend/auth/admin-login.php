<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/admin-session.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
    exit();
}

$conn = getDBConnection();
$username = $conn->real_escape_string($username);

$stmt = $conn->prepare("SELECT id, username, email, password, full_name, role, status FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    
    // Check if admin is active
    if ($admin['status'] !== 'active') {
        echo json_encode(['success' => false, 'message' => 'Your account is inactive. Contact administrator.']);
        $stmt->close();
        closeDBConnection($conn);
        exit();
    }
    
    if (password_verify($password, $admin['password'])) {
        // Update last login
        $updateStmt = $conn->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
        $updateStmt->bind_param("i", $admin['id']);
        $updateStmt->execute();
        $updateStmt->close();
        
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_role'] = $admin['role'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'admin' => [
                'id' => $admin['id'],
                'username' => $admin['username'],
                'name' => $admin['full_name'],
                'role' => $admin['role']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}

$stmt->close();
closeDBConnection($conn);
?>

