# CDS Portfolio Deployment & Operations Guide

## 1. Database Backup Cron Job

To ensure data safety, a database backup script has been provided at `scripts/backup_db.php`.
You should configure a cron job on your production server (e.g., via cPanel or Linux crontab) to run this script daily.

### Linux Crontab Example
To run the backup every day at midnight (00:00), add the following line to your server's crontab:
```bash
0 0 * * * /usr/bin/php /path/to/your/project/scripts/backup_db.php >> /path/to/your/project/logs/cron.log 2>&1
```

### cPanel Cron Job
1. Go to **cPanel > Advanced > Cron Jobs**.
2. Select **Once Per Day** from the common settings.
3. In the command field, enter:
   ```bash
   php /home/username/public_html/cds/scripts/backup_db.php
   ```
*(Replace paths according to your server directory structure).*

## 2. Admin Security

- The `schema.sql` file does **not** contain any hardcoded admin credentials for security reasons.
- To create a new admin user, use the provided CLI script via terminal or SSH:
  ```bash
  php scripts/create_admin.php
  ```
- **Password Requirements:** The script enforces strong passwords (minimum 8 characters, including at least one number and one letter).

## 3. Storage and Permissions
Ensure the following directories have write permissions (e.g., `chmod 755` or `chmod 775`) so the application can upload files and save backups:
- `uploads/blogs/`
- `uploads/gallery/`
- `uploads/gov_links/`
- `uploads/projects/`
- `uploads/publications/`
- `logs/backups/`
