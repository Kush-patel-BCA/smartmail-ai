# 🚀 How to Run SmartMail AI Project

## Quick Start Guide

### Prerequisites
- ✅ PHP 7.4 or higher
- ✅ MySQL/MariaDB
- ✅ Web server (Apache/Nginx) OR PHP built-in server
- ✅ (Optional) Composer for PHPMailer

---

## Step-by-Step Instructions

### Step 1: Setup Database

**Option A: Using MySQL Command Line**
```bash
mysql -u root -p
```
Then run:
```sql
CREATE DATABASE smartmail_ai;
USE smartmail_ai;
SOURCE /Users/arjun/smartmail-ai/database/smartmail.sql;
EXIT;
```

**Option B: Using phpMyAdmin**
1. Open phpMyAdmin
2. Create new database: `smartmail_ai`
3. Import `database/smartmail.sql` file

**Option C: Direct Import**
```bash
mysql -u root -p smartmail_ai < /Users/arjun/smartmail-ai/database/smartmail.sql
```

### Step 2: Configure Database Connection

Edit `backend/config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password (leave empty if no password)
define('DB_NAME', 'smartmail_ai');
```

### Step 3: Configure AI (Optional but Recommended)

Edit `backend/config/ai-config.php`:
```php
define('OPENAI_API_KEY', 'sk-your-actual-api-key-here');
```

Get API key from: https://platform.openai.com/api-keys

> **Note:** Without API key, AI features won't work, but other features will.

### Step 4: Start the Server

**Option A: PHP Built-in Server (Easiest for Testing)**
```bash
cd /Users/arjun/smartmail-ai
php -S localhost:8000
```

Then open: http://localhost:8000

**Option B: Using Apache/Nginx**
1. Copy project to web root (e.g., `/var/www/html/smartmail-ai` or `htdocs/smartmail-ai`)
2. Access via: `http://localhost/smartmail-ai`

**Option C: Using XAMPP/MAMP**
1. Copy project to `htdocs/smartmail-ai` (XAMPP) or `htdocs/smartmail-ai` (MAMP)
2. Start Apache and MySQL
3. Access: `http://localhost/smartmail-ai`

### Step 5: Access the Application

1. Open browser: http://localhost:8000 (or your server URL)
2. Click "Sign Up" to create an account
3. Login and start using!

---

## Optional: Setup Scheduled Emails (Cron Job)

For scheduled emails to work, set up a cron job:

```bash
crontab -e
```

Add this line (runs every minute):
```
* * * * * php /Users/arjun/smartmail-ai/backend/cron/email-scheduler.php >> /tmp/smartmail-cron.log 2>&1
```

---

## Troubleshooting

### ❌ "Connection failed" Error
- Check database credentials in `backend/config/db.php`
- Verify MySQL is running: `mysql -u root -p`
- Check database exists: `SHOW DATABASES;`

### ❌ "AI not working"
- Verify API key is set in `backend/config/ai-config.php`
- Check API quota at OpenAI dashboard
- Review browser console (F12) for errors

### ❌ "Page not found" or 404
- Make sure you're in the project directory
- Check PHP server is running
- Verify file paths are correct

### ❌ "Permission denied"
```bash
chmod 755 backend/cron/email-scheduler.php
chmod -R 755 backend/
```

### ❌ Emails not sending
- For localhost: PHP `mail()` may not work
- Use PHPMailer with SMTP (see `backend/config/mail.php`)
- Check PHP error logs

---

## Testing the Application

1. **Register Account**: Create a new user account
2. **Login**: Use your credentials to login
3. **Compose Email**: Click "Compose" button
4. **Test AI**: Enter command like "Write a professional email to a client"
5. **Send Email**: Fill recipient and send
6. **View Emails**: Check inbox, sent, drafts
7. **Categories**: Emails auto-categorize based on content

---

## Development Tips

- **Check PHP Version**: `php -v` (need 7.4+)
- **Check MySQL**: `mysql --version`
- **View Errors**: Check browser console (F12) and PHP error logs
- **Test Database**: `mysql -u root -p smartmail_ai -e "SHOW TABLES;"`

---

## Quick Commands Reference

```bash
# Start PHP server
cd /Users/arjun/smartmail-ai && php -S localhost:8000

# Check database
mysql -u root -p smartmail_ai -e "SELECT COUNT(*) FROM users;"

# View logs (if cron is set up)
tail -f /tmp/smartmail-cron.log
```

---

**Need more help?** Check `README.md` for detailed documentation.

