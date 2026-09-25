# 09 — Architecture

| Delivery | Phase I · step **9** · Gate 11 · Foundation from Phase III · step 24 · Domain from Phase VI · step 98 |
| --- | --- |
| Previous · Next | [08-content.md](08-content.md) · [10-admin.md](10-admin.md) |

**Status:** Accepted — Gate 11 signed off 2026-09-03. Application code begins at Phase III, step 24.

Modular **Laravel 13** monolith: one repo, one DB, one deploy. Public + `/admin` share use cases. Not microservices, not API+SPA.

## Stack

| | |
| --- | --- |
| PHP / Laravel | 8.5 / 13 · amended from 8.4 at Phase II step 13 |
| Public | Blade, Livewire 4, Alpine, Tailwind 4, Flux 2 **controls only** (custom G3 chrome) |
| Admin | Filament 5, native icons |
| Public icons | Lucide; custom SVGs for benches/testers |
| Data | MySQL 9.6 · amended from 8.4 at Phase II step 16 |
| Later | Redis, queue, scheduler · [12-docker.md](12-docker.md) |
| Build / prod | Vite · Nginx+PHP-FPM · container layout in [12-docker.md](12-docker.md) |
| VCS / tests | GitHub · Pest, Pint, Larastan |
| Packages | Spatie media, translatable, permission, activity log, settings |

Flux must not make the marketing site look like a kit demo.

```text
Livewire / Filament
  → validation & policies
  → application use cases
  → domain (availability, tariffs, transitions)
  → Eloquent
  → MySQL / Redis / mail / media
```

No 300-line controllers. No repository-per-model. Filament **must** call the same use cases as Livewire.

**Time:** Africa/Douala for “open now”; UTC for `created_at`. **Maps:** Leaflet/OSM, lazy; itinerary opens the user’s app.

**Git:** `main` / `develop` / `feature/*`. No secrets in git.

Rejected: WordPress, SPA split, page builder.

---

## i18n — three stores

1. **PHP** `lang/fr/*.php`, `lang/en/*.php` — nav, buttons, validation, **labels** for codes.  
2. **JSON columns** (`{"fr":"…","en":"…"}`) — CMS copy; Filament tabs, never raw JSON.  
3. **Codes** — state machines compare only these.

| Store as | Not |
| --- | --- |
| `received`, `under_review`, `confirmed`, `modification_requested`, `completed`, `cancelled` | `Demande reçue` |
| `draft`, `reviewed`, `published`, `archived` | `Brouillon` |
| `open_normal_hours` / `closed` | Holiday sentences |
| `ecole-de-police`, `nomayos` | translated identifiers |
| official category `code`, `XAF`, `fr`/`en` | labels as keys |
| `super_admin`, `centre_manager`, … | translated role rows |

URL slugs may be translated (`/tarifs` vs `/fees`). Centre segments stay codes. CMS examples: centre name/address, services, FAQs, road safety, tariff notes, ALT. Not in CMS: prices, GPS, agrément number, status enums.

Prefer PHP enums wrapping the same codes.

---

## Modules

Company · Centre (phones, hours, global or per-centre exceptions) · Catalogue · Tariff · Appointment · Content (blocks on fixed templates) · Contact · Identity · Audit.

**Use cases:** `CreateAppointmentRequest` (transaction + history + event) · confirm/reschedule/cancel · `TrackAppointment` (rate limit, generic error) · `PublishTariffVersion` · `ChangeCentreSchedule`.

**Availability:** one engine; exception then weekday; half-open interval.

**Events:** `AppointmentRequested`, `AppointmentStatusChanged`, `ContactMessageReceived`, `MediaUploaded`, `TariffVersionPublished`, `CentreScheduleChanged`. Writes stay sync; mail/images may queue. Notify via a port (email in V1).

**Cache** catalogues; invalidate on publish; never cache tracking.

**Security:** CSRF, throttle, honeypot, MFA, least-privilege DB, MIME limits.

## Exit

Gate 11 complete (2026-09-03). Laravel begins at step 24.
