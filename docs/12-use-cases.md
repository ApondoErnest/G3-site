# 12 — Use-case catalogue

| Delivery | Phase V · step **78** |
| --- | --- |
| Prerequisites | [12-domain-model.md](12-domain-model.md) · Gate 76 |
| Next | Steps 79–81 — Engine specs · Step 82 — Events |
| Implementation | `app/Actions/` · Phase VI steps 98–107 |

Application use cases for G3 Control V1. **Filament and Livewire call the same classes** ([09-architecture.md](09-architecture.md), [FR-AD-03](04-requirements.md)). Business logic does not live in Blade, Filament form callbacks, or controllers.

---

## 1. Conventions

### 1.1 Structure

```text
app/Actions/
  Appointment/
    CreateAppointmentRequest.php
    TransitionAppointmentStatus.php
    AddAppointmentInternalNote.php
    Data/
      CreateAppointmentRequestData.php
      TransitionAppointmentStatusData.php
  Tracking/
    TrackAppointment.php
  Tariff/
    PublishTariffVersion.php
    ...
  Schedule/
    ChangeCentreWeeklyHours.php
    CreateScheduleException.php
    ResolveCentreAvailability.php   ← query; spec step 79
  Contact/
    SubmitContactMessage.php
    ...
```

Each **command** use case is a single-purpose invokable class:

```php
final class CreateAppointmentRequest
{
    public function __invoke(CreateAppointmentRequestData $data): AppointmentRequestResult
    {
        // authorize → validate → transaction → event → return DTO
    }
}
```

**Query** use cases return read models / DTOs and perform no mutations (may use cache — step 83).

### 1.2 Cross-cutting rules

| Rule | Application |
| --- | --- |
| Authorization | Policy or gate **before** domain work |
| Transactions | `DB::transaction()` for create + history, publish, schedule writes [BR-APPT-005](06-rules.md) |
| Events | Dispatch **after** successful commit (step 82) |
| Notifications | Via `NotificationPort` interface — never SMTP from Livewire [FR-NT-02](04-requirements.md) |
| Idempotency | `CreateAppointmentRequest` honours `idempotency_key` [FR-AP-12](04-requirements.md) |
| Locale | Input carries `Locale`; copy from lang files in presenter layer |
| Errors | Domain exceptions → HTTP/Livewire generic messages; tracking never leaks [BR-TRACK-003](06-rules.md) |

### 1.3 Result DTOs

Public-facing results expose only customer-safe fields. Admin results may include internal ids for Filament routing.

---

## 2. Catalogue overview

| # | Use case | Type | Actor | Primary UI |
| ---: | --- | --- | --- | --- |
| 1 | `CreateAppointmentRequest` | Command | Public | Rendez-vous tab |
| 2 | `TransitionAppointmentStatus` | Command | Admin | Filament appointment |
| 3 | `UpdateAppointmentPreferredTime` | Command | Admin | Filament reschedule |
| 4 | `AddAppointmentInternalNote` | Command | Admin | Filament detail |
| 5 | `TrackAppointment` | Query | Public | Suivi tab |
| 6 | `ResolveCentreAvailability` | Query | Public + Admin | All live status widgets |
| 7 | `ResolveAllCentresAvailability` | Query | Public | Homepage · centres hub |
| 8 | `ChangeCentreWeeklyHours` | Command | Admin | Filament hours |
| 9 | `CreateScheduleException` | Command | Admin | Filament exceptions |
| 10 | `UpdateScheduleException` | Command | Admin | Filament exceptions |
| 11 | `DeleteScheduleException` | Command | Admin | Filament exceptions |
| 12 | `ResolveEffectiveTariff` | Query | Public | Tarifs · finder |
| 13 | `CreateTariffVersionDraft` | Command | Admin | Filament tariffs |
| 14 | `UpdateTariffVersionItems` | Command | Admin | Filament tariff editor |
| 15 | `MarkTariffVersionReviewed` | Command | Admin | Filament workflow |
| 16 | `PublishTariffVersion` | Command | Admin | Filament publish |
| 17 | `SubmitContactMessage` | Command | Public | Contact form |
| 18 | `TransitionContactStatus` | Command | Admin | Filament inbox |
| 19 | `AddContactInternalNote` | Command | Admin | Filament inbox |

Steps 79–84 expand engines, events, cache, and security — see [domain/README.md](domain/README.md).

**Next:** Step **79** — [12-availability-engine.md](12-availability-engine.md) (complete) · Phase VI opens at Gate **85** ✓

---

## 3. Appointment — request

### `CreateAppointmentRequest`

| | |
| --- | --- |
| **Requirements** | FR-AP-01–04, 07, 12 · BR-APPT-001–005 · BR-SVC-002 · BR-CENT-005 |
| **Actor** | Anonymous public (authenticated not required) |

**Input** (`CreateAppointmentRequestData`):

| Field | Type | Required |
| --- | --- | --- |
| `centreId` | int | yes |
| `serviceId` | int | yes |
| `vehicleCategoryId` | int | yes |
| `registration` | string | yes |
| `preferredDate` | date | yes |
| `preferredPeriod` | `PreferredPeriod` | yes |
| `contactName` | string | yes |
| `contactPhone` | string | yes → normalized E.164 |
| `contactEmail` | string | no |
| `preferredChannel` | `PreferredChannel` | no |
| `locale` | `Locale` | yes |
| `idempotencyKey` | string | no (from session/form token) |

**Flow:**

1. Rate limit check (appointment submit bucket) [NFR-S-04](05-quality.md)
2. Load centre, service, category — must exist, published, active
3. Assert `Service::isAvailableAt(centre, category)` [BR-SVC-002](06-rules.md)
4. Call `ResolveCentreAvailability` for `preferredDate` + period [BR-APPT-002](06-rules.md)
5. If idempotency key exists → return existing request (200-equivalent result)
6. **Transaction:**
   - Generate `PublicReference`
   - Insert `appointment_requests` status `received`
   - Insert first `appointment_status_histories` (actor `system`)
7. Dispatch `AppointmentRequested` event [step 82]
8. Return `AppointmentRequestResult` with reference + success copy key (`demande reçue`) [FR-AP-04](04-requirements.md)

**Output** (`AppointmentRequestResult`):

| Field | Public |
| --- | --- |
| `publicReference` | yes |
| `status` | yes (`received`) |
| `messageKey` | yes — lang key for UI |

**Never returned:** database id, internal notes.

**Errors:**

| Condition | Response |
| --- | --- |
| Invalid centre/service/category combo | Validation error (specific field) |
| Preferred time outside hours | Validation error on date/period |
| Inactive centre | Validation error |
| Rate limited | 429 / Livewire error |

**Called from:** Livewire `AppointmentRequestForm` · not Filament.

---

### `TransitionAppointmentStatus`

| | |
| --- | --- |
| **Requirements** | FR-AP-05–07, 09 · BR-APPT-003, 005 |
| **Actor** | Admin with policy on appointment + centre scope |

**Input** (`TransitionAppointmentStatusData`):

| Field | Type |
| --- | --- |
| `appointmentId` | int |
| `toStatus` | `AppointmentStatus` |
| `publicNote` | `TranslatableCopy` optional — customer-safe |
| `actor` | `User` |

**Flow:**

1. Authorize: role + centre scope [FR-AD-03](04-requirements.md)
2. Load appointment; assert transition allowed via state machine (step 80)
3. **Transaction:**
   - Update `appointment_requests.status`
   - Append `appointment_status_histories`
   - Set `finalized_at` if transitioning to `completed` or `cancelled`
4. Activity log + `AppointmentStatusChanged` event
5. Return updated appointment DTO for Filament refresh

**Legal transitions:** per [BR-APPT-003](06-rules.md) — enforced centrally, same for Pest and Filament.

**Called from:** Filament table actions (Confirm, Cancel, Complete, Start review, Request modification).

---

### `UpdateAppointmentPreferredTime`

| | |
| --- | --- |
| **Requirements** | FR-AP-09 reschedule · BR-APPT-002 |
| **Actor** | Admin (reception, centre manager, ops) |

**Input:** `appointmentId`, new `preferredDate`, `preferredPeriod`, optional `publicNote`, `actor`.

**Flow:**

1. Authorize + load appointment (typically status `under_review` or `modification_requested`)
2. Validate new preferred time via `ResolveCentreAvailability`
3. **Transaction:** update appointment fields; optional history row with note if status unchanged or transition to `under_review`
4. Event if status changed

**Called from:** Filament “Proposer autre créneau” action.

---

### `AddAppointmentInternalNote`

| | |
| --- | --- |
| **Requirements** | FR-AP-08 · BR-APPT-006 |
| **Actor** | Admin with appointment access |

**Input:** `appointmentId`, `body`, `author`.

**Flow:** Insert `appointment_internal_notes` — **no** history row, **no** event to public.

**Called from:** Filament detail panel textarea save.

---

## 4. Tracking

### `TrackAppointment`

| | |
| --- | --- |
| **Requirements** | FR-TR-01–05 · BR-TRACK-001–003 |
| **Actor** | Public |

**Input** (`TrackAppointmentData`):

| Field | Required |
| --- | --- |
| `publicReference` | yes |
| `phoneOrRegistration` | yes — second factor |

**Flow:**

1. Rate limit (tracking bucket) — generic failure on exceed [FR-TR-05](04-requirements.md)
2. Normalize phone (if numeric) or plate (upper, strip spaces)
3. Find appointment by `public_reference` — if not found → **generic failure** [BR-TRACK-003](06-rules.md)
4. Compare second factor: `contact_phone_e164` OR `registration_normalized` [BR-TRACK-001](06-rules.md)
5. Mismatch → **same generic failure** (no timing leak)
6. Load `appointment_status_histories` ordered ASC — **exclude** internal notes [FR-TR-04](04-requirements.md)
7. Return `TrackingResult` DTO

**Output** (`TrackingResult`):

| Field | Included |
| --- | --- |
| `publicReference` | yes |
| `currentStatus` | code only |
| `timeline` | `{status, labelKey, at}`[] — labels resolved in UI |
| `centreName` | translated — optional summary |

**Never included:** internal notes, admin names, other appointments, database id.

**Cache:** **never** [step 83].

**Called from:** Livewire `AppointmentTrackingForm` · same page tab [FR-TR-01](04-requirements.md).

---

## 5. Schedule

### `ResolveCentreAvailability`

| | |
| --- | --- |
| **Requirements** | FR-CE-07, 08 · BR-CENT-001–003 · BR-TIME-* |
| **Actor** | Internal (called by other use cases + Livewire read) |
| **Spec detail** | Step **79** |

**Input:** `centreId`, `at` (`CarbonImmutable` in display TZ) OR `date` + `PreferredPeriod`.

**Output** (`CentreAvailabilitySnapshot`):

| Field | Description |
| --- | --- |
| `state` | `LiveCentreState` |
| `isOpenNow` | bool |
| `nextCloseAt` | nullable datetime display |
| `nextOpenAt` | nullable datetime display |
| `isBookableOnDate` | bool for appointment validation |
| `reasonKey` | optional lang key if exception |

**Algorithm (summary):** exception → holiday policy → weekly hours; half-open intervals [BR-TIME-002](06-rules.md). Full spec step 79.

**Called from:** `CreateAppointmentRequest`, `UpdateAppointmentPreferredTime`, centre widgets, dashboard, Filament read-only widgets.

---

### `ResolveAllCentresAvailability`

**Input:** `at` optional (default now).

**Output:** map `centreId` → `CentreAvailabilitySnapshot`.

**Called from:** Homepage live strip, centres compare page, admin dashboard centre status card.

---

### `ChangeCentreWeeklyHours`

| | |
| --- | --- |
| **Requirements** | FR-CE-04 · BR-CENT-001 |
| **Actor** | Ops admin, centre manager (own centre) |

**Input:** `centreId`, array of 7 `WeeklyHoursData` (weekday, isOpen, opensAt, closesAt), `actor`.

**Flow:**

1. Authorize centre scope
2. Validate time windows (open < close when open)
3. **Transaction:** upsert 7 `centre_weekly_hours` rows
4. Invalidate availability cache (step 83)
5. `CentreScheduleChanged` event + activity log

**Called from:** Filament centre hours editor.

---

### `CreateScheduleException` / `UpdateScheduleException` / `DeleteScheduleException`

| | |
| --- | --- |
| **Requirements** | FR-CE-06 · BR-CENT-002 |
| **Actor** | Ops admin, centre manager (scoped) |

**Create input:** scope (all centres vs one), dates, open/closed, custom hours, bilingual reason, `actor`.

**Flow:** authorize → validate scope CHECK → transaction → cache invalidate → event.

**Delete:** hard delete exception row (admin audit logged); not used for appointments history.

**Called from:** Filament exceptions resource.

---

## 6. Tariff

### `ResolveEffectiveTariff`

| | |
| --- | --- |
| **Requirements** | FR-TA-04, 05, 09 · BR-TARIFF-001 |
| **Actor** | Internal query |
| **Spec detail** | Step **81** |

**Input:** optional `asOfDate` (default today display TZ), optional filters (`centreId`, `vehicleCategoryId`, `serviceId`).

**Output** (`EffectiveTariffResult`):

| Field | Description |
| --- | --- |
| `version` | published version metadata or null |
| `items` | filtered line DTOs with `MoneyXaf` |
| `isEmpty` | true → UI shows empty state [FR-TA-09](04-requirements.md) |

**Called from:** Tarifs page, homepage finder, appointment handoff prefill.

---

### `CreateTariffVersionDraft`

**Input:** `label`, `effectiveFrom`, optional `effectiveUntil`, `actor`.

**Flow:** insert `tariff_versions` status `draft`; activity log.

---

### `UpdateTariffVersionItems`

**Input:** `tariffVersionId`, array of item DTOs (category, amount, centres, optional service, notes).

**Precondition:** version status `draft` or `reviewed` only — not `published`/`archived` [BR-TARIFF-003](06-rules.md).

**Flow:** replace items in transaction (delete draft lines + reinsert) or upsert by id.

---

### `MarkTariffVersionReviewed`

**Input:** `tariffVersionId`, `actor`.

**Precondition:** status `draft`, has ≥1 item.

**Flow:** status → `reviewed`; set `reviewed_at`, `reviewed_by`.

---

### `PublishTariffVersion`

| | |
| --- | --- |
| **Requirements** | FR-TA-01, 08 · BR-TARIFF-003, 005 |
| **Actor** | Ops admin, super admin only [10-admin.md](10-admin.md) |

**Input:** `tariffVersionId`, `actor`, `confirmEffectiveFrom` (explicit confirm).

**Flow:**

1. Authorize elevated role
2. Precondition: status `reviewed`, items valid, dates valid
3. **Transaction:**
   - Find currently published version effective on date → set `archived`
   - Set target status `published`, `published_at`, `published_by`
4. Activity log + `TariffVersionPublished` event
5. Invalidate tariff/catalogue cache (step 83)

**Called from:** Filament publish modal [design/admin/70-tariff-publish.html](../design/admin/70-tariff-publish.html).

---

## 7. Contact

### `SubmitContactMessage`

| | |
| --- | --- |
| **Requirements** | FR-CT-01, 02, 05 · FR-NT-01 |
| **Actor** | Public |

**Input** (`SubmitContactMessageData`):

| Field | Required |
| --- | --- |
| `intent` | yes |
| `name`, `phone`, `email`, `subject`, `message` | yes |
| `centreId` | no |
| `locale` | yes |
| `honeypot` | must be empty |

**Flow:**

1. Honeypot + rate limit [FR-CT-05](04-requirements.md)
2. Validate intent enum, email format, phone normalize
3. Insert `contact_messages` status `new`
4. `ContactMessageReceived` event → notification port [FR-CT-04](04-requirements.md)
5. Return success ack (no internal id)

**Called from:** Livewire contact form.

---

### `TransitionContactStatus`

**Input:** `contactMessageId`, `toStatus` (`in_progress`, `resolved`), `actor`.

**Flow:** authorize → update status → set `resolved_at` on first `resolved` → activity log.

**Called from:** Filament inbox actions.

---

### `AddContactInternalNote`

Mirror appointment notes pattern for contact threads.

---

## 8. Authorization matrix (summary)

| Use case | Super | Ops | Centre Mgr | Reception | Content |
| --- | :---: | :---: | :---: | :---: | :---: |
| `CreateAppointmentRequest` | — | — | — | — | — (public) |
| `TrackAppointment` | — | — | — | — | — (public) |
| `SubmitContactMessage` | — | — | — | — | — (public) |
| `TransitionAppointmentStatus` | ✓ | ✓ | scoped | scoped | — |
| `AddAppointmentInternalNote` | ✓ | ✓ | scoped | scoped | — |
| `ChangeCentreWeeklyHours` | ✓ | ✓ | scoped | — | — |
| `CreateScheduleException` | ✓ | ✓ | scoped | — | — |
| `PublishTariffVersion` | ✓ | ✓ | — | — | — |
| `MarkTariffVersionReviewed` | ✓ | ✓ | — | — | — |
| `TransitionContactStatus` | ✓ | ✓ | scoped | ✓ | — |

Policies in `app/Policies/` — step 84 security design.

---

## 9. Events & notifications (preview)

| Use case | Event | Notification (V1) |
| --- | --- | --- |
| `CreateAppointmentRequest` | `AppointmentRequested` | Email to ops [FR-AP-11](04-requirements.md) |
| `TransitionAppointmentStatus` | `AppointmentStatusChanged` | Optional future |
| `PublishTariffVersion` | `TariffVersionPublished` | — |
| `ChangeCentreWeeklyHours` / exceptions | `CentreScheduleChanged` | — |
| `SubmitContactMessage` | `ContactMessageReceived` | Email [FR-CT-04](04-requirements.md) |

Full event payloads: step **82**.

---

## 10. Filament ↔ use case mapping

| Filament resource / action | Use case |
| --- | --- |
| Appointment → Confirm | `TransitionAppointmentStatus` → `confirmed` |
| Appointment → Cancel | `TransitionAppointmentStatus` → `cancelled` |
| Appointment → Complete | `TransitionAppointmentStatus` → `completed` |
| Appointment → Request modification | `TransitionAppointmentStatus` → `modification_requested` |
| Appointment → Reschedule | `UpdateAppointmentPreferredTime` |
| Appointment → Save note | `AddAppointmentInternalNote` |
| Tariff → Publish button | `PublishTariffVersion` |
| Tariff → Mark reviewed | `MarkTariffVersionReviewed` |
| Centre → Save hours | `ChangeCentreWeeklyHours` |
| Exception → Create | `CreateScheduleException` |
| Contact → Resolve | `TransitionContactStatus` → `resolved` |
| Dashboard widgets | `ResolveAllCentresAvailability`, Eloquent counts (read-only queries) |

**Rule:** Filament `Action::make()` handlers delegate in one line to `$useCase($data)` — no duplicate transition logic.

---

## 11. Testing strategy (step 98+)

| Use case | Pest focus |
| --- | --- |
| `CreateAppointmentRequest` | [07-acceptance.md](07-acceptance.md) § Appointments · idempotency · hours rejection |
| `TrackAppointment` | § Tracking · generic failure · no notes leak |
| `PublishTariffVersion` | § Tariffs · archive prior · transactional |
| `ResolveCentreAvailability` | § Live status scenarios |
| `TransitionAppointmentStatus` | Illegal transition rejected · history row count |

Test class naming: `tests/Feature/Actions/Appointment/CreateAppointmentRequestTest.php`.

---

## 12. Acceptance (step 78)

- [x] Nineteen use cases catalogued with inputs, flows, outputs
- [x] Public vs admin actors identified
- [x] Request, track, tariff, schedule, contact coverage per PLAN step 78
- [x] Filament mapping documented — shared with Livewire
- [x] FR/BR requirements traced
- [x] Query vs command distinction clear
- [x] Cross-references to steps 79–82 for engines and events

**Next:** Step **79** — Availability engine specification.
