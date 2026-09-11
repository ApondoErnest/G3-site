# 71 — Gate: experience design accepted

| Delivery | Phase IV · step **71** · Gate |
| --- | --- |
| Prerequisites | Steps 33–70 complete |
| Opens | Phase V · step 72 — Conceptual data model |
| Verified | 2026-09-05 |

Owner acceptance that Phase IV experience design is complete, consistent with specifications, and ready to inform database and domain design. **No migrations or public page implementation until Phase V gates are passed and Phase VI/VII work begins.**

---

## 1. Verification summary

| Layer | Steps | Artefacts | Status |
| --- | --- | --- | --- |
| Foundation | 33–36 | IA, design system, media, component states | ✓ |
| Wireframes · desktop | 37–47 | 11 pages | ✓ |
| Wireframes · mobile | 48–58 | 11 pages | ✓ |
| Hi-fi · public | 59–69 | 11 HTML previews + specs | ✓ |
| Hi-fi · admin | 70 | 5 screens + spec + stylesheet | ✓ |

**Total:** 38 steps · 52 files under `design/`

---

## 2. Page coverage

All V1 public pages from [03-scope.md](../docs/03-scope.md) and [33-information-architecture.md](33-information-architecture.md):

| Page | Wireframe D/M | Hi-fi | Route |
| --- | :---: | :---: | --- |
| Accueil | ✓ | ✓ | `/fr/accueil` |
| À propos | ✓ | ✓ | `/fr/a-propos` |
| Nos centres | ✓ | ✓ | `/fr/centres` |
| École de Police | ✓ | ✓ | `/fr/centres/ecole-de-police` |
| Nomayos | ✓ | ✓ | `/fr/centres/nomayos` |
| Services | ✓ | ✓ | `/fr/services` |
| Visite technique | ✓ | ✓ | `/fr/visite-technique` |
| Tarifs | ✓ | ✓ | `/fr/tarifs` |
| Rendez-vous & Suivi | ✓ | ✓ | `/fr/rendez-vous` |
| Sécurité routière | ✓ | ✓ | `/fr/securite-routiere` |
| Contact | ✓ | ✓ | `/fr/contact` |

Admin screens from [10-admin.md](../docs/10-admin.md):

| Screen | Preview | Requirements covered |
| --- | --- | --- |
| Connexion | [70-login.html](admin/70-login.html) | FR-AD-05 |
| Tableau de bord | [70-dashboard.html](admin/70-dashboard.html) | FR-AD-01 |
| Demandes RDV | [70-appointments.html](admin/70-appointments.html) | FR-AD-02, FR-AD-03 |
| Publication tarif | [70-tariff-publish.html](admin/70-tariff-publish.html) | BR-TARIFF-005 |
| Contenu FR/EN | [70-content.html](admin/70-content.html) | BR-LANG-001 |

---

## 3. Design system compliance

| Criterion | Reference | Verified |
| --- | --- | --- |
| G3 Signature Safety Bands palette | [34-design-system.md](34-design-system.md) · [01-baseline](../docs/01-baseline.md) | ✓ revised |
| Manrope headings · Inter body | [34-design-system.md](34-design-system.md) | ✓ |
| Full · card · micro band system | [34-design-system.md](34-design-system.md) | ✓ revised |
| Component states (hover, focus, error, empty) | [36-component-states.md](36-component-states.md) | ✓ |
| Media placeholders and rules | [35-media-guidelines.md](35-media-guidelines.md) | ✓ |
| FR default · EN parity pattern | [33-information-architecture.md](33-information-architecture.md) | ✓ |
| Admin French chrome V1 | [10-admin.md](../docs/10-admin.md) | ✓ |

---

## 4. Business rules represented in design

| Rule | Design evidence |
| --- | --- |
| BR-LANG-001 · bilingual publish gate | Admin content screen · publish disabled until EN complete |
| BR-TARIFF-005 · single published tariff | Tariff workflow stepper · archive on publish |
| BR-TRACK-003 · generic tracking errors | Rendez-vous hi-fi suivi tab |
| FR-AP-04 · *demande reçue* not confirmed | Rendez-vous success state |
| FR-TA-09 · empty tariff state | Tarifs hi-fi empty state |
| FR-AD-03 · internal notes ≠ timeline | Appointments detail panel |
| FR-CO-02 · agrément display | À propos hi-fi |
| NFR-S-11 · admin excluded from sitemap | IA §1 |

---

## 5. Phase IV prohibitions respected

| Prohibition | Status |
| --- | --- |
| No database migrations for business entities | ✓ — only Laravel default migrations |
| No public Blade pages beyond placeholder shell | ✓ — `pages/shell.blade.php` only |
| No Filament resources beyond default panel | ✓ — login only |
| Design artefacts are static HTML/CSS previews | ✓ — not production code |

---

## 6. Preview entry points

| Audience | Start URL (local static server on `design/`) |
| --- | --- |
| Public site | [hifi/59-accueil.html](hifi/59-accueil.html) |
| Admin | [admin/70-dashboard.html](admin/70-dashboard.html) |

Pages cross-link via navigation. Stylesheets: [hifi/shared.css](hifi/shared.css) · [admin/admin.css](admin/admin.css).

---

## 7. Gate decision

**Accepted.** Steps 33–70 deliver a complete, consistent experience design for G3 Control V1. Phase V (data and domain design) may proceed.

| | |
| --- | --- |
| Gate | 71 — Experience design accepted |
| Date | 2026-09-05 |
| Next active step | **72** — Conceptual data model |

---

## 8. Change control

Reopening Phase IV after this gate requires documented change control per [03-scope.md](../docs/03-scope.md). Wireframe or hi-fi changes that affect routes, page jobs, or business rules must be reflected in `docs/` before Phase VII implementation.
