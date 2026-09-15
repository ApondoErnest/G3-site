# Hi-fi specifications · steps 60–69

Consolidated visual specs. Previews use [shared.css](shared.css). Wireframes: [desktop](../wireframes/README.md).

| Step | Page | URL | Preview |
| ---: | --- | --- | --- |
| 60 | À propos | `/fr/a-propos` | [60-a-propos.html](60-a-propos.html) |
| 61 | Nos centres | `/fr/centres` | [61-centres.html](61-centres.html) |
| 62 | École de Police | `/fr/centres/ecole-de-police` | [62-centre-ecole-de-police.html](62-centre-ecole-de-police.html) |
| 63 | Nomayos | `/fr/centres/nomayos` | [63-centre-nomayos.html](63-centre-nomayos.html) |
| 64 | Services | `/fr/services` | [64-services.html](64-services.html) |
| 65 | Visite technique | `/fr/visite-technique` | [65-visite-technique.html](65-visite-technique.html) |
| 66 | Tarifs | `/fr/tarifs` | [66-tarifs.html](66-tarifs.html) |
| 67 | Rendez-vous & Suivi | `/fr/rendez-vous` | [67-rendez-vous.html](67-rendez-vous.html) |
| 68 | Sécurité routière | `/fr/securite-routiere` | [68-securite-routiere.html](68-securite-routiere.html) |
| 69 | Contact | `/fr/contact` | [69-contact.html](69-contact.html) |

All pages share chrome from step 59: sticky white header, G3 Signature Safety Bands, compact footer. **Not production code.**

**Colour revision (2026-09):** HTML previews may still show pre-revision dark-blue heroes or orange CTAs. Apply [34-design-system.md](../34-design-system.md) — white heroes, royal blue CTAs, orange bands only — when implementing Phase VII.

---

## 60 · À propos

**Job:** Who is G3? · Wireframe [38](../wireframes/desktop/38-a-propos.md)

| Zone | Hi-fi treatment |
| --- | --- |
| Hero | White `page-hero`, breadcrumb, H1 Manrope 2rem |
| Mission | `split-2` · body muted · image placeholder |
| Agrément | `band-soft` centred · FR-CO-02 |
| Values | 3-column cards · slogan words |
| Team | 4-col avatar grid · EMP if empty |
| CTA | Deep blue band · centres / contact / RDV |

**Step 119 implementation note · 2026-09-15**

Implemented as `resources/views/pages/about.blade.php` with supporting copy in `lang/fr/public.php` and `lang/en/public.php`, responsive styling in `resources/css/app.css`, and SVG/bitmap assets under `public/images/about/`.

| Production section | Notes |
| --- | --- |
| Notre identité | 128px desktop inset, identity copy, vision card, G3 en bref card, and 2020 → Aujourd'hui timeline |
| Exigence technique | Left image `technical-requirements.png`, right technical list with generated SVG icons |
| Nos valeurs | Compact blue band with centred, blurred, scaled G3 logo background; cards are more transparent and use two columns on mobile |
| L'équipe G3 | Text and values on the left, `technicians.png` on the right; the image is CSS-cropped/zoomed to show the team without editing the source file |

Verification completed during Step 119: `vendor/bin/pint --dirty --format agent`, `npm run build`, and `php artisan test --compact tests/Feature/Public/PublicShellTest.php`. Later layout-only refinements were rechecked with `npm run build` and browser viewport inspection.

---

## 61 · Nos centres

**Job:** Compare · pick centre · Wireframe [39](../wireframes/desktop/39-centres.md)

| Zone | Hi-fi treatment |
| --- | --- |
| Live compare | `live-strip` two cards · open pills |
| Map | `map-box` 320px lazy Leaflet |
| Cards | `centre-card` + orange top line · full action row |
| CTA | RDV primary |

**Step 120 implementation note · 2026-09-15**

Implemented as `resources/views/pages/centres.blade.php` with bilingual copy in `lang/fr/public.php` and `lang/en/public.php`, responsive styling in `resources/css/app.css`, selector behavior in `resources/js/app.js`, and centre assets under `public/images/centers/`.

| Production section | Notes |
| --- | --- |
| G3 Live selector | Screenshot-led layout with copy on the left, a real Google Maps iframe, two branded overlay markers, and a centre detail panel on the right |
| Centre detail tabs | École de Police active by default; tabs and map markers switch the visible centre card without navigation |
| Centre actions | `Itinéraire`, `Voir le centre`, and `Choisir ce centre`; call action removed from the action row while phone remains clickable in details |
| Mobile actions | Three action buttons fit on one row in the active centre card |
| Le standard G3 | Compact transparent blue band with centred, zoomed G3 logo background and four generated SVG standard icons |

Verification completed during Step 120: `vendor/bin/pint --dirty --format agent`, `npm run build`, `php artisan test --compact tests/Feature/Public/PublicShellTest.php`, and browser desktop/mobile inspection for overflow, selector state, map iframe, and compact standard band layout.

---

## 62–63 · Centre detail

**Job:** Visit this centre today · Wireframes [40](../wireframes/desktop/40-centre-ecole-de-police.md) / [41](../wireframes/desktop/41-centre-nomayos.md)

Shared layout: split hero (deep blue + image), status/hours panels, contact line, map, gallery grid, services list, cross-link card. Locked hours/phones from [01-baseline](../../docs/01-baseline.md). Nomayos: no opening year (Q-03).

---

## 64 · Services

**Job:** What G3 validates · Wireframe [42](../wireframes/desktop/42-services.md)

| Zone | Hi-fi treatment |
| --- | --- |
| Filter | Select + search full width |
| Cards | `service-card` with icon, centres tags, 3 actions |
| Empty | Hidden unpublished FR-SV-04 |

---

## 65 · Visite technique

**Job:** How inspection works · Wireframe [43](../wireframes/desktop/43-visite-technique.md)

Video `video-box` 16:9 · journey component · split process · document/FAQ accordions · no MOT copy · CTA RDV + tarifs.

---

## 66 · Tarifs

**Job:** Published cost only · Wireframe [44](../wireframes/desktop/44-tarifs.md)

Desktop: `matrix` table · Mobile: `tariff-card` stack (hidden on desktop). Finder row · print/share secondary buttons · empty state FR-TA-09. Amounts grouped FCFA.

---

## 67 · Rendez-vous & Suivi

**Job:** Request + track · Wireframe [45](../wireframes/desktop/45-rendez-vous.md)

Tabs 50/50 · orange active indicator · step bar dots · centre radio cards with live pills · purpose notice · suivi panel: ref + phone/plate · vertical timeline · generic error alert BR-TRACK-003 · success copy *demande reçue* FR-AP-04.

---

## 68 · Sécurité routière

**Job:** Evergreen safety · Wireframe [46](../wireframes/desktop/46-securite-routiere.md)

`chip-nav` jump links · stacked `content-section` blocks per FR-CN-03 · rain/Cameroon emphasis · link to visite technique.

---

## 69 · Contact

**Job:** Intent-first · Wireframe [47](../wireframes/desktop/47-contact.md)

`intent-grid` 2×2 · selected = blue border + soft bg · form panel + coordinates sidebar · honeypot hidden in impl · FR-CT-01 intents.

---

## Exit criteria (steps 60–69)

- [x] Hi-fi preview HTML for each public page (except home · step 59)
- [x] Shared stylesheet extracted ([shared.css](shared.css))
- [x] Design tokens and component states from steps 34–36 applied
- [x] Wireframe structure preserved; no new routes or scope
- [x] Previews cross-linked for stakeholder walkthrough

**Next:** Step 70 — Admin UX.
