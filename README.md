# Dress Donation Platform (Core PHP + MySQL + TailwindCSS)

A full **no-framework**, MVC-like NGO platform where users can donate used dresses to orphanages with role-based access and API support.

## 1) Folder Structure

```text
/config
  app.php
/core
  BaseModel.php
  Controller.php
  Csrf.php
  Database.php
  Mailer.php
  Session.php
/models
  User.php
  Donation.php
/controllers
  AuthController.php
  DashboardController.php
  DonationController.php
  ApiController.php
/views
  /layouts
    header.php
    footer.php
  /auth
    login.php
    register.php
  /dashboard
    admin.php
    user.php
    orphanage.php
  home.php
/api
  bootstrap.php
  users.php
  donations.php
  orphanage-actions.php
/assets
  /js
    validation.js
  /css
    app.css
/storage
  mail.log
index.php
router.php
.htaccess
schema.sql
```

## 2) Tech Stack
- Backend: Core PHP (no framework)
- DB: MySQL (PDO + prepared statements)
- Frontend: TailwindCSS + Vanilla JS
- Architecture: MVC-like custom structure
- Email: `mail()` + `storage/mail.log` fallback

## 3) Roles
- Admin
- User (Donor)
- Orphanage

Each has separate authentication and role-based dashboard.

## 4) Setup
1. Import DB schema:
   ```bash
   mysql -u root -p < schema.sql
   ```
2. Update DB credentials in `config/app.php`.

- `base_url` in `config/app.php` can be left empty for auto-detection (works in subfolders like `/Ngo-codex`), or set explicitly if your server setup requires it.
3. Run local server:
   ```bash
   php -S 0.0.0.0:8000 router.php
   ```
4. Open:
   - `http://localhost:8000/`

Default Admin:
- `admin@ngo.local` / `admin12345`

## 5) Core Features Delivered
- Secure login/registration with bcrypt
- Custom secure session manager (`core/Session.php`)
- CSRF token validation (`core/Csrf.php`)
- Donor donation submission (description, quantity, pickup address)
- Orphanage accept/reject flow
- Admin approval + orphanage assignment
- Email notifications on donation submission:
  - Admin recipients: `balaabineshh0@gmail.com` and `balaabinesh88@gmail.com`
  - Donor email

## 6) API Endpoints (REST-like)
- `GET|POST|PUT|PATCH|DELETE /api/users`
- `GET|POST|PUT|PATCH|DELETE /api/donations`
- `POST /api/orphanage/actions`

All APIs return JSON.

## 7) Security Notes
- PDO prepared statements everywhere
- Input validation in controllers
- Output escaping in views
- Session hardening + regeneration
- CSRF protection on form actions

