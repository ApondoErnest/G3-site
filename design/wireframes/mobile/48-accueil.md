# 48 — Mobile wireframe · Accueil

| Delivery | Phase IV · step **48** |
| --- | --- |
| Viewport | **Mobile** · 320–639px · 16px margins |
| URL | `/fr/accueil` · `/en/home` |
| Desktop | [37-accueil.md](../desktop/37-accueil.md) |

Same 11 zones as desktop — **single column stack**.

---

## Layout

```text
┌──────────────────────── 100% ────────────────────────┐
│ MOBILE HEADER + menu overlay (see wireframes README)   │
├──────────────────────────────────────────────────────┤
│ Z1 HERO · full-bleed image under text overlay          │
│ [IMG HERO full width 16:9]                           │
│ [overline] · ━━━━━━━ · headline · proposition        │
│ [ RDV (P) full width ] [ Suivi full width secondary ] │
├──────────────────────────────────────────────────────┤
│ Z2 LIVE STRIP · stacked · [LIVE]                     │
│ ┌ École de Police [OPEN] · closes 20:00 ──→ detail ┐ │
│ └───────────────────────────────────────────────────┘ │
│ ┌ Nomayos [OPEN] · closes 19:00 ──────────→ detail ┐ │
├──────────────────────────────────────────────────────┤
│ Z3 ACTIONS · 2×2 grid + 5th full width             │
│ ┌ RDV ┐ ┌ Suivi ┐                                     │
│ └─────┘ └──────┘                                     │
│ ┌ Tarifs ┐ ┌ Centre ┐                                 │
│ └────────┘ └───────┘                                 │
│ [ Préparer · full width row ]                        │
├──────────────────────────────────────────────────────┤
│ Z4 SLOGAN · centred · 3 lines or stacked words       │
│ Agrément line                                        │
├──────────────────────────────────────────────────────┤
│ Z5 JOURNEY · vertical or horizontal scroll           │
│ ●→●→●→●→●  labels below · swipe OK                   │
├──────────────────────────────────────────────────────┤
│ Z6 TARIFF · stacked                                  │
│ finder full width · result card below [LIVE/EMP]     │
├──────────────────────────────────────────────────────┤
│ Z7 CONTROLS · 2-col icon grid                        │
├──────────────────────────────────────────────────────┤
│ Z8 CENTRES · cards stacked full width (C)            │
├──────────────────────────────────────────────────────┤
│ Z9 PROOF · trust chips wrap · 2 per row              │
├──────────────────────────────────────────────────────┤
│ Z10 ROAD SAFETY · image top · text · link            │
├──────────────────────────────────────────────────────┤
│ Z11 CTA · full width primary                         │
├──────────────────────────────────────────────────────┤
│ FOOTER · stacked blocks · centre links · utility     │
└──────────────────────────────────────────────────────┘
```

## Mobile deltas

| vs desktop | Change |
| --- | --- |
| Nav | Hamburger; CTA inside menu + sticky optional bottom bar **suggestion** |
| Hero | Image above or behind text; CTAs stack full width |
| Live strip | Vertical cards, not side-by-side |
| Actions | 2×2 + fifth row |
| Journey | Horizontal scroll if 5 steps don't fit |

**Next:** Step 49 — Mobile · À propos.
