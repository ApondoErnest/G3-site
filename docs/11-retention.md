# 11 — Data retention

| Delivery | Phase V · step **75** |
| --- | --- |
| Prerequisites | [11-erd.md](11-erd.md) · [11-indexes-constraints.md](11-indexes-constraints.md) |
| Policy source | [05-quality.md](05-quality.md) § Data |
| Next | Step 76 — Gate: database design accepted |

Maps G3 Control retention policy to **tables**, **purge triggers**, **jobs**, and **exceptions**. Operational until G3 confirms periods in writing (noted as *proposed* below).

---

## 1. Policy summary

From [05-quality.md](05-quality.md):

| Data class | Proposed retention | G3 confirmation |
| --- | --- | --- |
| Appointment requests | **24 months** after final status | Pending |
| Contact messages | **12 months** after resolved | Pending |
| Audit log | **24 months** | Pending |
| Tracking lookups | **Not stored** — rate limiter only | Closed |
| Tariff versions | **Indefinite** (archive, never destroy) | Closed [BR-TARIFF-003](06-rules.md) |
| Media assets | Until unpublished/deleted by editor | Operational |
| Admin users | Deactivate; no automatic purge | Closed |

**PII scope:** appointment, tracking lookup inputs (transient), contact form only — [05-quality.md](05-quality.md), [NFR-S-07](05-quality.md).

---

## 2. Retention matrix

| Table | PII? | Retention class | Action after period |
| --- | :---: | --- | --- |
| `appointment_requests` | Yes | Appointment · 24 mo | **Purge** row + cascaded children |
| `appointment_status_histories` | Partial | With parent | **CASCADE** purge |
| `appointment_internal_notes` | Yes | With parent | **CASCADE** purge |
| `contact_messages` | Yes | Contact · 12 mo | **Purge** row + notes |
| `contact_internal_notes` | Yes | With parent | **CASCADE** purge |
| `activity_log` | Partial | Audit · 24 mo | **Delete** rows |
| `tariff_versions` | No | Indefinite | **Never** purge published/archived |
| `tariff_items` | No | With version | Never purge if version kept |
| `centres` | No | Indefinite | Master data |
| `centre_phones` | No | Indefinite | Master data |
| `centre_weekly_hours` | No | Indefinite | Master data |
| `schedule_exceptions` | No | Indefinite | Operational history |
| `operational_alerts` | No | Indefinite | Admin clears expired |
| `vehicle_categories` | No | Indefinite | Catalogue |
| `services` | No | Indefinite | Catalogue |
| `content_blocks` | No | Indefinite | CMS |
| `faq_entries` | No | Indefinite | CMS |
| `team_members` | No | Indefinite | CMS |
| `road_safety_sections` | No | Indefinite | CMS |
| `equipment` | No | Indefinite | CMS |
| `media` | No | Editorial | Manual unpublish/delete |
| `users` | Yes (admin) | Indefinite | Deactivate only |
| `settings` | No | Indefinite | Company config |
| `page_seo` | No | Indefinite | SEO config |
| `sessions` | No | Laravel default | GC by session driver |
| `password_reset_tokens` | No | Short-lived | Expire on use / TTL |
| Rate limiter keys | Transient | Minutes | Redis/file TTL — not DB |

---

## 3. Purge eligibility rules

### 3.1 Appointments — 24 months after **final status**

**Final statuses:** `completed`, `cancelled` ([BR-APPT-003](06-rules.md)).

**Eligibility timestamp:** `finalized_at` — computed as:

```text
MAX(appointment_status_histories.created_at)
WHERE status IN ('completed', 'cancelled')
```

Denormalized option (migration step 91+): add `finalized_at` nullable column on `appointment_requests`, set by state machine on first transition to final status — simplifies purge query and indexing.

**Purge query (conceptual):**

```sql
DELETE FROM appointment_requests
WHERE status IN ('completed', 'cancelled')
  AND finalized_at < NOW() - INTERVAL 24 MONTH;
```

**Cascades:** `appointment_status_histories`, `appointment_internal_notes` removed via FK CASCADE ([11-indexes-constraints.md](11-indexes-constraints.md) §3.2).

**Never purge:** requests still in `received`, `under_review`, `confirmed`, or `modification_requested`.

### 3.2 Contact messages — 12 months after **resolved**

**Eligibility:** `status = 'resolved'` AND `updated_at` (or dedicated `resolved_at`) older than 12 months.

**Recommended:** add `resolved_at` timestamp set on first transition to `resolved`.

**Purge query (conceptual):**

```sql
DELETE FROM contact_messages
WHERE status = 'resolved'
  AND resolved_at < NOW() - INTERVAL 12 MONTH;
```

**Cascades:** `contact_internal_notes`.

### 3.3 Audit log — 24 months

**Eligibility:** `activity_log.created_at` older than 24 months.

**Scope:** all log names unless legal hold flag added later.

```sql
DELETE FROM activity_log
WHERE created_at < NOW() - INTERVAL 24 MONTH;
```

**Note:** Purge audit **after** dependent operational rows if cross-referencing subject IDs matters for investigations — order: appointments/contacts first, then audit, or accept orphan subject references in old audit rows (document as acceptable for expired PII).

### 3.4 Tariff versions — never destroy

| Status | Retention |
| --- | --- |
| `draft` | Keep until deleted by admin or promoted; may delete empty drafts |
| `reviewed` | Keep until published or reverted to draft |
| `published` | **Permanent** archive on next publish [BR-TARIFF-003](06-rules.md) |
| `archived` | **Permanent** |

No scheduled purge job for `tariff_versions` or `tariff_items`.

---

## 4. What is never persisted

| Data | Mechanism | Reference |
| --- | --- | --- |
| Failed tracking lookups | Rate limiter counter only | [BR-TRACK-003](06-rules.md) |
| Tracking search arguments | Not logged to DB | [05-quality.md](05-quality.md) |
| Customer accounts | Out of scope | [03-scope.md](03-scope.md) |
| Slot inventory | Out of scope | NFR-C-02 |

---

## 5. Scheduled jobs

| Job | Schedule | Phase | Step |
| --- | --- | --- | --- |
| `PurgeExpiredAppointments` | Monthly · 03:00 Africa/Douala | VI+ | After step 91 |
| `PurgeExpiredContactMessages` | Monthly · 03:30 Africa/Douala | VI+ | After step 94 |
| `PurgeExpiredAuditLog` | Monthly · 04:00 Africa/Douala | VI+ | After step 95 |
| `PruneExpiredSessions` | Daily | III | Laravel scheduler |
| `PruneFailedJobs` | Weekly | IX | After queue exists |

Jobs run via Laravel scheduler; logged to application log; dry-run mode in staging.

---

## 6. Purge job behaviour

Each purge job must:

1. **Count** eligible rows before delete
2. **Log** count + date range to application log (not audit of individual PII rows)
3. **Delete** in batches (e.g. 500 rows) to avoid long locks
4. **Report** summary to ops (optional email in Phase X)
5. **Never** run in `local` unless `APP_ENV=local` and explicit flag

No soft-delete column required for V1 — hard delete after retention is intentional PII minimization.

---

## 7. Schema additions for retention (recommended)

Add during Phase VI migrations to simplify purge:

| Table | Column | Type | Purpose |
| --- | --- | --- | --- |
| `appointment_requests` | `finalized_at` | timestamp nullable | Set on first `completed`/`cancelled` |
| `contact_messages` | `resolved_at` | timestamp nullable | Set on first `resolved` |

**Indexes** (add in step 74 migrations):

| Index | Columns |
| --- | --- |
| `idx_appointment_requests_finalized_purge` | `status`, `finalized_at` |
| `idx_contact_messages_resolved_purge` | `status`, `resolved_at` |

Update [11-erd.md](11-erd.md) cross-reference — optional small add to ERD for these two columns.

---

## 8. Backup & restore interaction

| Concern | Rule |
| --- | --- |
| Backups | Include all tables; retention applies on **live** DB only [NFR-L-01](05-quality.md) |
| Restore drill | Restored DB may contain pre-purge data; re-run purge jobs after restore if needed [NFR-L-02](05-quality.md) |
| Off-server copy | Same retention as primary [NFR-L-04](05-quality.md) |

---

## 9. Legal & change control

| Item | Status |
| --- | --- |
| Form purpose notice | Suggested copy in [05-quality.md](05-quality.md) — implement on forms step 117+ |
| Footer legal pages | Out of V1 — change control if counsel requires |
| Retention period confirmation | **G3 to confirm** 24/12/24 months before go-live |
| GDPR-style erasure requests | Manual process via Super Admin until formal policy |

Changing retention periods requires change control ([03-scope.md](03-scope.md)) and update to this document.

---

## 10. Acceptance (step 75)

- [x] Every table classified: purge, cascade, indefinite, or transient
- [x] Appointment purge tied to final status + 24 months
- [x] Contact purge tied to resolved + 12 months
- [x] Audit purge 24 months
- [x] Tariff indefinite archive documented
- [x] Tracking not stored as dossier
- [x] Scheduled jobs and recommended schema columns defined
- [x] G3 confirmation flag noted for go-live

**Next:** Step **76** — Gate: database design accepted.
