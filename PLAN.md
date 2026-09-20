# G3 Control — Master Implementation Plan

Authoritative delivery sequence for the G3 Control digital platform.

| | |
| --- | --- |
| Product | Bilingual public website + secure operations admin |
| Method | Ten gated phases · 182 sequential steps · one active step |
| Specifications | [`docs/`](docs/README.md) |
| Governing principle | Design → document → implement → test → approve → advance |

Requirements, architecture, and locked facts live in `docs/`. This document defines **order of work only**.

---

## Delivery spine

One path from project creation to a complete, production website. Each layer starts only after the previous layer’s gate is accepted.

```mermaid
flowchart TD
  A[Phase I · Specification] --> B[Phase II · Environment]
  B --> C[Phase III · Application shell]
  C --> D[Phase IV · Experience design]
  D --> E[Phase V · Database and domain design]
  E --> F[Phase VI · Schema migrations]
  F --> G[Phase VI · Backend services]
  G --> H[Phase VI · Administration UI]
  H --> I[Phase VII · Public frontend]
  I --> J[Phase VIII · Quality assurance]
  J --> K[Phase IX · Containerisation]
  K --> L[Phase X · Production and operations]
```

| Order | Layer | Phase · steps | Why this comes here |
| ---: | --- | --- | --- |
| 1 | **Project definition** | I · 1–11 | Facts, scope, rules, and architecture frozen before code |
| 2 | **Workstation and repository** | II · 12–23 | Tools and Git standards before the application exists |
| 3 | **Application shell** | III · 24–32 | Empty Laravel, locales, Filament login, Pest — no business logic |
| 4 | **UX and UI design** | IV · 33–71 | Screens approved before tables and pages are built |
| 5 | **Database design** | V · 72–76 | ERD and constraints on paper before migrations |
| 6 | **Backend design** | V · 77–85 | Use cases, state machines, and policies before PHP modules |
| 7 | **Database build** | VI · 86–97 | Migrations and seeds, one group at a time with tests |
| 8 | **Backend build** | VI · 98–107 | Domain modules and Pest; shared by admin and public UI |
| 9 | **Administration UI** | VI · 108–116 | Filament ops console before public pages read live data |
| 10 | **Public frontend** | VII · 117–129 | One URL at a time against hi-fi and live data |
| 11 | **Integration and QA** | VIII · 130–150 | Hardening, full test suite, content, local UAT |
| 12 | **Containerisation** | IX · 151–159 | Docker only after local core is stable |
| 13 | **Production** | X · 160–182 | VPS, backups, restore drill, launch, handover |

**Not parallel:** do not design the database while wireframes are open. Do not build the public site while migrations are incomplete. Do not Dockerise before Gate 150.

---

## Governance

### Step states

| State | Mark | Meaning |
| --- | --- | --- |
| Complete | `[x]` | Accepted. Do not reopen without change control. |
| Active | `← active` | The only step in progress. |
| Pending | `[ ]` | Blocked until every lower step is `[x]`. |

**Advance:** complete the active step → mark `[x]` → move `← active` to the next number.

### Gates

A **gate** is a sign-off step. The next phase remains closed until its gate is `[x]`. A gate is not satisfied by partial delivery.

### Constraints

- One active step at a time
- No skipping gates
- No parallel phases
- No “mostly done” completions
- Laravel, Docker, and production deploy each have explicit gate prerequisites (see roadmap)

---

## Status

| | |
| --- | --- |
| Active phase | **VII — Public experience** |
| Complete | Steps 1–125 |
| **Active step** | **126** — Rendez-vous & Suivi |
| Pending | Steps 126–182 · Phases VII–X |

**Hard locks:** Laravel → Phase III (step 24) · Docker → Phase IX (step 150) · VPS → Phase X (step 159)

---

## Roadmap

| Phase | Name | Steps | Opens | Gate |
| --- | --- | ---: | --- | ---: |
| I | Specification | 1–11 | Project start | 11 |
| II | Environment | 12–23 | Gate 11 | 23 |
| III | Application foundation | 24–32 | Gate 23 | 32 |
| IV | Experience design | 33–71 | Gate 32 | 71 |
| V | Data & domain design | 72–85 | Gate 71 | 85 |
| VI | Core implementation | 86–116 | Gate 85 | 116 |
| VII | Public experience | 117–129 | Gate 116 | 129 |
| VIII | Quality assurance | 130–150 | Gate 129 | 150 |
| IX | Containerisation | 151–159 | Gate 150 | 159 |
| X | Production & operations | 160–182 | Gate 159 | 177 · 182 |

---

## Phase I — Specification

**Purpose:** Freeze product definition before tooling or code.  
**Deliverables:** `docs/01`–`10`  
**Prohibited:** Git setup, Laravel, Docker

- [x] **1** · Lock baseline → [`docs/01-baseline.md`](docs/01-baseline.md)
- [x] **2** · Project charter → [`docs/02-charter.md`](docs/02-charter.md)
- [x] **3** · V1 scope → [`docs/03-scope.md`](docs/03-scope.md)
- [x] **4** · Functional requirements → [`docs/04-requirements.md`](docs/04-requirements.md)
- [x] **5** · Quality standards → [`docs/05-quality.md`](docs/05-quality.md)
- [x] **6** · Business rules → [`docs/06-rules.md`](docs/06-rules.md)
- [x] **7** · Acceptance criteria → [`docs/07-acceptance.md`](docs/07-acceptance.md)
- [x] **8** · Content inventory → [`docs/08-content.md`](docs/08-content.md)
- [x] **9** · Architecture → [`docs/09-architecture.md`](docs/09-architecture.md)
- [x] **10** · Admin scope → [`docs/10-admin.md`](docs/10-admin.md)
- [x] **11** · **Gate — documentation sign-off** · Owner accepts `docs/01`–`10` in writing · Signed off 2026-09-03

---

## Phase II — Environment

**Purpose:** Prepare the local workstation and repository standards.  
**Opens:** Gate 11 · **Gate:** 23 · **Prohibited:** Laravel

- [x] **12** · Install Git · Acceptance: `git --version` succeeds
- [x] **13** · Install PHP · Acceptance: `php -v` succeeds · **8.5.6** (Homebrew; amended from 8.4 at owner request)
- [x] **14** · Install Composer · Acceptance: `composer -V` succeeds
- [x] **15** · Install Node · Acceptance: `node -v` succeeds · **v26.0.0**
- [x] **16** · Install MySQL · Acceptance: local connection succeeds · **9.6.0** (Homebrew; amended from 8.4 at owner request)
- [x] **17** · Install Mailpit · Acceptance: UI reachable locally · **v1.31.0** · http://127.0.0.1:8025
- [x] **18** · **Gate — workstation verified** · Steps 12–17 complete on this machine · Verified 2026-09-03
- [x] **19** · Initialise branches · `main`, `develop`, `feature/*` · Initial commit `7342ddf`
- [x] **20** · Secrets policy · `.env` excluded from version control · [`.gitignore`](.gitignore) · [`.env.example`](.env.example) · [CONTRIBUTING.md](CONTRIBUTING.md)
- [x] **21** · Configure Pint · Formatter runs on the repository · **Pint 1.30.5** · `composer format:test` passes
- [x] **22** · Configure Larastan · Static analysis runs on the repository · **Larastan 3.11** · `composer analyse` passes
- [x] **23** · **Gate — repository standards** · Steps 19–22 complete · Verified 2026-09-03

---

## Phase III — Application foundation

**Purpose:** Empty Laravel 13 shell with i18n, admin access, and test harness.  
**Opens:** Gate 23 · **Gate:** 32 · **Prohibited:** Business modules

- [x] **24** · Create Laravel 13 project · Acceptance: `php artisan --version` succeeds · **13.30.1**
- [x] **25** · Verify HTTP boot · Acceptance: `GET /` returns redirect · HTTP 200 on locale routes
- [x] **26** · Verify MySQL connectivity · Acceptance: migrations run · DB `g3_control`
- [x] **27** · Verify Vite build · `npm run build` succeeds · **Vite 8.2.2**
- [x] **28** · Install Filament 5 · `/admin` login works · **Filament 5.7.8**
- [x] **29** · Locale routing · `/fr/`, `/en/`, `/` → `/fr/accueil`
- [x] **30** · Time configuration · Africa/Douala display · UTC timestamps · [`config/time.php`](config/time.php) · [`App\Support\Clock`](app/Support/Clock.php)
- [x] **31** · Verify Pest · Test suite passes on empty app · **Pest 5.1.3** · 7 tests pass · `composer test`
- [x] **32** · **Gate — foundation accepted** · Steps 24–31 complete · Verified 2026-09-04

---

## Phase IV — Experience design

**Purpose:** Approved UX before schema or public implementation.  
**Opens:** Gate 32 · **Gate:** 71 · **Prohibited:** Migrations · public page code

- [x] **33** · Information architecture · Sitemap, navigation, page jobs · [`design/33-information-architecture.md`](design/33-information-architecture.md)
- [x] **34** · Design system · Brand tokens, typography, Safety Line · [`design/34-design-system.md`](design/34-design-system.md)
- [x] **35** · Media guidelines · Photography and video rules · [`design/35-media-guidelines.md`](design/35-media-guidelines.md)
- [x] **36** · Component states · Default, hover, focus, error, disabled, empty · [`design/36-component-states.md`](design/36-component-states.md)
- [x] **37** · Desktop wireframe — Accueil · [`design/wireframes/desktop/37-accueil.md`](design/wireframes/desktop/37-accueil.md)
- [x] **38** · Desktop wireframe — À propos · [`design/wireframes/desktop/38-a-propos.md`](design/wireframes/desktop/38-a-propos.md)
- [x] **39** · Desktop wireframe — Nos centres · [`design/wireframes/desktop/39-centres.md`](design/wireframes/desktop/39-centres.md)
- [x] **40** · Desktop wireframe — École de Police · [`design/wireframes/desktop/40-centre-ecole-de-police.md`](design/wireframes/desktop/40-centre-ecole-de-police.md)
- [x] **41** · Desktop wireframe — Nomayos · [`design/wireframes/desktop/41-centre-nomayos.md`](design/wireframes/desktop/41-centre-nomayos.md)
- [x] **42** · Desktop wireframe — Services · [`design/wireframes/desktop/42-services.md`](design/wireframes/desktop/42-services.md)
- [x] **43** · Desktop wireframe — Visite technique · [`design/wireframes/desktop/43-visite-technique.md`](design/wireframes/desktop/43-visite-technique.md)
- [x] **44** · Desktop wireframe — Tarifs · [`design/wireframes/desktop/44-tarifs.md`](design/wireframes/desktop/44-tarifs.md)
- [x] **45** · Desktop wireframe — Rendez-vous & Suivi · [`design/wireframes/desktop/45-rendez-vous.md`](design/wireframes/desktop/45-rendez-vous.md)
- [x] **46** · Desktop wireframe — Sécurité routière · [`design/wireframes/desktop/46-securite-routiere.md`](design/wireframes/desktop/46-securite-routiere.md)
- [x] **47** · Desktop wireframe — Contact · [`design/wireframes/desktop/47-contact.md`](design/wireframes/desktop/47-contact.md)
- [x] **48** · Mobile wireframe — Accueil · [`design/wireframes/mobile/48-accueil.md`](design/wireframes/mobile/48-accueil.md)
- [x] **49** · Mobile wireframe — À propos · [`design/wireframes/mobile/49-a-propos.md`](design/wireframes/mobile/49-a-propos.md)
- [x] **50** · Mobile wireframe — Nos centres · [`design/wireframes/mobile/50-centres.md`](design/wireframes/mobile/50-centres.md)
- [x] **51** · Mobile wireframe — École de Police · [`design/wireframes/mobile/51-centre-ecole-de-police.md`](design/wireframes/mobile/51-centre-ecole-de-police.md)
- [x] **52** · Mobile wireframe — Nomayos · [`design/wireframes/mobile/52-centre-nomayos.md`](design/wireframes/mobile/52-centre-nomayos.md)
- [x] **53** · Mobile wireframe — Services · [`design/wireframes/mobile/53-services.md`](design/wireframes/mobile/53-services.md)
- [x] **54** · Mobile wireframe — Visite technique · [`design/wireframes/mobile/54-visite-technique.md`](design/wireframes/mobile/54-visite-technique.md)
- [x] **55** · Mobile wireframe — Tarifs · [`design/wireframes/mobile/55-tarifs.md`](design/wireframes/mobile/55-tarifs.md)
- [x] **56** · Mobile wireframe — Rendez-vous & Suivi · [`design/wireframes/mobile/56-rendez-vous.md`](design/wireframes/mobile/56-rendez-vous.md)
- [x] **57** · Mobile wireframe — Sécurité routière · [`design/wireframes/mobile/57-securite-routiere.md`](design/wireframes/mobile/57-securite-routiere.md)
- [x] **58** · Mobile wireframe — Contact · [`design/wireframes/mobile/58-contact.md`](design/wireframes/mobile/58-contact.md)
- [x] **59** · Hi-fi design — Accueil · [`design/hifi/59-accueil.md`](design/hifi/59-accueil.md) · [preview](design/hifi/59-accueil.html)
- [x] **60** · Hi-fi design — À propos · [preview](design/hifi/60-a-propos.html) · [specs](design/hifi/60-69-specs.md)
- [x] **61** · Hi-fi design — Nos centres · [preview](design/hifi/61-centres.html)
- [x] **62** · Hi-fi design — École de Police · [preview](design/hifi/62-centre-ecole-de-police.html)
- [x] **63** · Hi-fi design — Nomayos · [preview](design/hifi/63-centre-nomayos.html)
- [x] **64** · Hi-fi design — Services · [preview](design/hifi/64-services.html)
- [x] **65** · Hi-fi design — Visite technique · [preview](design/hifi/65-visite-technique.html)
- [x] **66** · Hi-fi design — Tarifs · [preview](design/hifi/66-tarifs.html)
- [x] **67** · Hi-fi design — Rendez-vous & Suivi · [preview](design/hifi/67-rendez-vous.html)
- [x] **68** · Hi-fi design — Sécurité routière · [preview](design/hifi/68-securite-routiere.html)
- [x] **69** · Hi-fi design — Contact · [preview](design/hifi/69-contact.html)
- [x] **70** · Admin UX · [spec](design/admin/70-admin-ux.md) · [dashboard](design/admin/70-dashboard.html) · [login](design/admin/70-login.html) · [appointments](design/admin/70-appointments.html) · [tariffs](design/admin/70-tariff-publish.html) · [content](design/admin/70-content.html)
- [x] **71** · **Gate — experience design accepted** · Steps 33–70 complete · Verified 2026-09-05 · [`design/71-gate-experience-design.md`](design/71-gate-experience-design.md)

---

## Phase V — Data & domain design

**Purpose:** Approved data model and domain logic on paper.  
**Opens:** Gate 71 · **Gates:** 76 (database) · 85 (domain)

- [x] **72** · Conceptual data model · [`docs/11-conceptual-model.md`](docs/11-conceptual-model.md)
- [x] **73** · Logical ERD → [`docs/11-erd.md`](docs/11-erd.md)
- [x] **74** · Indexes, constraints, foreign keys · [`docs/11-indexes-constraints.md`](docs/11-indexes-constraints.md)
- [x] **75** · Retention mapped to tables · [`docs/11-retention.md`](docs/11-retention.md)
- [x] **76** · **Gate — database design accepted** · Steps 72–75 complete · Verified 2026-09-05 · [`docs/11-gate-database-design.md`](docs/11-gate-database-design.md)
- [x] **77** · Domain model · [`docs/12-domain-model.md`](docs/12-domain-model.md)
- [x] **78** · Use-case catalogue · [`docs/12-use-cases.md`](docs/12-use-cases.md)
- [x] **79** · Availability engine specification · [`docs/12-availability-engine.md`](docs/12-availability-engine.md)
- [x] **80** · Appointment state machine specification · [`docs/12-appointment-state-machine.md`](docs/12-appointment-state-machine.md)
- [x] **81** · Tariff engine specification · [`docs/12-tariff-engine.md`](docs/12-tariff-engine.md)
- [x] **82** · Domain events specification · [`docs/12-domain-events.md`](docs/12-domain-events.md)
- [x] **83** · Cache policy · [`docs/12-cache-policy.md`](docs/12-cache-policy.md)
- [x] **84** · Security design · [`docs/12-security-design.md`](docs/12-security-design.md)
- [x] **85** · **Gate — domain design accepted** · Steps 77–84 complete · Verified 2026-09-05 · [`docs/12-gate-domain-design.md`](docs/12-gate-domain-design.md)

---

## Phase VI — Core implementation

**Purpose:** Schema, domain services, and administration.  
**Opens:** Gate 85 · **Gates:** 97 · 107 · 116 · **Prohibited:** Public pages until Gate 116

### Schema

- [x] **86** · Migration — company / settings + test · `spatie/laravel-settings` · [`CompanySettings`](app/Settings/CompanySettings.php)
- [x] **87** · Migration — centres, phones, hours + test · [`2026_09_05_194500_create_centres_tables.php`](database/migrations/2026_09_05_194500_create_centres_tables.php)
- [x] **88** · Migration — schedule exceptions + test · [`2026_09_05_200000_create_schedule_exceptions_table.php`](database/migrations/2026_09_05_200000_create_schedule_exceptions_table.php)
- [x] **89** · Migration — catalogue + test · [`2026_09_05_201000_create_catalogue_tables.php`](database/migrations/2026_09_05_201000_create_catalogue_tables.php)
- [x] **90** · Migration — tariffs + test · [`2026_09_05_202000_create_tariff_tables.php`](database/migrations/2026_09_05_202000_create_tariff_tables.php)
- [x] **91** · Migration — appointments, history + test · [`2026_09_05_203000_create_appointment_tables.php`](database/migrations/2026_09_05_203000_create_appointment_tables.php)
- [x] **92** · Migration — internal notes · Separate from history · [`2026_09_05_204000_create_appointment_internal_notes_table.php`](database/migrations/2026_09_05_204000_create_appointment_internal_notes_table.php)
- [x] **93** · Migration — content + test · [`2026_09_05_205000_create_content_tables.php`](database/migrations/2026_09_05_205000_create_content_tables.php)
- [x] **94** · Migration — contact + test · [`2026_09_05_206000_create_contact_tables.php`](database/migrations/2026_09_05_206000_create_contact_tables.php)
- [x] **95** · Migration — media, identity, audit + test · `spatie/laravel-permission` · `spatie/laravel-medialibrary` · `spatie/laravel-activitylog` · [`207000_extend_users`](database/migrations/2026_09_05_207000_extend_users_for_admin_identity.php) · [`207100_admin_user_scopes`](database/migrations/2026_09_05_207100_create_admin_user_scopes_table.php)
- [x] **96** · Seed · Two centres and locked hours only · No invented prices or services · [`BaselineCentresSeeder`](database/seeders/BaselineCentresSeeder.php)
- [x] **97** · **Gate — schema accepted** · Steps 86–96 complete · Verified 2026-09-05 · [`docs/11-gate-schema-accepted.md`](docs/11-gate-schema-accepted.md)

### Domain

- [x] **98** · Module — company settings + Pest · [`ResolvePublicCompanyProfile`](app/Actions/Company/ResolvePublicCompanyProfile.php) · [`UpdateCompanySettings`](app/Actions/Company/UpdateCompanySettings.php)
- [x] **99** · Module — centres and availability + Pest · [`AvailabilityEngine`](app/Domain/Schedule/AvailabilityEngine.php) · [`ResolveCentreAvailability`](app/Actions/Schedule/ResolveCentreAvailability.php)
- [x] **100** · Module — catalogue + Pest · [`ResolvePublishedServices`](app/Actions/Catalogue/ResolvePublishedServices.php) · [`Service::isAvailableAt`](app/Models/Catalogue/Service.php)
- [x] **101** · Module — tariffs + Pest · [`TariffResolver`](app/Domain/Tariff/TariffResolver.php) · [`PublishTariffVersion`](app/Actions/Tariff/PublishTariffVersion.php)
- [x] **102** · Module — appointments + Pest · [`AppointmentStateMachine`](app/Domain/Appointment/AppointmentStateMachine.php) · [`CreateAppointmentRequest`](app/Actions/Appointment/CreateAppointmentRequest.php)
- [x] **103** · Module — tracking + Pest · [`TrackAppointment`](app/Actions/Appointment/TrackAppointment.php)
- [x] **104** · Module — content + Pest · [`PublishContentBlock`](app/Actions/Content/PublishContentBlock.php) · [`ResolvePublishedContentBlocksForPage`](app/Actions/Content/ResolvePublishedContentBlocksForPage.php)
- [x] **105** · Module — contact + Pest · [`SubmitContactMessage`](app/Actions/Contact/SubmitContactMessage.php) · [`ContactStateMachine`](app/Domain/Contact/ContactStateMachine.php)
- [x] **106** · Module — notification port + Pest · [`NotificationPort`](app/Contracts/NotificationPort.php) · [`MailNotificationAdapter`](app/Infrastructure/Notifications/MailNotificationAdapter.php)
- [x] **107** · **Gate — domain accepted** · Verified 2026-09-06 · [`docs/12-gate-domain-accepted.md`](docs/12-gate-domain-accepted.md)

### Administration

- [x] **108** · Auth · Five roles, MFA, policies · [`AdminPanelProvider`](app/Providers/Filament/AdminPanelProvider.php) · [`AuthServiceProvider`](app/Providers/AuthServiceProvider.php)
- [x] **109** · Dashboard · [`ResolveDashboardData`](app/Actions/Admin/ResolveDashboardData.php) · [`dashboard.blade.php`](resources/views/filament/pages/dashboard.blade.php)
- [x] **110** · Centre management · Hours, exceptions, alerts
- [x] **111** · Catalogue management
- [x] **112** · Tariff management · Draft → reviewed → published → archived
- [x] **113** · Appointment management · Illegal transitions rejected
- [x] **114** · Content and media · FR/EN tabs · No raw JSON editing
- [x] **115** · Audit log
- [x] **116** · **Gate — administration accepted** · Verified 2026-09-07 · [`docs/10-gate-administration-accepted.md`](docs/10-gate-administration-accepted.md)

---

## Phase VII — Public experience

**Purpose:** Ship each public URL against approved design and live data.  
**Opens:** Gate 116 · **Gate:** 129 · One page per step

- [x] **117** · Application shell · Navigation, footer, locale switch
- [x] **118** · Accueil · Homepage sections, responsive layout, carousel/equipment interactions, centres map, road-safety band
- [x] **119** · À propos · Identity, technical requirements, values, and team sections
- [x] **120** · Nos centres · Live centre selector, Google map, centre details, and G3 standard band
- [x] **121** · École de Police · Centre hero carousel, centre facts, and preparation section
- [x] **122** · Nomayos · Centre hero carousel, centre facts, and preparation section
- [x] **123** · Services · Compact service passport, editorial service portfolio, infrastructure proof block, and SVG service/proof icons
- [x] **124** · Visite technique · Interactive journey, control explorer, inspection-line proof, smart preparation, result paths, compact responsive layout, and SVG/media assets
- [x] **125** · Tarifs · Smart tariff navigator, price passport, identification assistant, MINT tariff integrity strip, official vehicle imagery, and SVG tariff icons
- [ ] **126** · ← active · Rendez-vous & Suivi
- [ ] **127** · Sécurité routière
- [ ] **128** · Contact
- [ ] **129** · **Gate — public experience accepted**

---

## Phase VIII — Quality assurance

**Purpose:** Harden, test, populate, and validate locally before containerisation.  
**Opens:** Gate 129 · **Gate:** 150 · **Prohibited:** Docker until Gate 150

### Integration & compliance

- [ ] **130** · Data integration · No hardcoded hours, phones, or prices
- [ ] **131** · Localisation audit · FR/EN parity and glossary consistency
- [ ] **132** · SEO · Canonical, hreflang, sitemap · `/admin` disallowed in robots
- [ ] **133** · Accessibility review
- [ ] **134** · Security review · CSRF, throttling, honeypot, tracking privacy
- [ ] **135** · Performance review
- [ ] **136** · **Gate — integration accepted**

### Automated tests

- [ ] **137** · Pest — live status (`docs/07-acceptance.md`)
- [ ] **138** · Pest — tariff lifecycle
- [ ] **139** · Pest — appointment transitions
- [ ] **140** · Pest — tracking privacy
- [ ] **141** · Pest — Livewire request flow
- [ ] **142** · Pest — Filament authorisation
- [ ] **143** · **Gate — test suite accepted**

### Content & UAT

- [ ] **144** · French content load
- [ ] **145** · English content load
- [ ] **146** · Media ingestion · Real G3 assets only
- [ ] **147** · Factual audit · Baseline accuracy · No invented tariffs
- [ ] **148** · Local UAT with G3 stakeholders
- [ ] **149** · End-to-end validation · Request → confirm → track
- [ ] **150** · **Gate — local core stable**

---

## Phase IX — Containerisation

**Purpose:** Reproducible deployment environment matching local core.  
**Opens:** Gate 150 · **Gate:** 159 · **Prohibited:** VPS until Gate 159

- [ ] **151** · Container architecture → [`docs/12-docker.md`](docs/12-docker.md)
- [ ] **152** · **Gate — container design accepted**
- [ ] **153** · Application container
- [ ] **154** · MySQL container
- [ ] **155** · Redis container
- [ ] **156** · Nginx + PHP-FPM
- [ ] **157** · Queue worker and scheduler
- [ ] **158** · Parity verification · Pest suite passes in containers
- [ ] **159** · **Gate — containerisation accepted**

---

## Phase X — Production & operations

**Purpose:** Deploy, protect, launch, and hand over operations.  
**Opens:** Gate 159 · **Gate:** 177 (go-live) · **182** (program close)

### Infrastructure

- [ ] **160** · Provision VPS
- [ ] **161** · Production environment configuration
- [ ] **162** · DNS
- [ ] **163** · Reverse proxy
- [ ] **164** · TLS / HTTPS
- [ ] **165** · Deployment pipeline
- [ ] **166** · Production migration
- [ ] **167** · Production seed · Settings, centres, hours
- [ ] **168** · Smoke test

### Resilience

- [ ] **169** · On-server backups
- [ ] **170** · Off-server backup replication
- [ ] **171** · Restore drill · Verified recovery, not configuration only
- [ ] **172** · Uptime and error monitoring
- [ ] **173** · Log rotation

### Pre-launch audit

- [ ] **174** · Security audit
- [ ] **175** · Production factual audit
- [ ] **176** · Accessibility and performance audit
- [ ] **177** · **Gate — go-live approved** · Includes steps 170–171

### Launch & handover

- [ ] **178** · Public launch
- [ ] **179** · Post-launch monitoring · 72 hours
- [ ] **180** · Operational handover · Named owners and procedures
- [ ] **181** · First monthly review
- [ ] **182** · First quarterly review · Program close

---

*Step 182 completes V1 delivery. Future scope requires change control per [`docs/03-scope.md`](docs/03-scope.md).*
