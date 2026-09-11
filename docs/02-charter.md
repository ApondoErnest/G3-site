# 02 — Charter

| Delivery | Phase I · step **2** · Gate 11 |
| --- | --- |
| Previous · Next | [01-baseline.md](01-baseline.md) · [03-scope.md](03-scope.md) |

| | |
| --- | --- |
| Name | G3 Control Digital Platform |
| Kind | Technical-inspection platform (not a brochure) |
| Sponsor | _TBD — G3 management_ |
| Technical owner | _TBD_ |
| Status | Accepted — Gate 11 signed off 2026-09-03 |

Facts: [01-baseline.md](01-baseline.md). Pages: [03-scope.md](03-scope.md).

## Problem

Visitors cannot, from one trustworthy place: see two centres open 7j/7 including holidays; get hours and phones; understand the visit; see official tariffs; request a visit and check its status. Staff have no single admin for hours, prices, and requests.

## Outcome

Credibility, clear centres and process, transparent tariffs, appointment **requests** + tracking, road safety, admin so nobody edits Blade to change a phone number.

Feel: technical, premium-editorial, G3 Signature Safety Bands (white · orange band · royal blue), not a generic dark-navy-and-orange automotive theme.

## Success (not “the site is online”)

- Baseline facts in admin, not hardcoded; one schedule change updates home, centre pages, and booking.
- Versioned tariffs; public sees only the effective published version.
- Request → reference → “demande reçue”; tracking needs reference + phone or plate; generic failure if mismatch.
- FR and EN complete; switch stays on the equivalent page.
- Mobile usable on a normal Cameroon connection.
- Reproducible deploy; off-server backup + restore test; monitoring before launch.

## Principles

Accuracy before marketing · one source of truth · mobile first · bilingual from day one · one module at a time · real G3 media · Docker only after local stability.

## Who decides

Developers do not invent tariffs, terminology, or agrément wording. Fill names before publish.

| Decision | Role |
| --- | --- |
| Agrément, slogans, go-live, scope, tariffs, services | Ownership |
| Addresses, phones, GPS, hours, exceptions, confirming requests | Ownership + centre manager |
| Inspection copy, documents | Technical (+ reception) |
| FR/EN copy, photos, video | Content (EN uses [08-content.md](08-content.md) glossary) |
| Road-safety claims | Content + technical |
| Stack | Implementer, informed to ownership |

**Suggestion:** named admin users from day one of Filament; one shared Super Admin password is not acceptable.

## Exit

Gate 11 complete (2026-09-03). Phase II open. G3 fills the decision matrix before public content ships.
