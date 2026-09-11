# 34 — Design system · G3 Signature Safety Bands

| Delivery | Phase IV · step **34** · **Revised** from real centre photography |
| --- | --- |
| Specs | [01-baseline](../docs/01-baseline.md) · [02-charter](../docs/02-charter.md) · [05-quality](../docs/05-quality.md) |
| Next | [36-component-states.md](36-component-states.md) |

Visual foundation for wireframes (37–58), hi-fi (59–69), and public implementation (117–129). **No application CSS in this step** — tokens defined here; Tailwind wiring in Phase VII.

**Supersedes:** the earlier model that treated blue and orange as co-equal brand colours. G3’s physical identity is **white above → thin orange safety band → royal blue below**. The website digitises that rhythm.

**Feel:** bright, clean, controlled, authoritative — aligned with *Sécurité. Simplicité. Confiance.*

---

## 1. Design principles

| Principle | Application |
| --- | --- |
| **White for clarity** | Dominant canvas — pages feel bright like the centres, not a saturated blue/orange site |
| **Orange for attention** | Thin bands and micro accents only — strong because controlled |
| **Royal blue for confidence** | Grounding fields, CTAs, institutional sections, footer |
| **Accuracy before decoration** | Live data (hours, prices, status) is visually primary |
| **Editorial clarity** | Generous whitespace, strong hierarchy ([33-information-architecture.md](33-information-architecture.md)) |
| **Mobile first** | ~320px on a normal Cameroon connection (NFR-P-01) |
| **Accessible by default** | WCAG 2.2 AA intent; status never colour alone (NFR-A-04) |

**Avoid:** large orange heroes · orange cards everywhere · 50/50 blue/orange layouts · orange body text · orange prices · orange form borders · dark-blue heroes (contradicts real centres) · stock garage / MOT tropes · Flux as public chrome.

---

## 2. Colour tokens

Hex source of truth: [01-baseline](../docs/01-baseline.md).

### 2.1 Palette

| Token | CSS variable | Hex | Role |
| --- | --- | --- | --- |
| Pure white | `--g3-white` | `#FFFFFF` | Main backgrounds, nav, cards, forms, editorial areas |
| Clean wall white | `--g3-wall` | `#F8FAFC` | Soft page canvas alternative |
| G3 Royal Blue | `--g3-royal` | `#145DAA` | Main saturated brand · CTAs · section footers · trust bars · road-safety hero |
| Deep Inspection Blue | `--g3-blue-deep` | `#0B2F5B` | Headings · high-contrast buttons · nav text · footer base · formal typography |
| Soft Royal Blue | `--g3-blue-soft` | `#EEF5FC` | Light tinted sections · tariff finder background |
| G3 Safety Orange | `--g3-orange` | `#F47721` | **Signature band** · 3–8px lines · progress current step · micro accents |
| Soft orange | `--g3-orange-tint` | `#FFF2E8` | Subtle orange wash — sparingly |
| Charcoal | `--g3-charcoal` | `#1F2937` | Body text on white |
| Cool grey | `--g3-muted` | `#667085` | Secondary text, captions |
| Concrete grey | `--g3-border` | `#E5E7EB` | Borders, dividers |
| Metal grey | `--g3-metallic` | `#92979D` | Equipment visuals, disabled icons |
| Success green | `--g3-success` | `#168653` | Open · confirmed · completed |
| Alert red | `--g3-error` | `#D92D20` | Real errors only |
| Safety yellow | `--g3-caution` | `#F4C430` | Technical caution only |

### 2.2 Semantic aliases

| Semantic | Maps to | Usage |
| --- | --- | --- |
| `--g3-surface-page` | `--g3-white` or `--g3-wall` | Page background |
| `--g3-surface-card` | `--g3-white` | Cards, modals, forms |
| `--g3-surface-brand` | `--g3-royal` | Trust bars, card footers, road-safety fields |
| `--g3-text-primary` | `--g3-charcoal` | Body |
| `--g3-text-heading` | `--g3-blue-deep` | H1–H3 on light backgrounds |
| `--g3-text-inverse` | `--g3-white` | Text on royal/deep blue |
| `--g3-focus` | `--g3-royal` | Focus ring + input focus border |
| `--g3-text-link` | `--g3-royal` | Inline links |

### 2.3 Visual distribution

| Family | Share | Notes |
| --- | ---: | --- |
| White / off-white | ~60% | Dominant — cleanliness and transparency |
| Royal + deep blues | ~28% | Authority and grounding |
| Orange | ~5% | Bands and accents only |
| Grey + functional | ~7% | Structure, borders, status colours |

### 2.4 Colour rules

- **Orange is never a large background.** Use as 3–8px bands, selected-state lines, current step, small labels, hover details.
- **Primary CTA fill is Royal Blue**, white text — not orange. Optional 3–4px orange edge on primary CTA only.
- **Prices** in deep blue or charcoal — never orange. Orange marks *official* or *active selection*, not monetary amounts.
- **Functional colours override brand** for appointment/tracking status (see [36-component-states.md](36-component-states.md)).
- **Deep Inspection Blue ≠ Royal Blue** — deep for typography/contrast; royal for brand fields.

---

## 3. G3 Signature Safety Bands

The recurring digital composition translated from centre walls:

```text
WHITE CONTENT
WHITE CONTENT
━━━━━━━━━━━━  ← orange band (3–8px)
████████████  ← royal blue base
```

Use at different scales — not literally on every small block.

### 3.1 Full band

White section → **5–7px orange horizontal rule** → **royal blue field** (white text).

**Use:** homepage hero transition · centres section intro · road-safety entry · final CTA · footer transition.

### 3.2 Card band

```text
┌──────────────────────────┐
│  photo / white content   │
├──────────────────────────┤  ← 3–4px orange
│  ROYAL BLUE FOOTER       │  actions: itinerary · call · RDV
└──────────────────────────┘
```

**Use:** centre cards · service cards · tariff summary · contact centre cards. **Signature component** — strongest link to physical G3 architecture.

### 3.3 Micro band

Short **3–4px orange line** under section heading or active nav/filter/tab — then deep blue or charcoal heading text.

**Use:** section intros · active navigation · selected filters · tariff page hero rule.

### 3.4 Deprecated patterns

Do **not** use: full-bleed dark-blue heroes · orange primary buttons · card-top orange as the only card brand element without blue footer · alternating orange sections.

---

## 4. Page patterns (colour rhythm)

### 4.1 Header

Almost entirely **white**.

| Zone | Treatment |
| --- | --- |
| Utility strip | Thin **royal blue** bar · white text · Yaoundé · hours · phone · FR \| EN |
| Main nav | White background · deep blue / royal logo and links |
| Active nav item | Short **orange underline** (micro band) |
| CTA “Rendez-vous & Suivi” | **Royal blue** button · white text · optional orange 3px bottom edge |

### 4.2 Homepage (conceptual rhythm)

| Zone | Background |
| --- | --- |
| 01 Hero | White editorial · real photo · deep blue headline · royal blue primary CTA |
| — | Orange band |
| — | Royal blue trust strip (2 centres · 7j/7 · agrément) |
| 02 Quick actions | White cards · royal blue icons · orange micro accents |
| 03 Slogan values | Soft blue or white · three editorial columns — not saturated boxes |
| 04 Journey | White · royal blue timeline · orange current step |
| 05 Tariff finder | Soft royal `#EEF5FC` · white navigator · royal tabs · orange active indicator |
| 06 Controls | White + photo · royal content panel · orange labels |
| 07 Centres | White · **card band** centre cards |
| 08 Team / proof | White or light grey · real staff photos |
| 09 Road safety | **Full royal blue field** · white text · orange headline accent |
| 10 Final CTA | White upper · orange divider · royal lower action bar |
| 11 Footer | Royal blue body · **4px orange top line** · deep blue copyright strip |

### 4.3 Other pages (summary)

| Page | Direction |
| --- | --- |
| **Tarifs** | Mostly white · prices in deep blue/charcoal · selected tab royal + white · orange = active/official marker |
| **Rendez-vous** | Progress: completed royal · **current orange** · future grey |
| **À propos** | White hero + photo · orange line · royal authority panel (agrément) · white editorial sections |
| **Visite technique** | White clarity · royal side panels for equipment/process · orange caution on counter-visit |
| **Sécurité routière** | Royal dominates · white/orange bridge · orange as safety marker only |
| **Contact** | White · intent cards white with royal icons · selected = orange line + royal border · centre cards use **card band** |

---

## 5. Typography

Unchanged from prior spec — Manrope (600, 700) for display; Inter (400, 500, 600) for body/UI. Subset only (NFR-P-05).

| Token | Use |
| --- | --- |
| `display-xl` / `display-lg` | Hero and page H1 — colour `--g3-blue-deep` on white heroes |
| `body` | `--g3-charcoal` on white |
| `overline` | Eyebrows e.g. “VISITE TECHNIQUE AUTOMOBILE” — optional `--g3-orange` on white heroes |

Currency: Inter tabular figures · deep blue · never orange.

Full scale: see prior revision §3.2 in git history if needed; values unchanged.

---

## 6. Spacing, radius, shadow, icons

4px grid · container max **1200px** · breakpoints 320 / 640 / 1024 — unchanged.

| Icons | Rule |
| --- | --- |
| Library | Lucide ~2px stroke |
| Default | `--g3-royal` |
| On royal blue fields | white |
| Orange | Band accents and current-step markers only — not default icon fill |

---

## 7. Core components

States in [36-component-states.md](36-component-states.md).

| Component | Spec |
| --- | --- |
| **Primary button** | `--g3-royal` fill · white text · 44px min-height · optional 3px orange bottom edge |
| **Secondary button** | White · 1px `--g3-royal` border · royal text |
| **Header** | White sticky · utility strip royal · shadow-sm on scroll |
| **Nav active** | Royal or deep text · **orange underline** micro band |
| **Centre card** | Photo → white body → **card band** footer (signature) |
| **Live status pill** | Green open · grey closed · never red for normal closure |
| **Form field** | White · concrete border · **royal focus** + subtle blue halo · errors red · required marker orange optional |

---

## 8. Tailwind mapping (Phase VII)

```css
@theme {
    --font-display: 'Manrope', ui-sans-serif, system-ui, sans-serif;
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;

    --color-g3-white: #FFFFFF;
    --color-g3-wall: #F8FAFC;
    --color-g3-royal: #145DAA;
    --color-g3-blue-deep: #0B2F5B;
    --color-g3-blue-soft: #EEF5FC;
    --color-g3-orange: #F47721;
    --color-g3-orange-tint: #FFF2E8;
    --color-g3-charcoal: #1F2937;
    --color-g3-muted: #667085;
    --color-g3-border: #E5E7EB;
    --color-g3-metallic: #92979D;
    --color-g3-success: #168653;
    --color-g3-error: #D92D20;
    --color-g3-caution: #F4C430;
}
```

---

## 9. Artefact alignment note

Wireframes (37–58) and hi-fi (59–69) created before this revision may show dark-blue heroes or orange primary CTAs. **Interpret them through this document** — white heroes, royal CTAs, and band system take precedence. Refresh hi-fi colours on next design pass; structure and IA remain valid.

---

## 10. Exit criteria (step 34)

- [x] G3 Signature Safety Bands documented (full · card · micro)
- [x] Revised palette aligned with [01-baseline](../docs/01-baseline.md)
- [x] Homepage and page colour rhythm specified
- [x] Deprecated co-equal blue/orange patterns removed
- [x] Component and Tailwind token mapping updated

**Next:** Step 35 — Media guidelines (hero safe areas on white, not dark overlay).
