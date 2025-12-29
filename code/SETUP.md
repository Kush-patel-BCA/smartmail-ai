# SmartMail AI - Quick Setup Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE smartmail_ai;
USE smartmail_ai;
SOURCE database/smartmail.sql;
EXIT;
```

### Step 2: Configure Database
Edit `backend/config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
define('DB_NAME', 'smartmail_ai');
```

### Step 3: Configure AI (Optional but Recommended)
Edit `backend/config/ai-config.php`:
```php
define('OPENAI_API_KEY', 'sk-your-actual-api-key-here');
```
Get your API key from: https://platform.openai.com/api-keys

### Step 4: Configure Email (Optional)
For basic setup, PHP's `mail()` function is used by default.

For production, edit `backend/config/mail.php` and uncomment PHPMailer code, then:
```bash
composer install
```

### Step 5: Set Up Cron Job (For Scheduled Emails)
```bash
crontab -e
```
Add:
```
* * * * * php /absolute/path/to/smartmail-ai/backend/cron/email-scheduler.php >> /var/log/smartmail-cron.log 2>&1
```

### Step 6: Set Permissions
```bash
chmod 755 backend/cron/email-scheduler.php
chmod 755 backend/
```

### Step 7: Access Application
Open in browser:
```
http://localhost/smartmail-ai
```

## ✅ Verification Checklist

- [ ] Database created and tables imported
- [ ] Database credentials configured
- [ ] OpenAI API key set (optional)
- [ ] Cron job configured (for scheduled emails)
- [ ] File permissions set correctly
- [ ] Web server running (Apache/Nginx)
- [ ] PHP version 7.4+ installed

## 🐛 Common Issues

### "Connection failed" error
- Check database credentials in `backend/config/db.php`
- Verify MySQL is running: `sudo service mysql status`

### AI not generating emails
- Verify API key is correct
- Check API quota at OpenAI dashboard
- Review browser console for errors

### Scheduled emails not sending
- Verify cron job is running: `crontab -l`
- Check cron logs
- Verify file path in cron job is absolute

### Emails not sending
- For localhost: PHP mail() may not work, use PHPMailer with SMTP
- Check PHP error logs
- Verify SMTP credentials if using PHPMailer

## 📝 Next Steps

1. Register your first account
2. Test AI email generation
3. Schedule a test email
4. Customize categories and keywords
5. Deploy to production server

## 🔒 Security Notes

- Change default database credentials
- Use strong passwords
- Keep API keys secure
- Enable HTTPS in production
- Regularly update dependencies

---

**Need Help?** Check the main README.md for detailed documentation.

