# 85 — Gate: domain design accepted

| Delivery | Phase V · step **85** · Gate |
| --- | --- |
| Prerequisites | Steps 77–84 complete · Gate 76 |
| Opens | Phase VI · step **86** — Migrations |
| Verified | 2026-09-05 |

Owner acceptance that G3 Control V1 **domain and backend design on paper** is complete. **Schema migrations (step 86) may begin.** Public pages remain prohibited until Gate 116.

---

## 1. Verification summary

| Step | Deliverable | Status |
| ---: | --- | --- |
| 77 | [12-domain-model.md](12-domain-model.md) | ✓ |
| 78 | [12-use-cases.md](12-use-cases.md) | ✓ |
| 79 | [12-availability-engine.md](12-availability-engine.md) | ✓ |
| 80 | [12-appointment-state-machine.md](12-appointment-state-machine.md) | ✓ |
| 81 | [12-tariff-engine.md](12-tariff-engine.md) | ✓ |
| 82 | [12-domain-events.md](12-domain-events.md) | ✓ |
| 83 | [12-cache-policy.md](12-cache-policy.md) | ✓ |
| 84 | [12-security-design.md](12-security-design.md) | ✓ |

---

## 2. Design coverage

| Area | Verified |
| --- | --- |
| PHP enums + value objects for all codes | ✓ |
| 19 use cases · Filament = Livewire | ✓ |
| Availability engine single source of truth | ✓ |
| Appointment state machine · BR-APPT-003 | ✓ |
| Tariff resolver · publish archive invariant | ✓ |
| 6 domain events + notification port | ✓ |
| Cache catalogues · never cache tracking | ✓ |
| Policies, rate limits, MFA, uploads | ✓ |
| Aligns with [07-acceptance.md](07-acceptance.md) test scenarios | ✓ |

---

## 3. Database design linkage

Domain design builds on Gate 76 ([11-gate-database-design.md](11-gate-database-design.md)):

| Domain artefact | Schema artefact |
| --- | --- |
| `AppointmentStatus` | `appointment_requests.status` |
| `AvailabilityEngine` | `centre_weekly_hours`, `schedule_exceptions` |
| `TariffResolver` | `tariff_versions`, `tariff_items` |
| `finalized_at` / `resolved_at` | retention purge columns |
| Events | activity_log + listeners |

---

## 4. Phase VI entry criteria

| Gate | Requirement | Status |
| --- | --- | --- |
| 85 | Domain design accepted | ✓ this gate |
| 85 | Database design accepted (76) | ✓ |
| 86+ | Implement migrations in PLAN order | Ready |
| 97 | Schema gate before domain PHP at scale | ✓ |
| 116 | Public pages blocked until admin gate | Enforced |

---

## 5. Implementation map (Phase VI preview)

| Design doc | Code module · PLAN step |
| --- | --- |
| Availability engine | Centres module · 99 |
| Tariff engine | Tariffs module · 101 |
| State machine + appointment use cases | Appointments · 102 |
| Track use case | Tracking · 103 |
| Events + notification port | 106 |
| Policies + MFA | Auth · 108 |

---

## 6. Outstanding before go-live (not blocking Gate 85)

| Item | Owner |
| --- | --- |
| Confirm retention periods | G3 |
| MFA package choice (Filament Breezy vs custom) | Dev at step 108 |
| Redis in production | Phase IX |
| Official catalogue/tariff data Q-01/Q-02/Q-06 | G3 |

---

## 7. Document index

| Document | Purpose |
| --- | --- |
| [12-domain-model.md](12-domain-model.md) | Entities, enums, VOs |
| [12-use-cases.md](12-use-cases.md) | Application commands/queries |
| [12-availability-engine.md](12-availability-engine.md) | Schedule resolution |
| [12-appointment-state-machine.md](12-appointment-state-machine.md) | Transitions |
| [12-tariff-engine.md](12-tariff-engine.md) | Effective tariff resolver |
| [12-domain-events.md](12-domain-events.md) | Events & listeners |
| [12-cache-policy.md](12-cache-policy.md) | Cache keys & TTL |
| [12-security-design.md](12-security-design.md) | Policies & hardening |

---

## 8. Gate decision

**Accepted.** Domain design for G3 Control V1 is approved. Phase VI schema migrations may proceed from step 86.

| | |
| --- | --- |
| Gate | 85 — Domain design accepted |
| Date | 2026-09-05 |
| Next active step | **86** — Migration: company / settings |

---

## 9. Change control

Changes to state machines, tariff rules, or availability algorithm after this gate require update to the relevant `12-*` doc and change control if acceptance criteria shift ([03-scope.md](03-scope.md)).
