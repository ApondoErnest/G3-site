# 45 — Desktop wireframe · Rendez-vous & Suivi

| Delivery | Phase IV · step **45** |
| --- | --- |
| URL | `/fr/rendez-vous` · `/en/appointment` |
| Tabs | Demande (default) · Suivi (`?tab=suivi` / `?tab=track`) |

Annotations: [37-accueil.md](37-accueil.md) §1 · tab states §5 [36-component-states.md](../../36-component-states.md).

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · utility CTA may appear redundant — keep for consistency         │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 PAGE TITLE                                                            │
│ [overline] RENDEZ-VOUS · ━━━━━━━                                         │
│ Demander une visite ou suivre votre demande                              │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 TABS                                                                  │
│ ┌─────────────────────┬─────────────────────┐                            │
│ │ ● Demande (active)  │   Suivi             │  orange 3px bottom active  │
│ └─────────────────────┴─────────────────────┘                            │
├──────────────────────────────────────────────────────────────────────────┤
│ TAB A · DEMANDE · multi-step form [LIVE] Livewire                        │
│                                                                          │
│ Step indicator: 1 Centre → 2 Service → 3 Véhicule → 4 Créneau → 5 Contact → 6 Revue │
│ ┌────────────────────────────────────────────────────────────────────┐   │
│ │ STEP (example · Centre)                                            │   │
│ │ ( ) École de Police [OPEN]   ( ) Nomayos [OPEN]                    │   │
│ │ [ Suivant ]                                                        │   │
│ └────────────────────────────────────────────────────────────────────┘   │
│ Step 3: immatriculation · category [LIVE] FR-VC                          │
│ Step 4: date + period · validated vs centre hours BR-APPT-002            │
│ Step 5: nom · téléphone · email opt · channel FR-AP-10                   │
│ Step 6: review summary · purpose notice [05-quality] · [ Envoyer (P) ]   │
│                                                                          │
│ SUCCESS · not “confirmed slot”                                           │
│ ✓ Demande reçue · Référence G3-26-XXXXX · FR-AP-04                      │
├──────────────────────────────────────────────────────────────────────────┤
│ TAB B · SUIVI · ?tab=suivi                                               │
│ ┌────────────────────────────────────────────────────────────────────┐   │
│ │ Référence [________]  Téléphone [________]  — ou —  Immat. [____]   │   │
│ │ [ Suivre ma demande (P) ]                                           │   │
│ └────────────────────────────────────────────────────────────────────┘   │
│ RESULT · timeline §7.2 · customer-safe statuses only FR-TR-04            │
│ ● received → under_review → confirmed → completed                        │
│ ERR · generic alert if mismatch BR-TRACK-003 · rate limited FR-TR-05     │
│ EMP · before search §8 empty pattern                                     │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Rules

- No numbered slot calendar · request only (FR-AP-01).
- Reference + phone **or** plate required (FR-TR-02).
- Prefills from tarifs/centres/services via query or session.

**Next:** Step 46 — Sécurité routière.
