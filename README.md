# Pulse

**Pulse** is a small Laravel application used as a hands-on sandbox. It is based on the official [Laravel Livewire starter kit](https://laravel.com/docs/starter-kits#livewire) (Fortify auth, Livewire, and Flux UI) with Vite and Tailwind CSS.

## What this project is

- A full-stack Laravel app for experimenting with real routes, queues, and database traffic—not a reusable Composer package.
- Optional **Docker** setup (`docker-compose.yml`) runs PHP, Nginx, MySQL, and a bootstrap script so the app can use MySQL with minimal manual wiring.

## Learning goal: Laravel Pulse

The main thing this repo is meant to pursue is **[Laravel Pulse](https://laravel.com/docs/pulse)**—Laravel’s first-party package for **application observability**: request and job throughput, slow endpoints and queries, exceptions, queues, and related signals in a live dashboard.

The learning goal is to understand how Pulse ingests metrics, how to tune it for local and containerized environments, and how monitoring fits next to normal feature work (auth, Livewire, background jobs). Tests disable Pulse via `PULSE_ENABLED=false` in `phpunit.xml` so the suite stays fast and deterministic.

## Running locally

- **Composer**: `composer setup` then `composer dev` (see `composer.json` scripts).
- **Docker**: from the project root, `docker compose up --build`, then open the mapped HTTP port (see `docker-compose.yml`).

## Sandbox Artisan commands

These live in `app/Console/Commands` and exist to generate predictable traffic for **Laravel Pulse** (slow queries, cache hits/misses, queued jobs). Run them with `php artisan …` from the project root (with a queue worker running if you expect jobs to process).

| Command | What it does |
|--------|----------------|
| `run:slow_query` | Runs `SELECT SLEEP(2)` against MySQL so Pulse can record a slow query. |
| `new:cache {key} {value}` | Writes a key/value with `Cache::put`, then reads it—useful for cache **hit** activity. |
| `run:missed_cache` | Looks up a unique key that was never stored—useful for cache **miss** activity. |
| `run:fast_job` | Dispatches `FastJob`: finishes immediately (logs only). |
| `run:slow_job` | Dispatches `SlowJob`: sleeps 2 seconds inside `handle()` when the worker runs it. |
| `run:slow_queue_job` | Dispatches `SlowQueueJob`: sleeps 2 seconds in the job constructor before `handle()` runs. |

Examples:

```bash
php artisan run:slow_query
php artisan new:cache demo_key "hello"
php artisan run:missed_cache
php artisan run:fast_job
php artisan run:slow_job
php artisan run:slow_queue_job
```
