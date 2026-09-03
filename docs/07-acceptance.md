# 07 — Acceptance

| Delivery | Phase I · step **7** · Gate 11 · Tested from Phase VIII · step 137 |
| --- | --- |
| Previous · Next | [06-rules.md](06-rules.md) · [08-content.md](08-content.md) |

Pass/fail. “Looks good” is not a pass. Pest names should cite the rule, e.g. `it('closes Nomayos at 19:00 on weekdays per BR-CENT-001')`.

## Live status

FR-CE-07/08 · BR-TIME-* · BR-CENT-001–003

In `Africa/Douala`:

- Monday 10:00 — École de Police open, next close 20:00; Nomayos open, next close 19:00.
- Sunday 10:00 — both open, next close 15:00.
- Sunday 15:00 — both closed; next open Monday 07:00 (unless an exception).
- Monday 06:59 — both closed; next open 07:00. Monday 07:00 — both open.
- École de Police Wednesday 20:00 — closed. Nomayos Wednesday 19:00 — closed.
- Exceptional **closed** date → closed all day, with the message.
- Exceptional **07:00–15:00** → those hours, not the weekly pattern.
- Holiday “open normal hours” → weekday hours, not Sunday, unless an exception exists.
- **Global** closure → both centres unbookable that date. **Centre-only** exception does not change the other.
- Homepage, centre page, appointment step 1 show the **same** state for the same timestamp.
- Status is not hardcoded in Blade. Livewire/Filament call the domain service.

## Tariffs

FR-TA-* · BR-TARIFF-*

- Draft and reviewed versions are invisible publicly.
- Publishing B does not destroy A; publish is transactional.
- Finder and Tarifs return the same price for the same category.
- Amounts render as grouped FCFA.
- Empty catalogue → contact/empty state, not dummy numbers.
- Handoff prefills category/centre on Rendez-vous.

## Appointments

FR-AP-* · BR-APPT-*

- Submit: one request, one unique reference, status `received`, one history row.
- Confirmation says the request was received, not that a slot is booked.
- Sunday after 15:00 rejected. Nomayos Wednesday after 19:00 rejected.
- École de Police Wednesday 19:30 accepted; 20:00 rejected.
- Exceptional closure: that date cannot be chosen.
- `received` → `completed` rejected. `received` → `under_review` → `confirmed` → `completed` succeeds with history. `received` → `cancelled` allowed.
- Double-click submit = one request. Invalid service for the centre rejected.
- Internal notes not in the public tracking payload.

## Tracking

FR-TR-* · BR-TRACK-*

- Matching phone **or** registration with the reference succeeds.
- Wrong second factor → generic failure (no leak). Reference only rejected.
- Internal notes absent from payload and HTML. Repeated failures rate-limited.

## Localisation

FR-LO-* · BR-LANG-*

- `/fr/tarifs` ⇄ `/en/fees`. `/fr/centres/nomayos` ⇄ `/en/centres/nomayos`.
- `/` → `/fr/accueil`. `/admin` is not prefixed.
- Livewire on a French URL keeps French validation.
- `status` stores `received`, not `Demande reçue`.
- Service title in admin = JSON `fr`/`en`, not two PHP lang keys.
- Nav “Tarifs” lives in `lang/fr/*.php`, not in the services table.

## Admin

FR-AD-02/03

- Centre Manager for École de Police cannot edit Nomayos hours or act as global admin on Nomayos appointments.
- Content Editor cannot confirm or cancel. Reception Officer cannot edit users, roles, or agrément.
- Centre Manager cannot publish tariffs unless explicitly granted.
- Unguessable Filament URL without permission → 403, not a hidden button only.
- Filament confirm uses the same transition rules as domain tests.

## Content, contact, SEO

- Service with empty English cannot be published (`BR-LANG-001`). Unpublished team members absent from À propos.
- Valid contact → New message (+ admin email if enabled). Filled honeypot → nothing stored. Rate limit after repeats.
- Each public FR/EN URL has unique title and canonical. Sitemap omits `/admin` and unpublished pages.
