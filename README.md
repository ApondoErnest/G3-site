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
| Progress | Steps 1–159 complete |
| Active step | **160** — Provision VPS |
| Pending | Steps 160–182 · Phase X |

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
| Production | X · 160 | **Active** |

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

The local application exists. Phases II–IX are complete. The Pest suite passes in `g3-control:test` with 368 tests and 3700 assertions. Nginx publishes host port 8082. The queue worker and one scheduler are running. MySQL 9.6 keeps `g3_control` on the `mysql-data` volume. Redis is cache only and keeps no data. Production deployment starts at step 160.
