# G3 Control

> **Sécurité. Simplicité. Confiance.**  
> **Safety. Simplicity. Trust.**

Professional bilingual platform for **G3 Control** — vehicle technical inspection in Yaoundé, Cameroon.

A single Laravel application delivers the public website and the secure operations console. Centres, hours, services, tariffs, appointment requests, tracking, and content are database-driven — not embedded in templates.

---

## Program status

| | |
| --- | --- |
| Phase | **III — Application foundation** |
| Progress | Phases I–II complete (steps 1–23) |
| Active step | **31** — Verify Pest |
| Pending | Steps 25–182 · Phases III–X |

Delivery is **strictly sequential**: specification → environment → application shell → design → database design → backend design → database build → backend build → admin → public frontend → QA → Docker → production.

| Milestone | Phase · Step | Status |
| --- | --- | --- |
| Specification | I · 11 | **Complete** |
| Environment | II · 23 | **Complete** |
| Application shell | III · 31 | **Active** |
| Experience design | IV · 33 | Locked |
| Database design | V · 72 | Locked |
| Database build | VI · 86 | Locked |
| Backend build | VI · 98 | Locked |
| Administration UI | VI · 108 | Locked |
| Public frontend | VII · 117 | Locked |
| Quality assurance | VIII · 130 | Locked |
| Containerisation | IX · 151 | Locked |
| Production | X · 160 | Locked |

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

Installation, local run, test, and deployment instructions will be added when Phases II–III and IX–X reach their respective gates. They are intentionally absent until the application exists.
