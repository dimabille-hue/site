# TomskAgroInvest modern site rebuild

This repository contains the modernization workspace for replacing the legacy custom PHP CMS used by `tomskagroinvest.ru`.

## Legacy inputs

The legacy project inputs are stored at the repository root:

```text
tomskagroinvest.zip         # archive with the current site files
u2818473_agroinvest.sql    # MySQL database dump
```

The archive expands into `tomskagroinvest.ru/`. Do not commit local extractions of the archive: the extracted source contains production configuration values and is ignored as `legacy_audit/`.

## Target stack

The rebuild is intentionally simple and conventional:

- Laravel application skeleton;
- Blade templates for the public site;
- Filament Admin planned for CMS management;
- MySQL as the first database target for easier legacy import;
- Laravel migrations and artisan commands for data migration.

## Current implementation status

- Base Laravel project files are present.
- Initial public route and Blade layout are present.
- Initial domain models are present for pages, menus, menu items, blocks, redirects, and settings.
- Initial migrations are present for the same core entities.
- `legacy:import --dry-run` reads the legacy SQL dump and reports table/row counts as the first import pipeline step.
- `App\Support\LegacySqlDump` provides a framework-independent parser for legacy MySQL `CREATE TABLE` and `INSERT` data.

## Local setup

Install PHP dependencies when network access is available:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Run tests:

```bash
php artisan test
```

## Migration notes

See [`docs/legacy-audit.md`](docs/legacy-audit.md) for the legacy CMS audit, database overview, risks, and migration model.
