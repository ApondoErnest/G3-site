# 47 — Desktop wireframe · Contact

| Delivery | Phase IV · step **47** |
| --- | --- |
| URL | `/fr/contact` · `/en/contact` |
| Page job | Route by **intent** · then form or deep-link |

Annotations: [37-accueil.md](37-accueil.md) §1 · intent tiles §6.3 [36-component-states.md](../../36-component-states.md).

---

## Full-page layout

```text
┌────────────────────────────────────────────────────────────── 1200px ──┐
│ HEADER · Contact = active                                                │
├──────────────────────────────────────────────────────────────────────────┤
│ Z1 HERO                                                                  │
│ [overline] CONTACT · ━━━━━━━                                             │
│ Comment pouvons-nous vous aider ?                                        │
├──────────────────────────────────────────────────────────────────────────┤
│ Z2 INTENT SELECT · FR-CT-01 · 4 tiles (C) · selected = blue border     │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐     │
│ │ Rendez-vous  │ │ Centre       │ │ Tarifs       │ │ Assistance   │     │
│ │              │ │              │ │              │ │ générale     │     │
│ └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘     │
│ Fast path: RDV intent → suggest → /rendez-vous · Tarifs → /tarifs       │
├──────────────────────────────────────────────────────────────────────────┤
│ Z3 FORM · 2-col · shown after intent (or default general)                │
│ ┌──────────────────────── 60% ────────────────────────┐ ┌─ 40% ────────┐ │
│ │ Nom [____]  Téléphone [____]  Email [____]          │ │ Coordonnées │ │
│ │ Objet [ pre-filled from intent ]                    │ │ g3sarl1@…   │ │
│ │ Centre [ optional ▼ ]                               │ │ BP 12775    │ │
│ │ Message [ textarea ]                                │ │ 2 centres   │ │
│ │ honeypot hidden · FR-CT-05                          │ │ phones      │ │
│ │ purpose notice [05-quality suggestion]              │ │             │ │
│ │ [ Envoyer (P) ]                                     │ │             │ │
│ └─────────────────────────────────────────────────────┘ └─────────────┘ │
├──────────────────────────────────────────────────────────────────────────┤
│ Z4 SUCCESS · confirmation message · no account created                   │
├──────────────────────────────────────────────────────────────────────────┤
│ FOOTER                                                                   │
└──────────────────────────────────────────────────────────────────────────┘
```

## Rules

- Intent first (FR-CT-01): appointment · centre · tariffs · assistance.
- Rate limit + honeypot (FR-CT-05).
- Deep-link when faster than duplicating flows.

**Next:** Step 48 — Mobile wireframe · Accueil.
