# G3 Control — Specification Pack

Product and technical definitions for **Phase I** (steps 1–11). **Gate 11 signed off 2026-09-03.** The full delivery sequence — environment, design, database, backend, admin, frontend, QA, deploy — lives in [PLAN.md](../PLAN.md).

Do not duplicate baseline facts (hours, phones, agrément) in other documents.

---

## Chronological order

### Phase I — write and sign off these documents (steps 1–11)

Read and approve in this order. Each document builds on the previous.

| Step | Doc | Title | Role in the sequence |
| ---: | ---: | --- | --- |
| 1 | [01](01-baseline.md) | Baseline | Locked company, centres, **G3 Signature Safety Bands** palette |
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
| 33–71 | [Design artefacts](../design/README.md) (IA, wireframes, hi-fi) · **Gate 71 ✓** · colour system revised in [34-design-system.md](../design/34-design-system.md) | Experience design |
| 72–76 | Database design · **Gate 76 ✓ 2026-09-05** · [index](11-gate-database-design.md) | Data model on paper |
| 72 | [`11-conceptual-model.md`](11-conceptual-model.md) | Conceptual |
| 73 | [`11-erd.md`](11-erd.md) | Logical ERD |
| 74 | [`11-indexes-constraints.md`](11-indexes-constraints.md) | Indexes & FKs |
| 75 | [`11-retention.md`](11-retention.md) | Retention |
| 76 | [`11-gate-database-design.md`](11-gate-database-design.md) | Gate |
| 77–85 | [Domain design](domain/README.md) · **Gate 85 ✓ 2026-09-05** | Backend design on paper |
| 77 | [`12-domain-model.md`](12-domain-model.md) | Domain model |
| 78 | [`12-use-cases.md`](12-use-cases.md) | Use-case catalogue |
| 79 | [`12-availability-engine.md`](12-availability-engine.md) | Availability engine |
| 80 | [`12-appointment-state-machine.md`](12-appointment-state-machine.md) | State machine |
| 81 | [`12-tariff-engine.md`](12-tariff-engine.md) | Tariff engine |
| 82 | [`12-domain-events.md`](12-domain-events.md) | Domain events |
| 83 | [`12-cache-policy.md`](12-cache-policy.md) | Cache policy |
| 84 | [`12-security-design.md`](12-security-design.md) | Security design |
| 85 | [`12-gate-domain-design.md`](12-gate-domain-design.md) | Gate |
| 86–97 | Migrations in repository · **Gate 97 ✓ 2026-09-05** · [`11-gate-schema-accepted.md`](11-gate-schema-accepted.md) | Database build |
| 98–107 | PHP modules + Pest · **Gate 107 ✓ 2026-09-06** · [`12-gate-domain-accepted.md`](12-gate-domain-accepted.md) | Backend build |
| 108–116 | Filament resources · **Gate 116 ✓ 2026-09-07** · [`10-gate-administration-accepted.md`](10-gate-administration-accepted.md) | Administration UI |
| 117–129 | Blade / Livewire pages · **Steps 118–128 Accueil + À propos + Nos centres + École de Police + Nomayos + Services + Visite technique + Tarifs + Rendez-vous & Suivi + Sécurité routière + Contact ✓** · **Gate 129 ✓ 2026-09-24** | Public frontend |
| 130–150 | Quality assurance · **Steps 130–136 ✓** · **Step 137 Pest — live status ✓** · **Step 138 Pest — tariff lifecycle ✓** · **Step 139 Pest — appointment transitions ✓** · **Step 140 Pest — tracking privacy ✓** · **Step 141 Pest — Livewire request flow ✓** · **Step 142 Pest — Filament authorisation ✓** · **Gate 143 ✓ 2026-09-25** · **Step 144 French content load ✓** · **Step 145 English content load ✓** · **Step 146 Media ingestion ✓** · **Step 147 Factual audit ✓** · **Step 148 Local UAT ✓** · **Step 149 End-to-end validation ✓** · **Gate 150 ✓ 2026-09-25** · 368 tests, 3700 assertions | Integration, compliance, UAT |
| 151–159 | Containerisation · **Step 151 Container architecture ✓** → [`12-docker.md`](12-docker.md) · **Gate 152 ✓ 2026-09-25** · **Step 153 Application container ✓** · **Step 154 MySQL container ✓** · **Step 155 Redis container ✓** · **Step 156 Nginx + PHP-FPM ✓** · **Step 157 Queue worker and scheduler ✓** · **Step 158 Parity verification ✓** · **Gate 159 ✓ 2026-09-25** | Docker after Gate 150 |
| 160 | VPS provisioned · Hostinger `89.117.37.202` · Ubuntu 24.04.4 · Docker 29.7.1 · **Accepted 2026-09-26** | Production host |
| 161 | Production environment · `/var/www/g3-control/.env.docker` · **Accepted 2026-09-26** | Production configuration |
| 162 | DNS · `g3control.com` and `www` → `89.117.37.202` · **Accepted 2026-09-26** | Public names |
| 163 | Reverse proxy · host nginx to `127.0.0.1:8082` · **Accepted 2026-09-26** | Public HTTP |
| 164 | TLS · Let’s Encrypt for `g3control.com` and `www` · expires 2026-12-25 · **Accepted 2026-09-26** | Public HTTPS |
| 165 | Deployment pipeline · clone `main` and `./docker/deploy.sh` · **Accepted 2026-09-26** | Repeatable deploy |
| 166 | Production migration · `https://g3control.com/up` returns 200 · **Accepted 2026-09-26** | Database schema |
| 167 | Production seed · centres, hours, official fees, media · **Accepted 2026-09-26** | Baseline data |
| 168 | Smoke test · public pages and `/admin/login` · **Accepted 2026-09-26** | Live site |
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
