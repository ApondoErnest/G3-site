# 08 — Content

| Delivery | Phase I · step **8** · Gate 11 · Loaded from Phase VIII · step 144 |
| --- | --- |
| Previous · Next | [07-acceptance.md](07-acceptance.md) · [09-architecture.md](09-architecture.md) |

French is the editorial source. Do not invent items marked below. Glossary is mandatory for English UI/CMS labels.

## Gaps (do not invent)

| ID | Gap |
| --- | --- |
| Q-01 | Official tariff matrix |
| Q-02 | Services per centre (taxis, utilitaires, bus / PL) |
| Q-03 | Nomayos opening year |
| Q-04 | G3 inspection-control names |
| Q-05 | Documents per category |
| Q-06 | Official category codes |
| Q-07 | Logo, photos, video |
| Q-08 | Legal entity name |
| Q-09 | Production domain |
| Q-10 | Analytics / social accounts |
| Q-11 | SMTP |
| Q-12 | Named appointment owners |
| Q-13 | WhatsApp click-to-chat? |
| Q-14 | 2020 = agrément only (default yes) |

Agrément **scope** is closed: company-wide.

## Copy ready now

Slogan, agrément line, email, BP, proposition, hero FR (EN hero = suggestion until content owner signs).

## Media (all missing until ingested)

Brand, both centres (hero/exterior/reception/lane), team, equipment, inspection stills, VT video+poster. Keep masters in `assets/source/`; site uses conversions.

## Pages

Homepage **11 zones:** hero · live strip · actions · slogan values · journey · tariff · controls · centres · proof · road safety · CTA. Actions: RDV, suivi, tarif, centre, préparer (first two = same URL, different tab).

| Page | Question |
| --- | --- |
| Home | Can I act, and is G3 serious? |
| About | Who is G3? (no Nomayos year until Q-03) |
| Centres | Where? Live status, map, compare |
| Centre detail | This site’s hours, phones, gallery, CTA |
| Services | What — G3-validated only |
| Visite technique | How — plus video, not foreign MOT copy |
| Tarifs | Cost — empty state if unpublished |
| RDV | Request + track |
| Road safety | Evergreen; rain relevant to Cameroon |
| Contact | Intent then form |

Trust chips: 2 centres · 7j/7 · holidays open · Agrément N°0291 depuis 2020. Journey: Préparer · Accueil · Contrôle · Validation · Résultat.

## Glossary

No “MOT”. UI labels via PHP lang files; codes stay snake_case ([09-architecture.md](09-architecture.md)).

| FR | EN | Avoid |
| --- | --- | --- |
| Visite technique | Technical inspection | MOT as title |
| Contre-visite | Counter-visit | |
| Centre | Inspection centre | Garage |
| Agrément | Approval (with number) | License |
| Tarif (page) | Fees | Price list as H1 |
| Rendez-vous | Appointment (request) | Booking a slot |
| Demande reçue | Request received | Appointment confirmed |
| En traitement | Under review | |
| Confirmée | Confirmed | |
| Annulée | Cancelled | Canceled |
| Ouvert actuellement | Open now | |
| Jours fériés | Public holidays | Bank holidays |
| Pneumatiques | Tyres | Tires |
| Immatriculation | Registration | License plate |
| Nos centres | Our centres | centers |
| Suivre ma demande | Track my request | |

Add official category codes here when G3 supplies them.
