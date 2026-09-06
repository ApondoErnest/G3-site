# 38 — Desktop wireframe · À propos

| Delivery | Phase IV · step **38** |
| --- | --- |
| Viewport | Desktop · 1200px container |
| URL | `/fr/a-propos` · `/en/about` |
| Page job | Who is G3? · No Nomayos year until Q-03 |

Annotations: same key as [37-accueil.md](37-accueil.md) §1.

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · À propos = active nav                                           │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 PAGE HERO · compact · white                                           │
│ Breadcrumb: Accueil → À propos                                           │
│ [overline] À PROPOS DE G3 CONTROL                                        │
│ ━━━━━━━ Safety Line                                                      │
│ Qui est G3 Control ?                          [IMG optional team/brand]  │
│ [DATA] intro paragraphs FR/EN · company-wide agrément FR-CO-02            │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 MISSION · 2-col                                                       │
│ ┌────────────────── 60% ──────────────────┐ ┌──── 40% ────────────────┐ │
│ │ Proposition · activity · Yaoundé         │ │ [IMG] reception/lane    │ │
│ │ Deux centres. 7 jours sur 7… [DATA]      │ │                         │ │
│ └──────────────────────────────────────────┘ └─────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 AGRÉMENT · soft blue band                                             │
│ Agrément N°0291 depuis 2020 · company-wide · not per centre              │
│ [DATA] scope explanation · no extra 2020 history beyond agrément         │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 VALUES · 3 columns                                                    │
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐                          │
│ │ Sécurité    │ │ Simplicité  │ │ Confiance   │  slogan pillars [DATA]   │
│ └─────────────┘ └─────────────┘ └─────────────┘                          │
├──────────────────────────────────────────────────────────────────────────┤
│ Z5 TEAM · [DATA] display_publicly only FR-CN-05                          │
│ [overline] NOTRE ÉQUIPE · ━━━━━━━                                        │
│ ┌────┐ ┌────┐ ┌────┐ ┌────┐   or [EMP] team coming soon §8.36           │
│ │avatar│ │avatar│ │avatar│ │avatar│  name · role                         │
│ └────┘ └────┘ └────┘ └────┘                                              │
├──────────────────────────────────────────────────────────────────────────┤
│ Z6 CTA · deep blue                                                       │
│ [ Voir nos centres ]  [ Nous contacter ]  [ Prendre rendez-vous (P) ]    │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER compact                                                           │
└──────────────────────────────────────────────────────────────────────────┘
```

## Zones

| # | Zone | Notes |
| ---: | --- | --- |
| 1 | Hero | Single H1; no Nomayos opening year (Q-03) |
| 2 | Mission | CMS blocks FR-CN-01 |
| 3 | Agrément | FR-CO-02 |
| 4 | Values | Slogan words — visual only |
| 5 | Team | Unpublished members hidden |
| 6 | CTA | Handoffs → centres, contact, RDV |

**Next:** Step 39 — Centres hub.
