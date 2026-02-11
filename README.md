# Odin - Resource Management Platform

Odin is a Laravel + MySQL application for managing, sharing, and securing learning/resources links.

## Tech Stack
- PHP 8.2+
- Laravel 12
- MySQL 8+
- Blade + Vite
- Eloquent ORM

## Implemented Requirements

### Architecture and Security
- MVC architecture
- Policies (`LinkPolicy`, `CategoryPolicy`)
- Form Requests for all key forms
- Middlewares:
  - `active` (`EnsureUserIsActive`)
  - `role` (`EnsureUserHasRole`)
- Soft Deletes on `links`, `categories`, `tags`
- Events / Listeners for activity tracking and notifications
- SRP-oriented separation (controllers, requests, policies, listeners, notifications)

### User Stories (US-07 to US-13)
- `US-07 Roles & Permissions`
  - Roles: `admin`, `editor`, `viewer`
  - Tables: `roles`, `role_user`
  - Admin full access via policy `before()`
  - Viewer read-only
- `US-08 Link Sharing`
  - Many-to-many link sharing with pivot permission (`lecture`, `edition`)
  - Table: `link_user`
- `US-09 Action History`
  - Admin-only activity log view
  - Table: `activity_logs`
  - Logged actions: create/update/delete/restore/share/permission update/revoke/force delete
- `US-10 Secure Deletion`
  - Soft delete for resources
  - Restore flow
  - Permanent delete reserved to admin
- `US-11 Advanced Validation`
  - Form Requests + custom error messages
  - URL validation
  - URL uniqueness enforced per resource owner
- `US-12 Favorites`
  - Favorite and unfavorite links
  - Favorites filter
  - Pivot table: `favorites`
- `US-13 Notifications`
  - Database notifications when:
    - a link is shared
    - share permission is changed
  - Laravel Notifications + listeners

## Database Tables
Minimum 8 tables requirement is exceeded. Main tables:
- `users`
- `categories`
- `links`
- `tags`
- `link_tag`
- `roles`
- `role_user`
- `link_user`
- `activity_logs`
- `favorites`
- `notifications`

## Local Setup
```bash
git clone <repo-url>
cd ORION-APP
composer install
cp .env.example .env
php artisan key:generate
```

Configure `.env` for MySQL, then:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## Deployment (US-14)
Production deployment guide is available in:
- `docs/DEPLOYMENT_LINUX.md`
- `deploy/nginx/odin.conf`

## Validation Commands
```bash
php -l app/Http/Controllers/LinkController.php
php artisan route:list
```

## Notes
- Default phpunit config uses SQLite in-memory. Ensure `pdo_sqlite` is enabled, or adapt test DB config to MySQL for your environment.
