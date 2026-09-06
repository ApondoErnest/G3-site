# 97 — Gate: schema accepted

| Delivery | Phase VI · step **97** · Gate |
| --- | --- |
| Prerequisites | Steps 86–96 complete · Gate 85 |
| Opens | Steps **98–107** — Domain PHP modules + Pest |
| Verified | 2026-09-05 |

Owner acceptance that G3 Control V1 **database schema in the repository** matches the approved design (Gate 76) and is seeded per baseline policy. **Domain modules (step 98+) may begin.** Public pages remain prohibited until Gate 116.

---

## 1. Verification summary

| Step | Deliverable | Status |
| ---: | --- | --- |
| 86 | Company settings · `spatie/laravel-settings` · [`CompanySettings`](../app/Settings/CompanySettings.php) | ✓ |
| 87 | Centres, phones, weekly hours | ✓ |
| 88 | Schedule exceptions | ✓ |
| 89 | Catalogue tables + pivots | ✓ |
| 90 | Tariff versions, items, centre pivot | ✓ |
| 91 | Appointment requests + status histories | ✓ |
| 92 | Appointment internal notes (separate from history) | ✓ |
| 93 | Content + SEO tables | ✓ |
| 94 | Contact messages + internal notes | ✓ |
| 95 | Users extensions, admin scopes, Spatie Permission / Media / Activity Log | ✓ |
| 96 | Baseline seed · 2 centres + locked hours only | ✓ |

**Test suite:** `composer test` — **75 passed** (159 assertions) on 2026-09-05.

---

## 2. Schema coverage vs [11-erd.md](11-erd.md)

| Module | Tables | Migrated |
| --- | --- | --- |
| Company | `settings` (Spatie) | ✓ |
| Centres | `centres`, `centre_phones`, `centre_weekly_hours` | ✓ |
| Schedule | `schedule_exceptions` | ✓ |
| Schedule | `operational_alerts` | **Deferred** — see §5 |
| Catalogue | `vehicle_categories`, `services`, `centre_service`, `service_vehicle_category`, `required_documents` | ✓ |
| Tariffs | `tariff_versions`, `tariff_items`, `tariff_item_centre` | ✓ |
| Appointments | `appointment_requests`, `appointment_status_histories`, `appointment_internal_notes` | ✓ |
| Contact | `contact_messages`, `contact_internal_notes` | ✓ |
| Content | `content_blocks`, `faq_entries`, `team_members`, `road_safety_sections`, `equipment`, `centre_equipment`, `page_seo` | ✓ |
| Identity | `users` (+ MFA columns), `admin_user_scopes`, Spatie Permission tables | ✓ |
| Media / audit | `media`, `activity_log` | ✓ |
| Foundation | `users`, `cache`, `jobs`, `sessions` (Phase III) | ✓ |

---

## 3. Constraints and indexes implemented

Per [11-indexes-constraints.md](11-indexes-constraints.md):

| Area | Verified in migrations + Pest |
| --- | --- |
| UNIQUE keys (`public_reference`, `centre.code`, tariff label, content block key, …) | ✓ |
| CHECK constraints (`chk_se_*`, `chk_cwh_*`, `chk_rd_*`, `chk_tv_*`, `chk_ti_*`, `chk_ar_locale`, `chk_cm_locale`) | ✓ |
| FK ON DELETE rules (CASCADE children, RESTRICT operational, SET NULL audit actors) | ✓ |
| Composite PK pivots (`centre_service`, `tariff_item_centre`, …) | ✓ |
| Query indexes (appointment queue, contact inbox, tariff resolver, activity log) | ✓ |

---

## 4. Seed policy (step 96)

| Table | Rows after `BaselineCentresSeeder` | Policy |
| --- | ---: | --- |
| `centres` | 2 | École de Police + Nomayos · [01-baseline.md](01-baseline.md) |
| `centre_phones` | 3 | E.164 baseline phones |
| `centre_weekly_hours` | 14 | 7 × 2 centres · locked hours |
| `vehicle_categories` | 0 | Until Q-06 |
| `services` | 0 | Until Q-02 |
| `tariff_versions` / `tariff_items` | 0 | Until Q-01 |
| Company settings | Defaults | Spatie settings migration · step 86 |

---

## 5. Outstanding before go-live (not blocking Gate 97)

| Item | Owner / when | Notes |
| --- | --- | --- |
| `operational_alerts` migration | Dev · before admin alerts (step 110) | In [11-erd.md](11-erd.md) step 88 grouping; PLAN step 88 scoped schedule exceptions only |
| Spatie role seeds (5 roles) | Dev · step 108 auth | ERD preview · not step 96 scope |
| Named admin users | G3 · step 108 | No shared passwords · [02-charter.md](02-charter.md) |
| Catalogue / tariff seed data | G3 · Q-01 / Q-02 / Q-06 | Tables empty by design |
| Retention period confirmation | G3 · pre-launch | [11-retention.md](11-retention.md) |

---

## 6. Phase VI entry criteria (domain build)

| Gate | Requirement | Status |
| --- | --- | --- |
| 85 | Domain design accepted | ✓ |
| 76 | Database design accepted | ✓ |
| 97 | Schema migrated + tested + baseline seed | ✓ this gate |
| 107 | Domain PHP accepted | ✓ [12-gate-domain-accepted.md](12-gate-domain-accepted.md) |
| 116 | Public pages | Blocked |

---

## 7. Migration index

| Migration | Step |
| --- | ---: |
| [`2022_12_14_083707_create_settings_table.php`](../database/migrations/2022_12_14_083707_create_settings_table.php) | 86 |
| [`database/settings/2026_09_05_192142_seed_company_settings_defaults.php`](../database/settings/2026_09_05_192142_seed_company_settings_defaults.php) | 86 |
| [`2026_09_05_194500_create_centres_tables.php`](../database/migrations/2026_09_05_194500_create_centres_tables.php) | 87 |
| [`2026_09_05_200000_create_schedule_exceptions_table.php`](../database/migrations/2026_09_05_200000_create_schedule_exceptions_table.php) | 88 |
| [`2026_09_05_201000_create_catalogue_tables.php`](../database/migrations/2026_09_05_201000_create_catalogue_tables.php) | 89 |
| [`2026_09_05_202000_create_tariff_tables.php`](../database/migrations/2026_09_05_202000_create_tariff_tables.php) | 90 |
| [`2026_09_05_203000_create_appointment_tables.php`](../database/migrations/2026_09_05_203000_create_appointment_tables.php) | 91 |
| [`2026_09_05_204000_create_appointment_internal_notes_table.php`](../database/migrations/2026_09_05_204000_create_appointment_internal_notes_table.php) | 92 |
| [`2026_09_05_205000_create_content_tables.php`](../database/migrations/2026_09_05_205000_create_content_tables.php) | 93 |
| [`2026_09_05_206000_create_contact_tables.php`](../database/migrations/2026_09_05_206000_create_contact_tables.php) | 94 |
| [`2026_09_05_207000_extend_users_for_admin_identity.php`](../database/migrations/2026_09_05_207000_extend_users_for_admin_identity.php) | 95 |
| [`2026_09_05_207100_create_admin_user_scopes_table.php`](../database/migrations/2026_09_05_207100_create_admin_user_scopes_table.php) | 95 |
| [`2026_09_05_207200_create_permission_tables.php`](../database/migrations/2026_09_05_207200_create_permission_tables.php) | 95 |
| [`2026_09_05_207300_create_media_table.php`](../database/migrations/2026_09_05_207300_create_media_table.php) | 95 |
| [`2026_09_05_207400_create_activity_log_table.php`](../database/migrations/2026_09_05_207400_create_activity_log_table.php) | 95 |
| [`BaselineCentresSeeder`](../database/seeders/BaselineCentresSeeder.php) | 96 |

---

## 8. Gate decision

**Accepted.** G3 Control V1 schema is migrated, constraint-tested, and baseline-seeded. Domain module implementation may proceed from step 98.

| | |
| --- | --- |
| Gate | 97 — Schema accepted |
| Date | 2026-09-05 |
| Next active step | **108** — Auth · Five roles, MFA, policies |

---

## 9. Change control

Schema changes after this gate require:

1. Update to [11-erd.md](11-erd.md) and [11-indexes-constraints.md](11-indexes-constraints.md) if design-affecting
2. New forward migration — never edit applied migrations
3. Pest feature test for new constraints
4. Change request per [03-scope.md](03-scope.md) if scope-affecting
