# 12 — Cache policy

| Delivery | Phase V · step **83** |
| --- | --- |
| Prerequisites | [09-architecture.md](09-architecture.md) · engines steps 79–81 |
| Implementation | `app/Support/CacheKeys.php` · step 99–101 |

Caching strategy for G3 Control V1. **Catalogues cacheable; tracking never cached** ([09-architecture.md](09-architecture.md)).

---

## 1. Principles

| Rule | Detail |
| --- | --- |
| Cache reads | Default driver `file` in local; **Redis** in production Docker [NFR-P-07](05-quality.md) |
| Keys | Centralized in `CacheKeys` class — no magic strings |
| Invalidation | Event-driven listeners (step 82) + explicit forget on admin save |
| TTL | Short for live status; long for stable catalogues |
| Stampede | Laravel `Cache::remember` with lock optional for tariff matrix |
| Personal data | Never cache tracking results or appointment PII |

---

## 2. Cacheable domains

| Domain | Key pattern | TTL | Invalidated when |
| --- | --- | --- | --- |
| Centre weekly hours + exceptions | `schedule:centre:{id}` | 24h | `CentreScheduleChanged` |
| Published services list | `catalogue:services:published` | 1h | Service publish/unpublish |
| Published categories | `catalogue:categories:published` | 1h | Category publish |
| Effective tariff version | `tariff:effective:{Y-m-d}` | 1h | `TariffVersionPublished` |
| Tariff matrix lines | `tariff:matrix:{versionId}` | 1h | Tariff publish / item edit (draft) |
| Content blocks (published) | `content:block:{key}` | 1h | Block publish |
| Operational alerts active | `alerts:active` | **60s** | Alert CRUD |
| Live “open now” strip | `availability:all:{Y-m-d-H-i}` | **60s** | Schedule change |
| Public centre cards | `public:centres:v2:{locale}` | 1h | Centre, phone, or weekly-hours save |
| Public fee catalogue | `public:tariff:{locale}:{Y-m-d}` | 1h | Tariff publish or published line edit |
| Header phone | `public:phone:primary` | 1h | Centre or phone save |

---

## 3. Never cache

| Path | Reason |
| --- | --- |
| `TrackAppointment` results | [BR-TRACK-003](06-rules.md) · no dossier · fresh read |
| Tracking rate limit counters | Use RateLimiter, not cache-aside for results |
| Appointment submit responses | Idempotency uses DB unique key |
| Contact form submissions | — |
| Admin appointment detail | Real-time ops |
| User/session/auth | Laravel defaults only |
| Filament tables | Live queries |

---

## 4. `CacheKeys` helper

```php
final class CacheKeys
{
    public static function scheduleCentre(int $centreId): string
    {
        return "schedule:centre:{$centreId}";
    }

    public static function tariffEffective(string $date): string
    {
        return "tariff:effective:{$date}";
    }

    public static function tariffMatrix(int $versionId): string
    {
        return "tariff:matrix:{$versionId}";
    }

    public static function catalogueServices(): string
    {
        return 'catalogue:services:published:v2';
    }

    // ...
}
```

---

## 5. Invalidation listeners

| Event | Keys cleared |
| --- | --- |
| `TariffVersionPublished` | `tariff:effective:*`, `tariff:matrix:{id}` |
| `CentreScheduleChanged` | `schedule:centre:{id}` or all centres if global |
| Service saved (published flag) | `catalogue:services:published:v2` |
| Content block published | `content:block:{key}` |

Use tagged cache when Redis available: `Cache::tags(['tariff'])->flush()`.

File driver: iterate known keys or use version suffix bump `tariff:v{n}:effective:{date}`.

---

## 6. Read patterns

### Catalogue pages (Services, Tarifs)

```php
Cache::remember(CacheKeys::catalogueServices(), 3600, fn () =>
    Service::query()->published()->ordered()->get()
);
```

### Tariff resolver

```php
Cache::remember(CacheKeys::tariffEffective($date), 3600, fn () =>
    $this->queryEffectiveVersion($date)
);
```

### Availability engine

Load schedule data from `Cache::remember(CacheKeys::scheduleCentre($id), 86400, …)`.

Compute **open now** from cached schedule + `Clock::nowDisplay()` — do not cache boolean result longer than 60s.

---

## 7. Local vs production

| Environment | Driver | Notes |
| --- | --- | --- |
| Local dev | `file` | `.env` `CACHE_STORE=file` |
| Tests | `array` | `phpunit.xml` |
| Production Docker | `redis` | Phase IX · step 151+ |

---

## 8. Acceptance (step 83)

- [x] Cacheable vs never-cache lists
- [x] Key naming convention
- [x] TTL guidance
- [x] Invalidation tied to domain events
- [x] Tracking explicitly excluded
- [x] Redis path for production noted

**Next:** Step **84** — Security design.
