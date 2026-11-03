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
| `contact_title`, `contact_text` | Text of the contact card |
| `pdf_education_descriptions` | Set to `1` to include education descriptions in the PDF |

## Tests

```bash
php artisan test
```
