# Website Settings

Centralized global configuration for the IK Saudi public site, managed from **Filament → System → Website Settings** (`/ik-admin/website-settings`).

## Architecture

```
config/website-settings.php     # Registry (keys, types, groups)
app/Services/SettingsService.php
app/Data/WebsiteSettingsBag.php
app/Models/SiteSetting + SiteSettingTranslation
database/seeders/SiteSettingsSeeder.php
```

### Storage

| Column / table | Purpose |
|----------------|---------|
| `site_settings.key` | Dot key (`general.logo`, `contact.phones`) |
| `site_settings.value` | Non-translatable scalar / JSON |
| `site_setting_translations.value` | Per-locale text |
| `site_settings.type` | `text`, `textarea`, `boolean`, `json`, `image` |

### Cache

- Key: `site_settings.v1.{locale}` (TTL from `SITE_SETTINGS_CACHE_TTL`, default 3600s)
- Cleared automatically via `SiteSettingObserver` on save/delete
- Disable globally: `SITE_SETTINGS_CACHE_ENABLED=false`

## Helpers

```php
setting('contact.phones');           // Current locale for translatable keys
setting('general.site_name', null, 'en');
settings('footer');                  // Whole group
setting_url('general.logo');         // Public disk URL
```

```blade
{{ setting('footer.copyright') }}
<img src="{{ setting_url('general.logo') }}" alt="{{ setting('general.site_name') }}" />
```

`$siteSettings` (`WebsiteSettingsBag`) is injected on layout, header, footer, and contact views.

## Filament UI

Tabbed settings page with sections, repeaters (phones, emails, social, footer links), color pickers, and image uploads (`storage/app/public/site-settings/`).

**Permission:** `system.manage` (super_admin / admin).

## Seeding

```bash
php artisan db:seed --class=SiteSettingsSeeder
```

## Security

- API keys (Mailchimp) stored in DB — restrict admin roles; consider encrypting at rest in production.
- Maps embed HTML is output raw — only trusted admins should edit.
- SMTP fields are documentation placeholders; real mail stays in `.env`.
- Image uploads use `config/security.php` mime/size limits.

## Performance

- Single cached payload per locale (not N+1 per key).
- Use `WebsiteSettingsBag` in views instead of repeated `setting()` calls when reading many values.

## Recommended extras

- `general.cookie_consent_enabled` + banner copy
- `contact.office_locations` repeater (multi-city)
- `branding.header_scripts` / `footer_scripts` for verified third-party snippets
- `seo.hreflang_defaults` for multilingual SEO
- Feature flags: `modules.news_enabled`, `modules.careers_enabled`
- `integrations.recaptcha_site_key` for public forms

## Blade example

```blade
@foreach ($siteSettings->socialLinks() as $social)
    <a href="{{ $social['url'] }}" rel="noopener">{{ $social['platform'] }}</a>
@endforeach
```
