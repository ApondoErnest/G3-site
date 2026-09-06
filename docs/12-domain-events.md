# 12 — Domain events

| Delivery | Phase V · step **82** |
| --- | --- |
| Prerequisites | [12-use-cases.md](12-use-cases.md) · [09-architecture.md](09-architecture.md) |
| Implementation | `app/Events/` · `app/Listeners/` · step 106 |

Domain events decouple write operations from notifications, cache invalidation, and async work ([09-architecture.md](09-architecture.md)). Dispatched **after** DB commit.

---

## 1. Principles

| Rule | Detail |
| --- | --- |
| Sync dispatch | Events fire in same request after `DB::commit()` |
| Async work | Listeners implement `ShouldQueue` for mail, image derivatives |
| No SMTP in UI | Listeners call `NotificationPort` [FR-NT-02](04-requirements.md) |
| Payload | IDs + codes only — not full Eloquent graphs |
| Naming | Past tense: `AppointmentRequested` |

Use Laravel events (`event()`) or explicit `EventDispatcher` injection in use cases.

---

## 2. Event catalogue

| Event | Trigger use case | Payload | Listeners |
| --- | --- | --- | --- |
| `AppointmentRequested` | `CreateAppointmentRequest` | `appointmentId`, `publicReference`, `centreId`, `locale` | `SendAppointmentNotification`, `LogAppointmentCreated` |
| `AppointmentStatusChanged` | `TransitionAppointmentStatus` | `appointmentId`, `from`, `to`, `actorUserId?` | Activity log (if not inline), future customer email |
| `ContactMessageReceived` | `SubmitContactMessage` | `contactMessageId`, `intent`, `locale` | `SendContactNotification` |
| `TariffVersionPublished` | `PublishTariffVersion` | `tariffVersionId`, `label`, `effectiveFrom`, `publishedBy` | `InvalidateTariffCache`, activity log |
| `CentreScheduleChanged` | Hours/exception use cases | `centreId?`, `changeType`, `actorUserId` | `InvalidateScheduleCache` |
| `MediaUploaded` | Filament media upload | `mediaId`, `collection`, `modelType`, `modelId` | Queue conversions [FR-MD-02](04-requirements.md) |

---

## 3. Event definitions

### `AppointmentRequested`

```php
final readonly class AppointmentRequested
{
    public function __construct(
        public int $appointmentId,
        public string $publicReference,
        public int $centreId,
        public string $locale,
    ) {}
}
```

### `AppointmentStatusChanged`

```php
final readonly class AppointmentStatusChanged
{
    public function __construct(
        public int $appointmentId,
        public AppointmentStatus $from,
        public AppointmentStatus $to,
        public ?int $actorUserId,
    ) {}
}
```

### `ContactMessageReceived`

```php
final readonly class ContactMessageReceived
{
    public function __construct(
        public int $contactMessageId,
        public ContactIntent $intent,
        public string $locale,
    ) {}
}
```

### `TariffVersionPublished`

```php
final readonly class TariffVersionPublished
{
    public function __construct(
        public int $tariffVersionId,
        public string $label,
        public string $effectiveFrom, // date string
        public int $publishedByUserId,
    ) {}
}
```

### `CentreScheduleChanged`

```php
final readonly class CentreScheduleChanged
{
    public function __construct(
        public ?int $centreId, // null = global exception
        public string $changeType, // weekly_hours | exception_created | exception_deleted
        public int $actorUserId,
    ) {}
}
```

### `MediaUploaded`

```php
final readonly class MediaUploaded
{
    public function __construct(
        public int $mediaId,
        public string $collection,
        public string $modelType,
        public int $modelId,
    ) {}
}
```

---

## 4. Listeners

| Listener | Queue? | Action |
| --- | :---: | --- |
| `SendAppointmentNotification` | yes | `NotificationPort::appointmentReceived()` [FR-AP-11](04-requirements.md) |
| `SendContactNotification` | yes | `NotificationPort::contactReceived()` [FR-CT-04](04-requirements.md) |
| `InvalidateTariffCache` | no | Forget `tariff:*` keys [step 83](12-cache-policy.md) |
| `InvalidateScheduleCache` | no | Forget `schedule:centre:{id}` |
| `GenerateMediaConversions` | yes | Spatie queue conversions |
| `LogAppointmentCreated` | no | Optional debug log — not audit (audit via activity log on transitions) |

**Failed jobs:** mail failure must **not** roll back stored request [NFR-R-02](05-quality.md).

---

## 5. Notification port

```php
interface NotificationPort
{
    public function appointmentReceived(AppointmentRequested $event): void;

    public function contactReceived(ContactMessageReceived $event): void;
}
```

V1 implementation: `MailNotificationAdapter` (queued Mailable). Livewire/Filament **never** implement this interface directly.

---

## 6. Registration

`app/Providers/EventServiceProvider.php` or `AppServiceProvider`:

```php
Event::listen(AppointmentRequested::class, SendAppointmentNotification::class);
// ...
```

Discover listeners via attributes (`#[ListensTo(...)]`) if preferred in Laravel 13.

---

## 7. Testing

| Test | Assert |
| --- | --- |
| Create appointment dispatches `AppointmentRequested` after commit | Event fake |
| Publish tariff dispatches + cache invalidated | Integration |
| Mail listener queued, appointment still persisted if mail fails | NFR-R-02 |

---

## 8. Acceptance (step 82)

- [x] Six domain events defined with payloads
- [x] Listener mapping and queue policy
- [x] NotificationPort boundary
- [x] Post-commit dispatch rule
- [x] Failed mail does not rollback

**Next:** Step **83** — Cache policy.
