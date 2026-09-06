# 12 — Domain model

| Delivery | Phase V · step **77** |
| --- | --- |
| Prerequisites | Gate 76 · [11-erd.md](11-erd.md) · [06-rules.md](06-rules.md) · [09-architecture.md](09-architecture.md) |
| Next | Step 78 — [12-use-cases.md](12-use-cases.md) · Steps 79–81 engines |
| Implementation | Phase VI · steps 98–107 |

PHP domain layer design for G3 Control V1: **modules**, **Eloquent entities**, **backed enums**, **value objects**, and **invariant mapping** to business rules. No application use cases yet (step 78) — no migrations (Gate 85).

---

## 1. Layering

```text
Livewire / Filament
  → Form requests & policies
  → Application use cases (step 78)
  → Domain services (availability, tariffs, transitions — steps 79–81)
  → Eloquent models + enums + value objects  ← this document
  → MySQL
```

| Layer | Location (planned) | Rule |
| --- | --- | --- |
| Enums & value objects | `app/Domain/Enums/`, `app/Domain/ValueObjects/` | Pure PHP; no HTTP |
| Eloquent models | `app/Models/{Module}/` | 1:1 with tables · [NFR-M-05](05-quality.md) |
| Settings | `app/Settings/` | Spatie Laravel Settings |
| Time | `app/Support/Clock.php` | UTC storage · Douala display · exists |
| Use cases | `app/Actions/` or `app/UseCases/` | Step 78 — Filament **must** call these |

**Rejected:** repository-per-model · anemic DTO-only domain with no Eloquent.

---

## 2. Module map

Nine modules from [09-architecture.md](09-architecture.md):

```text
app/Models/
  Company/          (settings holder — optional empty; Spatie primary)
  Centre/           Centre, CentrePhone, CentreWeeklyHours, ScheduleException, OperationalAlert, Equipment
  Catalogue/        VehicleCategory, Service, RequiredDocument
  Tariff/           TariffVersion, TariffItem
  Appointment/      AppointmentRequest, AppointmentStatusHistory, AppointmentInternalNote
  Contact/          ContactMessage, ContactInternalNote
  Content/          ContentBlock, FaqEntry, TeamMember, RoadSafetySection, PageSeo
  Identity/         User (existing), AdminUserScope
  (Media)           Spatie Media on models — no separate entity table beyond package
  (Audit)           Spatie Activity Log — no custom model unless extended
```

---

## 3. Backed enums (`app/Domain/Enums/`)

All enums are **`string`-backed** (`enum X: string`) matching DB `varchar` columns. Labels come from PHP lang files — **never** from enum case names in UI.

### 3.1 Enum catalogue

| Enum | Cases | DB column(s) | Lang file (planned) |
| --- | --- | --- | --- |
| `Locale` | `Fr`, `En` → `fr`, `en` | `locale` on appointments, contacts | — (framework) |
| `CentreStatus` | `Active`, `Inactive` | `centres.status` | `lang/*/centres.php` |
| `CentreCode` | `EcoleDePolice`, `Nomayos` | `centres.code` | codes fixed · not translated |
| `Weekday` | `Monday`…`Sunday` → `1`…`7` | `centre_weekly_hours.weekday` | `lang/*/dates.php` |
| `TariffVersionStatus` | `Draft`, `Reviewed`, `Published`, `Archived` | `tariff_versions.status` | `lang/*/tariffs.php` |
| `AppointmentStatus` | `Received`, `UnderReview`, `Confirmed`, `ModificationRequested`, `Completed`, `Cancelled` | `appointment_requests.status`, histories | `lang/*/appointments.php` |
| `PreferredPeriod` | `Morning`, `Afternoon`, `Any` | `appointment_requests.preferred_period` | `lang/*/appointments.php` |
| `PreferredChannel` | `Phone`, `Email`, `Whatsapp` | `appointment_requests.preferred_channel` | `lang/*/appointments.php` |
| `ContactIntent` | `Appointment`, `Centre`, `Tariffs`, `Assistance` | `contact_messages.intent` | `lang/*/contact.php` |
| `ContactStatus` | `New`, `InProgress`, `Resolved` | `contact_messages.status` | `lang/*/contact.php` |
| `AlertSeverity` | `Info`, `Warning`, `Critical` | `operational_alerts.severity` | `lang/*/alerts.php` |
| `HistoryActorType` | `System`, `User` | `appointment_status_histories.actor_type` | admin internal |
| `LiveCentreState` | `OpenNormalHours`, `Closed` | *computed* — not stored | `lang/*/centres.php` |
| `AdminRole` | `SuperAdmin`, `OperationsAdmin`, `CentreManager`, `ReceptionOfficer`, `ContentEditor` | Spatie `roles.name` | `lang/*/admin.php` |
| `ContentPage` | `Home`, `About`, `Centres`, … | `content_blocks.page`, `page_seo.page` | matches `config/locale.php` keys |
| `MediaCollection` | `Brand`, `Centres`, `Equipment`, `Team`, `Inspection`, `RoadSafety` | Spatie `collection_name` | admin filters |

### 3.2 Enum definitions (reference)

```php
// app/Domain/Enums/AppointmentStatus.php
enum AppointmentStatus: string
{
    case Received = 'received';
    case UnderReview = 'under_review';
    case Confirmed = 'confirmed';
    case ModificationRequested = 'modification_requested';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function isFinal(): bool
    {
        return match ($this) {
            self::Completed, self::Cancelled => true,
            default => false,
        };
    }

    /** Customer-safe for public tracking timeline */
    public function isPublicTimelineVisible(): bool
    {
        return true; // all statuses shown; notes filtered separately
    }
}
```

```php
// app/Domain/Enums/CentreCode.php
enum CentreCode: string
{
    case EcoleDePolice = 'ecole-de-police';
    case Nomayos = 'nomayos';
}
```

```php
// app/Domain/Enums/AdminRole.php — matches Spatie role names
enum AdminRole: string
{
    case SuperAdmin = 'super_admin';
    case OperationsAdmin = 'operations_admin';
    case CentreManager = 'centre_manager';
    case ReceptionOfficer = 'reception_officer';
    case ContentEditor = 'content_editor';

    public function requiresCentreScope(): bool
    {
        return match ($this) {
            self::CentreManager, self::ReceptionOfficer => true,
            default => false,
        };
    }
}
```

### 3.3 Lang file label pattern

```php
// lang/fr/appointments.php
'status' => [
    'received' => 'Demande reçue',
    'under_review' => 'En traitement',
    // ...
],
```

Filament selects and public UI: `__('appointments.status.'.$model->status->value)`.

---

## 4. Value objects (`app/Domain/ValueObjects/`)

Immutable types enforcing format rules at construction.

| Class | Responsibility | Rules |
| --- | --- | --- |
| `PublicReference` | `G3-YY-XXXXX` generation & validation | [BR-APPT-004](06-rules.md) · not equal to DB id |
| `PhoneNumber` | E.164 parse, store, national display | [BR-CENT-004](06-rules.md) |
| `RegistrationPlate` | Normalize (upper, strip spaces) + display | [FR-AP-12](04-requirements.md) tracking search |
| `MoneyXaf` | Positive integer amount, grouped FCFA format | [BR-TARIFF-002](06-rules.md) |
| `GeoPoint` | Valid lat/lng pair for centres | baseline GPS |
| `TimeWindow` | Half-open `[opens, closes)` interval | [BR-TIME-002](06-rules.md) |
| `TranslatableCopy` | `{fr, en}` with completeness check | [BR-LANG-001](06-rules.md) |

### 4.1 Examples

```php
final readonly class PublicReference
{
    private function __construct(public string $value) {}

    public static function generate(): self { /* G3-{yy}-{5 alnum} */ }

    public static function fromString(string $value): self { /* validate format */ }
}

final readonly class PhoneNumber
{
    public function __construct(public string $e164) {}

    public static function fromInput(string $input, string $defaultCountry = 'CM'): self;

    public function displayNational(): string;
}

final readonly class MoneyXaf
{
    public function __construct(public int $amount) {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be positive');
        }
    }

    public function formatted(): string; // "25 000 FCFA"
}
```

Models may cast to/from VOs via custom Eloquent casts (`CastsAttributes`).

---

## 5. Entities

### 5.1 Company

**`CompanySettings`** — Spatie settings class, not Eloquent.

| Property | Type | Invariant |
| --- | --- | --- |
| `legalName` | `?string` | Nullable Q-08 |
| `displayName` | `string` | Default G3 Control |
| `slogan` | `array{fr,en}` | [01-baseline.md](01-baseline.md) |
| `agrementNumber` | `string` | `0291` · company-wide [BR-COMP-001](06-rules.md) |
| `agrementYear` | `int` | |
| `email`, `postalAddress` | `string` | |
| `defaultSeoTitle`, `defaultSeoDescription` | `array{fr,en}` | |
| `socialLinks` | `array` | Optional |

Media: logo/favicon via Spatie on dedicated settings media model or `GeneralSettings` holder.

---

### 5.2 Centre module

#### `Centre` → `centres`

| Attribute | Cast / type | Notes |
| --- | --- | --- |
| `code` | `CentreCode` | UK |
| `name`, `address`, `landmark` | `array` JSON | CMS bilingual |
| `latitude`, `longitude` | `decimal:7` | |
| `status` | `CentreStatus` | [BR-CENT-005](06-rules.md) |
| `holiday_default_open` | `bool` | V1 default true |
| `seo_title`, `seo_description` | `array` nullable | |

**Relationships:** `phones`, `weeklyHours`, `services` (belongsToMany), `equipment` (belongsToMany), `exceptions` (hasMany scoped), `appointmentRequests`, `tariffItems` (belongsToMany via pivot).

**Scopes:** `scopeActive()` → `status = active`.

**Domain methods (design):**
- `isBookable(): bool` — active only
- `translatedName(Locale $locale): string`

#### `CentrePhone` → `centre_phones`

| Attribute | Cast |
| --- | --- |
| `e164` | string (VO on set) |
| `is_whatsapp` | bool |

#### `CentreWeeklyHours` → `centre_weekly_hours`

| Attribute | Cast |
| --- | --- |
| `weekday` | `Weekday` or int 1–7 |
| `is_open` | bool |
| `opens_at`, `closes_at` | `datetime:H:i` or custom TimeWindow |

#### `ScheduleException` → `schedule_exceptions`

| Attribute | Cast |
| --- | --- |
| `applies_to_all_centres` | bool |
| `centre_id` | nullable FK |
| `starts_on`, `ends_on` | date |
| `is_open` | bool |
| `reason` | array nullable |

**Invariant:** scope CHECK per [11-indexes-constraints.md](11-indexes-constraints.md).

#### `OperationalAlert` → `operational_alerts`

| Attribute | Cast |
| --- | --- |
| `severity` | `AlertSeverity` |
| `message` | array |
| `starts_at`, `expires_at` | datetime |

**Scope:** `scopeActiveNow()` — for public resolver.

#### `Equipment` → `equipment` + `centre_equipment` pivot

Bilingual `label` JSON; belongsToMany centres.

---

### 5.3 Catalogue module

#### `VehicleCategory` → `vehicle_categories`

| Attribute | Cast |
| --- | --- |
| `code` | string (official code Q-06) |
| `label`, `examples`, `description` | array |
| `is_published` | bool |

**Scope:** `scopePublished()`.

#### `Service` → `services`

| Attribute | Cast |
| --- | --- |
| `code` | string UK |
| `title`, `summary`, `body` | array |
| `icon` | string nullable |
| `is_published` | bool |

**Relationships:** `centres`, `vehicleCategories`, `requiredDocuments`, `tariffItems`, `appointmentRequests`.

**Domain method:** `isAvailableAt(Centre $centre, VehicleCategory $category): bool` — [BR-SVC-002](06-rules.md).

#### `RequiredDocument` → `required_documents`

Attached to category and/or service; at least one FK required.

---

### 5.4 Tariff module

#### `TariffVersion` → `tariff_versions`

| Attribute | Cast |
| --- | --- |
| `status` | `TariffVersionStatus` |
| `effective_from`, `effective_until` | date |
| `reviewed_at`, `published_at` | datetime |
| `reviewed_by`, `published_by` | User FK nullable |

**Relationships:** `items` hasMany `TariffItem`.

**Domain methods:**
- `canPublish(): bool` — status reviewed, has items, dates valid
- `isEffectiveOn(CarbonImmutable $date): bool`
- `archive(): void` — sets status archived (use case wraps transaction)

#### `TariffItem` → `tariff_items` + `tariff_item_centre`

| Attribute | Cast |
| --- | --- |
| `amount_xaf` | int · `MoneyXaf` on read |
| `validity_notes` | array nullable |
| `service_id` | nullable |

**Relationships:** `centres` belongsToMany, `vehicleCategory`, `service`, `version`.

---

### 5.5 Appointment module

#### `AppointmentRequest` → `appointment_requests`

| Attribute | Cast |
| --- | --- |
| `public_reference` | string · `PublicReference` VO |
| `status` | `AppointmentStatus` |
| `preferred_period` | `PreferredPeriod` |
| `preferred_channel` | `PreferredChannel` nullable |
| `locale` | `Locale` |
| `registration_normalized` | string |
| `contact_phone_e164` | string |
| `finalized_at` | datetime nullable |
| `idempotency_key` | string nullable |

**Relationships:** `centre`, `service`, `vehicleCategory`, `statusHistories`, `internalNotes`.

**Domain methods:**
- `isTrackableWith(string $phoneOrPlate): bool` — [BR-TRACK-001](06-rules.md) (used by use case)
- `recordTransition(AppointmentStatus $to, HistoryActorType $actor, ?User $user): AppointmentStatusHistory`

**Never expose:** `id`, internal notes on public API.

#### `AppointmentStatusHistory` → `appointment_status_histories`

Append-only; no `updated_at`. Cast `status`, `actor_type`, `public_note` array.

#### `AppointmentInternalNote` → `appointment_internal_notes`

Staff-only; never queried by tracking use case.

---

### 5.6 Contact module

#### `ContactMessage` → `contact_messages`

| Attribute | Cast |
| --- | --- |
| `intent` | `ContactIntent` |
| `status` | `ContactStatus` |
| `locale` | `Locale` |
| `resolved_at` | datetime nullable |

**Relationships:** `centre` optional, `internalNotes`.

#### `ContactInternalNote` → `contact_internal_notes`

Mirror appointment notes pattern.

---

### 5.7 Content module

#### `ContentBlock` → `content_blocks`

| Attribute | Cast |
| --- | --- |
| `key` | string UK e.g. `home.hero` |
| `page` | `ContentPage` |
| `content` | array nested `{fr:{fields}, en:{fields}}` |
| `locale_status` | array `{fr: bool, en: bool}` |
| `is_published` | bool |

**Domain method:** `isReadyToPublish(): bool` — both locales complete [BR-LANG-001](06-rules.md).

#### `FaqEntry`, `TeamMember`, `RoadSafetySection`, `PageSeo`

Standard published scopes; team `display_publicly`; road safety ordered by `sort_order` + `anchor` UK.

---

### 5.8 Identity module

#### `User` → `users` (extend existing)

| New attribute | Cast |
| --- | --- |
| `is_active` | bool |
| `mfa_secret` | encrypted string nullable |
| `mfa_confirmed_at` | datetime nullable |

**Traits:** `HasRoles` (Spatie), FilamentUser (exists).

**Relationships:** `centreScopes` hasMany `AdminUserScope`.

**Methods:** `hasCentreScope(Centre $centre): bool`, `canAccessCentre(Centre $centre): bool`.

#### `AdminUserScope` → `admin_user_scopes`

Links user to centre for scoped roles.

---

## 6. Computed domain types (not persisted)

| Type | Producer | Consumer |
| --- | --- | --- |
| `LiveCentreState` | Availability engine (step 79) | Public pages, dashboard |
| `CentreAvailabilitySnapshot` | Availability engine | open now, next open/close [FR-CE-07](04-requirements.md) |
| `EffectiveTariffVersion` | Tariff resolver (step 81) | Tarifs page, finder [FR-TA-04](04-requirements.md) |
| `TrackingResult` | Track use case | Suivi tab — timeline DTO, no internal fields |

---

## 7. Eloquent casts summary

| Pattern | Implementation |
| --- | --- |
| Enum columns | `'status' => AppointmentStatus::class` |
| JSON bilingual | `'title' => 'array'` + helper `translate(Locale)` |
| Encrypted | `'mfa_secret' => 'encrypted'` |
| Dates UTC | `'created_at' => 'datetime'` app timezone UTC |
| VO optional | Custom cast `PublicReferenceCast`, `PhoneNumberCast` |

---

## 8. Global scopes & query conventions

| Scope | Models | Rule |
| --- | --- | --- |
| `PublishedScope` | Service, VehicleCategory, FaqEntry, ContentBlock, … | Public queries only |
| `ActiveScope` | Centre, OperationalAlert | Active / active window |
| Centre policy scope | AppointmentRequest, ContactMessage | Filament list for scoped roles |

Public site **always** applies `PublishedScope` + `ActiveScope` in use cases — not in Blade.

---

## 9. Business rule → domain mapping

| Rule | Domain enforcement |
| --- | --- |
| BR-APPT-003 | `AppointmentStatus` transitions in state machine (step 80) |
| BR-APPT-004 | `PublicReference` VO; column UK |
| BR-APPT-006 | Separate `AppointmentInternalNote` model |
| BR-TARIFF-003 | `TariffVersionStatus::Archived`; no delete policy |
| BR-TARIFF-005 | Status enum order; publish in use case |
| BR-SVC-002 | `Service::isAvailableAt()` |
| BR-LANG-001 | `ContentBlock::isReadyToPublish()` |
| BR-CENT-005 | `Centre::isBookable()` |
| BR-TIME-001 | `Clock::displayTimezone()` |
| BR-TIME-002 | `TimeWindow::contains()` half-open |
| BR-TRACK-003 | No domain type leaks existence — use case returns generic failure |

---

## 10. Fixed identifiers registry

### 10.1 Centre codes (V1)

| Code | Enum case |
| --- | --- |
| `ecole-de-police` | `CentreCode::EcoleDePolice` |
| `nomayos` | `CentreCode::Nomayos` |

### 10.2 Content block keys (seed structure)

| Key | Page | Block type |
| --- | --- | --- |
| `home.hero` | home | hero |
| `home.live_strip` | home | strip |
| `about.mission` | about | prose |
| `about.agrement` | about | callout |
| `technical_inspection.intro` | technical_inspection | prose |
| `technical_inspection.video` | technical_inspection | video ref |

Full list finalized at content seed step 144; schema supports any `key` UK.

### 10.3 Road safety anchors (FR-CN-03)

`braking`, `tyres`, `lighting`, `visibility`, `equipment`, `dashboard_warnings`, `rain`, `pre_journey`, `inspection_link` — seeded as `road_safety_sections.anchor`.

---

## 11. Package integration

| Package | Domain touchpoint |
| --- | --- |
| Spatie Settings | `CompanySettings` |
| Spatie Translatable | Optional alternative to JSON arrays — **decision:** JSON columns + Filament tabs per [FR-LO-05](04-requirements.md) |
| Spatie Media | `HasMedia` on Centre, TeamMember, Equipment, ContentBlock holder |
| Spatie Permission | `AdminRole` enum ↔ role names |
| Spatie Activity Log | `LogsActivity` trait on TariffVersion, Centre, AppointmentRequest transitions |

---

## 12. Testing notes (step 98+)

Pest unit tests per enum:
- All cases round-trip DB value
- `AppointmentStatus::isFinal()` 
- `AdminRole::requiresCentreScope()`
- VO rejection of invalid phone/plate/reference
- `MoneyXaf` rejects zero/negative

Feature tests belong with use cases (step 78+).

---

## 13. Acceptance (step 77)

- [x] Nine modules mapped to model namespaces
- [x] All code columns from [11-erd.md](11-erd.md) have backed enums
- [x] Value objects for reference, phone, plate, money, time window
- [x] Entity attributes, casts, relationships documented
- [x] Computed types distinguished from persisted entities
- [x] Business rules mapped to domain enforcement points
- [x] Lang file label pattern documented
- [x] Aligns with [06-rules.md](06-rules.md) and [09-architecture.md](09-architecture.md)

**Next:** Phase VI implementation · Gate **85** ✓ — [12-gate-domain-design.md](12-gate-domain-design.md)
