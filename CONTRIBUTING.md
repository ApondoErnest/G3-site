# Contributing

## Delivery model

All work follows [PLAN.md](PLAN.md) in chronological order.

### Lifecycle (never reorder)

1. **Specification** — docs signed off (Phase I)
2. **Environment** — workstation and Git (Phase II)
3. **Application shell** — empty Laravel (Phase III)
4. **Experience design** — wireframes and hi-fi before any migration (Phase IV)
5. **Database design** — ERD on paper (Phase V · steps 72–76)
6. **Backend design** — use cases and state machines on paper (Phase V · steps 77–85)
7. **Database build** — migrations and seeds (Phase VI · steps 86–97)
8. **Backend build** — domain modules and Pest (Phase VI · steps 98–107)
9. **Administration UI** — Filament before public pages (Phase VI · steps 108–116)
10. **Public frontend** — one page at a time (Phase VII)
11. **Quality assurance** — integration, tests, UAT (Phase VIII)
12. **Containerisation** — after local core stable (Phase IX)
13. **Production** — VPS, backups, launch (Phase X)

Exactly **one active step** at a time. A phase opens only after its **gate** is complete.

| Layer | Phase · Step |
| --- | --- |
| Laravel shell | III · 24 |
| Database design | V · 73 |
| Migrations | VI · 86 |
| Backend modules | VI · 98 |
| Filament admin | VI · 108 |
| Public pages | VII · 117 |
| Docker | IX · 151 |
| Production VPS | X · 160 |

## Branching

| Branch | Purpose |
| --- | --- |
| `main` | Release-ready |
| `develop` | Integration |
| `feature/<module>` | Scoped work aligned to the active plan step |

## Architecture

Business rules belong in **use cases and domain services**, not in Blade views or Filament form classes. Public Livewire and Filament admin must invoke the same application layer.
