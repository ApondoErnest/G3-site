# 11 — Indexes, constraints & foreign keys

| Delivery | Phase V · step **74** |
| --- | --- |
| Prerequisites | [11-erd.md](11-erd.md) |
| Next | Step 75 — [11-retention.md](11-retention.md) |
| Implementation | Laravel migrations steps 86–95 |

Physical database rules for G3 Control V1: **indexes**, **CHECK constraints**, **UNIQUE constraints**, and **ON DELETE / ON UPDATE** behaviour for every foreign key in [11-erd.md](11-erd.md).

MySQL 9.6 · InnoDB · utf8mb4_unicode_ci.

---

## 1. Principles

| Principle | Rule |
| --- | --- |
| Referential integrity | All FKs declared in migrations; no orphan operational rows |
| Centre deletion | **Never** in V1 — `centres` use `RESTRICT` on all inbound FKs from transactional data |
| Tariff history | **Never** hard-delete published/archived versions — [BR-TARIFF-003](06-rules.md) |
| Append-only history | `appointment_status_histories` has no `updated_at`; no UPDATE policy in app |
| Purge vs delete | Retention purges (step 75) use explicit jobs; FK cascades apply only within an aggregate |
| Enum validation | Code columns validated in PHP enums (step 77); optional CHECK mirrors for defence in depth |
| Partial indexes | MySQL lacks PostgreSQL-style partial UNIQUE; tariff “one published” enforced in use case + transaction |

---

## 2. Global index naming

| Type | Pattern | Example |
| --- | --- | --- |
| Primary key | `pk_{table}` (implicit) | `id` |
| Unique | `uq_{table}_{columns}` | `uq_centres_code` |
| Foreign key | `fk_{child}_{parent}` | `fk_centre_phones_centres` |
| Index | `idx_{table}_{columns}` | `idx_appointment_requests_status_centre` |

---

## 3. Foreign keys — ON DELETE / ON UPDATE

`ON UPDATE` is **`RESTRICT`** everywhere (ids are immutable).

### 3.1 Master reference tables

| Child table | Column | Parent | ON DELETE | Rationale |
| --- | --- | --- | --- | --- |
| `centre_phones` | `centre_id` | `centres.id` | **CASCADE** | Owned by centre |
| `centre_weekly_hours` | `centre_id` | `centres.id` | **CASCADE** | Owned by centre |
| `centre_service` | `centre_id` | `centres.id` | **CASCADE** | Pivot |
| `centre_service` | `service_id` | `services.id` | **CASCADE** | Pivot |
| `centre_equipment` | `centre_id` | `centres.id` | **CASCADE** | Pivot |
| `centre_equipment` | `equipment_id` | `equipment.id` | **CASCADE** | Pivot |
| `service_vehicle_category` | `service_id` | `services.id` | **CASCADE** | Pivot |
| `service_vehicle_category` | `vehicle_category_id` | `vehicle_categories.id` | **CASCADE** | Pivot |
| `tariff_item_centre` | `tariff_item_id` | `tariff_items.id` | **CASCADE** | Owned by item |
| `tariff_item_centre` | `centre_id` | `centres.id` | **RESTRICT** | Centre is master |
| `tariff_items` | `tariff_version_id` | `tariff_versions.id` | **CASCADE** | Draft cleanup only |
| `tariff_items` | `vehicle_category_id` | `vehicle_categories.id` | **RESTRICT** | Catalogue master |
| `tariff_items` | `service_id` | `services.id` | **RESTRICT** | Nullable FK |
| `required_documents` | `vehicle_category_id` | `vehicle_categories.id` | **CASCADE** | Nullable |
| `required_documents` | `service_id` | `services.id` | **CASCADE** | Nullable |
| `admin_user_scopes` | `user_id` | `users.id` | **CASCADE** | Scope row |
| `admin_user_scopes` | `centre_id` | `centres.id` | **CASCADE** | Scope row |

### 3.2 Transactional / operational tables

| Child table | Column | Parent | ON DELETE | Rationale |
| --- | --- | --- | --- | --- |
| `schedule_exceptions` | `centre_id` | `centres.id` | **RESTRICT** | Historical schedule |
| `schedule_exceptions` | `created_by` | `users.id` | **SET NULL** | Preserve exception if user deactivated |
| `operational_alerts` | `centre_id` | `centres.id` | **RESTRICT** | Nullable |
| `appointment_requests` | `centre_id` | `centres.id` | **RESTRICT** | Operational record |
| `appointment_requests` | `service_id` | `services.id` | **RESTRICT** | Snapshot at submit |
| `appointment_requests` | `vehicle_category_id` | `vehicle_categories.id` | **RESTRICT** | Snapshot at submit |
| `appointment_status_histories` | `appointment_request_id` | `appointment_requests.id` | **CASCADE** | Aggregate child; purged with parent |
| `appointment_status_histories` | `actor_id` | `users.id` | **SET NULL** | Keep history if user deactivated |
| `appointment_internal_notes` | `appointment_request_id` | `appointment_requests.id` | **CASCADE** | Aggregate child |
| `appointment_internal_notes` | `author_id` | `users.id` | **RESTRICT** | Notes keep author reference |
| `contact_messages` | `centre_id` | `centres.id` | **RESTRICT** | Nullable |
| `contact_internal_notes` | `contact_message_id` | `contact_messages.id` | **CASCADE** | Aggregate child |
| `contact_internal_notes` | `author_id` | `users.id` | **RESTRICT** | |
| `tariff_versions` | `reviewed_by` | `users.id` | **SET NULL** | |
| `tariff_versions` | `published_by` | `users.id` | **SET NULL** | |

### 3.3 Deletion policy summary

| Entity | V1 delete policy |
| --- | --- |
| `centres` | **Prohibited** if any `appointment_requests` exist; otherwise admin-only with audit |
| `services`, `vehicle_categories` | **Prohibited** if referenced by appointments or published tariff items |
| `tariff_versions` | **Prohibited** if `published` or `archived`; draft may be deleted if no audit requirement |
| `appointment_requests` | Purge job only after retention ([11-retention.md](11-retention.md)) |
| `users` | **Deactivate** (`is_active = false`); never delete if authorship exists |

---

## 4. CHECK constraints

Implemented via `$table->check()` in Laravel migrations (MySQL 8.0.16+).

| Table | Constraint name | Expression |
| --- | --- | --- |
| `centre_weekly_hours` | `chk_cwh_open_times` | `(is_open = 0) OR (opens_at IS NOT NULL AND closes_at IS NOT NULL AND opens_at < closes_at)` |
| `schedule_exceptions` | `chk_se_scope` | `(applies_to_all_centres = 1 AND centre_id IS NULL) OR (applies_to_all_centres = 0 AND centre_id IS NOT NULL)` |
| `schedule_exceptions` | `chk_se_dates` | `ends_on IS NULL OR ends_on >= starts_on` |
| `schedule_exceptions` | `chk_se_open_times` | `(is_open = 0) OR (opens_at IS NOT NULL AND closes_at IS NOT NULL)` |
| `tariff_versions` | `chk_tv_dates` | `effective_until IS NULL OR effective_until >= effective_from` |
| `tariff_items` | `chk_ti_amount` | `amount_xaf > 0` |
| `required_documents` | `chk_rd_target` | `vehicle_category_id IS NOT NULL OR service_id IS NOT NULL` |
| `operational_alerts` | `chk_oa_dates` | `expires_at IS NULL OR expires_at >= starts_at` |
| `appointment_requests` | `chk_ar_locale` | `locale IN ('fr', 'en')` |
| `contact_messages` | `chk_cm_locale` | `locale IN ('fr', 'en')` |

**Code column values** (`status`, `intent`, etc.) validated primarily by PHP backed enums; CHECK optional duplicates listed in §8 if desired.

---

## 5. UNIQUE constraints

| Table | Columns | Purpose |
| --- | --- | --- |
| `settings` | (`group`, `name`) | Spatie settings |
| `centres` | `code` | Stable slug |
| `vehicle_categories` | `code` | Official category |
| `services` | `code` | Internal service code |
| `equipment` | `code` | Equipment identifier |
| `tariff_versions` | `label` | Human version label |
| `content_blocks` | `key` | Template slot |
| `road_safety_sections` | `anchor` | URL fragment |
| `centre_weekly_hours` | (`centre_id`, `weekday`) | One row per day |
| `centre_service` | (`centre_id`, `service_id`) | Pivot PK |
| `service_vehicle_category` | (`service_id`, `vehicle_category_id`) | Pivot PK |
| `tariff_item_centre` | (`tariff_item_id`, `centre_id`) | Pivot PK |
| `centre_equipment` | (`centre_id`, `equipment_id`) | Pivot PK |
| `admin_user_scopes` | (`user_id`, `centre_id`) | One scope row |
| `appointment_requests` | `public_reference` | Public tracking [BR-APPT-004](06-rules.md) |
| `appointment_requests` | `idempotency_key` | Double-submit guard (nullable UK) |
| `users` | `email` | Login (existing) |
| `page_seo` | `page` | PK |

---

## 6. Indexes by table

### 6.1 Company

**`settings`**

| Index | Columns | Type | Query |
| --- | --- | --- | --- |
| `uq_settings_group_name` | `group`, `name` | UNIQUE | Settings load |

### 6.2 Centres & schedule

**`centres`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_centres_code` | `code` | UNIQUE · route lookup |
| `idx_centres_status_sort` | `status`, `sort_order` | Public hub · active centres |

**`centre_phones`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_centre_phones_centre_sort` | `centre_id`, `sort_order` | Centre detail phones |

**`centre_weekly_hours`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_centre_weekly_hours_centre_weekday` | `centre_id`, `weekday` | UNIQUE |

**`schedule_exceptions`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_schedule_exceptions_centre_dates` | `centre_id`, `starts_on`, `ends_on` | Availability engine |
| `idx_schedule_exceptions_global_dates` | `applies_to_all_centres`, `starts_on`, `ends_on` | Global exceptions |

**`operational_alerts`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_operational_alerts_active_window` | `is_active`, `starts_at`, `expires_at` | Public banner resolver |
| `idx_operational_alerts_centre` | `centre_id` | Centre-scoped alerts |

### 6.3 Catalogue

**`vehicle_categories`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_vehicle_categories_code` | `code` | UNIQUE |
| `idx_vehicle_categories_published_sort` | `is_published`, `sort_order` | Finder · matrix |

**`services`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_services_code` | `code` | UNIQUE |
| `idx_services_published_sort` | `is_published`, `sort_order` | Services page |

**`required_documents`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_required_documents_category` | `vehicle_category_id`, `sort_order` | Checklist by category |
| `idx_required_documents_service` | `service_id`, `sort_order` | Checklist by service |

### 6.4 Tariffs

**`tariff_versions`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_tariff_versions_label` | `label` | UNIQUE |
| `idx_tariff_versions_status_effective` | `status`, `effective_from`, `effective_until` | Public resolver [FR-TA-04](04-requirements.md) |
| `idx_tariff_versions_status` | `status` | Admin filters |

**`tariff_items`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_tariff_items_version_sort` | `tariff_version_id`, `sort_order` | Matrix render |
| `idx_tariff_items_category` | `vehicle_category_id` | Finder |
| `idx_tariff_items_service` | `service_id` | Optional scope filter |

### 6.5 Appointments

**`appointment_requests`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_appointment_requests_public_reference` | `public_reference` | UNIQUE · tracking |
| `uq_appointment_requests_idempotency_key` | `idempotency_key` | UNIQUE · nullable |
| `idx_appointment_requests_queue` | `status`, `centre_id`, `created_at` | Admin dashboard · FR-AD-04 |
| `idx_appointment_requests_centre_created` | `centre_id`, `created_at` | Centre manager scope |
| `idx_appointment_requests_registration` | `registration_normalized` | Admin search |
| `idx_appointment_requests_phone` | `contact_phone_e164` | Admin search · tracking lookup |
| `idx_appointment_requests_created` | `created_at` | Retention purge |
| `idx_appointment_requests_finalized_purge` | `status`, `finalized_at` | Retention eligibility |

**`appointment_status_histories`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_appointment_status_histories_request_created` | `appointment_request_id`, `created_at` | Public timeline |
| `idx_appointment_status_histories_created` | `created_at` | Retention purge |

**`appointment_internal_notes`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_appointment_internal_notes_request_created` | `appointment_request_id`, `created_at` | Admin detail panel |

### 6.6 Contact

**`contact_messages`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_contact_messages_inbox` | `status`, `created_at` | Admin inbox · FR-AD-04 |
| `idx_contact_messages_intent` | `intent` | Filters |
| `idx_contact_messages_resolved` | `status`, `updated_at` | Legacy filter |
| `idx_contact_messages_resolved_purge` | `status`, `resolved_at` | Retention eligibility |

**`contact_internal_notes`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_contact_internal_notes_message_created` | `contact_message_id`, `created_at` | Thread view |

### 6.7 Content

**`content_blocks`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_content_blocks_key` | `key` | UNIQUE |
| `idx_content_blocks_page_published` | `page`, `is_published` | Page render |

**`faq_entries`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_faq_entries_category_sort` | `category_code`, `sort_order`, `is_published` | FAQ lists |

**`team_members`**

| Index | Columns | Query |
| --- | --- | --- |
| `idx_team_members_public_sort` | `display_publicly`, `sort_order` | About page |

**`road_safety_sections`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_road_safety_sections_anchor` | `anchor` | UNIQUE |
| `idx_road_safety_sections_published_sort` | `is_published`, `sort_order` | Page render |

### 6.8 Identity & audit

**`users`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_users_email` | `email` | UNIQUE (existing) |
| `idx_users_active` | `is_active` | Login filter |

**`admin_user_scopes`**

| Index | Columns | Query |
| --- | --- | --- |
| `uq_admin_user_scopes_user_centre` | `user_id`, `centre_id` | UNIQUE |
| `idx_admin_user_scopes_centre` | `centre_id` | Policy checks |

**`activity_log`** (Spatie)

| Index | Columns | Query |
| --- | --- | --- |
| `idx_activity_log_subject` | `subject_type`, `subject_id` | Entity audit trail |
| `idx_activity_log_created` | `created_at` | Retention purge |
| `idx_activity_log_log_name_created` | `log_name`, `created_at` | Filtered audit views |

**`media`** (Spatie)

| Index | Columns | Query |
| --- | --- | --- |
| (package default) | `model_type`, `model_id` | Polymorphic attach |
| `idx_media_collection` | `collection_name` | Library filters |

**Spatie Permission** — use package default indexes.

---

## 7. Application-level constraints (not DB-enforced)

These require use-case logic and tests (steps 77–81):

| Rule | Enforcement |
| --- | --- |
| At most one **published** tariff effective on a date | `PublishTariffVersion` transaction + row lock [FR-TA-04](04-requirements.md) |
| Appointment status transitions | State machine [BR-APPT-003](06-rules.md) |
| Booking only valid centre × service × category | `CreateAppointmentRequest` validation [BR-SVC-002](06-rules.md) |
| Preferred time within centre hours | Availability engine [BR-APPT-002](06-rules.md) |
| CMS publish requires FR + EN complete | `content_blocks.locale_status` [BR-LANG-001](06-rules.md) |
| Tracking lookup generic failure | No DB — rate limiter only [BR-TRACK-003](06-rules.md) |
| Inactive centre not bookable | `centres.status` check in use case [BR-CENT-005](06-rules.md) |

---

## 8. Optional CHECK mirrors (code columns)

Add if defence-in-depth desired; PHP enums remain source of truth:

```sql
-- Example: appointment_requests.status
CHECK (status IN (
  'received', 'under_review', 'confirmed',
  'modification_requested', 'completed', 'cancelled'
))
```

Same pattern for `tariff_versions.status`, `contact_messages.intent`, `contact_messages.status`, `operational_alerts.severity`.

---

## 9. Migration checklist

When implementing steps 86–95, each migration should:

1. Create table with column types from [11-erd.md](11-erd.md)
2. Add PRIMARY KEY and UNIQUE constraints (§5)
3. Add indexes (§6) — FK columns indexed automatically in Laravel if `foreignId()->constrained()`
4. Declare FK with explicit `onDelete()` per §3
5. Add CHECK constraints (§4) via `$table->check()`
6. Pest test: FK rejection, CHECK violation, unique collision where applicable

---

## 10. Acceptance (step 74)

- [x] ON DELETE / ON UPDATE defined for every FK
- [x] CHECK constraints for scope, dates, amounts, locale
- [x] UNIQUE constraints including public reference and idempotency
- [x] Query-driven indexes for admin queue, tracking, tariff resolver, availability
- [x] Application-level constraints documented separately
- [x] Migration implementation checklist provided

**Next:** Step **75** — Retention mapped to tables.
