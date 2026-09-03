# G3 Control — Specification Pack

Product and technical definitions for **Phase I** (steps 1–11). **Gate 11 signed off 2026-09-03.** The full delivery sequence — environment, design, database, backend, admin, frontend, QA, deploy — lives in [PLAN.md](../PLAN.md).

Do not duplicate baseline facts (hours, phones, agrément) in other documents.

---

## Chronological order

### Phase I — write and sign off these documents (steps 1–11)

Read and approve in this order. Each document builds on the previous.

| Step | Doc | Title | Role in the sequence |
| ---: | ---: | --- | --- |
| 1 | [01](01-baseline.md) | Baseline | Locked company, centres, brand |
| 2 | [02](02-charter.md) | Charter | Why the project exists; who decides |
| 3 | [03](03-scope.md) | Scope | V1 pages, routes, exclusions, change control |
| 4 | [04](04-requirements.md) | Requirements | `FR-*` functional specification |
| 5 | [05](05-quality.md) | Quality | `NFR-*`, definition of done, retention |
| 6 | [06](06-rules.md) | Rules | `BR-*` binding business logic |
| 7 | [07](07-acceptance.md) | Acceptance | Pass/fail criteria for implementation |
| 8 | [08](08-content.md) | Content | Inventory, glossary, open items |
| 9 | [09](09-architecture.md) | Architecture | Stack, layers, i18n, use cases |
| 10 | [10](10-admin.md) | Admin | Roles and Filament structure |
| 11 | — | **Gate 11** ✓ | Owner sign-off · Phase II open · 2026-09-03 |

### Later phases — documents created when their step is active

| Step | Document | Layer |
| ---: | --- | --- |
| 33–71 | Design artefacts (IA, wireframes, hi-fi) | Experience design |
| 73 | [`11-erd.md`](11-erd.md) | Database design |
| 77–84 | Domain design notes (may extend `09-architecture.md`) | Backend design |
| 86–97 | Migrations in repository | Database build |
| 98–107 | PHP modules + Pest | Backend build |
| 108–116 | Filament resources | Administration UI |
| 117–129 | Blade / Livewire pages | Public frontend |
| 151 | [`12-docker.md`](12-docker.md) | Containerisation |
| 177 | [`13-launch.md`](13-launch.md) | Go-live checklist |

---

## Reference map

| Need | Document |
| --- | --- |
| Locked facts | [01-baseline.md](01-baseline.md) |
| Functional requirements | [04-requirements.md](04-requirements.md) · `FR-*` |
| Quality requirements | [05-quality.md](05-quality.md) · `NFR-*` |
| Business rules | [06-rules.md](06-rules.md) · `BR-*` |
| Acceptance tests | [07-acceptance.md](07-acceptance.md) |
| Full sequence | [PLAN.md](../PLAN.md) |

---

## Legend

| Term | Meaning |
| --- | --- |
| **Locked** | Confirmed by G3 — implement as written |
| **Suggestion** | Proposed — requires explicit approval before adoption |
