# 10 — Admin

| Delivery | Phase I · step **10** · Gate 11 · Built from Phase VI · step 108 |
| --- | --- |
| Previous · Next | [09-architecture.md](09-architecture.md) · Gate 11 · [PLAN.md](../PLAN.md) |

`/admin` — Filament 5. Same use cases as the public site ([09-architecture.md](09-architecture.md)).

Five roles (combine assignments, not permissions):

| Role | Can | Cannot |
| --- | --- | --- |
| Super Administrator | All | — |
| Operations Administrator | Hours, exceptions, alerts, appointments | Users (unless also Super) |
| Centre Manager | Own centre ops | Other centre, global tariff publish, users |
| Reception Officer | Appointments, contacts | Users, tariff publish, settings |
| Content Editor | Blocks, media, road safety, FAQ, team, SEO | Appointment actions, users |

**Nav:** Operations (dashboard, appointments, contacts, alerts) · Centres (centres, hours, exceptions, equipment) · Catalogue (services, categories, documents) · Tariffs (versions, items) · Content (blocks, road safety, FAQ, team, media) · System (users, roles, settings, SEO, audit).

Dashboard: what needs attention today (new/under review, centre open/close, enquiries, current tariff, upcoming exceptions).

Appointments: search reference/plate/phone; contextual actions only. Notes separate from timeline. Tariffs: draft → review → publish → archive. CMS tabs FR/EN with completion; filters use **codes**, UI shows lang-file labels.

**Suggestion:** French admin chrome in V1.
