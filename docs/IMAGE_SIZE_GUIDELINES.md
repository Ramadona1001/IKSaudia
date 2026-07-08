# Image Size Guidelines

**Project:** IK Saudi Manufacturing (front website)  
**Audience:** Content editors, designers, and developers  
**Last updated:** 2026-07-08

Upload targets below are **recommended source dimensions**. The site uses `object-fit: cover` or `contain`, so slightly different crops still work if the aspect ratio is close.

---

## Quick reference

| Image type | Recommended size (W × H) | Aspect ratio | Format |
|---|---|---|---|
| Home hero slide | **1920 × 1080** | 16:9 | JPG / WebP |
| Inner page hero pattern tile | **60 × 60** (or seamless tile) | 1:1 tile | PNG / SVG |
| About (home) photo | **1200 × 800** | 3:2 | JPG / WebP |
| Service / project card | **800 × 400** | 2:1 | JPG / WebP |
| Service / page detail banner | **1400 × 760** | ~16:9 | JPG / WebP |
| Industry / product card | **1000 × 800** | 5:4 | JPG / WebP |
| Industry / product detail | **1400 × 720** | ~2:1 | JPG / WebP |
| Project detail | **1600 × 840** | ~16:9 | JPG / WebP |
| Client / partner / certification logo | **360 × 200** | ~9:5 | PNG (transparent) |
| Site logo (header / footer) | **200 × 200** (or SVG) | 1:1 | SVG / PNG |
| Sticky / loader logo | **440 × 280** | ~11:7 | PNG / SVG |
| Favicon | **64 × 64** (+ **180 × 180** Apple) | 1:1 | PNG / ICO |
| Open Graph / SEO | **1200 × 630** | ~1.91:1 | JPG / PNG |
| Testimonial avatar | **108 × 108** (min **216 × 216** @2x) | 1:1 | JPG / PNG |

---

## Detailed guidelines by type

### 1. Home hero slides

| | |
|---|---|
| **Where** | Home page slider (`home-sections` › Hero) |
| **CSS** | Full viewport width, `height: 100vh`, `min-height: 700px`, `background-size: cover` |
| **Upload** | **1920 × 1080** (minimum **1600 × 900**) |
| **Notes** | Keep important subjects in the center third; text overlays sit over a dark gradient. Avoid text baked into the image. |
| **Optimization** | WebP preferred; JPEG quality ~75–85; aim &lt; 350 KB per slide. |

### 2. About section image (home)

| | |
|---|---|
| **Where** | Home › About snippet `featured_image` |
| **CSS** | `.about-img-main` height **500px**, full column width, `object-fit: cover` |
| **Upload** | **1200 × 800** (or **1000 × 500** for a wider crop) |
| **Notes** | Left side on desktop; badge/float overlay near corners — leave slight margin from edges. |

### 3. Service images

| Use | CSS | Upload |
|---|---|---|
| Card | `.service-img-wrap` height **200px**, cover | **800 × 400** |
| Detail banner | Inline height **380px**, cover | **1400 × 760** |

Admin: Services → Featured image (`services/`). Editor ratios: 16:9, 4:3, 1:1.

### 4. Industry & product images

| Use | CSS | Upload |
|---|---|---|
| Card | `.industry-card` height **400px**, cover | **1000 × 800** |
| Detail | Height **360px**, cover | **1400 × 720** |

Admin: Industries / Products → Featured image.

### 5. Project images

| Use | CSS | Upload |
|---|---|---|
| Home / index card | Height **200–220px**, cover | **800 × 400** |
| Detail hero | Height **420px**, cover | **1600 × 840** |

Admin: Projects → Featured image.

### 6. CMS page featured image

| | |
|---|---|
| **Where** | Static CMS pages |
| **CSS** | Height **380px**, cover |
| **Upload** | **1400 × 760** |

### 7. Logos — clients, partners, certifications

| | |
|---|---|
| **Where** | Home marquees / grids, Clients, Partners, Certifications cards |
| **CSS** | `object-fit: contain`; max height **60–100px**; max width ~**180px** |
| **Upload** | **360 × 200** (or **320 × 144**) on transparent background |
| **Notes** | Prefer SVG or PNG with transparency. Center the mark; avoid text-heavy full-bleed photos. Pad ~10% around the logo. |
| **Optimization** | SVG best; PNG ≤ 80 KB. |

### 8. Site branding logos

| Asset | Setting key | Upload |
|---|---|---|
| Main / sticky header | `general.logo`, `general.logo_sticky` | **200 × 200** SVG/PNG (displays ~100px wide) |
| Footer | `general.logo_footer` | Same as main |
| Dark variant | `general.logo_dark` | Same |
| Loader | `branding.loading_logo` | **440 × 280**, max display ~220 × 140 |
| Favicon | `general.favicon` | **64 × 64** (+ 180 for Apple touch) |

### 9. Open Graph / social share

| | |
|---|---|
| **Setting** | `seo.og_image` / `general.seo_default_image` |
| **Upload** | **1200 × 630** |
| **Notes** | Safe zone: keep logo and title away from outer 5%. |

### 10. Testimonial avatars

| | |
|---|---|
| **Where** | Testimonials cards (Clients page / future CMS) |
| **CSS** | **54 × 54** circle, `object-fit: cover` |
| **Upload** | **216 × 216** (2× retina) |

### 11. Inner page hero pattern

| | |
|---|---|
| **Setting** | `branding.page_hero_pattern` (+ custom image) |
| **Upload (custom)** | Seamless tile, typically **60 × 60**–**120 × 120** PNG/SVG |
| **Notes** | Transparent patterns with brand accent work best. |

---

## Optimization recommendations

1. **Formats:** WebP for photos; SVG/PNG for logos; avoid large unoptimized PNGs for photos.
2. **Weight targets:** Hero &lt; 350 KB · Detail banners &lt; 250 KB · Cards &lt; 150 KB · Logos &lt; 80 KB.
3. **Lazy loading:** Front markup already uses `loading="lazy"` on many logos; keep that for below-the-fold images.
4. **Retina:** For logos and avatars, upload 2× display size; for full-width heroes, 1920px wide is enough for most viewports.
5. **Admin crop tools:** Filament image editors offer 16:9, 4:3, and 1:1 — prefer **16:9** for heroes/banners and **1:1** only for avatars/favicons.
6. **Naming:** Use descriptive, kebab-case filenames (e.g. `hero-petrochem-line.webp`) before upload.

---

## Admin upload map

| Content | Admin path | Field |
|---|---|---|
| Hero slides | Home Sections → Hero | Slide image |
| About photo | Home Sections → About | Featured image |
| Services / Industries / Products / Projects | Respective Filament resources | Featured image |
| Clients / Partners / Certifications | Respective resources | Featured image |
| Site logos & favicon | Website Settings → General | Logo fields |
| Colors & hero pattern | Website Settings → Branding | Brand / region colors |
| OG image | Website Settings → SEO | OpenGraph image |

---

## Not yet on the live front

These exist in admin (or as components) but have **no public page** today. Dimensions still apply when wired:

| Asset | Suggested size |
|---|---|
| News / blog featured | **1200 × 675** (16:9) |
| Gallery images | **1600 × 1200** |
| Team photos | **800 × 800** |
| Careers featured | **1200 × 630** |
| Footer background setting | **1920 × 800** (if enabled later) |
