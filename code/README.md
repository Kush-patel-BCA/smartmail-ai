# SmartMail AI - Intelligent Email Management System

SmartMail AI is a Gmail-inspired web-based email platform designed to intelligently manage and automate email communication. The system categorizes IT, Business, and Personal emails automatically and integrates an AI-powered assistant that can generate, rewrite, and schedule emails on user command.

## 🚀 Features

### Core Email Management
- **User Authentication**: Secure login and registration system
- **Email Organization**: Inbox, Sent, Drafts, and Trash folders
- **Email Threading**: Organize related emails together
- **Search & Filters**: Quick search and category-based filtering
- **Read/Unread Status**: Track email status

### Intelligent Categorization
Emails are automatically classified into:
- **IT Emails**: Server alerts, deployment notifications, Git updates
- **Business Emails**: Client communications, invoices, meetings
- **Personal/General Emails**: Personal correspondence
- **Promotions**: Marketing and promotional emails
- **Spam**: Filtered spam messages

### AI Email Assistant 🤖
- Generate email content from natural language commands
- Improve and rewrite existing emails
- Change tone (Professional, Friendly, Formal, Casual)
- Auto-generate subject lines
- Example commands:
  - "Write a professional follow-up email to a client"
  - "Draft an IT outage alert mail"
  - "Send meeting reminder to team"

### Email Scheduling ⏰
- Send emails immediately
- Schedule emails for future date & time
- Automated cron job for scheduled delivery

### Modern UI 🎨
- Smooth animations and transitions
- Gmail-like responsive layout
- Dark sidebar with light main content
- Animated buttons and loaders
- Mobile-friendly design

## 📋 Tech Stack

### Frontend
- HTML5
- CSS3
- JavaScript (ES6)
- Bootstrap 5
- Tailwind CSS (via CDN)
- Font Awesome Icons

### Backend
- PHP (Core PHP / MVC style)
- MySQL Database
- PHPMailer (for email sending)
- Cron Jobs (for scheduled emails)

### AI Integration
- OpenAI API (GPT-3.5-turbo)
- PHP cURL for API calls

## 📁 Project Structure

```
smartmail-ai/
│
├── index.php                # Landing page
├── login.php                # Login page
├── register.php             # Registration page
├── logout.php               # Logout handler
├── dashboard.php            # Main Gmail-like UI
│
├── assets/
│   ├── css/
│   │   ├── style.css        # Main styles
│   │   └── animations.css   # Animation styles
│   │
│   ├── js/
│   │   ├── main.js          # Main JavaScript
│   │   ├── ajax.js          # AJAX functions
│   │   ├── ai-email.js      # AI email generation
│   │   └── animations.js    # Animation utilities
│   │
│   ├── images/
│   └── fonts/
│
├── backend/
│   ├── config/
│   │   ├── db.php           # Database configuration
│   │   ├── mail.php         # Email configuration
│   │   └── ai-config.php    # AI API configuration
│   │
│   ├── auth/
│   │   ├── login.php        # Login handler
│   │   ├── register.php     # Registration handler
│   │   └── session.php       # Session management
│   │
│   ├── email/
│   │   ├── send-email.php   # Send email handler
│   │   ├── schedule-email.php # Schedule email handler
│   │   ├── fetch-emails.php # Fetch emails handler
│   │   ├── categorize-email.php # Email categorization
│   │   └── delete-email.php  # Delete email handler
│   │
│   ├── ai/
│   │   └── generate-email.php # AI email generation
│   │
│   └── cron/
│       └── email-scheduler.php # Cron job for scheduled emails
│
├── database/
│   └── smartmail.sql        # Database schema
│
├── .htaccess                # Apache configuration
└── README.md                # This file
```

## 🛠️ Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHPMailer - optional)

### Setup Steps

1. **Clone or download the project**
   ```bash
   cd smartmail-ai
   ```

2. **Create the database**
   ```bash
   mysql -u root -p < database/smartmail.sql
   ```

3. **Configure database connection**
   Edit `backend/config/db.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'smartmail_ai');
   ```

4. **Configure email settings**
   Edit `backend/config/mail.php`:
   - For basic mail(): No changes needed (uses PHP mail())
   - For PHPMailer: Uncomment PHPMailer code and configure SMTP settings

5. **Configure AI API**
   Edit `backend/config/ai-config.php`:
   ```php
   define('OPENAI_API_KEY', 'your-openai-api-key-here');
   ```

6. **Set up cron job for scheduled emails**
   ```bash
   crontab -e
   ```
   Add this line (runs every minute):
   ```
   * * * * * php /path/to/smartmail-ai/backend/cron/email-scheduler.php
   ```

7. **Set proper permissions**
   ```bash
   chmod 755 backend/cron/email-scheduler.php
   ```

8. **Access the application**
   - Open `http://localhost/smartmail-ai` in your browser
   - Register a new account or login

## 📝 Usage

### Creating an Account
1. Navigate to the registration page
2. Fill in your name, email, and password
3. Click "Register"

### Composing Emails with AI
1. Click the "Compose" button
2. Enter a command in the AI Assistant field (e.g., "Write a professional follow-up email")
3. Select the desired tone
4. Click "Generate"
5. Review and edit the generated email
6. Fill in the recipient email
7. Click "Send" or "Save Draft"

### Scheduling Emails
1. Compose an email
2. Fill in the "Schedule Email" field with date and time
3. Click "Send"
4. The email will be sent automatically at the scheduled time

### Viewing and Managing Emails
- Click on email categories in the sidebar to filter
- Use the search bar to find specific emails
- Click on an email to view full content
- Delete emails from the email view modal

## 🔒 Security Features

- Password hashing using PHP `password_hash()`
- SQL injection prevention with prepared statements
- Session-based authentication
- XSS protection headers
- Secure file access restrictions

## 🎨 Customization

### Changing Colors
Edit `assets/css/style.css`:
```css
:root {
    --primary-color: #4285f4;
    --secondary-color: #34a853;
    /* ... */
}
```

### Adding Categories
1. Update database schema in `database/smartmail.sql`
2. Add keywords in `backend/email/categorize-email.php`
3. Update UI in `dashboard.php`

## 🐛 Troubleshooting

### Emails not sending
- Check PHP mail() configuration or SMTP settings
- Verify email server credentials
- Check server logs

### AI not working
- Verify OpenAI API key is set correctly
- Check API quota and billing
- Review error messages in browser console

### Scheduled emails not sending
- Verify cron job is running
- Check file permissions
- Review cron job logs

## 📄 License

This project is open source and available for educational purposes.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For issues and questions, please open an issue on the project repository.

---

**Built with ❤️ using PHP, JavaScript, and AI**

