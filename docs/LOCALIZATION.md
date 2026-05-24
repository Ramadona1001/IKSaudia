# Localization

Public UI strings live in structured PHP files under `lang/{locale}/`. CMS **content** (page titles, service bodies) remains in database translation tables.

## Directory layout

```
lang/
├── en/
│   ├── common.php
│   ├── navigation.php
│   ├── home.php
│   ├── services.php
│   ├── projects.php
│   ├── contact.php
│   ├── footer.php
│   ├── buttons.php
│   └── cms.php          # Filament admin chrome
└── ar/
    └── (same files)
```

## Usage in Blade

```blade
{{ __('navigation.home') }}
{{ __('home.hero.default_title') }}
{{ __('buttons.contact_us') }}
```

Locale is set by `SetLocale` middleware from the URL segment (`/ar/...`, `/en/...`). You do not need `$locale === 'ar'` for copy.

Keep `$locale` only for **routing** and **direction**:

```blade
@php $locale = app()->getLocale(); @endphp
<a href="{{ route('contact', $locale) }}">{{ __('buttons.contact_us') }}</a>
```

RTL/LTR is applied on `<html dir="...">` via `$textDirection` from middleware — not from translation files.

## Validation attributes

```php
// StoreContactSubmissionRequest
'name' => __('contact.attributes.name'),
```

## CMS (Filament)

```php
Tab::make(__('cms.tabs.arabic'))
TextInput::make('title')->label(__('cms.fields.title'))
```

## Helpers

`App\Support\LocaleHelper` provides ordered keys for repeated lists (process steps, stats) and `LocaleHelper::trans()` with explicit fallback to `config('app.fallback_locale')`.

## Missing translation fallback

1. **Primary:** `config('app.fallback_locale')` (default `en`) via Laravel’s translator.
2. **Runtime:** `AppServiceProvider` registers `Lang::handleMissingKeysUsing()` to log missing keys in non-production and return the fallback locale string when available.
3. **Explicit:** `LocaleHelper::trans($key)` for critical paths.
4. **Development:** run `php artisan lang:check` (if installed) or grep for untranslated keys.

Never use inline ternaries for UI copy — add a key to both `lang/en` and `lang/ar`.

## Adding a new language

1. Copy `lang/en/` to `lang/{code}/`.
2. Register the locale in `config/cms.php` / `LocaleService`.
3. Add route prefix support in `SetLocale` middleware.
4. No Blade changes required if keys are identical.

## Best practices

| Do | Don’t |
|----|--------|
| `__('domain.key')` | `$locale === 'ar' ? '…' : '…'` |
| Nested keys by feature (`home.hero.badge`) | Flat unrelated keys |
| Same key structure in `en` and `ar` | English-only keys in `ar` files |
| DB for editable marketing copy | Lang files for CMS page bodies |
| `contact.fields.*` for labels | Duplicate keys in Blade |

## Refactored Blade example

**Before:**

```blade
{{ $locale === 'ar' ? 'تواصل معنا' : 'Contact us' }}
```

**After:**

```blade
{{ __('buttons.contact_us') }}
```
