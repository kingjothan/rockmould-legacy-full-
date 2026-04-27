# Rockmould Limited

Luxury real estate, construction, client portal, and admin CMS application built with Laravel 11, Blade, PHP 8.2, MySQL, and Vite.

## Stack

- Laravel 11
- PHP 8.2
- MySQL / MariaDB
- Blade templates
- Tailwind CSS
- Vite

## Local setup

1. Install PHP 8.2, Composer, Node.js, and MySQL.
2. Create the application database:

```sql
CREATE DATABASE rockmould CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE rockmould_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Confirm `.env` points to your MySQL credentials.
4. Install dependencies:

```bash
composer install
npm install
```

5. Generate the storage symlink and seed the database:

```bash
php artisan storage:link
php artisan migrate:fresh --seed
```

6. Start the application:

```bash
php artisan serve
npm run dev
```

If Windows starts the site with another PHP install, run Artisan with XAMPP PHP directly:

```bash
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

7. Open the correct local URLs:

```text
Public site:   http://127.0.0.1:8000/
Client portal: http://127.0.0.1:8000/portal
Admin login:   http://127.0.0.1:8000/admin/login
```

If you are using `php artisan serve`, do not add `/public` to the URL. The `public` directory is already the web root in that mode.

## Demo access

### Admin

- Email: `admin@rockmould.com`
- Password: `password`

### Portal

- Client phone: `08012345678`
- Inactive demo phone: `08045678901`

## Application areas

- Public marketing website
- Client portal with phone-first access and KYC request flow
- Admin dashboard and CMS for projects, content, payments, KYC, documents, media, roles, and activity logs

## Notes

- Uploaded files are served from `public/storage`.
- Project gallery pictures and videos are managed from `Admin > Media`. Filter by the project, then use `Exterior`, `Interior`, or `Gallery` as the gallery section. To replace an existing item, open `Edit Media Asset` and use `Replace asset file`; to add new project media, use `Admin > Projects > Open project > Add More Project Media` or the `Admin > Media` upload form.
- Large CMS/media uploads are configured for 200MB files. If Apache/XAMPP still shows `Content Too Large`, restart Apache so `public/.htaccess` / `public/.user.ini` upload settings are reloaded. If `php artisan serve` is used, make sure it is running from `C:\xampp\php\php.exe`; another PHP install may keep smaller upload limits.
- The old React/Vite version of the project is preserved in `_legacy_react_backup`.
- Feature tests run against the `rockmould_testing` database defined in `.env.testing`.
