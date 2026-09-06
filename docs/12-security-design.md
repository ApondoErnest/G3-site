# 12 — Security design

| Delivery | Phase V · step **84** |
| --- | --- |
| Prerequisites | [05-quality.md](05-quality.md) · [10-admin.md](10-admin.md) · [12-use-cases.md](12-use-cases.md) |
| Implementation | Phase VI steps 98–108 |

Security architecture for G3 Control V1: **policies**, **rate limiting**, **MFA**, **uploads**, and **public form hardening**.

---

## 1. Threat model (V1 scope)

| Surface | Threats mitigated |
| --- | --- |
| Public forms | Spam, enumeration, CSRF |
| Tracking | Reference enumeration, credential stuffing |
| Admin `/admin` | Unauthorized access, privilege escalation, session hijacking |
| Media upload | Malware, XSS via SVG, oversized files |
| API surface | Minimal — server-rendered only [NFR-P-06](05-quality.md) |

Out of scope V1: DDoS at edge (VPS/nginx later), WAF, Turnstile (suggested backlog [FR-CT](04-requirements.md)).

---

## 2. Authorization — policies

One policy class per aggregate. Register in `AuthServiceProvider`.

| Policy | Model | Rules |
| --- | --- | --- |
| `AppointmentPolicy` | `AppointmentRequest` | viewAny/view/update: super, ops, scoped manager/reception |
| `ContactMessagePolicy` | `ContactMessage` | viewAny/view/update: super, ops, scoped reception |
| `CentrePolicy` | `Centre` | update hours: super, ops, scoped manager |
| `ScheduleExceptionPolicy` | `ScheduleException` | CRUD: super, ops, scoped manager |
| `TariffVersionPolicy` | `TariffVersion` | publish: super, ops only; edit draft: ops |
| `ServicePolicy` | `Service` | content/catalogue roles |
| `ContentBlockPolicy` | `ContentBlock` | content editor + super |
| `UserPolicy` | `User` | super admin only |

### 2.1 Centre scoping

```php
protected function userCanAccessCentre(User $user, Centre $centre): bool
{
    if ($user->hasRole(['super_admin', 'operations_admin'])) {
        return true;
    }

    return $user->centreScopes()->where('centre_id', $centre->id)->exists();
}
```

Reception + Centre Manager require `admin_user_scopes` row ([12-domain-model.md](12-domain-model.md)).

### 2.2 Fail closed

Unauthorized Filament URL → **403** [07-acceptance.md](07-acceptance.md) · not empty UI only [FR-AD-03](04-requirements.md).

Use `$user->can('update', $appointment)` in use cases **before** state machine runs.

---

## 3. Rate limiting

Configure in `AppServiceProvider` or `RouteServiceProvider` / `bootstrap/app.php`.

| Limiter name | Scope | Limit | Applies to |
| --- | --- | --- | --- |
| `appointment-submit` | IP + fingerprint | 5 / hour | `CreateAppointmentRequest` |
| `tracking-lookup` | IP | 10 / hour | `TrackAppointment` |
| `contact-submit` | IP | 5 / hour | `SubmitContactMessage` |
| `login` | email + IP | 5 / minute | Filament login [NFR-S-01](05-quality.md) |

On exceed: public forms return generic validation-style message; tracking returns **same generic failure** as wrong credentials [BR-TRACK-003](05-quality.md).

Implementation: `RateLimiter::tooManyAttempts()` in use case entry.

---

## 4. CSRF & honeypot

| Control | Where |
| --- | --- |
| CSRF token | All Livewire POST [NFR-S-02](05-quality.md) |
| Honeypot field | Contact form hidden `website` field [FR-CT-05](04-requirements.md) |
| Idempotency token | Appointment form session token → `idempotency_key` |

Honeypot filled → silent no-op (no DB row, no error leak).

---

## 5. Admin authentication

| Control | Spec |
| --- | --- |
| MFA | TOTP required for all admin users in production [FR-AD-05](05-quality.md) · Filament Breezy or custom |
| Password policy | min 12 chars, mixed case + number; compromised check optional |
| Session timeout | 120 min idle; regenerate on login |
| Secure cookies | `SESSION_SECURE_COOKIE=true` prod [NFR-S-08](05-quality.md) |
| Remember me | Disabled for admin V1 (suggestion) |
| Shared password | Prohibited [02-charter.md](02-charter.md) |

`users.is_active = false` → cannot login.

---

## 6. Media uploads

| Rule | Value |
| --- | --- |
| Max size | 10 MB image · 50 MB video |
| Allowed MIME | `image/jpeg`, `image/png`, `image/webp`, `video/mp4` |
| Blocked | `application/*`, SVG upload (XSS), executables [NFR-S-06](05-quality.md) |
| Storage | `storage/app` private; public disk for conversions only |
| Filename | UUID from Spatie; no user-supplied paths |
| ALT text | Required FR+EN before publish on public collections |

Virus scan: backlog unless VPS provides ClamAV.

---

## 7. Data minimization

| Surface | Collected | Not collected |
| --- | --- | --- |
| Appointment | name, phone, email?, plate, preferences | ID scans, payment |
| Tracking | ref + phone OR plate (transient) | not stored as log |
| Contact | intent, name, phone, email, message | — |

Form notice suggested [05-quality.md](05-quality.md).

---

## 8. Infrastructure (reference)

| Item | Phase |
| --- | --- |
| HTTPS only | Phase X deploy |
| DB least privilege user | Phase X [NFR-S-09](05-quality.md) |
| `robots.txt` Disallow `/admin` | Phase VII [FR-AD-06](04-requirements.md) |
| Secrets in `.env` | [NFR-S-10](05-quality.md) |

---

## 9. Security checklist for implementation

| Step | Item |
| ---: | --- |
| 98–107 | Policy tests per role matrix |
| 108 | MFA enforced · 403 on forbidden routes |
| 117+ | CSRF on Livewire forms |
| 130+ | Penetration-style acceptance on tracking enumeration |

---

## 10. Acceptance (step 84)

- [x] Policy matrix per model and role
- [x] Centre scoping logic
- [x] Rate limiters defined
- [x] CSRF, honeypot, idempotency
- [x] MFA and session requirements
- [x] Upload MIME/size rules
- [x] PII minimization aligned with quality doc

**Next:** Step **85** — Gate: domain design accepted.
