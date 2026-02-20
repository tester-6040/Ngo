# NGO Dress Donation Portal (PHP + Tailwind + MySQL)

A no-framework web app where users donate used dresses for orphanages.

## Features
- Separate login for **Admin**, **User (Donor)**, and **Orphanage**.
- User can submit donation details.
- On each donation, an email is sent to admin (`mail()` + `mail.log` fallback).
- Admin can assign donations to orphanage accounts.
- Orphanages can view assigned donations.

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
2. Update DB credentials in `config.php` if needed.
3. Start PHP server:
   ```bash
   php -S 0.0.0.0:8000
   ```
4. Open `http://localhost:8000`.

## Default Admin
- Email: `admin@ngo.local`
- Password: `admin123`

## Project Pages
- `index.php` – landing page
- `register.php` – register as user/orphanage
- `login.php` – role-based login
- `donate.php` – user donation form + history
- `admin.php` – admin assignment panel
- `orphanage.php` – orphanage assigned donations
