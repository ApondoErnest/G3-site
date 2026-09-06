# 44 — Desktop wireframe · Tarifs

| Delivery | Phase IV · step **44** |
| --- | --- |
| URL | `/fr/tarifs` · `/en/fees` |
| Page job | **Cost** · published matrix only · never invented prices |

Annotations: [37-accueil.md](37-accueil.md) §1.

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · Tarifs = active                                                 │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO                                                                  │
│ [overline] TARIFS · ━━━━━━━  (not “Price list” as H1 EN glossary)        │
│ Tarifs de visite technique · version [LIVE] effective date FR-TA-04      │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 FINDER · [LIVE] same resolver as home FR-TA-05                        │
│ [ Recherche… ] [ Catégorie ▼ ] [ Centre ▼ ] [ Service ▼ ] [ Filtrer ]    │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 MATRIX · print-friendly FR-TA-06                                      │
│ ┌────────────────────────────────────────────────────────────────────┐   │
│ │ Catégorie      │ Validité   │ École de Police │ Nomayos │ XAF     │   │
│ │────────────────│────────────│─────────────────│─────────│─────────│   │
│ │ [DATA] row     │ …          │ ✓               │ ✓       │ 25 000  │   │
│ │ …              │            │                 │         │         │   │
│ └────────────────────────────────────────────────────────────────────┘   │
│ [ Imprimer ] [ Partager ] · print CSS must (suggestion PDF FR-TA-07)     │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 ROW DETAIL · expand or side panel on row select                       │
│ Notes FR/EN · centres · [ Demander RDV avec cette catégorie (P) ]        │
│ → /rendez-vous prefilled category + centre                               │
├──────────────────────────────────────────────────────────────────────────┤
│ Z5 EMPTY · FR-TA-09 · if no published version                            │
│ [EMP] honest message · no dummy numbers · [ Nous contacter ]             │
├──────────────────────────────────────────────────────────────────────────┤
│ Z6 LEGAL NOTE · [DATA] tariff disclaimer · validity dates                │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Rules

- Amounts: grouped XAF integers (e.g. `25 000 FCFA`).
- Draft/reviewed versions invisible (FR-TA-02).
- Empty catalogue → contact, not placeholders.

**Next:** Step 45 — Rendez-vous & Suivi.
