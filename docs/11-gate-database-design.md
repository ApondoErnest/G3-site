# 76 — Gate: database design accepted

| Delivery | Phase V · step **76** · Gate |
| --- | --- |
| Prerequisites | Steps 72–75 complete |
| Opens | Steps 77–85 domain design · Phase VI migrations after Gate 85 |
| Verified | 2026-09-05 |

Owner acceptance that G3 Control V1 **database design on paper** is complete: conceptual model, logical ERD, indexes/constraints, and retention policy. **No business migrations until Gate 85 (domain design accepted).** Foundation tables from Phase III remain.

---

## 1. Verification summary

| Step | Deliverable | Status |
| ---: | --- | --- |
| 72 | [11-conceptual-model.md](11-conceptual-model.md) | ✓ |
| 73 | [11-erd.md](11-erd.md) | ✓ |
| 74 | [11-indexes-constraints.md](11-indexes-constraints.md) | ✓ |
| 75 | [11-retention.md](11-retention.md) | ✓ |

---

## 2. Design coverage

| Area | Verified |
| --- | --- |
| 9 architecture modules mapped to tables | ✓ |
| 30 application tables + Spatie package tables | ✓ |
| Bilingual JSON CMS columns vs code columns vs PHP lang | ✓ |
| Appointment history ≠ internal notes (separate tables) | ✓ |
| Tariff versioning + centre pivot | ✓ |
| Admin centre scoping (`admin_user_scopes`) | ✓ |
| FK ON DELETE rules for all relationships | ✓ |
| CHECK constraints (scope, dates, amounts, locale) | ✓ |
| Query indexes (queue, tracking, tariff resolver, availability) | ✓ |
| Retention mapped with purge jobs | ✓ |
| Seed policy: 2 centres + hours only; empty catalogue/tariffs | ✓ |
| Open items Q-01/Q-02/Q-05/Q-06 accommodated | ✓ |

---

## 3. Business rules reflected in schema

| Rule | Design evidence |
| --- | --- |
| BR-APPT-004 | `public_reference` UK, not `id` |
| BR-APPT-006 | `appointment_internal_notes` separate from histories |
| BR-APPT-005 | Transactional create + history (app layer) |
| BR-TARIFF-003 | No soft-delete; archive status; RESTRICT on published delete |
| BR-TARIFF-004 | `tariff_item_centre` pivot |
| BR-LANG-001 | `content_blocks.locale_status` |
| BR-CENT-005 | `centres.status` |
| BR-COMP-001 | Agrément in settings, not centre row |
| NFR-U-01 | `activity_log` indexed for audit subjects |
| NFR-S-07 | PII tables identified in retention doc |

---

## 4. Phase V prohibitions respected

| Prohibition | Status |
| --- | --- |
| No business entity migrations in repository yet | ✓ |
| No invented seed prices or services | ✓ Documented |
| No public Blade implementation | ✓ |
| Design-only until Gate 85 for implementation | ✓ |

---

## 5. Outstanding before go-live (not blocking Gate 76)

| Item | Owner | Step |
| --- | --- | --- |
| Confirm retention periods (24/12/24 mo) | G3 | Pre-launch |
| Official category codes Q-06 | G3 | Before catalogue seed |
| Tariff matrix Q-01 | G3 | Before tariff seed |
| Services per centre Q-02 | G3 | Before catalogue seed |
| Add `finalized_at` / `resolved_at` columns | Dev | Migration 91/94 |
| PHP backed enums | Dev | Step 77 |

---

## 6. Document index

| Document | Purpose |
| --- | --- |
| [11-conceptual-model.md](11-conceptual-model.md) | Entities, domains, aggregates |
| [11-erd.md](11-erd.md) | Tables, columns, keys |
| [11-indexes-constraints.md](11-indexes-constraints.md) | Indexes, CHECK, FK delete rules |
| [11-retention.md](11-retention.md) | Purge policy and jobs |

---

## 7. Gate decision

**Accepted.** Database design for G3 Control V1 is approved on paper. Domain design (steps 77–85) may proceed; schema **implementation** opens after Gate 85.

| | |
| --- | --- |
| Gate | 76 — Database design accepted |
| Date | 2026-09-05 |
| Next active step | **77** — Domain model |

---

## 8. Change control

Schema changes after this gate require:

1. Update to [11-erd.md](11-erd.md) and related `11-*` docs
2. Change request per [03-scope.md](03-scope.md) if scope-affecting
3. New migration in Phase VI — never edit accepted gate documents retroactively without trace
