# Development Meeting Prep — Website Updates (Jul 2026)

## 1. Source code access

The project is a complete **Laravel 11 + Filament 5** CMS in this repository (`d:\IK Saudia`).

| Area | Location |
|---|---|
| App / domain | `app/` |
| Front views | `resources/views/front/` |
| Front CSS / JS | `public/assets/front/` |
| Admin (Filament) | `app/Filament/` |
| Config / settings | `config/website-settings.php` |
| Composer deps | `composer.json` → `vendor/` |
| NPM / Vite | `package.json` → `node_modules/` |
| Docs | `docs/` |

**To run locally:** clone/pull the repo, copy `.env`, run `composer install`, `npm install`, `php artisan migrate`, `php artisan db:seed` (as needed), `php artisan storage:link`, then `php artisan serve` (+ `npm run build` or `dev`).

No critical source folders are missing from the working tree. Dependencies are managed via Composer and npm (install required on each machine).

---

## 2. Completed changes (this task)

### Testimonials — horizontal centering

- Added CSS for `.testimonials-grid` (flex, `justify-content: center`) so cards sit centered when there are fewer than a full row.
- Swiper slide styles also center cards for slider use.
- Wired the **Testimonials** section on the **Clients** page with three sample cards (EN + AR copy), matching the design reference layout (centered header + centered card row).

### Independent color management

**Problem:** Only three global brand colors (`primary` / `secondary` / `accent`) drove `--c-dark`, which also colored body background and header **nav link** text — so changing “main” color changed the header appearance.

**Solution:**

- Introduced **region CSS variables** in `main.css`:
  - `--page-bg`, `--header-*`, `--hero-text`, `--footer-*`, `--section-*`
- Mapped header / body / footer styles to those region tokens (no longer `brand-primary` → header text by force).
- Extended **Website Settings → Branding** with separate admin color pickers:
  - Brand palette (primary / secondary / accent)
  - Page & hero (`page_bg`, `hero_text`)
  - Header (`bg`, `nav text`, `hover`, `icon bg`)
  - Footer (`bg`, `text`, `accent`)
- Updated `brand-colors.blade.php`, `config/website-settings.php`, and `SiteSettingsSeeder`.

Changing page/primary color no longer forces header bar or footer to follow; those have their own settings (with safe defaults).

### Image size guidelines

Documented in [`docs/IMAGE_SIZE_GUIDELINES.md`](IMAGE_SIZE_GUIDELINES.md): every live front image type, recommended W×H, aspect ratio, admin path, and optimization notes.

### Certifications / home theme (prior work in this branch)

- Homepage post-hero white content area (`.home-main`).
- Certifications as **image-only cards** (not marquee).

---

## 3. Technical limitations

| Topic | Limitation |
|---|---|
| Testimonials CMS | No Filament `Testimonial` model/resource yet. Clients page uses **sample** lang strings. Real CMS wiring is a follow-up. |
| Dual themes | Bootstrap/`front` theme is live; a parallel Tailwind layout under `resources/views/layouts` / `components/home` is not used by Web controllers. |
| Color pickers empty DB | New region keys fall back until saved in Website Settings (defaults match current look). Re-save Branding once to persist. |
| Cards still use brand primary | Service/foundation/FAQ card fills still use `--brand-primary` for CTAs/cards — intentional shared accent, not header/footer. Per-section card palettes can be expanded later if needed. |
| `color-mix()` | Used for borders/gradients; requires modern browsers (already used in the theme). |
| Hero hardcoded overlays | Some hero particle/gradients remain partly fixed; region vars cover page bg + hero text. |

---

## 4. Questions / clarifications for the meeting

1. **Testimonials content source** — Confirm whether to build a full Admin resource (CRUD + avatars) or keep static/CMS home-section items for v1.
2. **Where to show testimonials** — Clients page only, homepage also, or both?
3. **Sample quotes** — Are the temporary EN/AR testimonials OK until real client quotes are provided?
4. **Region colors ownership** — Should editors freely change header/footer independently, or should header stay locked to brand and only page/hero stay editable?
5. **Per-section palettes** — Do we need separate pickers for About / Services / FAQ backgrounds, or are brand + page + header + footer enough?
6. **Image guidelines adoption** — Should we add helper text under each Filament upload with the recommended size from `IMAGE_SIZE_GUIDELINES.md`?
7. **Source delivery** — Prefer Git access only, or a zip of the repo (excluding `vendor`/`node_modules`) for stakeholders?

---

## 5. Suggested verification checklist

- [ ] Soft-refresh front CSS; confirm header stays white when primary color changes in admin.
- [ ] Set a distinct **Header nav link** color and confirm only nav text changes.
- [ ] Set a distinct **Footer background** and confirm footer updates independently.
- [ ] Open `/clients` (EN + AR) — three testimonial cards centered in the section.
- [ ] Confirm Certifications cards and home white sections still look correct.
- [ ] Review `docs/IMAGE_SIZE_GUIDELINES.md` with the content team.
