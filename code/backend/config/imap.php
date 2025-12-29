<?php
// IMAP Configuration
// ----------------------------------------------------------------
// IMPORTANT: You must enable 'Less Secure Apps' or use an 'App Password'
// ----------------------------------------------------------------

// GMAIL Configuration (Default)
// 1. Go to Google Account -> Security
// 2. Enable 2-Step Verification
// 3. Go to App Passwords (search for it)
// 4. Generate a new password for 'Mail'
define('IMAP_HOST', '{imap.gmail.com:993/imap/ssl}INBOX');

// OUTLOOK / HOTMAIL Configuration
// define('IMAP_HOST', '{outlook.office365.com:993/imap/ssl}INBOX');

// YAHOO Configuration
// define('IMAP_HOST', '{imap.mail.yahoo.com:993/imap/ssl}INBOX');

// YOUR CREDENTIALS
// Replace the values below with your actual email and app password
define('IMAP_USERNAME', 'your-email@gmail.com');
define('IMAP_PASSWORD', 'your-app-password'); 

// SYNC SETTINGS
define('IMAP_LIMIT', 10); // How many emails to fetch at once
?>
