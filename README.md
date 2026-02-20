# NGO Dress Donation Portal (PHP + Tailwind + MySQL)

Production-style, no-framework web portal where users donate used dresses and admins route them to orphanages.

## Features
- Separate login for **Admin**, **User (Donor)**, and **Orphanage**.
- Professional, responsive Tailwind UI (dashboard cards, clean forms, role badges).
- User donation form with history tracking.
- Automatic admin mail notification when new donations are submitted (`mail()` with `mail.log` fallback).
- Admin assignment queue for routing donations to orphanages.
- Orphanage dashboard with completion updates.
- Security improvements: CSRF protection, session regeneration on login, secure session cookies.

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
3. Start local server:
   ```bash
   php -S 0.0.0.0:8000
   ```
4. Open `http://localhost:8000`.

## Default Admin
- Email: `admin@ngo.local`
- Password: `admin123`

## Main Pages
- `index.php` – modern landing page
- `register.php` – donor/orphanage signup
- `login.php` – role-based secure login
- `donate.php` – donor dashboard and donation history
- `admin.php` – admin assignment board
- `orphanage.php` – orphanage assignment/completion board
