# lexisvar.me: CV and portfolio CMS

The source of [lexisvar.me](https://lexisvar.me), my online CV. It is a small Laravel application with a private admin area. I edit my experience, projects, skills, education and profile there. The same data renders as:

- **`/`**: the public portfolio page, with SEO/Open Graph meta tags and schema.org `Person` data
- **`/simple`**: a single-column, printer- and ATS-friendly CV
- **`/export`**: the same CV as a PDF (download), rendered server-side with dompdf, with page numbers and document metadata
- **`/cv.pdf`**: the PDF opened in the browser, a short link to share

## Features

- CRUD admin (Laravel Breeze auth, public registration disabled) for:
  - experience
  - projects (role, stack, live/repo links, display order)
  - skills (grouped by category)
  - education
  - key/value profile settings
- Descriptions are plain text. Lines starting with `- ` render as bullet points, and all content is HTML-escaped.
- A visit log on the dashboard (page, IP, user agent).
- Feature tests for the public pages, PDF export, escaping and the admin flows.

## Stack

PHP 8 · Laravel 9 · Blade · hand-written CSS for the public page (no framework, light/dark, print styles) · Tailwind (admin) · dompdf · MySQL/PostgreSQL (SQLite for tests)

## Running locally

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan serve
```

Create an admin user with `php artisan tinker`:

```php
\App\Models\User::create(['name' => 'Admin', 'email' => 'you@example.com', 'password' => bcrypt('secret'), 'email_verified_at' => now()]);
```

Then sign in at `/login`.

## Profile settings (`General` keys)

| Key | Used for |
|---|---|
| `name`, `occupation` | Header, page title, PDF |
| `biography` | About / Summary (supports `- ` bullets) |
| `email`, `phone`, `address`, `language` | Contact details |
| `work_rights` | Optional line about work rights or location, shown on the page and PDF |
| `availability` | Optional short line under the headline |
| `meta_description` | Optional SEO description (defaults to the start of the biography) |
| `github`, `linkedin` | Optional profile URLs (default to lexisvar) |
| `show_phone` | Set to `0` to hide the phone number on the public page |
| `show_photo` | Set to `0` to hide the portrait |
| `highlights` | Key facts under About, one per line: `value \| label` (e.g. `10+ years \| building web software`) |
| `skill_categories` | Category order, comma separated (e.g. `Backend, Frontend & Mobile, Data, Cloud & DevOps`) |
| `compact_before` | Year; roles that ended before it show as one line (keeps the PDF to about 2 pages) |
| `pdf_summary` | Optional shorter summary for the PDF (defaults to `biography`) |
| `pdf_project_limit` | How many projects the PDF lists (default 3; the website shows all) |
| `contact_title`, `contact_text` | Text of the contact card |
| `pdf_education_descriptions` | Set to `1` to include education descriptions in the PDF |

## Deploying to Railway

The repo includes a `Dockerfile` (PHP 8.2 + Apache, admin assets built with Vite) and `railway.json` (health check `/health`). On every start the container caches config, routes and views, runs `php artisan migrate --force`, creates the admin user if needed, and starts Apache on Railway's `$PORT`.

1. **New project** → **Deploy from GitHub repo** → `lexisvar/cv-laravel` (branch of your choice).
2. **Add a MySQL database** to the project (MySQL keeps the existing data format).
3. **Variables on the web service:**

   | Variable | Value |
   |---|---|
   | `APP_KEY` | output of `php artisan key:generate --show` |
   | `APP_URL` | `https://lexisvar.me` (or the `*.up.railway.app` URL while testing) |
   | `APP_ENV` / `APP_DEBUG` | `production` / `false` (the image defaults) |
   | `DB_CONNECTION` | `mysql` |
   | `DATABASE_URL` | `${{MySQL.MYSQL_URL}}` |
   | `ADMIN_EMAIL` / `ADMIN_PASSWORD` | admin login, created on first deploy if missing (never overwritten; 12+ characters) |
   | `ADMIN_NAME` | optional |

4. **Custom domain:** Settings → Networking → *Custom Domain* → `lexisvar.me`, then add the DNS record Railway shows at your registrar.

### Moving the existing content

1. Old host → phpMyAdmin → select the database → **Export** → *Quick* → **SQL**.
2. Import it into Railway's MySQL (Railway → MySQL → *Connect* shows a public connection URL):

   ```bash
   mysql -h <host> -P <port> -u root -p railway < export.sql
   ```

3. Redeploy the web service. `migrate --force` adds only the new tables and columns (projects, skill categories); existing content is kept.

### Building the image locally

```bash
docker build -t cv-laravel .
docker run --rm -p 8080:8080 -e PORT=8080 -e APP_KEY="$(php artisan key:generate --show)" \
  -e DB_CONNECTION=sqlite -e DB_DATABASE=/var/www/html/storage/db.sqlite \
  -e ADMIN_EMAIL=you@example.com -e ADMIN_PASSWORD=a-long-password \
  --entrypoint sh cv-laravel -c 'touch storage/db.sqlite && chown www-data storage/db.sqlite && exec docker/start.sh'
# then open http://localhost:8080
```

## Tests

```bash
php artisan test
```
