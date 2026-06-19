# Legacy site audit: tomskagroinvest.ru

## Source artifacts

- Site archive: `tomskagroinvest.zip`.
- Database dump: `u2818473_agroinvest.sql`.
- The archive expands into `tomskagroinvest.ru/` and contains 1,183 entries.
- The SQL dump is a MySQL dump for database `u2818473_agroinvest` from MySQL Server `5.7.44-48`.

## High-level technology profile

The legacy site is a custom PHP CMS without Composer or a modern framework. It uses:

- procedural PHP and manually included classes;
- deprecated `mysql_*` database functions;
- Apache `.htaccess` rewrites into `index.php?query=...`;
- server-rendered PHP templates under `skin/`;
- an administrative panel under `admin/`;
- CKEditor/CKFinder for rich text and file management;
- a MySQL schema made of CMS content tables, module configuration tables, and statistics tables.

Sensitive production credentials are present in the extracted PHP configuration files. They must not be reused in the rebuilt application and should be rotated before any production work.

## Runtime request flow

1. `.htaccess` redirects non-`www` hosts to `www`, strips `/index.php`, and rewrites clean URLs to `/index.php?query=$1`.
2. `index.php` handles several hard-coded legacy redirects, loads `core/core.start.php`, then renders the page through `main::create_document()`, `main::headers()`, `main::body()`, and `main::close_document()`.
3. `core/core.start.php` starts the session, loads database settings, connects to MySQL, reads global configuration from the `config` table, splits the `query` parameter into route segments, loads CMS classes, and resolves the current page by URL or by the configured main page.
4. `class.main.php` orchestrates rendering by including core hooks and `skin/before.php`, `skin/headers.php`, `skin/body.php`, and `skin/after.php`.
5. `skin/body.php` builds the public header, menus, contacts, main/page template switch, footer, and simple monthly statistics counter.

## Public site structure

Key public directories and files:

- `index.php` — front controller plus legacy redirects.
- `.htaccess` — Apache routing and redirect rules.
- `core/` — CMS bootstrap, helpers, and data access classes.
- `skin/` — public templates, CSS, JavaScript, and page includes.
- `skin/inc/` — module-level public includes: `main.php`, `page.php`, `catalog.php`, `lists.php`, `items.php`, `forms.php`, `faq.php`, `maps.php`, `arenda.php`, `zayavka.php`, and `ajax.php`.
- `img/`, `img/upload/`, `userfiles/` — media and uploaded files.
- `errors/` — simple HTTP error pages.

## Admin panel structure

The administrative panel is located in `admin/` and includes:

- `admin/index.php` — login/session handling and the main admin shell;
- `admin/cfg.php` — database connection configuration;
- `admin/inc/` — CRUD screens for CMS modules;
- `admin/ckeditor/` and `admin/ckfinder/` — legacy WYSIWYG editor and file manager;
- `admin/kcaptcha/` — CAPTCHA dependency;
- `admin/css/`, `admin/js/`, `admin/img/` — admin UI assets.

The admin panel checks credentials against rows in `config` and `options`, uses MD5 password hashes, and stores auth state in PHP sessions.

## Database overview

The dump contains 41 tables:

- `advices`
- `blocks_config`, `blocks_data`
- `catalog`, `catalog_config`, `catalog_config2`, `catalog_data`
- `config`
- `forms`, `forms_data`
- `gallery_items`, `gallery_lists`, `gallery_pages`
- `items`, `items_config`, `items_data`, `items_filter`
- `lists`, `lists_config`, `lists_data`, `lists_dop_data`
- `menus`, `menus_config`, `menus_data`, `menus_dop_config`
- `notice`
- `options`
- `pages`, `pages_config`, `pages_data`
- `parts_config`, `parts_faq`, `parts_faq_data`, `parts_maps`, `parts_reviews`, `parts_reviews_data`, `parts_social`, `parts_watermark`
- `polez`, `sop`, `stat`

Approximate row counts from `INSERT` statements:

| Area | Tables | Approx. rows |
| --- | --- | ---: |
| Global blocks | `blocks_config`, `blocks_data` | 20 |
| Catalog | `catalog`, `catalog_config`, `catalog_config2`, `catalog_data` | 154 |
| Gallery | `gallery_items`, `gallery_lists`, `gallery_pages` | 421 |
| Items/filter data | `items`, `items_config`, `items_data`, `items_filter` | 128 |
| Lists | `lists`, `lists_config`, `lists_data`, `lists_dop_data` | 16 |
| Menus | `menus`, `menus_config` | 34 |
| Pages | `pages`, `pages_config`, `pages_data` | 22 |
| Notices/statistics | `notice`, `stat` | 379 |
| Config/options/parts | `config`, `options`, `parts_*` | 61 |

## Main functional areas to preserve

- Static/content pages with SEO fields and custom data blocks.
- Top and footer menus with nested children.
- Reusable global blocks for organization name, address, phone, and social links.
- Gallery/media attachments.
- Catalog/data-driven sections.
- Lists/items modules.
- Feedback/application forms.
- FAQ, maps, reviews/social/watermark configuration if still used.
- Legacy redirects currently hard-coded in `index.php`.
- Uploaded media from `img/upload/` and `userfiles/`.

## Key risks and modernization needs

1. **Unsupported database API** — the site uses `mysql_*`, removed in modern PHP versions.
2. **Hard-coded production credentials** — extracted configuration contains database credentials and must be replaced with environment variables/secrets management.
3. **Weak password storage** — admin authentication uses MD5 hashes.
4. **SQL injection risk** — filtering is inconsistent and SQL is built by string interpolation.
5. **Legacy third-party dependencies** — CKEditor/CKFinder and KCFinder-era code should be replaced or isolated.
6. **Tight coupling of content and templates** — rendering mixes PHP, SQL, HTML, and business logic.
7. **No dependency manager or tests** — no Composer, package manifests, or automated tests were found in the legacy code.
8. **SEO migration risk** — redirects and existing URLs must be preserved carefully.
9. **Media migration risk** — uploaded files and CKFinder paths need mapping into the new storage layer.

## Recommended target stack

Recommended baseline: **Laravel 11/12 + Filament Admin + MySQL or PostgreSQL + Blade/Livewire**.

Why this fits the project:

- PHP is close enough to the existing hosting/domain knowledge while removing the custom CMS burden.
- Filament can replace the current admin CRUD screens quickly.
- Laravel migrations and seeders are a good fit for importing the MySQL dump.
- Blade/Livewire can rebuild a mostly content-driven corporate site without unnecessary frontend complexity.
- Laravel's validation, CSRF protection, auth, queues, mail, storage, and route model binding address the main legacy risks.

Alternative if a richer frontend is required later: Laravel API + Next.js/Nuxt frontend. For the first migration pass, this is likely more complex than necessary.

## Proposed migration model

Suggested domain entities for the rebuilt app:

- `Page` — replaces `pages` and `pages_data`.
- `Menu` / `MenuItem` — replaces `menus`, `menus_data`, and config tables.
- `Block` — replaces `blocks_config` and `blocks_data`.
- `CatalogCategory` / `CatalogItem` / `CatalogFieldValue` — replaces catalog tables.
- `ContentList` / `ContentItem` — replaces lists/items modules if they are still needed as separate concepts.
- `Gallery` / `Media` — replaces gallery tables and uploaded file paths.
- `Form` / `FormSubmission` — replaces form configuration and submissions.
- `Redirect` — replaces hard-coded redirects from `index.php`.
- `Setting` — replaces `config`, `options`, and active `parts_*` configuration.

## Next implementation steps

1. Add the extracted legacy directory to `.gitignore` so audited source is not accidentally committed with secrets.
2. Create a new Laravel application skeleton in this repository.
3. Build initial migrations for pages, menus, blocks, redirects, media, and settings.
4. Write import scripts that read `u2818473_agroinvest.sql` into a temporary database and map old records into the new schema.
5. Rebuild the public templates from `skin/` in Blade/Tailwind.
6. Rebuild admin management screens in Filament.
7. Add tests for URL redirects, page rendering, menu rendering, and core imports.
8. Prepare deployment instructions and a final migration runbook.
