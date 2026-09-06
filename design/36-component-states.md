# 36 — Component states

| Delivery | Phase IV · step **36** |
| --- | --- |
| Design | [34-design-system.md](34-design-system.md) · [33-information-architecture.md](33-information-architecture.md) |
| Specs | [05-quality](../docs/05-quality.md) · [06-rules](../docs/06-rules.md) · [07-acceptance.md](../docs/07-acceptance.md) |
| Next | Step 37 — Desktop wireframe · Accueil |

Interactive state specs for the **public site** (wireframes 37–58, hi-fi 59–69, implementation 117–129). Filament admin states are defined in step 70.

**States covered:** default · hover · focus · error · disabled · empty.

---

## 1. Global rules

### 1.1 State tokens

| Token | Hex | Use |
| --- | --- | --- |
| `--g3-error` | `#C62828` | Error text, borders, icons — **confirmed** (was suggestion in step 34) |
| `--g3-error-tint` | `#FCEAEA` | Error field background, inline alert background |
| `--g3-error-border` | `#E57373` | Error input border (AA contrast on white) |
| `--g3-disabled-bg` | `#F6F7F9` | Disabled control fill |
| `--g3-disabled-text` | `#92979D` | Disabled label and value |
| `--g3-disabled-border` | `#E4E7EC` | Disabled border |

All other tokens: [34-design-system.md](34-design-system.md).

### 1.2 Interaction rules

| Rule | Detail |
| --- | --- |
| **Focus visible** | Keyboard: 2px `--g3-focus` ring, 2px offset; mouse click does not leave persistent ring (NFR-A-03) |
| **Touch targets** | Minimum 44×44px for every interactive control (NFR-A-08) |
| **Hover** | Desktop pointer only; no hover-only information (NFR-A-04) |
| **Disabled** | `cursor: not-allowed`; no hover change; excluded from tab order |
| **Error copy** | Label + message below field; icon optional; never colour alone (NFR-A-05) |
| **Motion** | State transitions 150ms; honour `prefers-reduced-motion` |
| **Orange** | Never used for error — errors are red; orange stays accent/CTA |

### 1.3 State legend (wireframes)

| Abbrev | Meaning |
| --- | --- |
| **DEF** | Default |
| **HOV** | Hover |
| **FOC** | Focus-visible |
| **ERR** | Error / invalid |
| **DIS** | Disabled |
| **EMP** | Empty (no data / no result) |

---

## 2. Buttons

### 2.1 Primary (Safety orange CTA)

| State | Background | Text | Border | Other |
| --- | --- | --- | --- | --- |
| **DEF** | `--g3-orange` | white | none | min-height 44px, `radius-md`, Inter 600 |
| **HOV** | `#D9691A`* | white | none | ~8% darken |
| **FOC** | `--g3-orange` | white | none | 2px `--g3-focus` ring, 2px offset |
| **ERR** | — | — | — | Use inline alert above form; button stays DEF until submit attempted |
| **DIS** | `--g3-disabled-bg` | `--g3-disabled-text` | `--g3-disabled-border` 1px | no shadow |

\*Computed hover; implement as `#D9691A` or filter — match visually in hi-fi.

**Labels:** “Envoyer la demande”, “Suivre ma demande”, “Rendez-vous & Suivi” (header).

### 2.2 Secondary (outline)

| State | Background | Text | Border |
| --- | --- | --- | --- |
| **DEF** | white | `--g3-blue-tech` | 1px `--g3-blue-tech` |
| **HOV** | `--g3-blue-soft` | `--g3-blue-bright` | 1px `--g3-blue-bright` |
| **FOC** | white | `--g3-blue-tech` | 1px `--g3-blue-tech` + focus ring |
| **DIS** | `--g3-disabled-bg` | `--g3-disabled-text` | `--g3-disabled-border` |

### 2.3 Ghost / link button

| State | Text | Decoration |
| --- | --- | --- |
| **DEF** | `--g3-blue-tech` | none |
| **HOV** | `--g3-blue-bright` | underline |
| **FOC** | `--g3-blue-tech` | underline + focus ring around padded hit area |
| **DIS** | `--g3-disabled-text` | none |

---

## 3. Navigation

### 3.1 Header nav link

| State | Text | Indicator |
| --- | --- | --- |
| **DEF** | `--g3-charcoal` | none |
| **HOV** | `--g3-blue-tech` | none |
| **FOC** | `--g3-blue-tech` | focus ring on padded 44px block |
| **Active** | `--g3-blue-tech` | 2px bottom `--g3-orange` (Safety Line segment) |
| **DIS** | — | Not used in V1 public nav |

### 3.2 Utility CTA (header)

Uses **primary button** states (§2.1). On mobile menu panel, full-width primary at bottom of stack.

### 3.3 Locale toggle (FR | EN)

| State | Style |
| --- | --- |
| **DEF** | Both locales visible; current = `--g3-charcoal` weight 600; other = `--g3-muted` weight 400 |
| **HOV** | Non-current locale → `--g3-blue-tech` |
| **FOC** | Focus ring on the locale link being activated |
| **DIS** | — | Not used |

Separator: `|` in `--g3-metallic`.

### 3.4 Mobile menu button

| State | Icon | Background |
| --- | --- | --- |
| **DEF** | Lucide menu, `--g3-charcoal` | transparent |
| **HOV** | `--g3-blue-tech` | `--g3-blue-soft` circle 44px |
| **FOC** | focus ring | |
| **Open** | Lucide X | same as HOV — panel visible |

---

## 4. Form controls

Shared field anatomy: **label** (Inter 500) → **control** → **help** (muted, optional) → **error message** (red, with optional Lucide `circle-alert` 16px).

Form notice (suggestion): muted one-liner above submit — *Ces informations servent uniquement à traiter votre demande.* ([05-quality](../docs/05-quality.md)).

### 4.1 Text input

| State | Background | Border | Text |
| --- | --- | --- | --- |
| **DEF** | white | 1px `--g3-border` | `--g3-charcoal` |
| **HOV** | white | 1px `--g3-metallic` | `--g3-charcoal` |
| **FOC** | white | 1px `--g3-blue-tech` | `--g3-charcoal` + focus ring |
| **ERR** | `--g3-error-tint` | 1px `--g3-error-border` | `--g3-charcoal` + error message below |
| **DIS** | `--g3-disabled-bg` | `--g3-disabled-border` | `--g3-disabled-text` |

Height 44px; padding 12px 16px; placeholder `--g3-muted`.

### 4.2 Textarea

Same states as text input; min-height 120px; vertical resize allowed.

### 4.3 Select / combobox

Same border/focus/error/disabled as text input. Chevron Lucide `--g3-muted`. Dropdown panel: white, `shadow-md`, `radius-md`, option **HOV** = `--g3-blue-soft`.

### 4.4 Checkbox / radio

| State | Control | Label |
| --- | --- | --- |
| **DEF** | 20px box, 1px `--g3-border` | `--g3-charcoal` |
| **HOV** | border `--g3-blue-tech` | — |
| **FOC** | focus ring on box | — |
| **Checked** | fill `--g3-blue-tech`, white check/dot | — |
| **ERR** | border `--g3-error-border` | error message below group |
| **DIS** | `--g3-disabled-bg`, muted mark | `--g3-disabled-text` |

Touch target: 44px row including label click area.

### 4.5 Date / period picker (appointment)

Uses select + date input states. **DIS** when centre closed on selected date (with inline help — not error until submit).

---

## 5. Tabs · Rendez-vous & Suivi

Two tabs: **Demande** / **Request** · **Suivi** / **Track** ([33-information-architecture.md](33-information-architecture.md)).

| State | Style |
| --- | --- |
| **DEF (inactive tab)** | `--g3-muted` text; bottom border transparent |
| **HOV** | `--g3-blue-tech` text |
| **FOC** | focus ring on tab button |
| **Active tab** | `--g3-charcoal` text weight 600; 3px bottom `--g3-orange` |
| **DIS** | — | Not used |

Tab panel switches content; URL query `?tab=suivi` / `?tab=track` syncs active tab.

---

## 6. Cards and tiles

### 6.1 Static card (informational)

Only **DEF** — white, `shadow-sm`, `radius-md`. No hover change.

### 6.2 Clickable card (centre, service, intent)

| State | Shadow | Border | Transform |
| --- | --- | --- | --- |
| **DEF** | `shadow-sm` | 1px `--g3-border` or none | none |
| **HOV** | `shadow-md` | 1px `--g3-blue-tech` | none (no scale — avoids CLS) |
| **FOC** | `shadow-md` | 1px `--g3-blue-tech` | focus ring around card |
| **DIS** | `shadow-sm` | `--g3-disabled-border` | opacity 0.7; no pointer |

Optional card-top Safety Line (3px orange) in all states except **DIS** (line → `--g3-disabled-border`).

### 6.3 Contact intent tile

Clickable card pattern. **Selected** intent: border 2px `--g3-blue-tech`, background `--g3-blue-soft`, check icon `--g3-blue-tech` top-right.

---

## 7. Status and feedback

### 7.1 Live centre pill (open / closed)

Status is **never colour alone** (NFR-A-04):

| Status | Background | Text | Icon |
| --- | --- | --- | --- |
| **Open** | `#E8F5EE`* | `--g3-success` | Lucide `circle-check` + “Ouvert actuellement” |
| **Closed** | `--g3-grey` | `--g3-muted` | Lucide `circle-minus` + “Fermé” + next open time |
| **Exception** | `--g3-orange-tint` | `--g3-charcoal` | Lucide `info` + exception message |

\*Success tint; pair with `--g3-success` text.

No **HOV** on read-only pills. **Closed is not red** — avoid alarm styling for normal overnight closure.

### 7.2 Appointment tracking timeline

Customer-safe statuses only (FR-TR-04). Each step: icon + label + date (muted).

| Step state | Icon colour | Label |
| --- | --- | --- |
| **Completed** | `--g3-success` | `--g3-charcoal` |
| **Current** | `--g3-blue-tech` | weight 600 |
| **Upcoming** | `--g3-metallic` | `--g3-muted` |
| **Cancelled** | `--g3-muted` | strikethrough optional on label |

### 7.3 Inline alerts

| Variant | Background | Border-left | Icon |
| --- | --- | --- | --- |
| **Info** | `--g3-blue-soft` | 4px `--g3-blue-tech` | `info` |
| **Success** | `#E8F5EE` | 4px `--g3-success` | `circle-check` |
| **Error** | `--g3-error-tint` | 4px `--g3-error` | `circle-alert` |

Used after form submit, tracking failure, rate limit. Error text `--g3-error`; body `--g3-charcoal`.

**Tracking mismatch** (BR-TRACK-003, NFR-S-05): single **generic error** alert — same visual as form error; copy must not reveal whether reference exists.

Example FR: *Les informations saisies ne correspondent pas à une demande enregistrée. Vérifiez votre référence et votre téléphone ou immatriculation.*

---

## 8. Empty states (EMP)

Pattern: centred block in `--g3-blue-soft` or white card; Lucide icon 32px `--g3-muted`; **heading-sm** Manrope; body `--g3-muted`; **primary** or **secondary** action.

| Context | Icon | Message intent | Action |
| --- | --- | --- | --- |
| **Tariffs unpublished** (FR-TA-09) | `file-x` | No official prices online yet — honest, no dummy numbers | Contact · Call |
| **Tariff search no match** | `search-x` | No category matches filters | Clear filters |
| **Services none published** | `clipboard-x` | Catalogue being updated | Contact |
| **Tracking — before search** | `search` | Enter reference and phone or plate | — |
| **Tracking — no result** | Use **error alert** (§7.3), not empty — generic failure | Retry | |
| **About — no team** | `users` | Team profiles coming soon | Contact |
| **Centre gallery empty** | `image-off` | Photos coming soon | Call / directions |
| **Home tariff teaser — no version** | Hide zone or show compact EMP linking to contact | | |

Empty states are **not** errors — calm tone, no red.

---

## 9. Page-level state matrix

Quick reference for wireframes:

| Surface | DEF | HOV | FOC | ERR | DIS | EMP |
| --- | --- | --- | --- | --- | --- | --- |
| Primary button | ✓ | ✓ | ✓ | — | ✓ | — |
| Secondary button | ✓ | ✓ | ✓ | — | ✓ | — |
| Nav link | ✓ | ✓ | ✓ | — | — | — |
| Text field | ✓ | ✓ | ✓ | ✓ | ✓ | — |
| Tab | ✓ | ✓ | ✓ | — | — | — |
| Clickable card | ✓ | ✓ | ✓ | — | ✓ | — |
| Status pill | ✓ | — | — | — | — | — |
| Tariff matrix | ✓ | row HOV | cell FOC | — | — | ✓ unpublished |
| Tracking form | ✓ | ✓ | ✓ | ✓ generic | submit DIS while loading | ✓ before search |

---

## 10. Loading (implementation note)

Not a named step-36 state, but wireframes may annotate:

| Pattern | Spec |
| --- | --- |
| Button submit | Label → spinner + “Envoi…”; button **DIS** |
| Tracking lookup | Inline spinner in results area; fields **DIS** |
| Livewire sections | Skeleton blocks in `--g3-grey` — no layout shift |

Prefer skeleton over blocking overlay on public pages.

---

## 11. Exit criteria (step 36)

- [x] Default, hover, focus, error, disabled defined for buttons, nav, and form controls
- [x] Tab, card, status pill, and alert variants specified
- [x] Empty-state patterns documented per page context
- [x] Error token `--g3-error` confirmed; tracking generic failure aligned with BR-TRACK-003
- [x] Accessibility rules (focus, touch, colour+label) traced to NFR-A-*

**Next:** Step 38 — Desktop wireframe · À propos.
