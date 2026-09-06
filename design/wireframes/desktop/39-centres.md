# 39 — Desktop wireframe · Nos centres

| Delivery | Phase IV · step **39** |
| --- | --- |
| URL | `/fr/centres` · `/en/centres` |
| Page job | Where? · Compare · live status · pick a centre |

Annotations: [37-accueil.md](37-accueil.md) §1.

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · Nos centres = active                                            │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO                                                                  │
│ [overline] NOS CENTRES · ━━━━━━━                                         │
│ Où effectuer votre visite technique ?                                      │
│ Deux centres à Yaoundé · 7j/7 · jours fériés ouverts                     │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 COMPARE STRIP · [LIVE] FR-CE-07/08                                    │
│ ┌─────────────────────────────┐  ┌─────────────────────────────┐         │
│ │ École de Police   [OPEN]    │  │ Nomayos           [OPEN]    │         │
│ │ Lun–Sam 07–20 · Dim 07–15   │  │ Lun–Sam 07–19 · Dim 07–15   │         │
│ │ Prochaine fermeture …       │  │ Prochaine fermeture …       │         │
│ └─────────────────────────────┘  └─────────────────────────────┘         │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 MAP · lazy Leaflet [LIVE] GPS both pins FR-CE-09                       │
│ ┌────────────────────────────────────────────────────────────────────┐   │
│ │                        [ MAP OSM ]                                  │   │
│ │     pin École de Police              pin Nomayos                    │   │
│ └────────────────────────────────────────────────────────────────────┘   │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 CENTRE CARDS · 2-col (C)                                              │
│ ┌────────────────────────────── (C) ──────────────────────────────┐      │
│ │ [IMG 16:9] │ École de Police                        [OPEN pill]  │      │
│ │            │ Descente ancien Texaco, École de Police             │      │
│ │            │ ☎ 687 187 516                                       │      │
│ │            │ [ Détail → ] [ Appeler ] [ Itinéraire ] [ RDV (P) ] │      │
│ └──────────────────────────────────────────────────────────────────┘      │
│ ┌────────────────────────────── (C) ──────────────────────────────┐      │
│ │ [IMG] │ Nomayos · Carrefour Nomayos · 2 phones · same actions   │      │
│ └──────────────────────────────────────────────────────────────────┘      │
├──────────────────────────────────────────────────────────────────────────┤
│ Z5 ALERTS · optional [LIVE] FR-CE-11                                    │
│ [ info banner ] exceptional closure / operational message                │
├──────────────────────────────────────────────────────────────────────────┤
│ Z6 CTA · Which centre suits you? → compare above · [ RDV (P) ]           │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Handoffs

| Action | Target |
| --- | --- |
| Détail | `/centres/ecole-de-police` or `/centres/nomayos` |
| RDV | `/rendez-vous` with centre prefill |
| Itinéraire | external maps app (Leaflet lazy NFR-P-04) |

**Next:** Steps 40–41 — Centre detail pages.
