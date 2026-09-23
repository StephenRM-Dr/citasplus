# CitasPlus (BarberPlus)

[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-pgsql-4169E1?logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![Vite](https://img.shields.io/badge/Vite-4-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Status](https://img.shields.io/badge/status-prototype%2Fdemo-orange)](AGENTS.md#8-known-gaps--inconsistencies)

Web application prototype for online appointment booking and management, originally built around a barbershop/salon use case (the underlying data model is generic enough for any appointment-based service business).

> Technical documentation for developers/AI agents (stack, architecture, conventions, known technical debt): see [AGENTS.md](AGENTS.md).

## Screenshots

| Public landing page | Booking flow |
|---|---|
| ![Landing](docs/screenshots/landing.png) | ![Booking](docs/screenshots/booking.png) |

| Business dashboard | Agenda (calendar) |
|---|---|
| ![Dashboard](docs/screenshots/dashboard.png) | ![Agenda](docs/screenshots/calendar.png) |

## Features

### For clients (public booking)
- Public booking page per business (`/book/{businessId}`): pick a service, employee, and available time slot.
- Real-time slot availability, generated in 30-minute blocks based on the business's working hours (`business_hours`), avoiding overlaps with existing appointments.
- Appointment confirmation (creates a record in `appointments`).
- Client registration and login (`/register`, `/login`).

### For businesses
- New business account registration (`/register`, creates the user and its associated `Business`) and login (`/login`).
- Own panel (`/business/dashboard`): today's appointments, monthly revenue, occupancy rate.
- Visual agenda (`/business/calendar`): all of the business's appointments on a calendar (FullCalendar), color-coded by status (pending, confirmed, completed, cancelled, no-show).
- Data model in place (though not yet fully wired to UI/logic) for:
  - Services (`services`) and employees (`employees`) per business.
  - Working hours per day of the week (`business_hours`) and special closures/holidays (`special_closures`).
  - Payments per appointment (`payments`) — no real payment gateway integration.
  - Reminders by email/SMS/WhatsApp (`reminders`) — modeled in the database only, no actual sending implemented.

### Full loop, ready to demo

1. A visitor goes to `/book/1` (the seeded demo business), picks a service and time slot, and books.
2. Log in as the business (`admin@barberia.com` / `admin`) and see the new appointment reflected instantly in `/business/dashboard` and `/business/calendar`.
3. The seeder already preloads 8 sample appointments (past, today, and upcoming, with varied statuses) and 2 completed payments, so the panel doesn't start empty.
4. You can also try registering a brand-new business from scratch via `/register`.

### Project status
This is a **prototype**: some pieces (reminders, payments, managing services/employees from the panel) are modeled in the database but don't have complete UI yet. See the "Known gaps" section of [AGENTS.md](AGENTS.md) for details.

## Prerequisites

- PHP `^8.1` with Composer
- Node.js + npm
- PostgreSQL (the project is configured for `pgsql`, not MySQL)
- Standard Laravel PHP extensions (pdo_pgsql, mbstring, openssl, etc.)

## Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Install JS dependencies
npm install

# 3. Configure environment variables
# Copy the example env file and adjust it for your local setup
cp .env.example .env
php artisan key:generate
# Set DB_PASSWORD (and other DB_* values) to match your local PostgreSQL setup.

# 4. Create the PostgreSQL database (if it doesn't exist)
#    createdb barberplus

# 5. Run migrations and seed demo data (business, employees, services, clients, and appointments)
php artisan migrate --seed
```

Seeded demo business credentials (to log in at `/login`): **admin@barberia.com** / **admin**.

> Note: `database/schema.sql` also exists, a manual dump of the schema equivalent to the migrations. Use **only one path** (migrations or the manual SQL) to avoid duplicating/drifting the schema.

## Running in development

Two processes need to run in parallel:

```bash
# Laravel server
php artisan serve

# Asset compilation (Vite) in watch mode
npm run dev
```

By default the app is available at `http://localhost:8000` (or whichever port `artisan serve` reports).

## Building for production

```bash
npm run build
```

## Additional notes

- No test suite is configured yet (PHPUnit is a dependency, but there's no `tests/` directory or `phpunit.xml`).
- No linter is configured (Laravel Pint is installed but has no config or associated script).
