# IK Saudi Corporate Website

Laravel 11 multilingual CMS and public website for **IK Saudi For Industries** — Saudi industrial manufacturing (pipeline intervention, Oil & Gas). Arabic-first, premium corporate UI, Filament admin at `/ik-admin`.

---

## Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 · PHP 8.2+ |
| Admin | Filament v5 (`/ik-admin`) |
| Frontend | Tailwind CSS v4 · Alpine.js · Blade components |
| Auth & roles | Spatie Laravel Permission |
| Activity log | Spatie Activity Log |
| Database | MySQL (production) / SQLite (dev) |
| Fonts | Inter (LTR) · IBM Plex Sans Arabic (RTL) via Fontsource |

---

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
php artisan serve
```

| | |
|---|---|
| **Public site** | http://localhost:8000 → redirects to `/ar` |
| **Admin** | http://localhost:8000/ik-admin |
| **Login** | `admin@iksaudi.com` / `ChangeMe!2026` *(change immediately)* |

### MySQL (production / local)

```bash
mysql -u root -e "CREATE DATABASE iksaudi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Set in `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=iksaudi`, `DB_COLLATION=utf8mb4_unicode_ci`

---

## Feature list (implemented)

### Public website

- [x] **Bilingual URLs** — `/ar/...` and `/en/...` with Arabic default
- [x] **Locale redirect** — `/` → preferred locale
- [x] **Premium design system** — navy / steel / gold palette, glass panels, industrial grid, scroll reveal ([docs/DESIGN_SYSTEM.md](docs/DESIGN_SYSTEM.md))
- [x] **Responsive layout** — mobile drawer nav, sticky glass header, mega menu (desktop)
- [x] **RTL / LTR** — `dir` on `<html>`, logical CSS, Arabic font stack
- [x] **Accessibility** — skip link, focus rings, reduced-motion support, ARIA on nav/carousel
- [x] **Homepage**
  - CMS-driven hero, about snippet, remaining sections
  - Interactive **services tabs** (featured services)
  - **Projects carousel** with links to detail pages
- [x] **Services** — listing grid + detail page (body, industries, CTAs)
- [x] **Projects** — listing grid + detail page (image, body, highlights, client/year, related services)
- [x] **CMS pages** — e.g. `/ar/about-us` from `pages` table
- [x] **Contact** — info panel + **working form** (saves to DB, reference number on success)
- [x] **SEO component** — meta title/description, OG tags, canonical per page
- [x] **Legacy URL redirects** — `*.html` paths → 301 to new routes (`RedirectSeeder`)

### Admin CMS (`/ik-admin`)

- [x] **13 Filament resources** — see [docs/ADMIN_ARCHITECTURE.md](docs/ADMIN_ARCHITECTURE.md)
- [x] **Translation tabs** (AR / EN) on content resources
- [x] **Publish workflow** — draft/publish, `published_at`, sort order
- [x] **SEO fields** per locale on content types
- [x] **Homepage sections** — reorderable blocks (hero, about, services grid, CTA, etc.)
- [x] **Roles** — `super_admin`, `admin`, `editor`, `hr` (Spatie)
- [x] **Contact submissions** — read/update in admin (from public form)
- [x] **Project featured image** — upload in admin, shown on public site
- [x] **Dashboard widget** — CMS overview

### Backend & data model

- [x] **Custom translation tables** (`*_translations`) — no third-party i18n package
- [x] **Shared model concerns** — `HasTranslations`, `Publishable`, `HasSeoMeta`
- [x] **Catalog services** — `HomePageService`, `ServiceCatalogService`, `ProjectCatalogService`, `PageService`, `LocaleService`
- [x] **Caching** — featured services/projects and slug lookups (1h TTL)
- [x] **Middleware** — `SetLocale`, `SecurityHeaders`, legacy redirect handling
- [x] **Seeders** — locales, admin user, roles, home content, pages, services, projects, redirects

### UI component library

| Component | Purpose |
|-----------|---------|
| `ui/button` | Primary, secondary, ghost, outline |
| `ui/card` | Glass cards with hover |
| `ui/badge` | Tags / status |
| `ui/section-heading` | Overline + title + subtitle |
| `ui/page-hero` | Inner page heroes |
| `ui/breadcrumb` | Navigation trail |
| `ui/cta-strip` | End-of-page call to action |
| `ui/project-image` | Project thumbnail with placeholder |
| `ui/form-field` | Accessible form inputs |
| `layout/site-header` | Mega menu + mobile drawer |
| `layout/site-footer` | CTA band + columns |
| `home/hero` | Homepage hero + stats |
| `home/services-showcase` | Tabbed services |
| `home/projects-showcase` | Horizontal carousel |

---

## Public routes

| URL | Description |
|-----|-------------|
| `/` | Redirect to `/ar` or `/en` |
| `/{locale}` | Homepage |
| `/{locale}/services` | Services listing |
| `/{locale}/services/{slug}` | Service detail |
| `/{locale}/projects` | Projects listing |
| `/{locale}/projects/{slug}` | Project detail |
| `/{locale}/contact` | Contact (GET form / POST submit) |
| `/{locale}/{slug}` | CMS page (e.g. `about-us`) |
| `/{legacy}.html` | 301 redirect to new URL |

---

## Architecture decisions

| Topic | Choice |
|-------|--------|
| Translations | Custom `*_translations` tables (full control) |
| Default locale | Arabic (`ar`) with English fallback |
| Admin path | `/ik-admin` (not `/admin`) |
| Legacy URLs | `redirects` table + `LegacyRedirectController` |
| Design tokens | `config/design.php` + Tailwind v4 `@theme` in `resources/css/app.css` |

---

## Key paths

```
app/Models/                 Eloquent + Concerns (HasTranslations, Publishable, HasSeoMeta)
app/Filament/Resources/     13 CMS resources
app/Http/Controllers/Web/   Home, Page, Service, Project, Contact, Locale, Legacy
app/Services/               Locale, HomePage, ServiceCatalog, ProjectCatalog, Page
app/Http/Middleware/        SetLocale, SecurityHeaders
config/design.php           Design system tokens (PHP)
database/migrations/        Full CMS schema (~20 app migrations)
database/seeders/           Demo content + admin + redirects
resources/views/            Public Blade + components
resources/css/app.css       Tailwind v4 theme + utilities
resources/js/app.js         Fonts + Alpine (header, reveal, tabs, carousel)
docs/DESIGN_SYSTEM.md       UI/UX documentation
docs/ADMIN_ARCHITECTURE.md  Filament CMS documentation
```

---

## Development

```bash
npm run dev              # Vite HMR
php artisan migrate:fresh --seed
php artisan view:clear
php artisan route:list
```

---

## Feedback & project status

### Strengths

1. **Solid foundation** — Schema, models, and Filament admin cover the full content model (services, projects, news, careers, partners, etc.) even where public pages are not built yet.
2. **Arabic-first** — Locale routing, RTL typography, and translation tables are implemented correctly for a Saudi corporate site.
3. **Premium public UI** — Consistent design system across homepage and inner pages; suitable for industrial / Aramco-vendor positioning.
4. **Editor-friendly CMS** — Translations, publish flags, SEO, and homepage sections give marketing teams control without code changes.
5. **SEO & migration ready** — Per-locale meta, legacy redirects, and clean slug URLs support launch from the old site.

### Recommended before production launch

| Priority | Item |
|----------|------|
| High | Change default admin password; enforce strong passwords / 2FA |
| High | Configure mail (contact form notifications to `info@iksaudi.com`) |
| High | `APP_DEBUG=false`, HTTPS, `config:cache`, `route:cache`, `view:cache` |
| Medium | Add real content, logos, and project photos in admin |
| Medium | Privacy / Terms pages (footer links are placeholders) |
| Medium | Custom 404 / 500 error pages matching design system |
| Low | Rate-limit contact form; honeypot or reCAPTCHA for spam |

### Not yet implemented (backlog)

Public pages still to build when needed:

- News / blog listing and articles
- Careers listing and application form
- Industries, certifications, clients, partners showcase pages
- Site-wide search
- Newsletter signup
- Spatie Media Library (galleries) — projects use single `featured_image` for now
- Filament Shield UI polish / granular permissions UI
- Queue workers for async mail and jobs
- Sitemap.xml and `robots.txt` generation
- Analytics / GTM integration

### Technical notes

- **Filament v5** was installed (requires PHP 8.2+); docs may reference v3 patterns in places — admin code targets v5 APIs.
- **Cache**: After bulk CMS edits, run `php artisan cache:clear` or wire model observers to clear catalog cache keys.
- **Storage**: Run `php artisan storage:link` so project images in `storage/app/public/projects` are web-accessible.

---

## Production checklist

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Environment:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_LOCALE=ar`
- `APP_URL=https://your-domain.com`
- MySQL credentials + `DB_COLLATION=utf8mb4_unicode_ci`

---

## Documentation

- [Design system](docs/DESIGN_SYSTEM.md) — colors, typography, components, CTA strategy
- [Frontend UX (enterprise)](docs/FRONTEND_UX.md) — homepage structure, animations, imagery, conversion
- [Production hardening](docs/PRODUCTION_HARDENING.md) — security audit, deployment, cache, queues, backups
- [Admin architecture](docs/ADMIN_ARCHITECTURE.md) — Filament resources, roles, translation workflow

---

## License

Proprietary — IK Saudi For Industries Company.
