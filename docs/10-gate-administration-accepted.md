# 116 — Gate: administration accepted

| Delivery | Phase VI · step **116** · Gate |
| --- | --- |
| Prerequisites | Steps 108–115 complete · Gate 107 |
| Opens | Steps **117–129** — Public experience |
| Verified | 2026-09-07 |

Owner acceptance that G3 Control V1 **Filament administration** implements the approved design ([10-admin.md](10-admin.md), [design/admin/](../design/admin/)) against the domain use cases (Gate 107). **Public Blade pages (step 117+) may begin.**

---

## 1. Verification summary

| Step | Deliverable | Status |
| ---: | --- | --- |
| 108 | Auth · five roles, MFA, policies · [`AdminPanelProvider`](../app/Providers/Filament/AdminPanelProvider.php) | ✓ |
| 109 | Dashboard · [`ResolveDashboardData`](../app/Actions/Admin/ResolveDashboardData.php) | ✓ |
| 110 | Centre management · hours, exceptions, alerts | ✓ |
| 111 | Catalogue management · services, categories, documents | ✓ |
| 112 | Tariff management · draft → reviewed → published → archived | ✓ |
| 113 | Appointment management · state machine transitions | ✓ |
| 114 | Content and media · FR/EN tabs, structured fields | ✓ |
| 115 | Audit log · read-only activity journal | ✓ |

**Test suite:** `composer test` — **256 passed** (908 assertions) on 2026-09-07.

---

## 2. Administration coverage

| Nav group | Resources / pages | Domain wiring |
| --- | --- | --- |
| **Operations** | Dashboard, appointment list/view | `TransitionAppointmentStatus`, `UpdateAppointmentPreferredTime`, `AddAppointmentInternalNote` |
| **Centres** | Centres, weekly hours, schedule exceptions, operational alerts | Schedule use cases, `ChangeCentreWeeklyHours` |
| **Catalogue** | Services, vehicle categories, required documents, tariff versions | Catalogue cache invalidation; tariff publish workflow |
| **Content** | Content blocks, FAQ, team, road safety, media library | `UpdateContentBlock`, publish/unpublish actions |
| **System** | Page SEO, audit log | Cache invalidation; Spatie activity log read-only |

---

## 3. Authorization matrix (FR-AD-03)

| Role | Dashboard | Centres | Catalogue | Tariffs | Appointments | Content | Audit |
| --- | ---: | ---: | ---: | ---: | ---: | ---: | ---: |
| Super Administrator | ✓ | ✓ | ✓ write | ✓ publish | ✓ | ✓ | ✓ |
| Operations Administrator | ✓ | ✓ write | ✓ read | ✓ publish | ✓ | — | ✓ |
| Centre Manager | ✓ scoped | ✓ scoped | — | read | ✓ scoped | — | — |
| Reception Officer | ✓ | — | — | — | ✓ scoped | — | — |
| Content Editor | ✓ | — | ✓ write | — | — | ✓ | — |

Verified in `tests/Feature/Admin/Admin*Test.php` and `AdminPolicyTest.php`.

---

## 4. Architecture compliance

| Rule | Status |
| --- | --- |
| Filament calls domain `app/Actions/` — no business logic in Blade | ✓ |
| Appointment illegal transitions rejected by state machine | ✓ |
| Tariff versions immutable after publish (no delete) | ✓ |
| Bilingual admin chrome FR default + EN switcher with full reload | ✓ |
| Content forms use FR/EN tabs — no raw JSON editing in UI | ✓ |
| Strict Filament authorization enabled | ✓ |

---

## 5. Outstanding before go-live (not blocking Gate 116)

| Item | Owner | Notes |
| --- | --- | --- |
| Contact inbox Filament resource | Dev · post-116 | Domain actions exist; not in steps 108–115 scope |
| User/role management UI | Dev · post-116 | Policies exist; System nav placeholder |
| Company settings admin page | Dev · post-116 | `UpdateCompanySettings` action exists |
| Spatie `LogsActivity` on models | Dev · post-116 | Audit table exists; trait wiring deferred |
| Equipment centre management | Dev · post-116 | Table exists; Filament resource deferred |

---

## 6. Gate decision

**Accepted** — 2026-09-07

Phase VI administration is complete. Proceed to **Phase VII · step 117** (application shell).

---

## 7. Change control

Changes to admin nav structure, role matrix, or workflow state machines after this gate require update to [10-admin.md](10-admin.md) and re-verification of affected Pest tests.
