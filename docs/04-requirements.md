# 04 — Requirements

| Delivery | Phase I · step **4** · Gate 11 · Implemented from Phase VI · step 98 |
| --- | --- |
| Previous · Next | [03-scope.md](03-scope.md) · [05-quality.md](05-quality.md) |

IDs are stable. Do not reuse a deleted ID. **M** = V1 must · **S** = should · **C** = could (drop if it delays the gate).

Facts: [01-baseline.md](01-baseline.md). Rules: [06-rules.md](06-rules.md). Admin: [10-admin.md](10-admin.md).

### Company `FR-CO`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-CO-01 | M | Store name, slogans, agrément number and year, email, postal, logo, favicon, social, default SEO. |
| FR-CO-02 | M | Agrément N°0291 is company-wide, never a centre field. |
| FR-CO-03 | M | Public agrément, slogan, email, BP come from company settings. |

### Centres `FR-CE`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-CE-01 | M | Multiple centres. V1 seeds two active. |
| FR-CE-02 | M | Identity, slug, address/landmark FR/EN, GPS, email, postal, status, order, SEO, hero, gallery. |
| FR-CE-03 | M | Phones as related rows (label, E.164, order, callable). Nomayos has two. |
| FR-CE-04 | M | Weekly hours per weekday (open/closed, open, close). |
| FR-CE-05 | M | Holiday policy. Both V1 centres: normally **open** on holidays. |
| FR-CE-06 | M | Exceptions override the week. One centre or all. |
| FR-CE-07 | M | Open/closed **now**, next close, next open, in `Africa/Douala`. |
| FR-CE-08 | M | Same calculation on homepage, centre pages, admin, appointment form. |
| FR-CE-09 | M | Call and itinerary actions. |
| FR-CE-10 | S | A third centre later without rewriting templates. |
| FR-CE-11 | S | Operational alerts (severity, optional centre, start, expiry) on the public site. |

**Suggestion FR-CE-S1:** optional `is_whatsapp` for `wa.me`. No WhatsApp API.

### Services `FR-SV`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-SV-01 | M | Bilingual title, summary, body, icon, order, publish flag. |
| FR-SV-02 | M | Linked to one or more centres. |
| FR-SV-03 | M | Explicit service–vehicle compatibility. Documents on category, service, or both. |
| FR-SV-04 | M | Unpublished services never public. |
| FR-SV-05 | M | Services page shows which centres perform each service. |

Catalogue is G3-validated only ([08-content.md](08-content.md)).

### Categories `FR-VC`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-VC-01 | M | Code, labels FR/EN, examples, description, order, publish. |
| FR-VC-02 | M | Finder, services, documents, and booking share these records. |

### Tariffs `FR-TA`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-TA-01 | M | Versioned. Publish does not overwrite history. |
| FR-TA-02 | M | Effective from/until; draft / **reviewed** / published / archived. No silent edit of published history. |
| FR-TA-03 | M | Items: category, XAF integer, validity, notes FR/EN, centres, optional service. |
| FR-TA-04 | M | Public default = currently effective published version. |
| FR-TA-05 | M | Homepage finder and Tarifs use the same resolver. |
| FR-TA-06 | M | Search, filters, matrix, print-friendly, share, appointment handoff. |
| FR-TA-07 | S | PDF of the current matrix. |
| FR-TA-08 | M | Publish is an explicit confirm + audit. |
| FR-TA-09 | M | No published version → honest empty state, never invented prices. |

**Suggestion:** print CSS is must; PDF is should. Do not block tariff sign-off on a PDF library.

### Appointments `FR-AP`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-AP-01 | M | Submit a **request**: centre, service, vehicle, preferred date/period, contact. |
| FR-AP-02 | M | Unique public reference, not the database id (e.g. `G3-26-A8FD2`). |
| FR-AP-03 | M | Preferred time validated against that centre (`BR-APPT-002`). |
| FR-AP-04 | M | After submit: **demande reçue** / **request received** + reference. |
| FR-AP-05 | M | Codes: `received`, `under_review`, `confirmed`, `modification_requested`, `completed`, `cancelled`. Labels from PHP lang. No Show out of V1. |
| FR-AP-06 | M | Only legal transitions; illegal jumps rejected. |
| FR-AP-07 | M | Immutable history on every change. Create + first history = one transaction. |
| FR-AP-08 | M | Internal notes ≠ history; never on public tracking. |
| FR-AP-09 | M | Admin: confirm, request modification, reschedule, complete, cancel, notes. |
| FR-AP-10 | S | Preferred channel: telephone, email, optional WhatsApp label only. |
| FR-AP-11 | S | New requests notify admin (in-app + email). |
| FR-AP-12 | M | Registrations normalized for search. Rapid double submit = one request. |

Not a capacity calendar. No numbered slots.

### Tracking `FR-TR`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-TR-01 | M | Same page as booking, via a tab. |
| FR-TR-02 | M | Reference **and** telephone **or** registration. |
| FR-TR-03 | M | Reference alone never reveals a request. Mismatch = generic failure. |
| FR-TR-04 | M | Public timeline = customer-safe statuses only. |
| FR-TR-05 | M | Rate-limited. |

### Contact `FR-CT`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-CT-01 | M | Intent first: appointment, centre, tariffs, assistance. |
| FR-CT-02 | M | Name, telephone, email, subject, optional centre, message. |
| FR-CT-03 | M | New → In progress → Resolved, with internal notes. |
| FR-CT-04 | S | New messages notify admin. |
| FR-CT-05 | M | Honeypot + rate limiting. |

**Suggestion:** Turnstile later if spam appears.

### Content `FR-CN`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-CN-01 | M | About, inspection, FAQs, team, equipment, road safety, named blocks on **fixed templates**. No page builder. |
| FR-CN-02 | M | One evergreen road-safety page, ordered sections. |
| FR-CN-03 | M | Sections at least: braking, tyres, lighting, visibility, equipment, dashboard warnings, rain, pre-journey, link to inspection. |
| FR-CN-04 | S | FAQs categorised; can surface on relevant pages. |
| FR-CN-05 | M | Team: `display_publicly`. |
| FR-CN-06 | M | Equipment belongs to one or both centres. |
| FR-CN-07 | M | Admin FR \| EN tabs. JSON-backed storage; never raw JSON. |
| FR-CN-08 | M | Publish blocked until both languages complete (`BR-LANG-001`). |

### Media `FR-MD`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-MD-01 | M | Central library, collections (centres, equipment, team, inspection, road safety, brand). |
| FR-MD-02 | M | Title, ALT FR/EN, publish, WebP/AVIF derivatives. |
| FR-MD-03 | M | Inspection video on Visite technique with poster; not autoplayed on every homepage visit. |

### Admin `FR-AD`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-AD-01 | M | Filament 5 at `/admin`, own login. |
| FR-AD-02 | M | Roles: Super Admin, Operations Admin, Centre Manager (scoped), Reception Officer, Content Editor. |
| FR-AD-03 | M | Authorisation server-side, not only hidden menus. |
| FR-AD-04 | M | Dashboard: today’s requests by status/centre, contacts, live open/closed, upcoming exceptions. |
| FR-AD-05 | M | MFA, login throttle, session timeout, password policy, audit. |
| FR-AD-06 | M | `robots.txt` disallows `/admin`. |

Combine **assignments**, not the permission model. Filament calls the same use cases as Livewire.

### Notifications `FR-NT`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-NT-01 | S | New appointments and contacts via a notification port (V1 = email). |
| FR-NT-02 | M | Business code must not call SMTP from Livewire/Filament. |

### Localisation `FR-LO`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-LO-01 | M | `/fr/` and `/en/` with translated slugs ([03-scope.md](03-scope.md)). |
| FR-LO-02 | M | Language switch = equivalent page. |
| FR-LO-03 | M | `/admin` has no locale prefix. |
| FR-LO-04 | M | UI language in `lang/fr/*.php` and `lang/en/*.php`. |
| FR-LO-05 | M | CMS copy as JSON-backed columns (`fr`/`en`). Tabs, never raw JSON. |
| FR-LO-06 | M | Domain values are language-neutral **codes**. Labels from PHP. State machines compare codes only. |

### SEO `FR-SE`

| ID | Pri | Requirement |
| --- | --- | --- |
| FR-SE-01 | M | Unique title, description, canonical per FR/EN page. |
| FR-SE-02 | M | hreflang between equivalents. |
| FR-SE-03 | M | XML sitemap of public pages only. |
| FR-SE-04 | S | JSON-LD: organisation, both locations, FAQ where relevant, breadcrumbs. |
