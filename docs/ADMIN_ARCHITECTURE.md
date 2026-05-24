# IK Saudi — Filament CMS Admin Architecture

## Panel

| Setting | Value |
|---------|--------|
| Path | `/ik-admin` |
| Package | Filament v5 |
| Auth | Laravel session + `FilamentUser` contract |
| Locales managed | `ar` (default), `en` |

## Navigation structure

```
Dashboard (stats widget)
├── Homepage
│   └── Homepage Sections (reorderable)
├── Content
│   ├── Services
│   ├── Industries
│   ├── Projects
│   ├── Certifications
│   ├── Clients
│   ├── Partners
│   ├── News
│   ├── Careers
│   └── Pages
├── Engagement
│   ├── Career Applications (read/update only)
│   └── Contact Submissions (read/update only)
└── Structure
    └── URL Redirects (301 map)
```

## Translation pattern

All public content uses **custom translation tables** (not Astrotomic):

```
services → service_translations (locale, title, slug, …)
```

### Filament form flow

1. **FormSchemas** — shared publish block, AR/EN tabs, SEO sections  
2. **CreateTranslatableRecord** / **EditTranslatableRecord** — sync translations + SEO on save  
3. **SyncsModelTranslations** + **SyncsSeo** traits  

### SEO

Polymorphic `seo_meta` table per entity per locale (`meta_title`, `meta_description`, `og_image`).

## Roles (Spatie Permission)

| Role | Access |
|------|--------|
| `super_admin` | Everything (Gate bypass) |
| `admin` | Full CMS permissions |
| `editor` | Content CRUD + publish |
| `hr` | Careers + applications |

Seed: `php artisan db:seed --class=RolePermissionSeeder`

## File layout

```
app/Filament/
├── Concerns/           # SyncsModelTranslations, SyncsSeo
├── Navigation/         # NavigationGroup constants
├── Resources/
│   ├── Concerns/       # CreateTranslatableRecord, EditTranslatableRecord
│   ├── {Entity}/
│   │   ├── {Entity}Resource.php
│   │   ├── Schemas/{Entity}Form.php
│   │   ├── Tables/{Entity}Table.php
│   │   └── Pages/ List, Create, Edit
├── Support/            # FormSchemas helpers
└── Widgets/            # CmsOverviewWidget
```

## Adding a new content module

1. Create model + `{model}_translations` migration  
2. Add `HasTranslations`, `Publishable`, `HasSeoMeta` traits as needed  
3. Copy `Industries/` resource bundle and rename  
4. Extend `CreateTranslatableRecord` / `EditTranslatableRecord`  
5. Register sort order + `NavigationGroup::CONTENT` on Resource  
6. Add public route + controller when ready for frontend  

## Security notes

- Inactive users cannot access panel (`canAccessPanel`)  
- Admin path is non-default (`ik-admin`)  
- Enable 2FA via Filament Breezy (recommended for production)  
- Assign least-privilege roles to editors  
