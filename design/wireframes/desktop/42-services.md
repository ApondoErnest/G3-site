# 42 — Desktop wireframe · Services

| Delivery | Phase IV · step **42** |
| --- | --- |
| URL | `/fr/services` · `/en/services` |
| Page job | **What** G3 validates · which centres perform each service |

Annotations: [37-accueil.md](37-accueil.md) §1.

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · Services = active                                               │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO                                                                  │
│ [overline] NOS SERVICES · ━━━━━━━                                        │
│ Quels services G3 propose-t-il ?                                         │
│ Catalogue validé G3 uniquement · FR-SV-01                                │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 FILTER · optional                                                     │
│ [ Tous les centres ▼ ]  [ Rechercher un service… ]                       │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 SERVICE LIST · stacked cards (C) or 2-col grid                        │
│ ┌──────────────────────────────── (C) ────────────────────────────────┐  │
│ │ [icon] │ Visite technique · cat. X, Y, Z                           │  │
│ │        │ Summary [DATA] FR/EN                                       │  │
│ │        │ Disponible à: École de Police · Nomayos  [LIVE] FR-SV-05   │  │
│ │        │ [ En savoir plus ] [ Tarifs → ] [ Demander RDV (P) ]       │  │
│ └──────────────────────────────────────────────────────────────────────┘  │
│ ┌──────────────────────────────── (C) ────────────────────────────────┐  │
│ │ [icon] │ Service 2 … (unpublished hidden FR-SV-04)                  │  │
│ └──────────────────────────────────────────────────────────────────────┘  │
│ … repeat per published service Q-02                                      │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 EXPANDED DETAIL · inline or modal on “En savoir plus”                 │
│ Body [DATA] · documents link Q-05 · vehicle compatibility FR-SV-03       │
├──────────────────────────────────────────────────────────────────────────┤
│ Z5 EMPTY · [EMP] if no published services                                │
├──────────────────────────────────────────────────────────────────────────┤
│ Z6 CTA · [ Voir les tarifs ] · [ Prendre rendez-vous (P) ]               │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Rules

- Unpublished services never listed (FR-SV-04).
- Do not invent service list until Q-02 closed — wireframe shows structure with placeholders.

**Next:** Step 43 — Visite technique.
