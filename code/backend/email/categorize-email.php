<?php
// Email Categorization Logic
function categorizeEmail($subject, $body, $sender) {
    $subject = strtolower($subject);
    $body = strtolower($body);
    $sender = strtolower($sender);
    
    // IT Keywords
    $itKeywords = ['server', 'deployment', 'git', 'alert', 'error', 'log', 'database', 'api', 'endpoint', 
                   'ssl', 'certificate', 'backup', 'monitoring', 'uptime', 'downtime', 'maintenance'];
    
    // Business Keywords
    $businessKeywords = ['invoice', 'payment', 'meeting', 'client', 'proposal', 'contract', 'agreement',
                        'deadline', 'project', 'delivery', 'order', 'quote', 'estimate'];
    
    // Promotions Keywords
    $promotionKeywords = ['sale', 'discount', 'offer', 'deal', 'promo', 'coupon', 'special', 'limited time'];
    
    // Spam Keywords
    $spamKeywords = ['winner', 'congratulations', 'claim now', 'urgent action', 'click here', 'free money'];
    
    // Check IT
    foreach ($itKeywords as $keyword) {
        if (strpos($subject, $keyword) !== false || strpos($body, $keyword) !== false) {
            return 'IT';
        }
    }
    
    // Check Business
    foreach ($businessKeywords as $keyword) {
        if (strpos($subject, $keyword) !== false || strpos($body, $keyword) !== false) {
            return 'Business';
        }
    }
    
    // Check Spam
    foreach ($spamKeywords as $keyword) {
        if (strpos($subject, $keyword) !== false || strpos($body, $keyword) !== false) {
            return 'Spam';
        }
    }
    
    // Check Promotions
    foreach ($promotionKeywords as $keyword) {
        if (strpos($subject, $keyword) !== false || strpos($body, $keyword) !== false) {
            return 'Promotions';
        }
    }
    
    // Default to General
    return 'General';
}
?>

