# Rockmould Limited Hosting and Deployment Guide

Prepared for: Rockmould Limited
Domain: https://www.rockmould.com/
Application: Laravel 11, PHP 8.2+, MySQL, Blade, Vite
Last reviewed: April 27, 2026

## Best Affordable Hosting Recommendation

The best low-cost practical choice for Rockmould is Hostinger Cloud Startup if the budget allows it. It is still affordable, gives more room for media-heavy CMS usage, and is safer for a Laravel website where admins upload project pictures and videos. If the budget is tighter, Hostinger Business Web Hosting can work, but Cloud Startup is the better fit for a premium real estate website with public pages, admin CMS, client portal, documents, and large media uploads.

Recommended order:

1. Hostinger Cloud Startup - best balance for this project. Use this if Rockmould will upload many project images/videos and wants smoother admin usage.
2. Hostinger Business Web Hosting - lower-cost starting option. Use this if the site traffic is still moderate and media storage is managed carefully.
3. Namecheap Stellar Business - budget alternative with cPanel and SSH. Good if you prefer cPanel familiarity, but confirm PHP, Composer, cron, upload limits, and Laravel document root settings before purchase.
4. Cloudways on DigitalOcean - stronger managed cloud option. Better performance and control, but more expensive monthly than shared/cloud hosting.

## Why Hostinger Cloud Startup Is Recommended

Rockmould is not just a brochure website. It includes:

- Public luxury website pages.
- Admin/CMS controls for content, projects, media, documents, blog, careers, testimonials, FAQ, and settings.
- Client portal.
- Consultation booking.
- MySQL database records.
- Uploads for project photos, videos, documents, and public page media.

Because of that, choose hosting with:

- PHP 8.2 or higher.
- MySQL or MariaDB.
- SSH access.
- Composer support.
- Cron jobs.
- SSL certificate support.
- Ability to point the domain document root to Laravel's `public` directory.
- Enough storage and inode allowance for media uploads.
- Backups.
- Configurable upload limits for 200 MB media.

Hostinger's current public pricing page lists Business Web Hosting at an introductory rate around $3.99/month on long-term billing and Cloud Startup around $7.99/month on long-term billing, with higher renewal prices. Namecheap's shared hosting page lists Stellar Business around $4.98/month for the first year on annual billing and renews yearly. Cloudways/DigitalOcean starts around $14/month for managed cloud hosting. Prices change often, so confirm at checkout before payment.

## Minimum Server Requirements

Use these requirements before buying any plan:

- PHP: 8.2, 8.3, or 8.4.
- Database: MySQL 8+ or MariaDB 10.6+.
- Web server: Apache or Nginx.
- Composer: available through SSH.
- Node.js: helpful but not mandatory if assets are built locally before upload.
- SSH/SFTP: required for clean deployment.
- Cron jobs: required for Laravel scheduler.
- SSL: required for `https://www.rockmould.com`.
- Storage: 50 GB minimum recommended because of videos and project media.
- Upload limit: configurable to at least 256 MB.
- Memory limit: 512 MB minimum, 1024 MB preferred.
- Max execution time: 300 seconds or higher for large uploads.
- Backups: daily preferred.

## Buying the Hosting

1. Go to Hostinger and choose Cloud Startup if possible.
2. Use the closest practical data center to your primary audience. For Nigeria, Europe or UK is often a reasonable choice if an Africa region is not available.
3. Complete payment.
4. In hPanel, add the domain `rockmould.com`.
5. Enable free SSL for both `rockmould.com` and `www.rockmould.com`.
6. Create a MySQL database and database user.
7. Save these values securely:

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
DB_HOST=127.0.0.1
DB_PORT=3306
```

## Domain DNS Setup

At the domain registrar for `rockmould.com`, update DNS:

```text
Type: A
Name: @
Value: HOSTING_SERVER_IP
TTL: 300 or automatic

Type: CNAME
Name: www
Value: rockmould.com
TTL: 300 or automatic
```

If the host gives nameservers instead, you may also point the domain to the hosting nameservers. Using nameservers is simpler for beginners. Using A/CNAME gives more DNS control.

After DNS changes, propagation can take a few minutes to 48 hours.

## Recommended Folder Structure

Best structure if the host allows custom document root:

```text
/home/username/rockmould-app
/home/username/rockmould-app/public
```

Set the domain document root to:

```text
/home/username/rockmould-app/public
```

This is the safest Laravel setup because only the `public` folder is web-accessible.

If the host cannot point the domain to `/public`, use this fallback:

```text
/home/username/rockmould-app
/home/username/public_html
```

Move the contents of Laravel's `public` folder into `public_html`, then edit `public_html/index.php`:

```php
require __DIR__.'/../rockmould-app/vendor/autoload.php';
$app = require_once __DIR__.'/../rockmould-app/bootstrap/app.php';
```

Only use this fallback if custom document root is not available.

## Deployment From GitHub

After the code is pushed to GitHub, use SSH on the server:

```bash
cd /home/username
git clone https://github.com/kingjothan/rockmould-legacy-full-.git rockmould-app
cd rockmould-app
```

Install PHP dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with production values:

```env
APP_NAME="Rockmould Limited"
APP_ENV=production
APP_KEY=base64:generated_key_here
APP_DEBUG=false
APP_URL=https://www.rockmould.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email_username
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@rockmould.com
MAIL_FROM_NAME="Rockmould Limited"
```

Run migrations:

```bash
php artisan migrate --force
```

If this is a fresh launch and you want seed data:

```bash
php artisan db:seed --force
```

Create storage link:

```bash
php artisan storage:link
```

Optimize Laravel:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Frontend Build

Preferred option: build locally and commit/upload `public/build`.

Local command:

```bash
npm install
npm run build
```

If Node.js is available on the server:

```bash
npm ci
npm run build
```

If Node.js is not available on the server, build locally and upload the generated `public/build` folder.

## File Permissions

Laravel must be able to write to:

```text
storage
bootstrap/cache
```

Typical commands on Linux hosting:

```bash
chmod -R 775 storage bootstrap/cache
```

If the host uses a specific web server user/group, follow their support guidance instead of forcing broad permissions.

## Large Upload Configuration

Rockmould needs project images and videos up to about 200 MB. Configure PHP like this:

```ini
upload_max_filesize=256M
post_max_size=300M
memory_limit=512M
max_execution_time=300
max_input_time=300
max_file_uploads=50
```

On shared hosting, this may be set in one of these places:

- PHP Options in the hosting control panel.
- `.user.ini` inside the public directory.
- `.htaccess` if Apache allows PHP values.
- Host support ticket if the plan restricts upload limits.

After changing upload settings, restart the active PHP process if the host provides that option, or wait for `.user.ini` changes to reload.

## Cron Job for Laravel Scheduler

Add one cron job:

```bash
* * * * * php /home/username/rockmould-app/artisan schedule:run >> /dev/null 2>&1
```

On Hostinger, use hPanel Cron Jobs. On cPanel, use Cron Jobs. If the host requires a full PHP path, use their PHP binary path, for example:

```bash
/usr/bin/php /home/username/rockmould-app/artisan schedule:run
```

## Queue Worker

If emails or heavy jobs are queued, the best production setup is a persistent queue worker:

```bash
php artisan queue:work --sleep=3 --tries=3 --timeout=120
```

Many shared hosts do not allow permanent background workers. If the host does not support queue workers, use:

```env
QUEUE_CONNECTION=sync
```

For better scaling later, move to Cloudways, VPS, or a host with supervisor/process management.

## Email Setup

Use one of these:

- Zoho Mail for domain email.
- Google Workspace.
- Microsoft 365.
- Hostinger or Namecheap SMTP if included.
- Mailgun, Brevo, or Postmark for transactional email.

Use a real SMTP service before launch so consultation emails, client notifications, password resets, and admin messages do not fail.

Also configure DNS records for email:

- MX records.
- SPF.
- DKIM.
- DMARC.

## SSL Setup

1. Enable SSL in the hosting dashboard.
2. Force HTTPS in the hosting panel if available.
3. Keep `APP_URL=https://www.rockmould.com`.
4. Test:

```text
https://rockmould.com
https://www.rockmould.com
```

One version should redirect to the preferred version. Recommended preferred version:

```text
https://www.rockmould.com
```

## Admin/CMS Launch Checklist

After deployment:

1. Log into `/admin/login`.
2. Change the default admin password.
3. Confirm Settings has the correct phone, email, office address, and social links.
4. Upload production logos.
5. Update homepage hero image/video.
6. Update About page story, team, milestones, and CTA.
7. Update Services, Projects, Portfolio, Blog, Testimonials, FAQ, Careers, and Gallery.
8. Add project media in Admin > Media.
9. Assign media to the correct project and gallery section: Exterior, Interior, or Gallery.
10. Confirm client-visible media can be downloaded inside the portal.
11. Upload documents for client projects.
12. Test consultation booking emails.
13. Test contact forms and portal support forms.
14. Confirm the website works on mobile.

## Backup Plan

Before launch:

- Enable hosting backups.
- Download a full database backup.
- Download the `storage/app/public` folder.
- Keep a GitHub copy of the source code.

Weekly:

- Export database.
- Backup uploaded media.
- Confirm hosting automatic backups are actually running.

Before every major update:

```bash
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

## Security Checklist

- `APP_DEBUG=false` in production.
- Strong admin passwords.
- HTTPS enabled.
- `.env` never committed to GitHub.
- `vendor`, `node_modules`, logs, and local cache not committed.
- Database user should not use root credentials.
- Keep Laravel dependencies updated.
- Restrict admin access to trusted users only.
- Enable 2FA on hosting and GitHub accounts.

## Recommended Final Choice

Start with Hostinger Cloud Startup if Rockmould wants a low-cost but more comfortable launch. It is the better fit because the project includes CMS media uploads, client portal, admin tools, documents, and videos. If monthly cost is the main concern, start with Hostinger Business and upgrade when traffic or upload volume grows.

## Sources Checked

- Hostinger pricing: https://www.hostinger.com/pricing
- Hostinger Laravel support: https://www.hostinger.com/support/1583301-which-laravel-versions-are-supported-at-hostinger
- Hostinger cron jobs: https://support.hostinger.com/en/articles/1583465-how-to-set-up-a-cron-job-at-hostinger
- Hostinger Composer support: https://support.hostinger.com/en/articles/5792078-how-to-use-composer
- Namecheap shared hosting: https://www.namecheap.com/hosting/shared/
- Namecheap Composer on shared servers: https://www.namecheap.com/support/knowledgebase/article.aspx/9977/29/how-to-install-composer-on-shared-servers/
- Cloudways/DigitalOcean managed hosting: https://www.digitalocean.com/products/cloudways
- Cloudways pricing: https://www.cloudways.com/en/pricing.php
