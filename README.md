# NGO Dress Donation Portal (PHP + Tailwind + MySQL)

Production-grade, no-framework portal where users donate used dresses and admins route them to orphanages.

## Features
- Separate login for **Admin**, **User (Donor)**, and **Orphanage**.
- Clean professional UI with modern typography, polished cards, responsive tables, and role-specific dashboards.
- Extensionless routes (`/login`, `/donate`, `/admin`) instead of `.php` URLs.
- User donation form with complete history tracking.
- Automatic admin mail notification when new donations are submitted (`mail()` with `mail.log` fallback).
- Admin assignment board for routing donations to orphanages.
- Orphanage dashboard with completion updates.
- Security hardening:
  - CSRF protection
  - secure session cookies + session regeneration
  - account active/inactive authorization
  - login lockout for repeated failed attempts (5 failures => 15-minute lock)

## Tech Stack
- PHP (no framework)
- TailwindCSS (CDN)
- MySQL
- Vanilla JavaScript

## Setup
1. Create database + tables:
   ```bash
   mysql -u root -p < schema.sql
   ```
2. Update DB credentials in `config.php`.
3. Start local server with URL rewriting router:
   ```bash
   php -S 0.0.0.0:8000 router.php
   ```
4. Open `http://localhost:8000`.

## Route Behavior / Subfolder Hosting
- Links are generated as clean URLs using automatic base-path detection from `SCRIPT_NAME`.
- This fixes deployments such as `http://localhost/Ngo-codex/` where routes must become `http://localhost/Ngo-codex/dashboard` (not `/dashboard`).
- If your environment cannot auto-detect correctly, set `APP_BASE_PATH` in `config.php` (example: `/Ngo-codex`).

## Apache Requirements
- Enable `mod_rewrite` and allow `.htaccess` (`AllowOverride All`) in your virtual host.
- `.htaccess` is included to hide `.php` extensions in Apache deployments.

## Default Admin
- Email: `admin@ngo.local`
- Password: `admin123`

## Main Routes
- `/` – professional landing page
- `/register` – donor/orphanage signup
- `/login` – role-based secure login
- `/donate` – donor dashboard and donation history
- `/admin` – admin assignment board
- `/orphanage` – orphanage assignment/completion board
