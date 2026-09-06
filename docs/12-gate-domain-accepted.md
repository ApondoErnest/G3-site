# 107 — Gate: domain accepted

| Delivery | Phase VI · step **107** · Gate |
| --- | --- |
| Prerequisites | Steps 98–106 complete · Gate 97 |
| Opens | Steps **108–116** — Filament administration |
| Verified | 2026-09-06 |

Owner acceptance that G3 Control V1 **domain PHP modules** in the repository implement the approved design (Gate 85) against the migrated schema (Gate 97). **Filament administration (step 108+) may begin.** Public Blade pages remain prohibited until Gate 116.

---

## 1. Verification summary

| Step | Deliverable | Status |
| ---: | --- | --- |
| 98 | Company settings · [`ResolvePublicCompanyProfile`](../app/Actions/Company/ResolvePublicCompanyProfile.php) · [`UpdateCompanySettings`](../app/Actions/Company/UpdateCompanySettings.php) | ✓ |
| 99 | Centres & availability · [`AvailabilityEngine`](../app/Domain/Schedule/AvailabilityEngine.php) · schedule use cases | ✓ |
| 100 | Catalogue resolvers · [`ResolvePublishedServices`](../app/Actions/Catalogue/ResolvePublishedServices.php) · [`ResolveServiceAvailability`](../app/Actions/Catalogue/ResolveServiceAvailability.php) | ✓ |
| 101 | Tariffs · [`TariffResolver`](../app/Domain/Tariff/TariffResolver.php) · publish workflow | ✓ |
| 102 | Appointments · [`AppointmentStateMachine`](../app/Domain/Appointment/AppointmentStateMachine.php) · CRUD commands | ✓ |
| 103 | Tracking · [`TrackAppointment`](../app/Actions/Appointment/TrackAppointment.php) | ✓ |
| 104 | Content · publish/unpublish · public resolvers | ✓ |
| 105 | Contact · [`ContactStateMachine`](../app/Domain/Contact/ContactStateMachine.php) · submit + admin commands | ✓ |
| 106 | Notification port · [`NotificationPort`](../app/Contracts/NotificationPort.php) · [`MailNotificationAdapter`](../app/Infrastructure/Notifications/MailNotificationAdapter.php) | ✓ |

**Test suite:** `composer test` — **198 passed** (571 assertions) on 2026-09-06.

---

## 2. Use-case catalogue vs implementation

All **19** commands and queries from [12-use-cases.md](12-use-cases.md) §2 are implemented as invokable classes under `app/Actions/`:

| # | Use case | Module |
| ---: | --- | --- |
| 1 | `CreateAppointmentRequest` | Appointment |
| 2 | `TransitionAppointmentStatus` | Appointment |
| 3 | `UpdateAppointmentPreferredTime` | Appointment |
| 4 | `AddAppointmentInternalNote` | Appointment |
| 5 | `TrackAppointment` | Appointment |
| 6 | `ResolveCentreAvailability` | Schedule |
| 7 | `ResolveAllCentresAvailability` | Schedule |
| 8 | `ChangeCentreWeeklyHours` | Schedule |
| 9–11 | `CreateScheduleException` / `UpdateScheduleException` / `DeleteScheduleException` | Schedule |
| 12 | `ResolveEffectiveTariff` | Tariff |
| 13–16 | Tariff draft / items / reviewed / publish | Tariff |
| 17 | `SubmitContactMessage` | Contact |
| 18 | `TransitionContactStatus` | Contact |
| 19 | `AddContactInternalNote` | Contact |

**Additional query/command classes** (supporting public pages and admin, same layer):

| Module | Classes |
| --- | --- |
| Company | `ResolvePublicCompanyProfile`, `UpdateCompanySettings` |
| Catalogue | `ResolvePublishedVehicleCategories`, `ResolveRequiredDocuments` |
| Content | `PublishContentBlock`, `UpdateContentBlock`, `UnpublishContentBlock`, seven public resolvers |

Filament resources (steps 108–115) and Livewire pages (steps 117–129) **must call these classes** — no duplicated business logic in UI layers [FR-AD-03](04-requirements.md).

---

## 3. Domain engines and invariants

| Design doc | PHP implementation | Verified in Pest |
| --- | --- | --- |
| [12-availability-engine.md](12-availability-engine.md) | `AvailabilityEngine`, `ResolveCentreAvailability` | ✓ FR-CE-07/08 · BR-TIME-* |
| [12-appointment-state-machine.md](12-appointment-state-machine.md) | `AppointmentStateMachine` | ✓ BR-APPT-003 |
| [12-tariff-engine.md](12-tariff-engine.md) | `TariffResolver`, `PublishTariffVersion` | ✓ BR-TARIFF-* |
| [12-domain-events.md](12-domain-events.md) | `AppointmentRequested`, `ContactMessageReceived`, queued listeners | ✓ FR-AP-11 · FR-CT-04 |
| [12-cache-policy.md](12-cache-policy.md) | `CacheKeys`, remember/forget in resolvers and writes | ✓ catalogue · tariff · content |
| [12-security-design.md](12-security-design.md) | Authorization traits, rate limiters | Partial — full policies at step 108 |

---

## 4. Architecture rules enforced

| Rule | Requirement | Status |
| --- | --- | --- |
| Shared use cases | Filament = Livewire = same `app/Actions/` classes | ✓ layer ready |
| No SMTP in UI | Mail only via `MailNotificationAdapter` [FR-NT-02](04-requirements.md) | ✓ architecture test |
| Notification port | Listeners delegate to `NotificationPort` interface | ✓ |
| Post-commit events | `CreateAppointmentRequest` dispatches after `DB::commit()` | ✓ |
| Tracking never cached | `TrackAppointment` has no `Cache::remember` | ✓ |
| Internal notes separate | Appointment/contact notes ≠ status history | ✓ BR-APPT-006 |
| Failed mail | Does not remove stored request [NFR-R-02](05-quality.md) | ✓ |

---

## 5. Events implemented vs deferred

| Event | Status | Notes |
| --- | --- | --- |
| `AppointmentRequested` | ✓ | Queued `SendAppointmentNotification` |
| `AppointmentStatusChanged` | Dispatched · no listener yet | Optional customer email — future |
| `ContactMessageReceived` | ✓ | Queued `SendContactNotification` |
| `TariffVersionPublished` | Deferred | Cache forget inline in `PublishTariffVersion` |
| `CentreScheduleChanged` | Deferred | Cache forget inline in schedule use cases |
| `MediaUploaded` | Deferred | Step 114 content & media |

---

## 6. Phase VI entry criteria (administration build)

| Gate | Requirement | Status |
| --- | --- | --- |
| 85 | Domain design accepted | ✓ |
| 97 | Schema migrated + tested | ✓ |
| 107 | Domain PHP modules + Pest | ✓ this gate |
| 116 | Public pages | Blocked |
| 129 | Public experience gate | Blocked |

---

## 7. Module index

| Module | Key paths |
| --- | --- |
| Domain | [`app/Domain/`](../app/Domain/) — enums, VOs, engines, state machines |
| Use cases | [`app/Actions/`](../app/Actions/) — commands and queries |
| Events | [`app/Events/`](../app/Events/) · [`app/Listeners/`](../app/Listeners/) |
| Notifications | [`app/Contracts/NotificationPort.php`](../app/Contracts/NotificationPort.php) · [`app/Infrastructure/Notifications/`](../app/Infrastructure/Notifications/) |
| Cache | [`app/Support/CacheKeys.php`](../app/Support/CacheKeys.php) |
| Tests | [`tests/Feature/`](../tests/Feature/) · [`tests/Unit/`](../tests/Unit/) · [`tests/Pest.php`](../tests/Pest.php) |

---

## 8. Outstanding before go-live (not blocking Gate 107)

| Item | Owner / when | Notes |
| --- | --- | --- |
| Spatie role seeds (5 roles) | Dev · step 108 | Auth gate |
| Named admin users + MFA | G3 / Dev · step 108 | [02-charter.md](02-charter.md) |
| Filament resources | Dev · steps 109–115 | Wire use cases |
| Public Blade/Livewire pages | Dev · steps 117–129 | Gate 116 first for admin |
| `operational_alerts` table | Dev · step 110 | Deferred from step 88 |
| Catalogue / tariff seed data | G3 · Q-01 / Q-02 / Q-06 | Tables empty by design |
| Remaining domain events | Dev · as needed | Media upload · optional status emails |

---

## 9. Gate decision

**Accepted.** Domain PHP for G3 Control V1 is implemented, tested, and ready for Filament administration from step 108. Public pages remain blocked until Gate 116.

| | |
| --- | --- |
| Gate | 107 — Domain accepted |
| Date | 2026-09-06 |
| Next active step | **108** — Auth · Five roles, MFA, policies |

---

## 10. Change control

Domain logic changes after this gate require:

1. Update to the relevant `12-*` design doc if behaviour shifts
2. Pest test covering new or changed acceptance criteria
3. Change request per [03-scope.md](03-scope.md) if scope-affecting
