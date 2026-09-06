# Admin UX · step 70

Filament 5 hi-fi for `/admin`. Implementation: Phase VI steps 108–116 · Spec: [docs/10-admin.md](../../docs/10-admin.md).

| Screen | Preview | Role context |
| --- | --- | --- |
| Connexion | [70-login.html](70-login.html) | All |
| Tableau de bord | [70-dashboard.html](70-dashboard.html) | Super Admin · Ops |
| Demandes RDV | [70-appointments.html](70-appointments.html) | Reception · Centre Manager |
| Publication tarif | [70-tariff-publish.html](70-tariff-publish.html) | Ops · Super Admin |
| Contenu FR/EN | [70-content.html](70-content.html) | Content Editor |

**Stylesheet:** [admin.css](admin.css) · **Tokens:** [34-design-system.md](../34-design-system.md) · **States:** [36-component-states.md](../36-component-states.md)

Open [`70-dashboard.html`](70-dashboard.html) for the full walkthrough. **Not production code.**

---

## 1 · Design intent

The admin experience mirrors G3 Control’s public brand — deep blue authority, orange action, Safety Line accent — while optimising for **daily operational work**: triage appointments, publish tariffs, edit bilingual content.

| Principle | Application |
| --- | --- |
| Clarity first | Dense data in tables; detail in side panels, not modals |
| Role-aware | Nav and actions scoped per [docs/10-admin.md](../../docs/10-admin.md) |
| French chrome V1 | Labels, dates, timezone Africa/Douala |
| Trust & audit | Confirm dialogs for publish; timeline vs internal notes separated |
| Filament alignment | Layout maps to Filament 5 sidebar + topbar + resource pages |

**Filament primary colour (implementation):** G3 orange `#F47A20` — replace default Amber in `AdminPanelProvider.php` at step 108.

---

## 2 · Shell & navigation

### 2.1 Layout

```
┌─────────────────────────────────────────────────────────┐
│ Preview banner (design only)                            │
├──────────┬──────────────────────────────────────────────┤
│ Sidebar  │ Topbar · title · search · actions            │
│ 280px    ├──────────────────────────────────────────────┤
│ sticky   │ Content · padding 32px                       │
│          │                                              │
│ User     │                                              │
└──────────┴──────────────────────────────────────────────┘
```

### 2.2 Nav groups

| Group | Items | FR labels |
| --- | --- | --- |
| Opérations | Dashboard, appointments, contacts, alerts | Tableau de bord, Demandes RDV, Messages, Alertes |
| Centres | Centres, hours, exceptions, equipment | Centres, Horaires, Exceptions, Équipements |
| Catalogue | Services, categories, documents, tariffs | Services, Catégories, Documents, Tarifs |
| Contenu | Blocks, road safety, FAQ, team, media | Pages & blocs, Sécurité routière, FAQ, Équipe, Médias |
| Système | Users, roles, settings, SEO, audit | Utilisateurs, Rôles, Paramètres, SEO, Journal |

Active item: white overlay + **3px orange inset** left border. Badge counts on queue items (orange pill).

### 2.3 Topbar

- Page title (Manrope 1.25rem)
- Global search: reference, plate, phone (appointments/contacts)
- Notification bell with orange dot
- “Voir le site” secondary → public hi-fi Accueil

---

## 3 · Visual system (admin)

Extends [34-design-system.md](../34-design-system.md):

| Token | Admin use |
| --- | --- |
| `--g3-blue-deep` | Sidebar gradient, login brand panel |
| `--g3-orange` | Primary buttons, active nav accent, stat card accent |
| `--g3-blue-tech` | Links, focus rings, workflow current step |
| `--g3-grey` | Page background, table header, centre status cards |
| Safety Line | 48×3px orange under page headers |

**Typography:** Manrope headings · Inter body · monospace for references (`G3-26-A8FD2`).

**Elevation:** Cards `shadow-sm` + 1px border · modals `shadow-lg` + backdrop blur.

**Responsive:** Sidebar hidden &lt;900px (Filament mobile drawer in implementation). Stats grid 4→2 columns &lt;1200px.

---

## 4 · Connexion

**Preview:** [70-login.html](70-login.html) · **Req:** FR-AD-05 MFA in production

| Zone | Treatment |
| --- | --- |
| Brand panel | Deep blue gradient · G3 Control logo · Safety Line · tagline |
| Form panel | White centred card · email + password · orange CTA full width |
| Footer hint | MFA required in production |

Split 50/50 desktop; brand hidden on mobile.

---

## 5 · Tableau de bord

**Preview:** [70-dashboard.html](70-dashboard.html) · **Req:** FR-AD-01 dashboard widgets

### 5.1 Stat cards (4-up)

| Card | Metric | Accent |
| --- | --- | --- |
| Nouvelles demandes | Count + centre split | Orange top bar |
| En traitement | Count | Blue |
| Centres ouverts | x/y + closing times | Green |
| Messages | Unhandled count | Blue |

### 5.2 Widgets

| Widget | Content |
| --- | --- |
| File des demandes | Priority table · ref, name, centre, status badge · Open CTA |
| Statut des centres | Live open/closed per centre · hours · next exception |
| Tarif en vigueur | Published version + effective date · link to tariff admin |
| Activité récente | Timeline of last events |

**Timezone:** Display Africa/Douala in preview clock and timestamps.

---

## 6 · Demandes RDV (queue + detail)

**Preview:** [70-appointments.html](70-appointments.html) · **Req:** FR-AD-02, FR-AD-03

### 6.1 List

- Filter bar: status, centre, date range
- Sortable table with hover row highlight
- Selected row: blue tint + orange left inset

### 6.2 Detail panel (420px)

- Reference mono + title
- **Contextual actions only** by status:
  - Reçue → Confirmer · Proposer autre créneau · Refuser
  - En traitement → (confirm/reschedule per business rules)
- Info block: centre, date, plate, phone, email
- **Notes internes** — textarea, never shown on public timeline
- **Timeline publique** — client-visible events only

Centre Manager sees own centre scope; Reception sees assigned centre.

---

## 7 · Publication tarif

**Preview:** [70-tariff-publish.html](70-tariff-publish.html) · **Req:** BR-TARIFF-005, FR-TA-*

### 7.1 Workflow stepper

```
Brouillon → Revu → Publication → Archivé
```

- Done steps: green circle + check
- Current: blue circle + focus ring
- Pending: grey outline

### 7.2 Publish flow

1. Gradient banner when ready: version label, line count, effective date
2. “Publier la version” opens confirmation modal
3. Modal: effective date, archive notice, audit log mention
4. Primary confirm = green “Publier maintenant”

### 7.3 Supporting panels

- Line preview table (service, category, FCFA)
- History timeline (draft, review, prior published version)

Centre Manager **cannot** publish; Ops and Super Admin can.

---

## 8 · Contenu FR/EN

**Preview:** [70-content.html](70-content.html) · **Req:** BR-LANG-001, FR-CN-*

### 8.1 Locale tabs

| Tab | Indicator |
| --- | --- |
| Français | Green check when complete |
| English | Empty circle when incomplete |

Active tab: orange bottom border (matches public site tab pattern, step 67).

### 8.2 Publish gate

- Warning alert when EN incomplete
- Progress bar (50% in preview)
- **Publish button disabled** until both locales pass validation
- Completion matrix table: field × FR × EN status badges

### 8.3 Form

Standard fields for content block (hero example): title, subtitle, CTAs, media picker with step 35 hint.

Actions: Save draft · Switch locale · Preview public FR page.

**Filters in admin:** use stable codes; UI labels from lang files.

---

## 9 · Status badges

| Code | Label FR | Colour |
| --- | --- | --- |
| received | Reçue | Info blue |
| review | En traitement | Warning amber |
| confirmed | Confirmée | Success green |
| cancelled | Annulée | Grey |
| open | Ouvert | Success |
| closed | Fermé | Grey |
| draft | Brouillon | Grey |
| published | Publié | Success |

Dot + pill pattern from [36-component-states.md](../36-component-states.md).

---

## 10 · Roles & visibility (summary)

| Screen / action | Super | Ops | Centre Mgr | Reception | Content |
| --- | :---: | :---: | :---: | :---: | :---: |
| Dashboard (global) | ✓ | ✓ | scoped | — | — |
| Appointments | ✓ | ✓ | own centre | own centre | — |
| Tariff publish | ✓ | ✓ | — | — | — |
| Content FR/EN | ✓ | — | — | — | ✓ |
| Users / system | ✓ | — | — | — | — |

Filament policies enforce scopes at implementation.

---

## 11 · Filament mapping (Phase VI)

| Design artefact | Filament construct |
| --- | --- |
| Sidebar groups | `NavigationGroup` + custom theme CSS |
| Dashboard widgets | Filament widgets (stats, table, timeline) |
| Appointment queue | Resource list + ViewRecord slide-over or split |
| Tariff workflow | Custom page + action modals + state machine |
| Content FR/EN | Tabs plugin or custom Livewire tabs on resource form |
| Status badges | Table column badge component |
| Login | Filament auth + MFA package |

---

## 12 · Accessibility

- Focus rings: 3px blue glow on inputs (matches public forms)
- Modal: `role="dialog"`, labelled heading, escape to close (implementation)
- Tab list: `role="tablist"` / `role="tab"` / `aria-selected`
- Status badges: colour + text + dot (not colour alone)
- Minimum touch target 40px on primary actions

---

## 13 · Out of scope (this step)

- Live Filament resources, policies, or database
- MFA implementation
- Real search/filter backend
- Mobile admin wireframes (Filament responsive defaults suffice for V1)

---

## 14 · Acceptance checklist

- [x] Login, dashboard, appointments, tariff publish, content FR/EN previews
- [x] Shared [admin.css](admin.css) with G3 tokens
- [x] Nav groups match [docs/10-admin.md](../../docs/10-admin.md)
- [x] Tariff workflow draft → reviewed → published → archived
- [x] CMS publish blocked until FR + EN complete
- [x] Internal notes ≠ public timeline on appointments
- [x] Cross-linked preview navigation

**Next:** Step **71** — Gate: experience design accepted (steps 33–70).
