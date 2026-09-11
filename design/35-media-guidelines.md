# 35 — Media guidelines

| Delivery | Phase IV · step **35** |
| --- | --- |
| Specs | [08-content](../docs/08-content.md) · [04-requirements](../docs/04-requirements.md) · [06-rules](../docs/06-rules.md) · [05-quality](../docs/05-quality.md) |
| Design | [34-design-system.md](34-design-system.md) · [33-information-architecture.md](33-information-architecture.md) |
| Next | Step 36 — Component states |

Rules for photography, video, ALT text, file handling, and placement. Wireframes (37–58) and hi-fi (59–69) use **placeholders** until G3 supplies assets (Q-07). Implementation ingests via Spatie media (FR-MD-01) in Phase VI.

**Governing rule:** real G3 media by default (**BR-MEDIA-001**). Stock “garage” imagery is a last-resort placeholder only — never shipped as final public content.

---

## 1. Principles

| Principle | Detail |
| --- | --- |
| **Real over generic** | Show École de Police and Nomayos as they are — lanes, reception, equipment, staff where consented. |
| **Technical credibility** | Inspection is precise and calm; not racing, not luxury dealership, not foreign MOT branding. |
| **Editorial quality** | Premium composition aligned with [34-design-system.md](34-design-system.md) — clean lines, natural light, restrained colour grading. |
| **Bilingual metadata** | Title and ALT in FR **and** EN before publish (FR-MD-02, BR-LANG-001). |
| **Performance first** | Derivatives for web; lazy load; no homepage hero video autoplay (NFR-P-03, NFR-P-04). |
| **Do not invent** | Until Q-07 is closed, use labelled placeholders — no fake logos, fabricated lanes, or AI-generated “centres”. |

---

## 2. Asset inventory (V1)

All items below are **missing** until G3 ingests them ([08-content](../docs/08-content.md) Q-07). This is the shoot/commission checklist.

### 2.1 Brand

| Asset | Format | Use |
| --- | --- | --- |
| Logo — primary (colour) | SVG + PNG @2x | Header, footer, OG default |
| Logo — reversed (white) | SVG + PNG @2x | Royal blue trust bars, footer, road-safety sections |
| Favicon | ICO + SVG | Browser tab |
| Social share default | 1200×630 JPG/PNG | OG/Twitter when page has no hero |

**Logo rules:** Clear space = height of the “G3” mark on all sides. Minimum digital height 32px. Do not recolour outside brand blues/orange. Do not add effects (shadows, gradients, outlines).

### 2.2 Centres (×2)

For **École de Police** and **Nomayos**, each:

| Shot type | Intent | Min count |
| --- | --- | --- |
| **Hero** | Wide exterior or approach — recognisable landmark context | 1 |
| **Exterior** | Facade, signage, entry | 2–3 |
| **Reception** | Customer waiting / accueil area | 1–2 |
| **Lane / pit** | Inspection lane, equipment in context — no customer plates visible | 2–4 |
| **Detail** | Clean equipment, signage, safety markings | 2 |

**Photography direction:** Yaoundé daylight; show cleanliness and order; include people only with written consent; blur or crop registration plates in every frame.

### 2.3 People and equipment

| Collection | Content |
| --- | --- |
| **Team** | Named roles only when `display_publicly` approved (FR-CN-05); professional, uniform or branded dress if applicable |
| **Equipment** | Benches, testers, tools — tag to one or both centres (FR-CN-06); prefer custom SVG icons for diagrams where photos are unclear ([09-architecture](../docs/09-architecture.md)) |
| **Inspection stills** | Step-by-step visite technique — accueil, contrôle, validation — **G3 process**, not stock MOT imagery |

### 2.4 Video

| Asset | Spec |
| --- | --- |
| **Visite technique feature** | 60–120s overview of the inspection journey at a G3 centre |
| **Poster frame** | 16:9 still from video or dedicated shoot — used before play |
| **Captions** | FR required; EN **suggestion** — burn-in not required if player supports WebVTT |

**Placement:** Primary on `/fr/visite-technique` / `/en/technical-inspection` (FR-MD-03). Optional short clip on homepage only as a **thumbnail + modal** — never autoplaying full MP4 on load (NFR-P-04).

### 2.5 Road safety

| Type | Direction |
| --- | --- |
| Editorial stills | Tyres, lights, rain, visibility — **Cameroon-relevant** (wet season, urban roads) |
| Avoid | Generic European motorway stock; MOT-style government branding |

---

## 3. Photography rules

### 3.1 Style

| Do | Don’t |
| --- | --- |
| Natural or soft fill light; true-to-life colour | Heavy filters, neon colour grades, HDR halos |
| Horizontal heroes 16:9 or 3:2; vertical detail 4:5 for mobile crops | Ultra-wide distortion on lanes |
| Real Yaoundé context where helpful | Anonymous “anywhere Africa” stock |
| Show modern, maintained facilities | Cluttered backgrounds, visible competitor branding |
| Consistent series per centre (same visit/day if possible) | Mixing unrelated shoots on one centre page |

**Colour grading:** Slight warmth acceptable; preserve brand blues and orange accents in scene (uniforms, signage) without oversaturating.

### 3.2 Composition

- **Hero:** Subject centre-weighted; leave **safe title area** (left 40% or lower third) for Manrope headline on **white editorial hero** — not a dark blue overlay ([34-design-system.md](34-design-system.md)). Real centre photography should read bright and clean like the physical environment.
- **Cards:** Centre cards use 4:3 or 16:9 top image; faces optional — building/lane preferred for hub.
- **Gallery:** Centre detail — 6–12 images max published; chronological or spatial order (exterior → reception → lane).
- **Team:** Head-and-shoulders or half-body; neutral background; consistent crop ratio across members.

### 3.3 Legal and privacy

- Written consent for identifiable staff/customers.
- **No readable plates** unless G3 explicitly approves a marketing exception.
- No minors without guardian consent.
- Respect centre security rules during lane shoots.

### 3.4 Stock and placeholders

| Context | Allowed |
| --- | --- |
| Wireframes / hi-fi (steps 37–69) | Grey placeholders with dimension labels |
| Local dev before ingest | Neutral placeholder service — **not** in production |
| Production | **Real G3 only** (BR-MEDIA-001) |

If a temporary gap exists at launch, prefer **text + icon** empty states over misleading stock photos.

---

## 4. Video rules

### 4.1 Production

| Property | Guideline |
| --- | --- |
| Resolution | 1080p minimum master; 4K master **suggestion** if available |
| Aspect | 16:9 primary |
| Length | Feature 60–120s; social cuts out of V1 scope |
| Audio | Clear voiceover or centre ambience; no copyrighted music without licence |
| Tone | Calm, instructional — matches “Simplicité” in the slogan |

### 4.2 Playback (public site)

| Rule | Detail |
| --- | --- |
| **No autoplay with sound** | User gesture required to play |
| **Homepage** | No full-width autoplaying MP4 (NFR-P-04); poster + play button only |
| **Visite technique** | Inline player with poster; lazy-load below fold when possible |
| **Reduced motion** | Respect `prefers-reduced-motion` — show poster, hide auto animations |
| **Controls** | Native or accessible custom controls; keyboard operable (NFR-A-02) |

### 4.3 Delivery formats

| Layer | Format |
| --- | --- |
| Master | `.mp4` (H.264) in `assets/source/video/` |
| Web | Optimised MP4 + optional WebM **suggestion** |
| Poster | AVIF/WebP + JPEG fallback, 16:9, same crop as player |

---

## 5. File management

### 5.1 Repository layout

```text
assets/source/          ← masters (gitignored or LFS — not hot-linked in production)
  brand/
  centres/ecole-de-police/
  centres/nomayos/
  team/
  equipment/
  inspection/
  road-safety/
  video/
public/media/           ← generated conversions only (via Spatie, Phase VI)
```

Masters stay out of git if large; document filenames in admin media library. Site serves **derivatives only** ([08-content](../docs/08-content.md)).

### 5.2 Naming convention

```text
{centre|brand|team|equipment|inspection|road-safety}_{subject}_{variant}_{YYYYMMDD}.{ext}
```

Examples:

- `ecole-de-police_lane_wide_hero_20260401.jpg`
- `brand_logo_primary.svg`
- `video_visite_technique_master_20260401.mp4`

Lowercase, hyphens in slugs, no spaces. Variant: `hero`, `exterior`, `reception`, `lane`, `detail`, `thumb`.

### 5.3 Accepted formats

| Type | Upload (admin) | Web output |
| --- | --- | --- |
| Photo | JPEG, PNG | AVIF + WebP + JPEG fallback (NFR-P-03) |
| Logo | SVG, PNG | SVG preferred; PNG @1x/@2x |
| Video | MP4 | MP4 (+ poster images as above) |

Reject executables and unexpected MIME types (NFR-S-06). **Suggestion:** max 15 MB per photo, 200 MB per video master — confirm at implementation.

---

## 6. Responsive derivatives

Spatie (or equivalent) generates named conversions — wireframes reference these logical sizes:

| Conversion | Width | Typical use |
| --- | --- | --- |
| `thumb` | 400px | Cards, lists |
| `card` | 800px | Centre cards, service tiles |
| `hero` | 1600px | Page heroes, OG when page-specific |
| `hero-xl` | 2400px | Retina desktop hero **suggestion** |
| `gallery` | 1200px | Centre gallery lightbox |
| `square` | 600×600 | Team avatars |

**Crop:** `hero` uses focal-point centre (subject); never stretch. **`object-fit: cover`** in UI; art direction per page in wireframes.

---

## 7. ALT text and titles

| Rule | Detail |
| --- | --- |
| **Language** | ALT matches page locale (NFR-A-06); store FR + EN in media library (FR-MD-02) |
| **Content** | Describe what matters — location, action, equipment — not “image of” filler |
| **Decorative** | Empty ALT (`alt=""`) only if purely decorative (rare) |
| **Title field** | Internal/admin search label; may differ from ALT |
| **Glossary** | EN uses [08-content](../docs/08-content.md) terms — *technical inspection*, not *MOT* |

**Examples**

| FR ALT | EN ALT |
| --- | --- |
| Lane d’inspection au centre École de Police, Yaoundé | Inspection lane at the École de Police centre, Yaoundé |
| Réception du centre Nomayos, vue intérieure | Nomayos centre reception, interior view |
| Contrôle des pneumatiques pendant la visite technique | Tyre check during technical inspection |

Publish blocked until both languages complete (BR-LANG-001).

---

## 8. Page placement map

Where media appears in V1 ([33-information-architecture.md](33-information-architecture.md)):

| Page | Media |
| --- | --- |
| **Home** | Hero image or restrained video poster; centre thumbs; optional inspection still; **no** autoplay video |
| **About** | Team portraits; company/agrément context photo **suggestion** |
| **Centres hub** | One thumb per centre + map (Leaflet — not raster) |
| **Centre detail** | Hero + gallery (6–12); optional reception/lane |
| **Services** | Icon or small photo per service when available |
| **Visite technique** | Feature video + poster; journey step stills |
| **Tarifs** | None required — clarity over decoration |
| **Rendez-vous & Suivi** | None — form-focused |
| **Sécurité routière** | Section headers per topic (rain, tyres, lights…) |
| **Contact** | Optional subtle brand still — not required |

---

## 9. Collections (admin)

Aligns with FR-MD-01 and [10-admin.md](../docs/10-admin.md):

| Collection | Scope |
| --- | --- |
| `brand` | Logos, favicon, default OG |
| `centres` | Per-centre heroes, exteriors, galleries |
| `equipment` | Linked to centre(s) |
| `team` | Portraits; publish per member |
| `inspection` | Process stills for visite technique + homepage journey |
| `road-safety` | Evergreen article imagery |

Unpublished items never appear publicly (FR-MD-02 publish flag).

---

## 10. Wireframe and hi-fi placeholders

Until Q-07 closes:

| Placeholder | Spec |
| --- | --- |
| Hero | `#F1F6FB` block, label `HERO 16:9 · École de Police` |
| Card image | `#F6F7F9` block, `4:3 THUMB` |
| Video | Poster block + ▶ icon; note “VT feature 16:9” |
| Avatar | Circle `#E4E7EC`, initials |

Use design-system greys only — not stock photos in approved wireframes.

---

## 11. Exit criteria (step 35)

- [x] Photography style, composition, and privacy rules documented
- [x] V1 asset inventory and centre shoot checklist defined
- [x] Video production and playback rules aligned with FR-MD-03 and NFR-P-04
- [x] File layout, naming, formats, and derivative sizes specified
- [x] ALT/title bilingual rules and page placement map complete
- [x] Traceability to BR-MEDIA-001, FR-MD-*, and Q-07

**Next:** Step 37 — Desktop wireframe · Accueil.
