<?php
// AI Configuration (OpenAI API)
define('OPENAI_API_KEY', 'your-openai-api-key-here'); // Change this
define('OPENAI_API_URL', 'https://api.openai.com/v1/chat/completions');
define('AI_MODEL', 'gpt-3.5-turbo');

function callOpenAI($prompt, $systemPrompt = "You are a helpful email assistant.") {
    $apiKey = OPENAI_API_KEY;
    
    // Check if API key is not configured or is the default placeholder
    if (empty($apiKey) || $apiKey === 'your-openai-api-key-here') {
        // Fallback to Mock AI if no key is provided
        return generateMockResponse($prompt);
    }
    
    $data = [
        'model' => AI_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 500,
        'temperature' => 0.7
    ];
    
    $ch = curl_init(OPENAI_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        // Fallback to mock if API fails (e.g. quota exceeded or network issue)
        return generateMockResponse($prompt);
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['choices'][0]['message']['content'])) {
        return [
            'success' => true,
            'content' => $result['choices'][0]['message']['content']
        ];
    }
    
    return [
        'success' => false,
        'message' => 'Invalid response from AI API'
    ];
}

function generateMockResponse($prompt) {
    $subject = "Message";
    $body = "Generated email body.";
    $promptLower = strtolower($prompt);
    $tone = 'Professional';
    if (preg_match('/generate a\s+([a-z]+)/i', $promptLower, $m)) {
        $tone = ucfirst($m[1]);
    }

    if (strpos($promptLower, 'leave') !== false) {
        if ($tone === 'Creative') {
            $subject = "Leave Request — coverage arranged and timelines planned";
            $body = "Dear Manager,\n\nI’d like to request a short leave during the mentioned dates. I’ve wrapped my current deliverables and coordinated coverage so work continues smoothly while I’m away. I’ll remain reachable for anything urgent.\n\nAppreciate your consideration.\n\nWarm regards,\nYour Name";
        } elseif ($tone === 'Friendly' || $tone === 'Casual') {
            $subject = "Requesting a short leave (coverage in place)";
            $body = "Hi Manager,\n\nI’d like to take a short leave during the specified dates. My tasks are up to date and a colleague will cover any pending items. I’ll be available for urgent messages.\n\nThanks!\nYour Name";
        } else {
            $subject = "Leave Request with coverage details";
            $body = "Dear Manager,\n\nI would like to request leave for a short period within the specified dates. My current tasks are complete, and coverage has been arranged with a colleague to ensure continuity.\n\nThank you for your understanding.\n\nSincerely,\nYour Name";
        }
    } elseif (strpos($promptLower, 'meeting') !== false) {
        if ($tone === 'Creative') {
            $subject = "Let’s align — meeting to spark momentum";
            $body = "Hi Team,\n\nI’d love to set up a meeting to align on priorities and next steps. Please share your availability, and I’ll send a calendar invite with the agenda and materials.\n\nLooking forward to it!\nYour Name";
        } else {
            $subject = "Meeting Request and agenda confirmation";
            $body = "Hi Team,\n\nI would like to schedule a meeting to review our updates and discuss next steps. Please share your availability so I can send an invite with the agenda.\n\nBest regards,\nYour Name";
        }
    } elseif (strpos($promptLower, 'follow up') !== false || strpos($promptLower, 'follow-up') !== false) {
        if ($tone === 'Creative') {
            $subject = "Circling back — quick check on our discussion";
            $body = "Hi,\n\nJust circling back on our recent conversation. If there’s anything you need from me to move forward, I’m happy to provide it.\n\nThanks and looking forward to your update.\nYour Name";
        } else {
            $subject = "Following up on our previous discussion";
            $body = "Hello,\n\nI’m following up on our recent discussion. Please let me know if there are any updates or if additional information is needed from my side.\n\nThank you.\nYour Name";
        }
    } else {
        if ($tone === 'Creative') {
            $subject = "Quick inquiry — seeking clarity to move ahead";
            $body = "Hello,\n\nI’m reaching out with a brief inquiry to understand the details needed to proceed confidently. A short note with the next steps or any references would be greatly appreciated.\n\nThank you!\nYour Name";
        } else {
            $subject = "Inquiry regarding the requested information";
            $body = "Hello,\n\nI’m writing to request additional details so we can move forward. Please share the relevant information or next steps at your convenience.\n\nThank you.\nYour Name";
        }
    }

    return [
        'success' => true,
        'content' => "SUBJECT: $subject\n\nBODY:\n$body"
    ];
}
?>
