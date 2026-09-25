# G3 Control

> **Sécurité. Simplicité. Confiance.**  
> **Safety. Simplicity. Trust.**

Professional bilingual platform for **G3 Control** — vehicle technical inspection in Yaoundé, Cameroon.

A single Laravel application delivers the public website and the secure operations console. Centres, hours, services, tariffs, appointment requests, tracking, and content are database-driven — not embedded in templates.

---

## Program status

| | |
| --- | --- |
| Phase | **IX — Containerisation** |
| Progress | Steps 1–150 complete |
| Active step | **151** — Container architecture |
| Pending | Steps 151–182 · Phases IX–X |

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
| Containerisation | IX · 151 | **Active** |
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

The local application exists. Phases II–VIII are complete, and Phase IX is in progress at step 151. Production deployment instructions wait for Phase X.
