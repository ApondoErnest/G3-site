# 34 — Design system

| Delivery | Phase IV · step **34** |
| --- | --- |
| Specs | [01-baseline](../docs/01-baseline.md) · [02-charter](../docs/02-charter.md) · [05-quality](../docs/05-quality.md) · [09-architecture](../docs/09-architecture.md) |
| IA | [33-information-architecture.md](33-information-architecture.md) |
| Next | Step 35 — Media guidelines |

Visual foundation for wireframes (37–58), hi-fi (59–69), and public implementation (117–129). **No application CSS in this step** — tokens are defined here for consistent design artefacts; Tailwind theme wiring happens in Phase VII.

**Feel:** technical, premium-editorial, distinctly G3 — not a generic automotive template ([02-charter](../docs/02-charter.md)).

---

## 1. Design principles

| Principle | Application |
| --- | --- |
| **Accuracy before decoration** | Live data (hours, prices, status) is visually primary; marketing chrome is secondary. |
| **Safety Line signature** | Orange accent line marks G3-owned sections — recognisable without logo overload. |
| **Editorial clarity** | Generous whitespace, strong hierarchy, short action paths ([33-information-architecture.md](33-information-architecture.md)). |
| **Mobile first** | Layouts work at ~320px on a normal Cameroon connection (NFR-P-01, NFR-D-02). |
| **Bilingual tolerance** | Components accommodate ~30% longer English without breaking (NFR-B-03). |
| **Accessible by default** | WCAG 2.2 AA intent; orange never carries meaning alone (NFR-A-01, NFR-A-04). |

**Avoid:** stock garage imagery tropes, neon racing palettes, gradient-heavy SaaS kits, Flux/Laravel UI as the public chrome ([09-architecture](../docs/09-architecture.md)).

---

## 2. Colour tokens

Source of truth for hex values: [01-baseline](../docs/01-baseline.md). Semantic names below are for design and implementation.

### 2.1 Brand palette

| Token | CSS variable | Hex | Role |
| --- | --- | --- | --- |
| Deep blue | `--g3-blue-deep` | `#12315B` | Header/footer backgrounds, hero overlays, primary headings on light |
| Technical blue | `--g3-blue-tech` | `#1769B0` | Primary buttons, links, icon default, focus ring |
| Bright blue | `--g3-blue-bright` | `#1479CF` | Hover/active links, chart accents, secondary emphasis |
| Safety orange | `--g3-orange` | `#F47A20` | **Safety Line**, primary CTA, key highlights |
| Orange tint | `--g3-orange-tint` | `#FFF2E8` | Soft highlight backgrounds, alert/info panels |
| White | `--g3-white` | `#FFFFFF` | Page surface, cards |
| Soft blue | `--g3-blue-soft` | `#F1F6FB` | Alternate section background |
| Grey | `--g3-grey` | `#F6F7F9` | Page canvas, subtle borders |
| Metallic | `--g3-metallic` | `#92979D` | Disabled icons, decorative rules |
| Charcoal | `--g3-charcoal` | `#202B37` | Body text, H1–H3 on light |
| Muted | `--g3-muted` | `#667085` | Secondary text, captions, meta |
| Success | `--g3-success` | `#168653` | Open now, confirmed states |

### 2.2 Semantic aliases

| Semantic | Maps to | Usage |
| --- | --- | --- |
| `--g3-surface-page` | `--g3-grey` | Default page background |
| `--g3-surface-card` | `--g3-white` | Cards, modals, form panels |
| `--g3-surface-alt` | `--g3-blue-soft` | Band sections (journey, proof) |
| `--g3-text-primary` | `--g3-charcoal` | Body copy, labels |
| `--g3-text-secondary` | `--g3-muted` | Help text, timestamps |
| `--g3-text-inverse` | `--g3-white` | Text on deep blue |
| `--g3-border` | `#E4E7EC`* | Dividers, input borders |
| `--g3-border-strong` | `--g3-metallic` | Table headers, card outlines |
| `--g3-focus` | `--g3-blue-tech` | Keyboard focus ring (NFR-A-03) |
| `--g3-error` | `#C62828` | Form errors, alert borders — see [36-component-states.md](36-component-states.md) |

\*Derived neutrals for UI chrome; not brand colours.

### 2.3 Usage balance

Target composition on marketing pages ([01-baseline](../docs/01-baseline.md)):

| Family | Share | Notes |
| --- | ---: | --- |
| White + grey + soft blue | 60–65% | Air and readability |
| Blues | 20–25% | Structure, trust, navigation |
| Orange | 5–8% | Safety Line + one primary CTA per viewport — **never body text** (NFR-A-04) |

### 2.4 Colour rules

- **Orange is accent only** — Safety Line, primary CTA fill, icon emphasis, active tab indicator. Not paragraphs, not links, not form labels.
- **Status is not colour alone** — open/closed, appointment status: icon + label + colour (NFR-A-04).
- **Contrast** — charcoal on white and white on deep blue meet AA for normal text; muted text only for non-essential meta.
- **Dark sections** — deep blue bands with white text; Safety Line stays orange for brand continuity.

---

## 3. Typography

### 3.1 Typefaces

| Role | Family | Weights loaded | Fallback |
| --- | --- | --- | --- |
| **Display / headings** | **Manrope** | 600, 700 | system-ui, sans-serif |
| **Body / UI** | **Inter** | 400, 500, 600 | system-ui, sans-serif |

Subset only — no full family download (NFR-P-05). Implementation: self-hosted woff2 via Vite; no Google Fonts CDN in production.

**Note:** Laravel’s default shell currently uses Instrument Sans; replace with Manrope/Inter when public templates are built (Phase VII).

### 3.2 Type scale

Base size **16px** (`1rem`) on mobile; root unchanged on desktop.

| Token | Font | Size / line | Weight | Use |
| --- | --- | --- | --- | --- |
| `display-xl` | Manrope | 2.5rem / 1.15 | 700 | Hero headline (one per page) |
| `display-lg` | Manrope | 2rem / 1.2 | 700 | Page H1 |
| `heading-lg` | Manrope | 1.5rem / 1.25 | 600 | Section H2 |
| `heading-md` | Manrope | 1.25rem / 1.3 | 600 | Card titles, H3 |
| `heading-sm` | Manrope | 1.125rem / 1.35 | 600 | Subsections, widget titles |
| `body-lg` | Inter | 1.125rem / 1.6 | 400 | Lead paragraphs |
| `body` | Inter | 1rem / 1.6 | 400 | Default body |
| `body-sm` | Inter | 0.875rem / 1.5 | 400 | Meta, captions, footer |
| `label` | Inter | 0.875rem / 1.4 | 500 | Form labels, nav items |
| `button` | Inter | 0.9375rem / 1.2 | 600 | Buttons, tabs |
| `overline` | Inter | 0.75rem / 1.4 | 600 | Eyebrows, trust chips — uppercase, +0.04em tracking |

### 3.3 Typography rules

- One `display-xl` or `display-lg` per page — no competing heroes.
- Manrope for headings only; Inter for everything else (including button text).
- Maximum line length **~65ch** for long prose (road safety, visite technique).
- **English overflow:** allow heading line-breaks; avoid fixed-width labels in nav; test FR/EN pairs on the same wireframe (NFR-B-03).
- Agrément and slogans use `body-sm` or `overline` — never larger than the page H1.
- Currency amounts: Inter `heading-md` tabular figures; suffix `FCFA` in `body-sm`.

---

## 4. Safety Line

The **Safety Line** is G3’s signature motif — the visual shorthand for “Sécurité” in the brand. It separates G3 from generic automotive themes ([02-charter](../docs/02-charter.md)).

### 4.1 Definition

| Property | Value |
| --- | --- |
| Colour | Safety orange `#F47A20` (`--g3-orange`) |
| Default thickness | **4px** |
| Default length | **64px** (short accent) |
| Cap | Square (no rounded caps) |
| Orientation | Horizontal by default |

### 4.2 Variants

| Variant | Spec | When |
| --- | --- | --- |
| **Accent** | 64×4px, left-aligned | Below section eyebrows, above H2 |
| **Hero** | 96×4px, left-aligned | Hero content block, below headline or slogan |
| **Full bleed** | 100% width × 3px | Between major page bands (use sparingly — max 2 per page) |
| **Card top** | 100% width × 3px | Top edge of centre/tariff cards |
| **CTA anchor** | 48×4px, centred above primary button | Appointment landing, homepage closing CTA |
| **Vertical** | 4px wide × 32px | Optional pull-quote or timeline marker — rare |

### 4.3 Placement rules

**Use on:** section intros, hero, centre cards, tariff matrix header, road-safety article sections, confirmation screens.

**Do not use on:** body paragraphs, inline links, form fields, every list item, Filament admin (admin uses Filament chrome — step 70).

**Pairing:** Safety Line often appears with an **overline** label (e.g. “Nos centres”, “Tarifs officiels”) in muted or technical blue — line sits between overline and heading.

### 4.4 Examples (wireframe intent)

```text
[overline · NOS CENTRES]
━━━━━━━━━━━━━━━          ← Safety Line accent (64px)
Trouver un centre         ← heading-lg Manrope
```

```text
┌─────────────────────────────── card ───
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ card top
│  École de Police          [Ouvert]
│  ...
```

---

## 5. Spacing and layout

4px base grid.

| Token | Value | Typical use |
| --- | --- | --- |
| `--g3-space-1` | 4px | Tight icon gap |
| `--g3-space-2` | 8px | Inline spacing |
| `--g3-space-3` | 12px | Compact padding |
| `--g3-space-4` | 16px | Default component padding |
| `--g3-space-5` | 20px | Card padding (mobile) |
| `--g3-space-6` | 24px | Section gap (mobile) |
| `--g3-space-8` | 32px | Card padding (desktop) |
| `--g3-space-10` | 40px | Between components |
| `--g3-space-12` | 48px | Section padding (mobile) |
| `--g3-space-16` | 64px | Section padding (desktop) |
| `--g3-space-20` | 80px | Hero vertical padding |
| `--g3-space-24` | 96px | Major band separation |

### Layout

| Breakpoint | Width | Container |
| --- | --- | --- |
| Mobile | 320px–639px | 16px horizontal margin |
| Tablet | 640px–1023px | max-width 720px, 24px margin |
| Desktop | 1024px+ | max-width **1200px**, centred, 32px margin |

12-column grid on desktop; 4-column on mobile. Gutters: 24px desktop, 16px mobile.

---

## 6. Radius, shadow, border

| Token | Value | Use |
| --- | --- | --- |
| `--g3-radius-sm` | 6px | Inputs, chips |
| `--g3-radius-md` | 10px | Buttons, cards |
| `--g3-radius-lg` | 16px | Modals, hero media |
| `--g3-radius-full` | 9999px | Status pills, avatar |

| Token | Value | Use |
| --- | --- | --- |
| `--g3-shadow-sm` | `0 1px 2px rgba(32,43,55,0.06)` | Cards at rest |
| `--g3-shadow-md` | `0 4px 12px rgba(32,43,55,0.08)` | Dropdowns, sticky header |
| `--g3-shadow-lg` | `0 12px 32px rgba(32,43,55,0.12)` | Modals |

Borders: 1px solid `--g3-border` default; 2px `--g3-focus` on focus-visible (offset 2px).

---

## 7. Icons

| Rule | Detail |
| --- | --- |
| Library | **Lucide** (~2px stroke) public site ([01-baseline](../docs/01-baseline.md), [09-architecture](../docs/09-architecture.md)) |
| Default colour | Technical blue `#1769B0` |
| Emphasis | Safety orange for CTA-accompanying icons only |
| Size | 20px inline · 24px buttons · 32px feature tiles |
| Touch target | Minimum **44×44px** interactive area (NFR-A-08) |
| Custom | Bench/tester SVGs for equipment sections — match 2px stroke weight |

Filament admin uses native Filament/Heroicons — not Lucide — per step 70.

---

## 8. Core components (visual spec)

Detailed states in [36-component-states.md](36-component-states.md). Baseline appearance:

| Component | Spec |
| --- | --- |
| **Primary button** | Safety orange fill, white text, `radius-md`, min-height 44px; hover darken ~8%; focus ring |
| **Secondary button** | White fill, 1px `--g3-blue-tech` border, tech blue text |
| **Ghost / link** | Tech blue text, underline on hover; no orange |
| **Header** | White or deep blue variant; sticky on scroll; shadow-sm when scrolled |
| **Nav link** | Inter `label`; active = tech blue text + optional 2px bottom Safety Line |
| **Utility CTA** | Primary button styling; label “Rendez-vous & Suivi” |
| **Card** | White surface, `radius-md`, `shadow-sm`, optional card-top Safety Line |
| **Live status pill** | Success green + label “Ouvert actuellement” / icon; closed = muted + charcoal — not red |
| **Form field** | 44px height, `radius-sm`, border `--g3-border`; states in [36-component-states.md](36-component-states.md) |

---

## 9. Motion

| Rule | Detail |
| --- | --- |
| Duration | 150–200ms UI feedback; 300ms panel open |
| Easing | `ease-out` enter, `ease-in` exit |
| Respect | `prefers-reduced-motion: reduce` — disable non-essential transitions |
| Prohibited | Homepage hero video autoplay (NFR-P-04); parallax; page transition animations |

---

## 10. Tailwind mapping (implementation reference)

For Phase VII — wireframes reference token names only.

```css
@theme {
    --font-display: 'Manrope', ui-sans-serif, system-ui, sans-serif;
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;

    --color-g3-blue-deep: #12315B;
    --color-g3-blue-tech: #1769B0;
    --color-g3-blue-bright: #1479CF;
    --color-g3-orange: #F47A20;
    --color-g3-orange-tint: #FFF2E8;
    --color-g3-blue-soft: #F1F6FB;
    --color-g3-grey: #F6F7F9;
    --color-g3-charcoal: #202B37;
    --color-g3-muted: #667085;
    --color-g3-success: #168653;
}
```

Utility classes (illustrative): `font-display`, `text-g3-charcoal`, `bg-g3-orange`, `border-g3-border`.

---

## 11. Exit criteria (step 34)

- [x] Brand colour tokens documented with semantic roles and usage rules
- [x] Typography scale defined (Manrope + Inter)
- [x] Safety Line motif specified with variants and placement rules
- [x] Spacing, layout, radius, shadow, and icon rules aligned with NFRs
- [x] Traceability to baseline and charter

**Next:** Step 37 — Desktop wireframe · Accueil.
