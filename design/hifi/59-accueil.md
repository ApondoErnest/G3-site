# 59 — Hi-fi design · Accueil

| Delivery | Phase IV · step **59** |
| --- | --- |
| URL | `/fr/accueil` · `/en/home` |
| Wireframes | Desktop [37](../wireframes/desktop/37-accueil.md) · Mobile [48](../wireframes/mobile/48-accueil.md) |
| Preview | [59-accueil.html](59-accueil.html) |
| Next | Step 60 — Hi-fi · À propos |

Pixel-level visual specification for the homepage. Static HTML preview uses self-contained CSS — **not** production Blade/Tailwind (Phase VII).

---

## 1. Page canvas

| Property | Desktop (≥1024px) | Mobile (<640px) |
| --- | --- | --- |
| Background | `#F6F7F9` (`--g3-grey`) | same |
| Container | max 1200px, px 32px | px 16px |
| Section gap | 64px (`--g3-space-16`) | 48px (`--g3-space-12`) |
| Font stack | Manrope headings · Inter body | same |

---

## 2. Global chrome

### Header (sticky)

| Property | Value |
| --- | --- |
| Height | 72px |
| Background | `#FFFFFF` |
| Border bottom | 1px `#E4E7EC` |
| Scrolled | `box-shadow: 0 4px 12px rgba(32,43,55,0.08)` |
| Logo height | 36px |
| Nav | Inter 500 14px `#202B37`; active `#1769B0` + 2px bottom `#F47A20` |
| CTA | bg `#F47A20`, text white, h 44px, px 20px, radius 10px, hover `#D9691A` |
| Locale | 14px; current 600 `#202B37`; other `#667085` |

### Footer

| Property | Value |
| --- | --- |
| Background | `#12315B` |
| Text | `#FFFFFF` / muted `#92979D` for meta |
| Padding | 48px 0 |
| Links | `#FFFFFF` hover underline |

---

## 3. Zone specifications

### Z1 · Hero

| Element | Style |
| --- | --- |
| Layout desktop | 55/45 split; left pad 48px; min-height 520px |
| Layout mobile | Image top full-bleed; text block below on white |
| Left panel bg | `#12315B` (desktop) |
| Overline | Inter 600 12px uppercase `#92979D` letter-spacing 0.04em |
| Safety Line | 96×4px `#F47A20` |
| H1 | Manrope 700 40px/1.15 `#FFFFFF` (desktop) · `#202B37` (mobile) |
| Lead | Inter 400 18px `#F1F6FB` (desktop) · `#667085` (mobile) — proposition text |
| Primary btn | Orange fill · "Prendre rendez-vous" |
| Secondary btn | White outline 1px `#FFFFFF` text white (desktop) · outline blue (mobile) |
| Image | 16:9 cover, radius 16px right (desktop); placeholder `#F1F6FB` until Q-07 |

**Copy (locked):** proposition from baseline. H1 = `[content owner — hero FR]`; preview uses factual placeholder *Visite technique à Yaoundé*.

### Z2 · Live strip

| Element | Style |
| --- | --- |
| Band | bg `#F1F6FB`, py 24px, radius 16px |
| Cards | white, border 1px `#E4E7EC`, p 20px, flex 50/50 gap 24px |
| Centre name | Manrope 600 18px `#202B37` |
| Open pill | bg `#E8F5EE`, text `#168653`, icon + "Ouvert actuellement", radius full, px 12 py 4 |
| Meta | Inter 14px `#667085` |

### Z3 · Action grid

| Element | Style |
| --- | --- |
| Tiles | white card, shadow sm, radius 10px, p 20px, icon 32px `#1769B0` |
| Label | Inter 600 15px `#202B37` |
| Hover | shadow md, border `#1769B0` |
| Desktop | 5 equal columns gap 16px |
| Mobile | 2×2 + fifth full width |

Icons: Lucide — calendar, search, tag, map-pin, clipboard-list.

### Z4 · Slogan / values

| Element | Style |
| --- | --- |
| Alignment | centre |
| Slogan words | Manrope 700 28px `#12315B` · middle word `#1769B0` optional |
| Safety Line | 64px centred |
| Agrément | Inter 14px `#667085` |

### Z5 · Journey

| Element | Style |
| --- | --- |
| Background | white section card, py 40px |
| Overline + Safety Line | left-aligned |
| Timeline | horizontal line 2px `#E4E7EC`; nodes 12px circle `#1769B0` |
| Labels | Inter 500 14px under nodes |
| Link | "Préparer" step → visite technique |

### Z6 · Tariff teaser

| Element | Style |
| --- | --- |
| Layout | 50/50 white cards gap 24px |
| Finder | selects h 44px border `#E4E7EC`, radius 6px |
| Result panel | soft blue bg `#F1F6FB`, price Manrope 600 24px |
| Empty | muted message + ghost link contact |

### Z7 · Controls

| Element | Style |
| --- | --- |
| Grid | 5 cols desktop · 2 cols mobile |
| Item | icon circle 48px bg `#F1F6FB`, label Inter 14px |
| Labels | `[DATA]` placeholders until Q-04 |

### Z8 · Centres snapshot

| Element | Style |
| --- | --- |
| Cards | white, card-top Safety Line 3px orange, img 4:3 top radius 10px 10px 0 0 |
| Actions | secondary outline buttons inline |
| Link | "Voir tous nos centres" tech blue |

### Z9 · Proof

| Element | Style |
| --- | --- |
| Band | bg `#F6F7F9` full bleed |
| Trust chips | overline style, border 1px `#E4E7EC`, radius full, px 16 py 8 |

Chips: 2 centres · 7j/7 · Jours fériés ouverts · Agrément N°0291.

### Z10 · Road safety teaser

| Element | Style |
| --- | --- |
| Layout | 40/60 image + copy |
| Image | placeholder `#E4E7EC` 4:3 |
| CTA | ghost link `#1769B0` |

### Z11 · Closing CTA

| Element | Style |
| --- | --- |
| Band | bg `#12315B`, py 64px, centred |
| H2 | Manrope 600 24px white |
| Safety Line | 48px centred orange |
| Buttons | primary orange + secondary white outline |

---

## 4. Responsive breakpoints

| Breakpoint | Key changes |
| --- | --- |
| `<640px` | Hamburger menu; hero stack; live cards stack; actions 2×2 |
| `640–1023px` | Container 720px; 3-col actions wraps |
| `≥1024px` | Full desktop layout per wireframe 37 |

Preview HTML includes desktop layout + `@media (max-width: 639px)` overrides.

---

## 5. Accessibility checklist

- [x] Focus rings 2px `#1769B0` offset 2px on all interactives
- [x] Open/closed: icon + text + colour
- [x] Orange not used for body text
- [x] Touch targets ≥44px on mobile CTAs
- [x] `lang="fr"` on document

---

## 6. Exit criteria (step 59)

- [x] All 11 zones styled with design tokens
- [x] Desktop and mobile visual rules documented
- [x] Static HTML preview in `design/hifi/`
- [x] Matches wireframes 37/48 structure and IA handoffs
- [x] No production code in `resources/`

**Next:** Step 60 — Hi-fi · À propos.
