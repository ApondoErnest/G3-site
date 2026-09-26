# 12 — Container architecture

| Delivery | Phase IX · step **151** · Gate **152** accepted 2026-09-25 · Gate **159** accepted 2026-09-25 |
| --- | --- |
| Prerequisites | Gate 150 · [09-architecture.md](09-architecture.md) · [12-cache-policy.md](12-cache-policy.md) · [NFR-P-07](05-quality.md) |
| Implementation | Steps **153–158** done |
| Prohibited | This phase does not provision the VPS, public TLS, or off-server backups. Those are Phase X |

One Compose project for the same Laravel 13 monolith that runs locally. Public pages and `/admin` stay one application, one database, one deploy. Containers do not split the domain into services.

---

## 1. Topology

```text
host :8082
  → nginx
       static files from public/  (images, Vite build)
       PHP → app :9000 (php-fpm)
  app, worker, scheduler, migrate  →  one image
  those processes → mysql :3306
                 → redis :6379   (cache and rate limits)
```

| Service | Image | Lifetime | Step |
| --- | --- | --- | --- |
| `nginx` | `nginx:stable-alpine` | Long-running. The only published port | 156 |
| `app` | `g3-control` runtime target | php-fpm | 153 |
| `mysql` | `mysql:9.6` | Long-running. Named volume | 154 |
| `redis` | `redis:7-alpine` | Long-running. No volume | 155 |
| `worker` | same image as `app` | `php artisan queue:work` | 157 |
| `scheduler` | same image as `app` | `php artisan schedule:work` | 157 |
| `migrate` | same image as `app` | One-shot. Exits 0 before `app` starts | 153 |

Node builds assets and is not a running service. Mail is an external SMTP endpoint from the environment. This compose file does not contain a mail catcher or an SMTP server.

Host port **8082** maps to nginx port 80 so the stack can sit beside `php artisan serve` on port 8000. `APP_URL` for this stack is `http://localhost:8082` until the production domain is confirmed.

---

## 2. Image

`docker/php/Dockerfile` is multi-stage. `compose.yaml` and `.dockerignore` live at the repository root. Nginx config lives at `docker/nginx/default.conf`.

| Stage | Base | Produces |
| --- | --- | --- |
| `assets` | Node 22 LTS | `public/build` from `npm run build` (Vite 8) |
| `vendor` | `php:8.5-cli` | `vendor/` from `composer install --no-dev --optimize-autoloader` |
| `test` | `php:8.5-cli` | Dev dependencies plus `pdo_sqlite`. Step 158 runs `php artisan test` here |
| `runtime` | `php:8.5-fpm` | Production vendor, built assets, no Node, no `.env`, no Pest |

The test stage uses the same PHP 8.5 and the same application code as runtime. `phpunit.xml` keeps `DB_CONNECTION=sqlite` and an in-memory database, so the Pest run does not need the MySQL container.

Runtime PHP extensions: `pdo_mysql`, `redis` (phpredis), `gd`, `exif`, `fileinfo`, `intl`, `mbstring`, `bcmath`, `zip`, `opcache`, `pcntl`. The test stage also installs `pdo_sqlite`.

`.dockerignore` excludes `.env`, `vendor/`, `node_modules/`, and `public/build/` so those are produced inside the build.

On runtime start, when `APP_ENV=production`:

1. `php artisan config:cache`
2. `php artisan route:cache`
3. `php artisan view:cache`
4. `php artisan storage:link`

[NFR-P-07](05-quality.md). The test stage does not cache config; `composer test` already clears it.

---

## 3. Processes and the local core

The container stack changes only the drivers that the cache policy already assigns to production. Sessions, queues, and the filesystem stay on the drivers the application uses today.

| Setting | Local `.env` | Container runtime |
| --- | --- | --- |
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_TIMEZONE` | `UTC` | `UTC` |
| `APP_LOCALE` / `APP_FALLBACK_LOCALE` | `fr` | `fr` |
| `DB_HOST` | `127.0.0.1` | `mysql` |
| `REDIS_HOST` | `127.0.0.1` | `redis` |
| `CACHE_STORE` | `database` | `redis` |
| `QUEUE_CONNECTION` | `database` | `database` |
| `SESSION_DRIVER` | `database` | `database` |
| `FILESYSTEM_DISK` | `local` | `local` |
| `LOG_CHANNEL` | `stack` | `stderr` |
| `ADMIN_MFA_REQUIRED` | `false` | unset, so production requires MFA |

`CACHE_STORE=redis` is the production rule in [12-cache-policy.md](12-cache-policy.md). Rate limits follow that store. Tracking results stay uncached.

The queue stays on the database driver. `SendAppointmentNotification` and `SendContactNotification` already implement `ShouldQueue`. The worker drains the `jobs` table. A failed mail job does not roll back the stored request [NFR-R-02](05-quality.md).

`APP_ENV=production` makes admin MFA required through `config/admin.php`. The Pest stage sets `APP_ENV=testing` and does not inherit that requirement.

Scheduler clock stays UTC, matching `APP_TIMEZONE`. Retention jobs that [11-retention.md](11-retention.md) places at 03:00 Africa/Douala set that timezone on the schedule entry. The container does not move the application timezone. One scheduler replica. A second scheduler is not part of this design.

The worker command is `php artisan queue:work database --sleep=1 --tries=3 --max-time=3600`. `--max-time` lets a new image replace the process. `restart: unless-stopped` on `worker` and `scheduler`.

---

## 4. Data

| Store | Volume | Survives an image rebuild |
| --- | --- | --- |
| MySQL data directory | `mysql-data` | Yes |
| `storage/` (private media, public disk, logs if any) | `app-storage`, mounted on `app`, `worker`, and `scheduler` | Yes |
| Redis | none | No. Cache and rate-limit counters only |
| `bootstrap/cache` | container filesystem, rewritten by `config:cache` on start | No |

MySQL database name remains `g3_control`, charset `utf8mb4`. The application connects as a dedicated user with rights on that database only. The root password is an administrative secret and is not `DB_PASSWORD`.

`migrate` runs `php artisan migrate --force` once per deploy and then exits. `app`, `worker`, and `scheduler` start after that exit code is 0. Boot does not seed. Baseline and admin seeds stay explicit commands.

Public files already in `public/images/` ship inside the image. Spatie media on the `local` disk and anything under `storage/app/public` live on `app-storage`, so the worker and php-fpm see the same files.

Redis runs with persistence disabled (`--save "" --appendonly no`) and is not published to the host.

---

## 5. Network, HTTP, and health

Compose network name: `g3`.

| Port | Published |
| --- | --- |
| nginx `80` | Host `8082` |
| php-fpm `9000` | No |
| MySQL `3306` | No |
| Redis `6379` | No |

Nginx serves files from `public/` and forwards PHP to `app:9000`. `client_max_body_size`, `upload_max_filesize`, and `post_max_size` are **16m**, which covers a centre photograph. SVG uploads stay rejected by the application.

| Check | Probe |
| --- | --- |
| nginx | HTTP `GET /up` (Laravel health route) returns 200 |
| MySQL | `mysqladmin ping` |
| Redis | `redis-cli ping` |
| worker / scheduler | Process is running; Docker restarts it [NFR-V-02](05-quality.md) |

Logs go to stderr. Rotation is the container log driver [NFR-L-03](05-quality.md).

Boot order: MySQL healthy and Redis healthy, then `migrate` exits 0, then `app`, then nginx. `worker` and `scheduler` wait for `migrate` as well.

---

## 6. Secrets

Secrets are environment variables at runtime. They are not copied into the image and they are not committed.

| Secret | Used by |
| --- | --- |
| `APP_KEY` | `app`, `worker`, `scheduler`, `migrate` |
| `DB_PASSWORD` | Application user |
| `MYSQL_ROOT_PASSWORD` | MySQL bootstrap only |
| `REDIS_PASSWORD` | Set when the internal network should require one. Empty is allowed while Redis has no host port |
| `MAIL_PASSWORD` | Worker, when the SMTP endpoint requires it |

`MAIL_HOST` is supplied with the environment. This document does not choose a provider.

---

## 7. Later steps

| Step | Work |
| ---: | --- |
| 152 | This document accepted 2026-09-25 |
| 153 | Done. `g3-control:runtime`, `g3-control:test`, `.dockerignore`, `migrate` |
| 154 | Done. `mysql:9.6`, volume `mysql-data`, database `g3_control` |
| 155 | Done. `redis:7-alpine`, no volume, port 6379 unpublished |
| 156 | Done. `nginx:stable-alpine` on host port 8082, PHP forwarded to `app:9000` |
| 157 | Done. `queue:work database` and one `schedule:work` replica |
| 158 | Done. Pest in `g3-control:test`: 368 tests, 3700 assertions |
| 159 | Gate accepted 2026-09-25. Steps 153–158 verified |

Phase X still owns the VPS, the public TLS endpoint in front of nginx, the production domain, and off-server copies of `mysql-data` and `app-storage`.

---

## 8. Acceptance (step 151)

- [x] One Compose project and one application image
- [x] nginx, php-fpm, MySQL 9.6, Redis, queue worker, scheduler, and a one-shot migrate
- [x] Redis is the production cache; the queue and sessions stay on the database
- [x] Media and MySQL survive an image rebuild; Redis does not
- [x] Only nginx is published, on host port 8082
- [x] Pest runs in a test stage on sqlite, as `phpunit.xml` already does
- [x] VPS, TLS, the production domain, and the SMTP provider stay outside this phase

**Next:** Phase X · step **169** — On-server backups. Step **168** accepted 2026-09-26: public smoke test passed.
