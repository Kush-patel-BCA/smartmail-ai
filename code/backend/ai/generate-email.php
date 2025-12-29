<?php
require_once __DIR__ . '/../config/ai-config.php';
require_once __DIR__ . '/../auth/session.php';

requireLogin();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$command = $data['command'] ?? '';
$tone = $data['tone'] ?? 'Professional';
$context = $data['context'] ?? '';

if (empty($command)) {
    echo json_encode(['success' => false, 'message' => 'Command is required']);
    exit();
}

// Build prompt
$systemPrompt = "You are a professional email assistant. Generate well-structured, clear, and appropriate emails based on user requests.";
$prompt = "Generate a $tone email based on this request: $command";

if (!empty($context)) {
    $prompt .= "\n\nAdditional context: $context";
}

$prompt .= "\n\nPlease provide:\n1. A clear and concise subject line\n2. A well-written email body\n\nFormat your response as:\nSUBJECT: [subject line]\n\nBODY:\n[email body]";

$result = callOpenAI($prompt, $systemPrompt);

if ($result['success']) {
    // Parse response to extract subject and body
    $content = $result['content'];
    $subject = '';
    $body = '';
    
    if (preg_match('/SUBJECT:\s*(.+?)(?:\n|$)/i', $content, $subjectMatch)) {
        $subject = trim($subjectMatch[1]);
    }
    
    if (preg_match('/BODY:\s*(.+)/is', $content, $bodyMatch)) {
        $body = trim($bodyMatch[1]);
    } else {
        // If no BODY tag, use everything after SUBJECT
        $body = preg_replace('/SUBJECT:.*?\n\n?/i', '', $content);
        $body = trim($body);
    }
    
    // If subject not found, generate one
    if (empty($subject)) {
        $subjectPrompt = "Generate a concise email subject line for: $command";
        $subjectResult = callOpenAI($subjectPrompt, "You are an email subject line generator. Provide only the subject line, no additional text.");
        if ($subjectResult['success']) {
            $subject = trim($subjectResult['content']);
        } else {
            $subject = "Email from SmartMail AI";
        }
    }
    
    // If body is empty, use the full content
    if (empty($body)) {
        $body = $content;
    }
    
    echo json_encode([
        'success' => true,
        'subject' => $subject,
        'body' => $body,
        'raw_response' => $content
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => $result['message']
    ]);
}
?>

