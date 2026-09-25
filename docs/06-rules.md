# 06 — Business rules

| Delivery | Phase I · step **6** · Gate 11 · Coded from Phase VI · step 98 |
| --- | --- |
| Previous · Next | [05-quality.md](05-quality.md) · [07-acceptance.md](07-acceptance.md) |

Binding. Code implements these IDs. Owners: [02-charter.md](02-charter.md).

Hours in [01-baseline.md](01-baseline.md) are the operational values; this file is the logic.

### Company

**BR-COMP-001** Agrément N°0291 is company-wide, not centre-specific.  
**BR-COMP-002** Default locale French; `/` → `/fr/accueil`; missing UI keys fall back to French.  
**BR-COMP-003** No invented certifications, stats, testimonials, hours, or prices.

### Centres and time

**BR-CENT-001** Schedules are per centre (baseline hours) unless an exception applies.  
**BR-CENT-002** Exceptions override weekly hours; may be one centre or all.  
**BR-CENT-003** Holidays default **open**; admin can override.  
**BR-CENT-004** E.164 storage, national display.  
**BR-CENT-005** Inactive centres are not bookable and not “open now.”  
**BR-TIME-001** Wall-clock: Africa/Douala.  
**BR-TIME-002** Half-open hours: open at start, closed **at** close.  
**BR-TIME-003** `created_at` / audit in UTC.

### Appointments

**BR-APPT-001** Submit = request until confirmed. Copy: demande reçue.  
**BR-APPT-002** Time must fit that centre’s period (hours + holiday + exceptions).  
**BR-APPT-003** Codes only: `received` → `under_review` \| `cancelled`; `under_review` → `confirmed` \| `modification_requested` \| `cancelled`; `modification_requested` → `under_review` \| `confirmed` \| `cancelled`; `confirmed` → `completed` \| `cancelled`. No `no_show` in V1.  
**BR-APPT-004** Public references ≠ database IDs.  
**BR-APPT-005** Create, status change, tariff publish are transactional with history/audit.  
**BR-APPT-006** Internal notes ≠ status history; never on tracking.

### Tracking and tariffs

**BR-TRACK-001** Reference + phone **or** registration.  
**BR-TRACK-002** No notes, admin names, or other requests.  
**BR-TRACK-003** Generic error only (no existence leak).  
**BR-TARIFF-001** Public = published and effective.  
**BR-TARIFF-002** Integer XAF, grouped FCFA display.  
**BR-TARIFF-003** Archive, never destroy. A published version's lines can change so only some categories get a new amount. Publishing a new version that covers the whole list archives the previous published version.  
**BR-TARIFF-004** Item only at linked centres (and service if scoped).  
**BR-TARIFF-005** draft → reviewed → published → archived; publish needs elevated permission.

### Language, services, media, privacy

**BR-LANG-001** Publish only with FR **and** EN CMS fields.  
**BR-LANG-002** Language switch = equivalent page.  
**BR-LANG-003** English per [08-content.md](08-content.md) glossary.  
**BR-LANG-004** Fixed templates + content blocks; no page builder.  
**BR-LANG-005** UI in PHP `lang/fr|en`.  
**BR-LANG-006** CMS in JSON-backed fields; tabs, not JSON editors.  
**BR-LANG-007** Domain branches on codes (`received`, not `Demande reçue`). See [09-architecture.md](09-architecture.md).  
**BR-SVC-001** Only published, real G3 services.  
**BR-SVC-002** Never book a service/category at a centre that cannot do it.  
**BR-MEDIA-001** Real G3 photos by default.  
**BR-PRIV-001** Min PII ([05-quality.md](05-quality.md)).
