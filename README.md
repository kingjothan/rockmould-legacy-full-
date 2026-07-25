# Rockmould Limited — Platform

Full-stack web platform for Rockmould Limited, a premium real estate and construction company based in Lagos, Nigeria. The platform covers a public marketing website, a client portal, and a full admin CMS — all in a single Laravel application.

---

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP 8.2 |
| Frontend | Blade templates, Tailwind CSS, Vite |
| Database | MySQL / MariaDB |
| File storage | cPanel `public_html/storage` via custom `site_public` disk |
| Mail | SMTP (configured in `.env`) |
| Hosting | cPanel shared hosting — web root `~/public_html`, app root `~/rockmould` |

---

## Application Areas

### 1. Public Marketing Website
The customer-facing site at `rockmould.com`. Covers:
- **Home** — hero, featured projects, stats, testimonials, team, blog highlights
- **Projects** — filterable listing and individual project pages with gallery (Exterior, Interior, General, Floor Plan tabs), features & amenities, construction progress bar, brochure download, and inquiry form
- **Portfolio** — curated project showcase
- **Gallery** — site-wide media gallery
- **About** — company story, team members, values
- **Services** — what Rockmould offers
- **Blog / Insights** — articles and updates
- **Careers** — live job openings with application form
- **FAQ** — frequently asked questions
- **Contact** — general contact form
- **Consultation Booking** — calendar-based booking system with availability management

### 2. Client Portal
Private area at `/portal` for registered property buyers. Access is phone-number based (no password). Covers:
- **Dashboard** — assignment summary, progress, outstanding balance
- **Project Overview** — full project detail, milestones, recent updates, brochure download, media gallery
- **Live Updates** — construction progress updates with photos and videos (portal-exclusive)
- **Documents** — project and assignment-specific documents
- **Media** — curated project gallery
- **Balance Sheet** — payment history and downloadable statement PDF
- **Receipts** — downloadable payment receipt PDFs
- **KYC** — document submission and status tracking
- **Support** — ticket-based support system
- **Profile** — personal details management

### 3. Admin CMS
Private dashboard at `/admin` for internal staff. Role-based access (Admin, Editor, Project Manager, Finance, Content Manager). Covers:

**Projects & Clients**
- Projects — create/edit with cover image, gallery, brochure PDF upload, features, timeline milestones, and live updates (with media attachments)
- Project Assignments — assign clients to units within projects
- Units — manage individual units per project
- Clients — manage client accounts and portal access
- KYC — review and approve/reject client KYC submissions

**Finance**
- Payments — record and track client payments
- Balance sheets and receipt generation

**Media**
- Upload or link images and videos per project, organised by section: Exterior, Interior, General, Floor Plan
- Supports direct file upload or CDN URL linking

**Content (CMS)**
- Pages — homepage hero, about, services content
- Blog / Posts — full article management
- Testimonials — client testimonials
- Team Members — staff profiles
- FAQ — question and answer pairs
- Careers — job openings with requirements and benefits
- Footer — footer link groups
- Newsletter — compose and send newsletters to subscribers with PDF attachments

**Operations**
- Consultation Bookings — manage inbound consultation requests and calendar availability
- Forms — view contact, partnership, and project inquiry submissions
- Support Tickets — client support queue
- Activity Log — full audit trail of admin actions
- Roles — manage staff roles and permissions
- Notifications — in-app notification management
- Settings — site-wide configuration

---

## Key Design Decisions

- **Dark-only theme** — light mode has been removed entirely. The site is permanently dark.
- **File storage** — all user-uploaded files (brochures, newsletter attachments, media) are stored to the `site_public` disk which maps directly to `~/public_html/storage`. Laravel's default `public` disk + `storage:link` symlink is not used because the symlink is unreliable on this shared host.
- **Live Updates are portal-only** — the public project page shows a "View Live Updates" button that redirects to the portal login. Actual update content (text, photos, videos) is only visible to logged-in clients.
- **Project media tabs** — the public project page shows Exterior, Interior, General, and Floor Plan tabs inside the Project Media section. The first available tab auto-activates on load. Tabs with no content are not rendered.
- **Progress bar** — the Overall Completion % on project pages is calculated automatically from the Project Start date to the Project Completion date. It caps at 99% for in-progress projects and shows 100% only when status is set to Completed.

---

## Local Setup

### Prerequisites
- PHP 8.2
- Composer
- Node.js + npm
- MySQL or MariaDB

### Steps

**1. Create databases**
```sql
CREATE DATABASE rockmould CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE rockmould_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```
Update `.env` with your database credentials, mail settings, and `SITE_PUBLIC_PATH`.

**4. Migrate and seed**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

**5. Build frontend assets**
```bash
npm run dev
```

**6. Start the server**
```bash
php artisan serve
```

On Windows with XAMPP, if the wrong PHP version runs:
```bash
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

### Local URLs
```
Public site:   http://127.0.0.1:8000/
Client portal: http://127.0.0.1:8000/portal
Admin login:   http://127.0.0.1:8000/admin/login
```

---

## Demo Accounts

### Admin / CMS Staff
All seeded staff accounts use the password `password`.

| Name | Role | Email |
|---|---|---|
| Adaeze Okafor | Admin | `admin@rockmould.com` |
| Damilola Akin | Editor | `editor@rockmould.com` |
| Oluwaseun Adebayo | Project Manager | `projects@rockmould.com` |
| Ifeoma George | Finance | `finance@rockmould.com` |

Login at: `/admin/login`

### Client Portal
Portal access is phone-number only — no password.

| Client | Portal Phone |
|---|---|
| Adebayo Ogunleye | `08012345678` |
| Chioma Nwankwo | `08023456789` |
| Ibrahim Musa | `08034567890` |
| Folake Bakare | `08045678901` (inactive) |

Login at: `/portal`

---

## Production Deployment

The live site runs on cPanel shared hosting.

- **Web root:** `~/public_html`
- **App root:** `~/rockmould`
- **SSH:** port `21098`
- **Uploads:** stored to `~/public_html/storage/` via the `site_public` disk
- **Build:** run `npm run build` locally, then upload compiled assets from `public/build/` to `~/public_html/build/`
- **Blade changes:** upload the changed file via SFTP to the corresponding path under `~/rockmould/resources/views/`
- **PHP changes:** upload to `~/rockmould/app/` — no restart needed on shared hosting

> **Important:** The live server has direct edits not committed to git. Always upload specific changed files via SFTP rather than running `git pull`, which will conflict.

---

## Notes

- Project brochures are uploaded as PDFs from **Admin → Projects → Edit** and are available for download on both the public project page and the client portal.
- Project media is managed from **Admin → Media**. Use the Gallery Section field to tag each asset as Exterior, Interior, General, or Floor Plan.
- Large uploads (up to 200 MB) are configured via `public/.htaccess` and `public/.user.ini`. Restart Apache after changes for XAMPP to pick them up.
- Feature tests run against the `rockmould_testing` database defined in `.env.testing`.
- The original React/Vite version of the project is preserved in `_legacy_react_backup`.
