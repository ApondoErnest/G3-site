# G3 Control

> **Sécurité. Simplicité. Confiance.**  
> **Safety. Simplicity. Trust.**

Professional bilingual platform for **G3 Control** — vehicle technical inspection in Yaoundé, Cameroon.

A single Laravel application delivers the public website and the secure operations console. Centres, hours, services, tariffs, appointment requests, tracking, and content are database-driven — not embedded in templates.

---

## Program status

| | |
| --- | --- |
| Phase | **X — Production & operations** |
| Progress | Steps 1–177 complete |
| Active step | **178** — Public launch |
| Pending | Steps 178–182 · Phase X |

Delivery is **strictly sequential**: specification → environment → application shell → design → database design → backend design → database build → backend build → admin → public frontend → QA → Docker → production.

| Milestone | Phase · Step | Status |
| --- | --- | --- |
| Specification | I · 11 | **Complete** |
| Environment | II · 23 | **Complete** |
| Application shell | III · 31 | **Complete** |
| Experience design | IV · 71 | **Complete** |
| Database design | V · 85 | **Complete** |
| Database build | VI · 97 | **Complete** |
| Backend build | VI · 107 | **Complete** |
| Administration UI | VI · 116 | **Complete** |
| Public frontend | VII · 117–129 | **Complete** |
| Quality assurance | VIII · 150 | **Complete** |
| Containerisation | IX · 159 | **Complete** |
| Production | X · 177 | **Complete** |

Mark the active step complete in [PLAN.md](PLAN.md) before advancing.

---

## Documentation

| Resource | Description |
| --- | --- |
| [PLAN.md](PLAN.md) | Master plan · delivery spine · 182 steps |
| [docs/README.md](docs/README.md) | Specification pack · chronological index |
| [docs/01-baseline.md](docs/01-baseline.md) | Locked company, centres, and brand facts |
| [docs/03-scope.md](docs/03-scope.md) | V1 scope, routes, exclusions |
| [docs/09-architecture.md](docs/09-architecture.md) | Stack, layers, internationalisation |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Workflow and delivery rules |

Operational facts (hours, phones, agrément) are maintained in the baseline only. Updates follow change control in `docs/03-scope.md`.

---

## Developer guide

The local application exists. Phases II–IX are complete. The Pest suite passes with 370 tests and 3731 assertions. Nginx publishes host port 8082. The queue worker and one scheduler are running. MySQL 9.6 keeps `g3_control` on the `mysql-data` volume. Redis is cache only and keeps no data. The production host is the existing Hostinger VPS at `89.117.37.202`. Its environment file is `/var/www/g3-control/.env.docker`. `g3control.com` resolves to that host. Host nginx forwards HTTPS to `127.0.0.1:8082`. The production tree is `/var/www/g3-control` on `main`. The database is migrated and seeded. The public smoke test passed. A daily on-server backup at 01:00 Africa/Douala (00:00 UTC) keeps 14 days of the database, media, public files, and environment file. The operator Mac pulls that copy at 13:00 Africa/Douala, with a fallback at 18:00, through a key that can only list, stream, and checksum backups. A restore drill imported that backup into a throwaway database and removed it. An uptime check runs every 5 minutes and emails `aernestngonge@gmail.com` on failure. Healthchecks.io alerts when the server backup, the uptime check, or the Mac pull fails or stops running. Container logs are capped at 10 MB × 5, and the host monitor and backup logs rotate weekly. The production security audit passed, with MFA left off by choice and no HSTS header. The production factual audit matched the locked baseline, and Re-inspection is the approved English term for contre-visite. The accessibility and performance audit passed. Go-live was approved on 2026-09-29. Admin sign-in stays without an authenticator code, the site sends no HSTS header, and retention stays at the proposed 24, 12, and 24 months. Since 2026-10-13 the homepage hero uses five WebP images (0.76 MB in total) and only moves to a slide once its image has loaded. Every other public photo is also a WebP copy sized to its display width, and photos below the first screen load lazily; the original PNGs stay for the media seeder. Public launch is step 178.
