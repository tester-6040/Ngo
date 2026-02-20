# Final Prompt: Recreate the NGO Dress Donation Platform From Scratch

You are an expert **senior full-stack engineer**. Rebuild this project **entirely from scratch** in a clean, production-minded way.

## Objective
Build a complete **Dress Donation Platform** for an NGO using:
- **Core PHP (no framework)**
- **MySQL (PDO + prepared statements)**
- **TailwindCSS + vanilla JavaScript**
- Clean MVC-like structure

The app should support:
1. User registration/login
2. Role-based access (`admin`, `user`, `orphanage`)
3. Donor donation submission
4. Admin approval and orphanage assignment
5. Orphanage accept/reject actions
6. Email notifications to:
   - `balaabineshh0@gmail.com`
   - `balaabinesh88@gmail.com`
   - donor (confirmation)
7. REST-like JSON API endpoints

---

## Hard Requirements

### Architecture
Implement this structure:

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
README.md
```

### Security
- Use password hashing (`password_hash`, `password_verify`)
- Use CSRF tokens for all state-changing form submissions
- Use prepared statements for all DB queries
- Escape all rendered user data (`htmlspecialchars`)
- Enforce role-based route protection server-side
- Regenerate sessions on login

### Business Rules
- Donation statuses: `pending`, `accepted`, `rejected`
- Valid transitions are one-way from `pending` to final states
- Once donation is final (`accepted`/`rejected`), hide/disable relevant UI actions
- Backend must enforce transition validity even if UI is bypassed

### Notifications
When donor submits a donation:
- Send notification to both admin emails (deduplicated)
- Send confirmation to donor email
- If `mail()` fails, append message to `storage/mail.log`

### API
Provide JSON endpoints:
- `GET|POST|PUT|PATCH|DELETE /api/users`
- `GET|POST|PUT|PATCH|DELETE /api/donations`
- `POST /api/orphanage/actions`

Return consistent JSON shape:
```json
{
  "success": true,
  "data": {},
  "error": null
}
```

### Database
Create `schema.sql` with all required tables, indexes, foreign keys, and seed admin account:
- admin email: `admin@ngo.local`
- admin password: `admin12345` (stored hashed)

### Documentation
`README.md` must include:
- setup steps
- environment config
- run instructions (`php -S 0.0.0.0:8000 router.php`)
- roles and workflows
- API examples
- security notes

---

## Quality Bar
- Keep code concise, readable, and modular.
- Avoid duplicate logic.
- Add helper methods where useful.
- Include defensive validation and clear error messages.
- Ensure app runs locally with no framework dependencies.

---

## Deliverables
1. Complete codebase with all files above.
2. Working end-to-end flows for all roles.
3. Syntax-valid PHP across project.
4. Clean, readable README.
5. No placeholders or TODO stubs.

Now generate the full implementation.
