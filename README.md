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

### Admin / CMS staff

All seeded admin-side staff accounts use the same demo password: `password`.

| Name | Role | Login email | Password | Status |
| --- | --- | --- | --- | --- |
| Adaeze Okafor | Admin | `admin@rockmould.com` | `password` | Active |
| Damilola Akin | Editor | `editor@rockmould.com` | `password` | Active |
| Oluwaseun Adebayo | Project Manager | `projects@rockmould.com` | `password` | Active |
| Ifeoma George | Finance | `finance@rockmould.com` | `password` | Active |

Use these at: `http://127.0.0.1:8000/admin/login`

### Client portal

Client portal access is phone-number only. There is no password or OTP in this phase.

| Client | Email | Portal phone in database | Local phone shortcut | Status |
| --- | --- | --- | --- | --- |
| Adebayo Ogunleye | `adebayo@example.com` | `+2348012345678` | `08012345678` | Active |
| Chioma Nwankwo | `chioma@example.com` | `+2348023456789` | `08023456789` | Active |
| Ibrahim Musa | `ibrahim@example.com` | `+2348034567890` | `08034567890` | Active |
| Folake Bakare | `folake@example.com` | `+2348045678901` | `08045678901` | Inactive / pending |

Use these at: `http://127.0.0.1:8000/portal`

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
