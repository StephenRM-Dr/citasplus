# AGENTS.md — CitasPlus

Guidance for any AI coding agent (or human) working in this repository. This file is tool-agnostic; `CLAUDE.md` imports it and adds only Claude-Code-specific notes.

## 1. Project overview

CitasPlus is an early-stage appointment-booking SaaS prototype — "Citas" is Spanish for "appointments". The current build is branded internally as **BarberPlus** (`.env` → `APP_NAME=BarberPlus`) and modeled around a barbershop use case, but the underlying data model is generic enough for any service business (salons, clinics, etc.).

- All user-facing UI text and code comments are in **Spanish**.
- All code identifiers (classes, methods, variables) are in **English**.
- The project is a **prototype**: several routes and views are explicitly marked in code as unfinished (e.g. `routes/web.php` comments the landing page as "no implementada aún" — not implemented yet). Treat missing pieces as intentional incompleteness, not bugs to silently patch — see [§8 Known gaps](#8-known-gaps--inconsistencies).

## 2. Tech stack

| Layer | Technology |
|---|---|
| Backend framework | Laravel 10 (`laravel/framework ^10.10`), PHP `^8.1` |
| Database | PostgreSQL (`DB_CONNECTION=pgsql`, database name `barberplus`) |
| Auth | Session auth (`web` guard) for Blade pages + Laravel Sanctum (`api` guard) for the `v1` JSON API |
| Frontend build | Vite 4 + `laravel-vite-plugin`, entry points `resources/css/app.css` and `resources/js/app.js` |
| Frontend framework | None as SPA — server-rendered Blade views with vanilla JS. Alpine.js (`^3.17.4`) is imported in `resources/js/app.js` and installed, but not yet used in any Blade view (`x-data`/`@click` not found anywhere). |
| Styling | Bootstrap 5 + Bootstrap Icons, loaded via CDN in most views; no Tailwind |
| Calendar UI | FullCalendar 5.11.3, loaded via CDN |
| HTTP client | Guzzle (`guzzlehttp/guzzle ^7.2`), Axios on the frontend |
| Dev tooling | Laravel Pint (installed, no config committed), PHPUnit 10 (installed, unused), Laravel Sail, Mockery, Faker |

## 3. Directory map

```
app/
  Http/Controllers/   AuthController, BusinessController, PublicController, base Controller
  Models/              User, Business, Service, Employee, Appointment, Payment, Reminder, BusinessHour, SpecialClosure
  Http/Middleware/      only TrimStrings.php (custom) + Laravel stock middleware
database/
  migrations/          one file per table, all dated 2023_01_01_00000X
  seeders/             DatabaseSeeder.php — seeds a demo "Premium Barber Shop" business
  schema.sql           hand-written Postgres schema dump, parallel to the migrations — check for drift if editing either
resources/
  views/               Blade templates: layouts/app.blade.php, auth/, business/, public/, welcome.blade.php
  js/app.js            Vite entry — imports Alpine (installed, not yet used in any view)
  css/app.css          Vite entry — imports bootstrap-icons
routes/
  web.php              Blade pages + a couple of AJAX JSON endpoints (/api/slots/{id}, /api/book/{id})
  api.php              small Sanctum-protected v1 API (mostly unused by the actual booking flow)
  console.php          Artisan console routes
```

**Not part of the Laravel app** — do not treat these as the real frontend or try to wire them in without asking:
- `index.html` at the project root — a standalone static Bootstrap + FullCalendar mockup, CDN-only, not served through `public/`.
- `demo-barber/` — a separate standalone HTML/CSS/JS prototype/landing page, also disconnected from Laravel.

## 4. Architecture

- **Request flow**: mostly server-rendered Blade pages, with a small number of `fetch()`-driven AJAX endpoints defined directly in `routes/web.php` (not `routes/api.php`) for the public booking flow. This is not a SPA and not a fully separated REST API — a hybrid.
- **No service/repository/form-request layers.** Controllers talk directly to Eloquent models; validation is inline via `Validator::make()` / `$request->validate()`. Follow this pattern for consistency unless asked to introduce a new layer.
- **Where the core business logic lives**: `PublicController::getAvailableSlots()` and `PublicController::bookAppointment()` implement slot generation (30-minute intervals) and double-booking prevention via overlap queries on `start_time`/`end_time`. This is the most important logic in the app — read it carefully before changing booking behavior.
- **Auth**: two parallel systems — session-based `web` guard for the Blade UI (`AuthController`), and Sanctum `api` guard for `routes/api.php`'s `v1` endpoints. Authorization is a plain `role` string column on `users` (`business` / `customer`), checked via `User::isBusiness()` / `isCustomer()` — there is no permissions package.
- `/register` creates a `business`-role `User` plus its `Business` record (the form is framed as business signup, "Crea tu cuenta empresarial") — there is no separate public flow to register as a plain customer; customers are created implicitly via `PublicController::bookAppointment()`'s `firstOrCreate`.
- `business/calendar.blade.php` renders a FullCalendar view fed by the JSON endpoint `GET /business/calendar/events` (`BusinessController::calendarEvents()`), colored by appointment status.

## 5. Data model

PostgreSQL database `barberplus`, Eloquent ORM, 9 tables:

- `users` — role enum `business` / `customer`. Seeder creates 1 business owner + 4 demo customers.
- `businesses` — belongs to a business-role user
- `services` — belongs to a business
- `employees` — belongs to a business
- `appointments` — links `business_id`, `client_id` (user), `service_id`, `employee_id`, `start_time`/`end_time`, `status` (pending/confirmed/completed/cancelled/no_show)
- `payments` — belongs to an appointment, `status` (pending/completed/failed/refunded)
- `reminders` — belongs to an appointment, `type` (email/sms/whatsapp) — **no SMS/WhatsApp/email provider is actually integrated**, these are schema-only
- `business_hours` — per-day-of-week open/close per business
- `special_closures` — holiday/closure date overrides per business

## 6. Conventions

- Code identifiers (classes, methods, variables): **English**, PSR/Laravel-standard camelCase/PascalCase.
- Database columns and migration/table names: **snake_case**.
- Comments and all UI copy: **Spanish** (e.g. `// Citas de hoy`, `// Rutas Públicas de Reserva`). Keep new comments and UI text in Spanish to match.
- Validation is inline in controllers (no `FormRequest` classes) — match this unless the task specifically calls for introducing one.
- Blade views embed page-specific `<style>`/`<script>` blocks directly rather than extracting partials/components; each view currently defines its own CSS color variables rather than sharing a design system.
- `config/app.php` locale is `en` even though all UI content is Spanish — Laravel's i18n/`lang/` system is not used at all; Spanish strings are hardcoded directly into Blade files.

## 7. Commands

```
composer install       # install PHP dependencies
npm install             # install JS dependencies
php artisan migrate      # run migrations against the pgsql connection in .env
php artisan serve        # local dev server
npm run dev               # Vite dev server
npm run build              # Vite production build
```

- **No test command exists.** PHPUnit is a dev dependency but there is no `tests/` directory and no `phpunit.xml` — the test suite has not been bootstrapped yet.
- **No lint command exists.** Laravel Pint is a dev dependency but has no committed config (`pint.json`) and no `composer.json` script wired up.

## 8. Known gaps / inconsistencies

These are the current state of an in-progress prototype, not necessarily bugs — **flag or ask before "fixing" them**, since some may be intentional or already tracked elsewhere:

- **Not a git repository.** No `.git`, no `.gitignore`. Don't assume git commands or commit history are available.
- **No README**, and this AGENTS.md/CLAUDE.md pair is the first onboarding documentation the project has had.
- **No `.env.example`** — only a committed `.env` with real-looking local credentials (Postgres user/password, app key). If a repo is initialized, `.env` should be gitignored and an `.env.example` created before committing.
- **`composer.json` is still named `laravel/laravel`** and the database is still named `barberplus` — remnants of the app's origin as a barbershop-specific build before the more generic "CitasPlus" branding was introduced. Renaming either is a real (if low-risk) change — don't do it silently.
- **Two disconnected static HTML prototypes** (root `index.html`, `demo-barber/`) exist outside the Laravel app. They are design references, not live code — don't treat them as "the real frontend that needs wiring up."
- **No automated tests** and **no lint config**, despite both PHPUnit and Pint being installed as dependencies.
- **Locale mismatch**: `config/app.php` locale is `en`, but all UI content is hardcoded Spanish with no i18n layer in use.
