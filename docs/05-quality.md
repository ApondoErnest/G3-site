# 05 — Quality

| Delivery | Phase I · step **5** · Gate 11 · Verified from Phase VIII · step 130 |
| --- | --- |
| Previous · Next | [04-requirements.md](04-requirements.md) · [06-rules.md](06-rules.md) |

Non-functionals, definition of done, data handling. Facts stay in [01-baseline.md](01-baseline.md).

## NFR

### Performance

| ID | Requirement |
| --- | --- |
| NFR-P-01 | Usable on a normal Cameroon mobile connection |
| NFR-P-02 | Aim LCP &lt; 2.5s, CLS &lt; 0.1, INP &lt; 200ms on reasonable 4G |
| NFR-P-03 | AVIF/WebP + JPEG fallback |
| NFR-P-04 | Lazy maps/video; no homepage full MP4 |
| NFR-P-05 | Subset Manrope/Inter only |
| NFR-P-06 | Small public JS; no SPA |
| NFR-P-07 | Prod: config/route/view cache; Redis in Docker |

### Accessibility

| ID | Requirement |
| --- | --- |
| NFR-A-01 | WCAG 2.2 AA intent |
| NFR-A-02 | Keyboard-usable controls |
| NFR-A-03 | Visible focus |
| NFR-A-04 | Orange is not body text; status is not colour alone |
| NFR-A-05 | Labelled forms, clear errors |
| NFR-A-06 | Meaningful ALT in the page language |
| NFR-A-07 | `lang` matches locale; logical headings |
| NFR-A-08 | Adequate touch targets |

### Security

| ID | Requirement |
| --- | --- |
| NFR-S-01 | MFA, strong passwords, throttle, secure reset, session timeout |
| NFR-S-02 | CSRF on state-changing requests |
| NFR-S-03 | Policies on every admin action |
| NFR-S-04 | Rate limits: login, appointment, tracking, contact |
| NFR-S-05 | Tracking fails closed with a generic error |
| NFR-S-06 | Upload type/size; no executables in public media |
| NFR-S-07 | Collect only appointment, tracking, or contact data |
| NFR-S-08 | Secure production cookies (HTTPS, httpOnly, SameSite) |
| NFR-S-09 | Least-privilege database user |
| NFR-S-10 | No secrets in git |
| NFR-S-11 | `/admin` not in sitemap; robots disallow |

### Other

| ID | Requirement |
| --- | --- |
| NFR-R-01 | Appointment create and status change are transactional with history |
| NFR-R-02 | Failed mail must not drop the stored request |
| NFR-M-01 | Hours, prices, services, copy editable without deploy |
| NFR-M-02 | No business logic in Blade or Filament forms |
| NFR-M-03 | Pint + Larastan from Laravel foundation onward |
| NFR-M-04 | One Laravel 13 app: public + admin |
| NFR-M-05 | No repository-per-model |
| NFR-C-01 | Extra centres without template rewrite |
| NFR-C-02 | V1 = requests, not slot contention |
| NFR-V-01 | Uptime + error logging before launch |
| NFR-V-02 | Queue health once queues exist |
| NFR-E-01 | Local SEO on centre pages |
| NFR-E-02 | hreflang + canonicals |
| NFR-B-01 | Full FR/EN public experience |
| NFR-B-02 | English follows the glossary |
| NFR-B-03 | Layouts tolerate longer English |
| NFR-U-01 | Audit appointments, tariffs, schedules, settings, users, services |
| NFR-L-01 | Automated DB + media backups before launch |
| NFR-L-02 | Restore is tested |
| NFR-L-03 | Log rotation |
| NFR-L-04 | Off-server backup copy |
| NFR-D-01 | Current Chrome, Safari, Firefox, Edge |
| NFR-D-02 | ~320px through desktop |

Do not promise 99.99% uptime. Promise monitoring, backups, restore.

## Done

Implemented against `FR-*` · accepted per [07-acceptance.md](07-acceptance.md) · tested · bilingual if public · responsive · accessible · reviewed (same use cases in Filament) · documented if it changes a procedure. Both languages before publish (`BR-LANG-001`). Admin chrome may stay French.

## Data (internal — no legal pages in V1)

Collect only: request (name, phone, optional email, channel, vehicle, centre, service, preferred time); tracking lookup; contact form. No ID scans, payments, or extra DOB.

Form notice (suggestion): *Ces informations servent uniquement à traiter votre demande.* / *We use these details only to handle your request.*

Retention (G3 to confirm): appointments 24 months after final status · contacts 12 months after resolved · audit 24 months · tracking lookups not a dossier.

PII: appointment roles only. Footer legal pages only if counsel requires (change control).
