# 40 — Desktop wireframe · École de Police

| Delivery | Phase IV · step **40** |
| --- | --- |
| URL | `/fr/centres/ecole-de-police` · `/en/centres/ecole-de-police` |
| Page job | Everything to visit **this** centre today |

Shared layout with [41-centre-nomayos.md](41-centre-nomayos.md) — centre-specific `[DATA]` below.

Annotations: [37-accueil.md](37-accueil.md) §1.

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · Nos centres = active (breadcrumb carries centre name)           │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO · split                                                          │
│ Breadcrumb: Accueil → Nos centres → École de Police                      │
│ ┌──────────────── 55% ────────────────┐ ┌──────── 45% ────────────────┐ │
│ │ École de Police          [OPEN LIVE] │ │ [IMG HERO centre exterior]  │ │
│ │ ━━━━━━━                              │ │                             │ │
│ │ Descente ancien Texaco, École de Police                             │ │
│ │ [ Appeler ] [ Itinéraire ] [ RDV ici (P) ]                          │ │
│ └──────────────────────────────────────┘ └─────────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 STATUS + HOURS · 2-col [LIVE]                                         │
│ ┌─────────────────────┐ ┌─────────────────────────────────────────────┐ │
│ │ Ouvert actuellement │ │ Horaires · Lun–Sam 07:00–20:00              │ │
│ │ Fermeture 20:00     │ │ Dim 07:00–15:00 · Jours fériés: ouvert      │ │
│ │ Prochaine ouverture │ │ Exceptions calendar [LIVE] if any           │ │
│ └─────────────────────┘ └─────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 CONTACT · 3-col                                                       │
│ ☎ 687 187 516 · g3sarl1@gmail.com · BP 12775 Yaoundé                     │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 MAP · lazy · GPS 3.8786152, 11.5116814                                │
│ ┌────────────────────────────────────────────────────────────────────┐   │
│ │ [ MAP single pin ]  [ Ouvrir l'itinéraire ]                         │   │
│ └────────────────────────────────────────────────────────────────────┘   │
├──────────────────────────────────────────────────────────────────────────┤
│ Z5 GALLERY · [DATA] or [EMP] photos Q-07                                 │
│ [overline] LE CENTRE · ━━━━━━━                                           │
│ [img][img][img][img][img][img]  → lightbox                               │
├──────────────────────────────────────────────────────────────────────────┤
│ Z6 SERVICES HERE · [LIVE] FR-SV-05                                       │
│ Services disponibles à ce centre · list linked services · unpublished hidden│
├──────────────────────────────────────────────────────────────────────────┤
│ Z7 OTHER CENTRE · teaser card → Nomayos (C)                              │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Centre facts (locked)

| Field | Value |
| --- | --- |
| Landmark | Descente ancien Texaco, École de Police |
| Phone | 687 187 516 |
| Mon–Sat | 07:00–20:00 |
| Sun | 07:00–15:00 |

**Next:** Step 41 — Nomayos (mirror layout).
