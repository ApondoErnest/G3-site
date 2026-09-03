# 03 — Scope

| Delivery | Phase I · step **3** · Gate 11 |
| --- | --- |
| Previous · Next | [02-charter.md](02-charter.md) · [04-requirements.md](04-requirements.md) |

V1 is frozen until a change request is accepted.

## In

**Nav:** Accueil · À propos · Nos centres · Services · Visite technique · Tarifs · Sécurité routière · Contact  
**CTA:** Rendez-vous & Suivi · **FR | EN**  
**Private:** `/admin`

| FR | EN |
| --- | --- |
| `/fr/accueil` | `/en/home` |
| `/fr/a-propos` | `/en/about` |
| `/fr/centres` | `/en/centres` |
| `/fr/centres/ecole-de-police` | `/en/centres/ecole-de-police` |
| `/fr/centres/nomayos` | `/en/centres/nomayos` |
| `/fr/services` | `/en/services` |
| `/fr/visite-technique` | `/en/technical-inspection` |
| `/fr/tarifs` | `/en/fees` |
| `/fr/rendez-vous` | `/en/appointment` |
| `/fr/securite-routiere` | `/en/road-safety` |
| `/fr/contact` | `/en/contact` |

`/` → `/fr/accueil`. `/admin` has no locale prefix. Tracking is a tab on rendez-vous (`?tab=suivi` / `?tab=track`), not a second path.

**Capabilities:** live status, smart tariffs, appointment request + tracking, contact intents, alerts, Filament ops.

**Page jobs:** Services = what · Visite technique = how · Tarifs = cost · Centres = where.

**Journeys**

- Appointment: centre → service → vehicle → slot window → contact → review → reference → staff confirm/change/cancel → track  
- Tariff: type → category → price/validity/centres → optional prefilled request  
- Centre: status → call/directions → services → request  
- Track: reference + phone or plate → public timeline  
- Contact: intent → form or deep-link  

Footer: compact, no legal nav. **Suggestion:** one-line purpose notice on forms ([05-quality.md](05-quality.md)).

## Out of V1

Flottes, blog/Conseils/news, legal pages, customer accounts, payments, certificates, live line, expiry reminders, concierge, mobile inspection, fleet, native app, WhatsApp/SMS automation, slot inventory, page builder, API+SPA, Docker during feature work.

**Backlog (own requirements later):** reminders, WhatsApp, portal, history, real slots, queue, QR, payments, extra centre (model already allows it), no-show status, footer legal if counsel requires.

Expandability (not backlog): more centres/services without a redesign.

## Change request

Do not code first. Record: date, requester, feature, reason, urgency, impact (DB / UI / backend / security / tests / deploy), decision (V1 / backlog / reject), owner from [02-charter.md](02-charter.md).

## Exit

In/out and routes signed off.
