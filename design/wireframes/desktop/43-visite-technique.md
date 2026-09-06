# 43 — Desktop wireframe · Visite technique

| Delivery | Phase IV · step **43** |
| --- | --- |
| URL | `/fr/visite-technique` · `/en/technical-inspection` |
| Page job | **How** inspection works · prepare · not foreign MOT copy |

Annotations: [37-accueil.md](37-accueil.md) §1.

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · Visite technique = active                                       │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO                                                                  │
│ [overline] VISITE TECHNIQUE · ━━━━━━━                                    │
│ Comment se déroule la visite technique ?                                 │
│ G3 process · not MOT / foreign network copy                                │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 VIDEO · FR-MD-03 · poster + play · no autoplay                         │
│ ┌────────────────────────────────────────────────────────────────────┐   │
│ │              [ VIDEO POSTER 16:9 ]  ▶  Play                        │   │
│ │              Inspection feature 60–120s · lazy load                │   │
│ └────────────────────────────────────────────────────────────────────┘   │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 JOURNEY · horizontal 5 steps (same as home)                           │
│ Préparer → Accueil → Contrôle → Validation → Résultat                    │
│ ●────●────●────●────●  [DATA] step descriptions                          │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 PROCESS · 2-col sections [DATA] FR-CN-01                              │
│ ┌─ Section ─────────────────┐ ┌─ Section ─────────────────┐              │
│ │ Avant la visite           │ │ À l'arrivée               │              │
│ │ documents Q-05            │ │ accueil · immatriculation │              │
│ └───────────────────────────┘ └───────────────────────────┘              │
├──────────────────────────────────────────────────────────────────────────┤
│ Z5 DOCUMENTS · [DATA] Q-05 · per category links                          │
│ [overline] DOCUMENTS À PRÉVOIR · ━━━━━━━                                 │
│ list / accordion by vehicle category FR-VC                               │
├──────────────────────────────────────────────────────────────────────────┤
│ Z6 FAQ · [DATA] FR-CN-04 optional accordion                              │
├──────────────────────────────────────────────────────────────────────────┤
│ Z7 CTA · deep blue                                                       │
│ [ Prendre rendez-vous (P) ]  [ Consulter les tarifs ]                    │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Rules

- Terminology: *visite technique* / *technical inspection* — never “MOT” as H1.
- Video on this page primary; homepage links here for “Préparer”.

**Next:** Step 44 — Tarifs.
