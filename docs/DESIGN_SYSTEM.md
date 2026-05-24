# IK Saudi — Premium Industrial Design System

Corporate design language for a Saudi industrial manufacturing company (Oil & Gas, pipeline intervention). Targets **Aramco vendor–grade** polish: dark navy, steel neutrals, restrained gold accent, strong typography, subtle motion.

---

## 1. Brand principles

| Principle | Application |
|-----------|-------------|
| **Premium corporate** | Generous whitespace, glass panels, restrained gradients |
| **Industrial** | Grid overlays, technical overlines, metric stats |
| **Saudi context** | Arabic-first (`ar` default), RTL/LTR parity, IBM Plex Arabic |
| **Trust** | Certifications, clients, clear CTAs to contact |
| **Performance** | CSS-only where possible; Alpine for menus/tabs/carousel |

---

## 2. Color palette

Defined in `config/design.php` and `resources/css/app.css` (`@theme`).

| Token | Hex | Usage |
|-------|-----|--------|
| `navy-950` | `#050d18` | Page background |
| `navy-900` | `#0a1628` | Footer, deep sections |
| `navy-800` | `#0f2240` | Cards, panels |
| `navy-700` | `#163058` | Hover states |
| `steel-100`–`500` | Cool greys | Body text hierarchy |
| `accent` | `#c9a227` | CTAs, overlines, focus rings |
| `accent-light` | `#e4c65a` | Hover on accent |
| `white` | `#ffffff` | Headlines on dark |

**Contrast:** Body text uses `steel-200`/`steel-300` on navy; primary buttons use `navy-950` on `accent` (WCAG AA for large text).

---

## 3. Typography

| Utility | Size (approx) | Use |
|---------|----------------|-----|
| `.text-display-2xl` | 4.5rem | Hero headline |
| `.text-display-xl` | 3.75rem | Section heroes |
| `.text-display-lg` | 3rem | Section titles |
| `.text-display-md` | 2.25rem | Cards |
| `.text-body-lg` | 1.125rem | Lead paragraphs |
| `.text-overline` | 0.75rem caps | Labels, mega menu groups |

**Fonts:** Inter (LTR), IBM Plex Arabic (RTL) — loaded in `resources/js/app.js` via Fontsource.

**Locale rule:** `html[lang="ar"]` applies Arabic font stack automatically.

---

## 4. Spacing & layout

| Token | Value |
|-------|--------|
| `.container-iks` | `max-w-7xl`, responsive horizontal padding |
| `.section-padding` | `py-16 md:py-24 lg:py-28` |
| `.section-padding-sm` | `py-12 md:py-16` |

Grid: 12-column mental model; homepage sections stack → 2-col at `lg`.

---

## 5. Motion & accessibility

- **Reveal:** `.reveal` + `scrollReveal` Alpine (Intersection Observer)
- **Reduced motion:** `@media (prefers-reduced-motion: reduce)` disables transforms
- **Focus:** Visible rings on `accent`; skip link in `layouts/app.blade.php`
- **ARIA:** `role="navigation"`, `aria-expanded` on mobile menu, carousel `aria-label`

---

## 6. Tailwind / CSS architecture

Tailwind v4 — tokens in `resources/css/app.css`:

```css
@theme {
  --color-navy-950: #050d18;
  /* ... */
}
```

Component layer: `.glass-panel`, `.btn`, `.mega-menu`, `.nav-link`, `.reveal`.

No separate `tailwind.config.js` required for v4; reference `config/design.php` for PHP/CMS use.

---

## 7. Component library

| Component | Path | Notes |
|-----------|------|--------|
| Button | `components/ui/button.blade.php` | `primary`, `secondary`, `ghost`, `outline`; sizes `sm`/`md`/`lg` |
| Card | `components/ui/card.blade.php` | Glass border, optional hover lift |
| Badge | `components/ui/badge.blade.php` | Status / tags |
| Section heading | `components/ui/section-heading.blade.php` | Overline + title + subtitle |
| Site header | `components/layout/site-header.blade.php` | Mega menu, mobile drawer |
| Site footer | `components/layout/site-footer.blade.php` | CTA band + columns |
| Hero | `components/home/hero.blade.php` | Stats, dual CTA |
| Services showcase | `components/home/services-showcase.blade.php` | Tabbed interactive panel |
| Projects showcase | `components/home/projects-showcase.blade.php` | Horizontal carousel |

---

## 8. Public routes (inner pages)

| Route | View |
|-------|------|
| `/{locale}/services` | `services/index` — service grid |
| `/{locale}/services/{slug}` | `services/show` — detail + sidebar |
| `/{locale}/projects` | `projects/index` — project grid |
| `/{locale}/projects/{slug}` | `projects/show` — detail + metadata sidebar |
| `/{locale}/contact` | `pages/contact` — form + info |
| `/{locale}/{slug}` | `pages/show` — CMS pages |

Shared layout components: `ui/page-hero`, `ui/breadcrumb`, `ui/cta-strip`, `ui/project-image`, `ui/form-field`.

Project images: upload via Filament → `featured_image` on `projects` table (public disk).

---

## 9. Homepage structure

1. **Hero** — CMS-driven copy + industrial gradient mesh  
2. **About** — Two-column: heading + glass panel + link to About page  
3. **Services** — Tab list + detail panel (`servicesTabs`)  
4. **Projects** — Carousel (`projectsCarousel`)  
5. **CMS sections** — CTA, certifications, etc. via `sections/*` includes  

Controller: `HomeController` passes `featuredServices`, `featuredProjects`. Header mega menu: `View::composer` on `site-header`.

---

## 10. Mobile navigation UX

- Sticky glass header (`backdrop-blur`, `border-white/10`)
- **Hamburger** opens full-height drawer (`translate` + `opacity`, `x-trap`)
- **Accordion** sub-nav for Services on small screens
- **Locale switcher** in header (AR | EN)
- Touch targets ≥ 44px; body scroll locked when menu open

---

## 11. Mega menu (desktop)

- Trigger: Services + chevron  
- Panel: 3-column grid — featured services, quick links, CTA card  
- Keyboard: focusable links; closes on outside click / Escape (extend in Alpine if needed)  
- Data: `$featuredServices` from `ServiceCatalogService`

---

## 12. CTA strategy

| Placement | Action | Variant |
|-----------|--------|---------|
| Hero | Contact + Services | Primary + outline |
| Footer band | Contact + Services | Primary + secondary |
| Section ends | Contextual (About → Read more) | Outline |
| Sticky header | Contact icon/link | Ghost |

**Hierarchy:** One primary (`accent`) per viewport; secondary = glass/white border.

Config reference: `config/design.php` → `cta` array.

---

## 13. RTL / LTR

- `dir` on `<html>` from middleware / layout  
- Logical properties: `ms-`, `me-`, `ps-`, `pe-`, `start-`, `end-`  
- Carousel prev/next swap labels in Arabic  
- Mirror chevrons via `rtl:rotate-180` where needed  

---

## 14. Build & usage

```bash
npm run build
php artisan view:clear
```

Use layout:

```blade
@extends('layouts.app')
@section('content')
  <x-home.hero />
@endsection
```

---

## 15. File index

| File | Purpose |
|------|---------|
| `config/design.php` | Design tokens for app/config |
| `resources/css/app.css` | Theme + utilities |
| `resources/js/app.js` | Fonts + Alpine stores |
| `docs/DESIGN_SYSTEM.md` | This document |

---

*Last updated: design system v1 — premium industrial corporate.*
