# 37 — Desktop wireframe · Accueil

| Delivery | Phase IV · step **37** |
| --- | --- |
| Viewport | **Desktop** · 1024px–1440px (container max 1200px) |
| URL | `/fr/accueil` · EN equivalent `/en/home` (mirror layout) |
| Page job | Can I act, and is G3 serious? ([33-information-architecture.md](../33-information-architecture.md)) |
| Zones | 11 ([08-content.md](../../docs/08-content.md)) |
| Next | Step 38 — Desktop wireframe · À propos |

Lo-fi structure only — no colour, typography, or final copy beyond locked baseline facts. Hi-fi: step 59.

---

## 1. Annotations key

| Mark | Meaning |
| --- | --- |
| `[IMG]` | Photo placeholder per [35-media-guidelines.md](../35-media-guidelines.md) |
| `[LIVE]` | Livewire · live domain data (hours, open/closed) |
| `[DATA]` | CMS / admin settings |
| `→` | Navigation handoff |
| `(P)` | Primary button state §2.1 [36-component-states.md](../36-component-states.md) |
| `(C)` | Clickable card §6.2 |

---

## 2. Full-page layout

```text
┌─────────────────────────────────────────────────────────────────────────────── 1200px ───┐
│ HEADER [sticky]                                                                              │
│ [Logo G3]   Accueil  À propos  Nos centres  Services  VT  Tarifs  Sécurité  Contact         │
│                                                    [ Rendez-vous & Suivi (P) ]    FR | EN   │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO · deep blue band or split                                                            │
│ ┌────────────────────────────── 55% ──────────────────────────┐ ┌────── 45% ──────────────┐ │
│ │ [overline] VISITE TECHNIQUE · YAOUNDÉ                        │ │                         │ │
│ │ ━━━━━━━ Safety Line 96px                                     │ │   [IMG HERO 16:9]       │ │
│ │ [Hero headline — FR content owner]                           │ │   centre exterior       │ │
│ │ Deux centres. 7 jours sur 7. Une même exigence de sécurité.  │ │                         │ │
│ │                                                              │ │                         │ │
│ │ [ Prendre rendez-vous (P) ]  [ Suivre ma demande ]           │ └─────────────────────────┘ │
│ └──────────────────────────────────────────────────────────────┘                             │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z2 LIVE STRIP · bg soft blue · full width inside container                                   │
│ ┌─────────────────────────────┐    ┌─────────────────────────────┐                          │
│ │ École de Police    [OPEN]   │    │ Nomayos            [OPEN]   │  [LIVE] FR-CE-07/08      │
│ │ Ouvert · fermeture 20:00    │    │ Ouvert · fermeture 19:00    │                          │
│ │ → centre detail             │    │ → centre detail             │                          │
│ └─────────────────────────────┘    └─────────────────────────────┘                          │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z3 ACTION GRID · 5 tiles · single row                                                        │
│ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐                                      │
│ │ icon   │ │ icon   │ │ icon   │ │ icon   │ │ icon   │                                      │
│ │ RDV    │ │ Suivi  │ │ Tarifs │ │ Centre │ │Préparer│  (C) handoffs → IA §3.2              │
│ └────────┘ └────────┘ └────────┘ └────────┘ └────────┘                                      │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z4 SLOGAN / VALUES                                                                           │
│        Sécurité.    Simplicité.    Confiance.          [DATA] slogan FR                      │
│        ━━━━━━━ Safety Line accent                                                            │
│        Agrément N°0291 depuis 2020 · company-wide [DATA]                                     │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z5 JOURNEY · 5 steps · horizontal                                                            │
│  [overline] VOTRE PARCOURS                                                                   │
│  ━━━━━━━                                                                                     │
│  ●──────────●──────────●──────────●──────────●                                               │
│ Préparer   Accueil    Contrôle   Validation  Résultat                                        │
│ → /visite-technique                                                                          │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z6 TARIFF TEASER · 2-col                                                                 │
│ ┌──────────────────────────── 50% ────────────────────────┐ ┌──────── 50% ────────────────┐ │
│ │ [overline] TARIFS                                        │ │ [LIVE] price or [EMP]       │ │
│ │ ━━━━━━━                                                  │ │                             │ │
│ │ Trouver un tarif                                         │ │ 25 000 FCFA                 │ │
│ │ [ Category ▼ ] [ Type ▼ ]  [ Afficher ]                  │ │ Catégorie · validité        │ │
│ │ same resolver as /tarifs FR-TA-05                         │ │ [ Demander RDV (P) ]        │ │
│ │ → /tarifs · prefilled handoff                             │ │ or empty → contact link     │ │
│ └──────────────────────────────────────────────────────────┘ └─────────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z7 CONTROLS · inspection scope                                                             │
│ [overline] CONTRÔLES G3                                                                      │
│ ━━━━━━━                                                                                      │
│ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐   [DATA] names Q-04 — do not invent           │
│ │ icon │ │ icon │ │ icon │ │ icon │ │ icon │   icons: Lucide or custom SVG                 │
│ │ ctrl │ │ ctrl │ │ ctrl │ │ ctrl │ │ ctrl │                                               │
│ └──────┘ └──────┘ └──────┘ └──────┘ └──────┘                                               │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z8 CENTRES SNAPSHOT · 2 cards                                                              │
│ [overline] NOS CENTRES · ━━━━━━━ · [ Voir tous → /centres ]                                 │
│ ┌────────────────────────────── (C) ──────────────────────────┐ ┌──────────────────────────┐ │
│ │ [IMG 4:3] │ École de Police              [OPEN pill]        │ │ [IMG] │ Nomayos  [OPEN]   │ │
│ │           │ Descente ancien Texaco…                         │ │       │ Carrefour Nomayos │ │
│ │           │ [ Appeler ] [ Itinéraire ] [ Détail → ]         │ │       │ same actions      │ │
│ └─────────────────────────────────────────────────────────────┘ └──────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z9 PROOF / TRUST · bg grey band                                                              │
│  ( 2 centres )  ( 7j/7 )  ( Jours fériés ouverts )  ( Agrément N°0291 )   trust chips       │
│  ┌──────────────────────── optional ────────────────────────┐                                │
│  │ [IMG equipment]  Équipement de contrôle homologué …       │  omit if no media Q-07       │
│  └──────────────────────────────────────────────────────────┘                                │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z10 ROAD SAFETY TEASER · 2-col                                                               │
│ ┌──────────────────── 40% ────────────────┐ ┌──────────── 60% ─────────────────────────────┐ │
│ │ [IMG rain/tyres]                         │ │ [overline] SÉCURITÉ ROUTIÈRE               │ │
│ │                                          │ │ ━━━━━━━                                    │ │
│ │                                          │ │ Conduire sous la pluie à Yaoundé …         │ │
│ │                                          │ │ [ Lire les conseils → /securite-routiere ] │ │
│ └──────────────────────────────────────────┘ └────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ Z11 CLOSING CTA · deep blue band                                                             │
│              Prêt pour votre visite technique ?                                              │
│              ━━━━━━━ CTA anchor line                                                         │
│              [ Prendre rendez-vous (P) ]    [ Nous contacter ]                               │
├──────────────────────────────────────────────────────────────────────────────────────────────┤
│ FOOTER compact                                                                               │
│ G3 Control · Agrément · slogan    |    Centres links    |    RDV · Suivi · Tarifs · Contact   │
│ g3sarl1@gmail.com · BP 12775 Yaoundé                              FR | EN                    │
└──────────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Zone specification

| # | Zone | Height (approx) | Content source | Notes |
| ---: | --- | --- | --- | --- |
| 1 | **Hero** | 480–560px | `[DATA]` hero FR · baseline proposition | No autoplay video (NFR-P-04). Headline area = left 40% safe zone over image if overlay variant used. |
| 2 | **Live strip** | 96px | `[LIVE]` centre availability service | Same state as centre pages at same timestamp (FR-CE-08). Pills: §7.1 component states. |
| 3 | **Actions** | 120px | PHP lang labels | RDV → `/rendez-vous` · Suivi → `?tab=suivi` · Tarifs → `/tarifs` · Centre → `/centres` · Préparer → `/visite-technique`. |
| 4 | **Slogan / values** | 160px | `[DATA]` company settings | Three slogan words may be typographic emphasis — not three separate pages. |
| 5 | **Journey** | 140px | `[DATA]` content block | Step 1 links to visite technique; others informational on home. |
| 6 | **Tariff teaser** | 220px | `[LIVE]` tariff resolver | Empty if no published version (FR-TA-09) — hide price panel, show EMP §8. |
| 7 | **Controls** | 180px | `[DATA]` CMS | Placeholder labels until Q-04 closed. Max 5–6 items. |
| 8 | **Centres snapshot** | 280px | `[LIVE]` + `[DATA]` | Hub handoff. Cards use `(C)` hover/focus states. |
| 9 | **Proof** | 120–200px | `[DATA]` + trust chips | Chips from [08-content.md](../../docs/08-content.md). Optional equipment strip. |
| 10 | **Road safety** | 200px | `[DATA]` featured section | Rotate evergreen topic; rain default for Cameroon. |
| 11 | **Closing CTA** | 160px | PHP lang | One primary `(P)` per viewport section — second CTA is secondary outline. |

---

## 4. Header and footer detail

### Header (desktop)

| Element | Spec |
| --- | --- |
| Height | 72px |
| Background | white; `shadow-md` when scrolled |
| Nav | 7 links; **Accueil** = active (2px orange bottom) |
| CTA | Primary button “Rendez-vous & Suivi” |
| Locale | FR \| EN top-right |

### Footer

Per [33-information-architecture.md](../33-information-architecture.md) §2.3 — three columns + utility links. No legal nav.

---

## 5. Responsive note (for step 48)

This document is **desktop only**. Mobile Accueil (step 48) will stack: hero full-bleed → live strip vertical → actions 2×2 grid (+ fifth full width) → remaining zones single column.

---

## 6. Data and behaviour

| Feature | Requirement |
| --- | --- |
| Live status | FR-CE-07, FR-CE-08, BR-CENT-* |
| Tariff finder | FR-TA-05 — identical result to `/tarifs` for same inputs |
| Agrément | FR-CO-02 — company-wide, not per centre |
| Hero / controls copy | Do not invent — Q-04, Q-07, hero EN pending content sign-off |
| Alerts | `[LIVE]` optional FR-CE-11 operational banner above header when active |

---

## 7. Wireframe checklist

- [x] All 11 homepage zones present in order
- [x] Global header, utility CTA, locale switch
- [x] Five action tiles with correct handoffs
- [x] Live centre strip with two centres
- [x] Tariff teaser with empty-state path
- [x] Trust chips and journey steps
- [x] Compact footer
- [x] Annotations for LIVE/DATA boundaries
- [x] Desktop container 1200px aligned with design system

**Next:** Step 38 — Desktop wireframe · À propos.
