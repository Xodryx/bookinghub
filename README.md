# BookingHub

A multi-tenant booking SaaS for small businesses (studios, salons, consultants),
built as a **full-stack portfolio project**: a Laravel REST API consumed by an
Angular single-page application, fully containerized with Docker.

> 🚧 **Status: work in progress.** The infrastructure and API foundation are in
> place and running; features are being added incrementally (see the roadmap).
> This README documents the architecture and how to run it today.

---

## Why this project

Most portfolio projects are tutorials. This one is designed to look like real
software: clean layered architecture, stateless authentication, multi-tenancy,
automated tests and a reproducible Docker environment — the things a team
actually cares about.

## Tech stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.4, Laravel 13, REST API |
| Auth | Laravel Sanctum (httpOnly cookie, SPA authentication) |
| Database | MySQL 8 |
| Frontend | Angular (TypeScript) — *planned* |
| Infrastructure | Docker & Docker Compose (nginx + php-fpm + mysql) |
| Testing | PHPUnit (feature tests) |

## Architecture

The backend follows a layered design so responsibilities stay separated and
testable:

```
HTTP request
   │
   ▼
Middleware        auth, CORS
   │
   ▼
Controller        validates input, returns responses (thin)
   │
   ▼
Service           business logic (no HTTP, no SQL)
   │
   ▼
Repository        data access
   │
   ▼
MySQL
```

Multi-tenancy is modelled from the start: every user belongs to a tenant
(`tenant_id`), so data is isolated per business.

The Docker setup runs three services on one network:

```
nginx  ──►  backend (php-fpm)  ──►  mysql
:8000        Laravel API             :3307
```

## Getting started

**Requirements:** Docker Desktop.

```bash
# 1. Configure the backend environment
cp backend/.env.example backend/.env

# 2. Build and start the stack
docker compose up -d --build

# 3. Generate the app key and run migrations
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate

# 4. Open the API
# http://localhost:8000
```

## Roadmap

- [x] Dockerized environment (nginx + php-fpm + MySQL)
- [x] Laravel API foundation, running and migrated
- [ ] Authentication with Sanctum (register / login / logout)
- [ ] Tenants & users (multi-tenant model)
- [ ] Services & availability management
- [ ] Bookings (create / list / cancel)
- [ ] Feature tests for the API
- [ ] Angular front-end (SPA)

## Author

**Rodrigo Manuguerra** — Backend developer (6 years, PHP).
Open to remote freelance / part-time collaborations.
