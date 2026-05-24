# IK Saudi — Enterprise Frontend UX Guide

Premium industrial UI for Saudi manufacturing / Aramco-vendor positioning. Blade + Tailwind v4 + Alpine.js only.

---

## 1. Improved homepage structure

| Order | Section | Component | Purpose |
|-------|---------|-----------|---------|
| 1 | Cinematic hero | `home/hero` + `home/industrial-background` | Full-viewport, animated grid/beam/scanline |
| 2 | Statistics | `home/stats-band` | Animated counters (`#stats`) |
| 3 | About | `home/about-premium` | Brand story + CMS copy |
| 4 | Services | `home/services-showcase` | Tabbed capabilities |
| 5 | Inline CTA | `home/cta-section` (`inline`) | Mid-page contact prompt |
| 6 | Industries | `home/industries` | Sector cards from CMS |
| 7 | Process | `home/process-timeline` | 5-step methodology (`#process`) |
| 8 | Projects | `home/projects-showcase` | Carousel + dots (`#projects`) |
| 9 | Certifications | `home/certifications` | Trust + certs (`#trust`) |
| 10 | Primary CTA | `home/cta-section` (`primary`) | Consultation + call |
| 11 | CMS blocks | `sections/*` | Extra homepage modules |
| — | Footer CTA | `layout/site-footer` | Global conversion band |

---

## 2. New premium sections

- **Trust bar** — certification badges with hover glow
- **Stats band** — `statCounter` Alpine + Intersection Observer
- **Process timeline** — vertical/horizontal responsive steps
- **CTA banner** — gradient mesh, trust bullets, phone + contact
- **Page loader** — branded ring, respects reduced motion

---

## 3. Refined Tailwind design tokens

See `resources/css/app.css` `@theme`:

| Token | Use |
|-------|-----|
| `--color-navy-950` → `500` | Depth hierarchy |
| `--color-accent` / `accent-light` | Gold CTAs, highlights |
| `--shadow-glow` / `shadow-elevated` | Premium depth |
| `--ease-out-expo` | Cinematic easing |
| `--duration-cinematic` (900ms) | Hero entrance |

**Utilities:** `.hero-cinematic`, `.glass-panel-elevated`, `.card-premium`, `.text-gradient-gold`, `.stat-value`, `.mega-panel`, `.section-divider`

---

## 4. Animation strategy

| Type | Implementation | A11y |
|------|----------------|------|
| Page load | `.page-loader` + Alpine `pageLoader` | Short, skippable via fast load |
| Hero entrance | CSS `@keyframes heroEnter` staggered | Disabled if `prefers-reduced-motion` |
| Scroll reveal | `.reveal` / `.reveal-scale` + `scrollReveal` | All visible immediately when reduced motion |
| Stagger | `.reveal-stagger-1` … `6` | Transition-delay only |
| Counters | `statCounter` + `requestAnimationFrame` | Shows final value if reduced motion |
| Hover | `.card-premium`, `.hover-shine`, button `active:scale` | No motion required |
| Mega menu | Alpine `x-transition` scale + opacity | Keyboard: Escape closes |
| Carousel | `scrollIntoView` + dot sync | Arrow buttons labeled |

**Rule:** No external animation libraries. CSS + Alpine only.

---

## 5. Component improvements

| Component | Changes |
|-----------|---------|
| `ui/button` | Lift on hover, active scale, stronger shadows |
| `ui/card` | `card-premium` lift, gradient overlay on hover |
| `ui/page-hero` | Cinematic beam + grid for inner pages |
| `layout/site-header` | Hover mega menu, mobile accordion services, body scroll lock |
| `layout/site-footer` | Gradient CTA band, refined link hovers |
| `ui/skeleton` | Shimmer loading placeholder |

---

## 6. Mobile UX improvements

- Full-height drawer with close header + scrollable body
- Services **accordion** in mobile nav (no mega panel on small screens)
- Larger touch targets (44px+ on nav buttons)
- Project carousel: `min-w-[88%]` cards, snap center, horizontal padding
- Sticky-safe spacing under fixed header
- `100svh` hero for mobile browser chrome

---

## 7. Industrial-themed UI ideas (implemented)

- Fine + coarse **grid overlays** on heroes and sections
- **Rotating conic beam** (`.hero-beam`) — subtle energy/industrial motion
- **Gold gradient typography** on statistics
- **Glass panels** with inset gold highlight
- **Monospace step numbers** on process timeline
- **Pipeline / facility** placeholder panels until photography is added

---

## 8. Better visual storytelling

1. **Hero** — Who we are (Saudi manufacturer) + why trust us (bullet panel)
2. **Trust bar** — Third-party standards immediately after hero
3. **Stats** — Quantified credibility
4. **About** — Location + sector visual card (Dammam, Oil & Gas)
5. **Services** — Problem/solution via tabbed narratives
6. **Process** — How we work (reduces procurement risk)
7. **Projects** — Proof of delivery
8. **CTA** — Clear next step with response-time promise

---

## 9. Conversion-focused CTA placement

| Position | CTA | Variant |
|----------|-----|---------|
| Hero | Start project / Explore capabilities | Primary + secondary |
| About | Our story / View projects | Primary + outline |
| Services panel | Service details | Primary per tab |
| CTA banner | Book consultation / Call now | Primary + secondary |
| Header (sticky) | Contact | Primary sm |
| Footer band | Contact + Services | Primary + secondary |

**Hierarchy:** One gold primary per viewport; phone CTA on banner for high-intent users.

---

## 10. Recommended imagery direction

| Area | Direction |
|------|-----------|
| **Hero** | Wide cinematic shot: Dammam facility exterior, pipeline infrastructure at golden hour, or abstract industrial steel with navy grade |
| **About card** | Aerial of 2nd Industrial City or workshop floor with safety gear |
| **Services** | One iconographic or photo per service: scraping tools, polyurethane application, subsea hardware |
| **Projects** | Before/after pipeline work, on-site teams in PPE, client-approved case photos (upload via admin `featured_image`) |
| **Process** | Optional small icons per step — blueprint, CNC/manufacturing, field deployment |
| **Trust** | Official certification marks (ISO, ASME, API) as SVG logos when licensed |
| **Tone** | Cool navy shadows, warm gold accents, high contrast, no stock “handshake” clichés |
| **People** | Saudi/GCC workforce in proper PPE, diverse engineering teams |
| **Format** | WebP, 1920px hero, 800px cards, lazy-load below fold |

---

## Files reference

```
resources/css/app.css          Tokens + utilities
resources/js/app.js            Alpine stores
resources/views/pages/home.blade.php
resources/views/components/home/*
resources/views/components/layout/*
resources/views/components/ui/*
config/design.php              PHP token mirror
```

---

*Enterprise UI v2 — industrial premium.*
