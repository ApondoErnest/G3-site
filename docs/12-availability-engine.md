# 12 — Availability engine

| Delivery | Phase V · step **79** |
| --- | --- |
| Prerequisites | [12-use-cases.md](12-use-cases.md) · [12-domain-model.md](12-domain-model.md) |
| Implementation | `app/Domain/Schedule/AvailabilityEngine.php` · step 99 |

Single engine for **“open now”**, **next open/close**, and **appointment date validation** ([FR-CE-07](04-requirements.md), [FR-CE-08](04-requirements.md)). All surfaces call `ResolveCentreAvailability` — never duplicate logic in Blade, Livewire, or Filament.

---

## 1. Responsibilities

| Question | Method |
| --- | --- |
| Is the centre open **right now**? | `isOpenNow(centre, at?)` |
| When does it close next (if open)? | `nextCloseAt(centre, at?)` |
| When does it open next (if closed)? | `nextOpenAt(centre, at?)` |
| Can a customer request this **date + period**? | `isBookableOnDate(centre, date, period)` |
| Full snapshot for UI | `snapshot(centre, at?)` → `CentreAvailabilitySnapshot` |

Wall-clock: **Africa/Douala** ([BR-TIME-001](06-rules.md)). Storage remains UTC ([BR-TIME-003](06-rules.md)).

---

## 2. Algorithm priority

For a given centre and **calendar date** `D` in Douala:

```text
1. Inactive centre (status != active)     → CLOSED · not bookable [BR-CENT-005]
2. Applicable schedule exception(s)       → exception wins [BR-CENT-002]
3. Public holiday policy                  → if holiday & default open → weekday hours for D [BR-CENT-003]
4. Weekly hours for weekday(D)            → baseline [BR-CENT-001]
5. Derive open windows (may be empty)
```

### 2.1 Exception resolution

Load exceptions where `starts_on <= D <= ends_on` (treat `ends_on` NULL as single day).

| Scope | Applies when |
| --- | --- |
| `applies_to_all_centres = true` | Every centre |
| `centre_id = X` | That centre only |

**Precedence:** if both global and centre-specific match, **centre-specific wins** over global for that centre.

If multiple exceptions same scope overlap, **most recently created** wins (document in code; rare in V1).

| Exception `is_open` | Result |
| --- | --- |
| `false` | Closed all day on D |
| `true` | Custom `[opens_at, closes_at)` on D |

### 2.2 Holiday policy (V1)

No public holiday calendar table in V1. **`holiday_default_open = true`** on both centres ([01-baseline.md](01-baseline.md)).

When a holiday calendar is added later (backlog), rule remains: if holiday + default open → use **weekday hours for the calendar weekday of D**, not Sunday hours ([07-acceptance.md](07-acceptance.md)).

Until calendar exists: step 3 is a no-op; weekly hours apply.

### 2.3 Weekly hours

Lookup `centre_weekly_hours` for `weekday(D)` ISO 1=Mon … 7=Sun.

If `is_open = false` → closed all day.

---

## 3. Half-open intervals

**Open at** `opens_at`, **closed at** `closes_at` means interval **[opens_at, closes_at)** ([BR-TIME-002](06-rules.md)).

| Instant | Open? |
| --- | --- |
| 07:00 | yes (if opens 07:00) |
| 19:59 | yes (if closes 20:00) |
| 20:00 | **no** |

Implement via `TimeWindow::contains(CarbonImmutable $instant)`.

---

## 4. Preferred period validation

For `CreateAppointmentRequest` / `UpdateAppointmentPreferredTime` ([BR-APPT-002](06-rules.md)).

Customer picks **date** `D` + **period** (not a numbered slot).

| Period | Intersection test on date D |
| --- | --- |
| `morning` | Centre open window ∩ **[07:00, 12:00)** is non-empty |
| `afternoon` | Centre open window ∩ **[12:00, closes_at)** is non-empty |
| `any` | Centre has any open window on D |

Examples (acceptance [07-acceptance.md](07-acceptance.md)):

| Scenario | Result |
| --- | --- |
| École Wed **19:30** + `afternoon` | ✓ (open until 20:00) |
| École Wed **20:00** | ✗ (half-open closed) |
| Nomayos Wed after 19:00 | ✗ (closes 19:00) |
| Sunday after 15:00 | ✗ (Sunday closes 15:00) |
| Global closure exception on D | ✗ not bookable |

---

## 5. “Open now” snapshot

At instant `t` (Douala):

1. Resolve today's open windows (algorithm §2).
2. `isOpenNow` = any window contains `t`.
3. `nextCloseAt` = end of the current window if open, else null.
4. `nextOpenAt` = start of next window (today later or future days, search up to 14 days ahead).

`LiveCentreState`:

| Value | Condition |
| --- | --- |
| `open_normal_hours` | `isOpenNow` true |
| `closed` | otherwise |

Exception closure may attach `reasonKey` from exception JSON for admin; public UI uses generic closed copy unless ops publishes alert.

---

## 6. Class design

```text
app/Domain/Schedule/
  AvailabilityEngine.php          # pure logic; injects repository or Eloquent queries
  CentreAvailabilitySnapshot.php  # readonly DTO
  TimeWindow.php                  # value object
  ScheduleRepository.php          # optional thin loader — not repository-per-model pattern;
                                  # may inline queries in engine for V1
```

```php
final class AvailabilityEngine
{
    public function snapshot(Centre $centre, ?CarbonImmutable $at = null): CentreAvailabilitySnapshot;

    public function isBookableOnDate(
        Centre $centre,
        CarbonImmutable $date,
        PreferredPeriod $period,
    ): bool;
}
```

**Clock injection:** use `App\Support\Clock::nowDisplay()` for testability.

---

## 7. Caching

Weekly hours + exceptions for a centre cached under key `schedule:centre:{id}` ([12-cache-policy.md](12-cache-policy.md)).

Invalidate on `ChangeCentreWeeklyHours`, exception CRUD, `CentreScheduleChanged` event.

**Never cache** per-request “open now” across users for long TTL — short TTL (60s) acceptable for homepage strip only (step 83).

---

## 8. Test matrix (Pest · step 99)

All tests freeze `Clock` to Douala instant. Cite rules in test names per [07-acceptance.md](07-acceptance.md) § Live status.

| Test | Rule |
| --- | --- |
| Monday 10:00 both open with correct next close | FR-CE-07 |
| Monday 06:59 closed, 07:00 open | BR-TIME-002 |
| Sunday 15:00 closed | baseline |
| Exception closed all day | BR-CENT-002 |
| Exception 07:00–15:00 overrides Wednesday | acceptance |
| Global closure both unbookable | acceptance |
| Centre-only exception other centre unchanged | acceptance |
| Same snapshot from two use case entry points | FR-CE-08 |

---

## 9. Acceptance (step 79)

- [x] Single engine; priority exception → holiday → weekly
- [x] Half-open intervals defined
- [x] Preferred period intersection rules
- [x] Inactive centre handling
- [x] DTO + class layout
- [x] Cache invalidation hooks
- [x] Acceptance test matrix aligned with step 07

**Next:** Step **80** — Appointment state machine.
