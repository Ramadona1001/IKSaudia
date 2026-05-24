<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NavigationService
{
    public const HEADER_LOCATION = 'header';

    /**
     * @return list<array<string, mixed>>
     */
    public function headerItems(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return Cache::remember("navigation.header.{$locale}", 3600, function () use ($locale) {
            $menu = Menu::query()
                ->where('location', self::HEADER_LOCATION)
                ->where('is_active', true)
                ->first();

            if (! $menu) {
                return $this->defaultHeaderItems($locale);
            }

            $items = $menu->rootItems()
                ->where('is_active', true)
                ->with('translations')
                ->get();

            if ($items->isEmpty()) {
                return $this->defaultHeaderItems($locale);
            }

            return $items->map(fn (MenuItem $item) => $this->mapItem($item, $locale))->all();
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function toFormState(): array
    {
        $menu = $this->headerMenu();

        return $menu->rootItems()
            ->with('translations')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (MenuItem $item) => [
                'id' => $item->id,
                'label_ar' => $item->translationFor('ar')?->label,
                'label_en' => $item->translationFor('en')?->label,
                'link_type' => $item->route_name ? 'route' : ($item->url && str_starts_with($item->url, '#') ? 'anchor' : ($item->url ? 'url' : 'route')),
                'route_name' => $item->route_name,
                'page_slug' => is_array($item->route_params) ? ($item->route_params['slug'] ?? null) : null,
                'url' => $item->url,
                'is_mega_menu' => $item->is_mega_menu,
                'is_visible' => $item->is_active,
                'sort_order' => $item->sort_order,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    public function syncFromForm(array $items): void
    {
        DB::transaction(function () use ($items) {
            $menu = $this->headerMenu();
            $keptIds = [];

            foreach ($items as $index => $row) {
                if (! is_array($row)) {
                    continue;
                }

                $payload = $this->mapFormRowToItem($row, $index);
                $itemId = $row['id'] ?? null;

                if ($itemId) {
                    $item = MenuItem::query()->where('menu_id', $menu->id)->find($itemId);
                    $item?->update($payload);
                } else {
                    $item = MenuItem::query()->create(array_merge($payload, ['menu_id' => $menu->id]));
                }

                if (! $item) {
                    continue;
                }

                $keptIds[] = $item->id;

                foreach (['ar', 'en'] as $locale) {
                    MenuItemTranslation::query()->updateOrCreate(
                        ['menu_item_id' => $item->id, 'locale' => $locale],
                        ['label' => $row["label_{$locale}"] ?? ''],
                    );
                }
            }

            MenuItem::query()
                ->where('menu_id', $menu->id)
                ->whereNull('parent_id')
                ->whereNotIn('id', $keptIds)
                ->delete();
        });

        $this->clearCache();
    }

    public function clearCache(): void
    {
        foreach (config('locales.supported', ['ar', 'en']) as $locale) {
            Cache::forget("navigation.header.{$locale}");
        }
    }

    public function resolveUrl(array $item, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if (($item['href'] ?? null) && str_starts_with($item['href'], '#')) {
            return $item['href'];
        }

        if (! empty($item['url']) && ($item['link_type'] ?? '') === 'url') {
            $url = $item['url'];
            if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '/')) {
                return str_starts_with($url, '/') ? url($url) : $url;
            }

            return url("/{$locale}/".ltrim($url, '/'));
        }

        $route = $item['route'] ?? $item['route_name'] ?? null;

        if ($route && \Illuminate\Support\Facades\Route::has($route)) {
            if (in_array($route, ['products.index', 'products.show'], true)) {
                $params = $route === 'products.show'
                    ? [$item['slug'] ?? $item['params'][0] ?? '']
                    : [];

                return route($route, $params);
            }

            $params = $item['params'] ?? [$locale];

            if ($route === 'page.show') {
                $params = [$locale, $item['page_slug'] ?? $item['params'][1] ?? 'about-us'];
            }

            return route($route, $params);
        }

        return $item['href'] ?? '#';
    }

    protected function headerMenu(): Menu
    {
        return Menu::query()->firstOrCreate(
            ['location' => self::HEADER_LOCATION],
            ['name' => 'Main header', 'is_active' => true],
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapItem(MenuItem $item, string $locale): array
    {
        $translation = $item->translate($locale);

        $mapped = [
            'key' => 'menu-'.$item->id,
            'label' => $translation?->label ?? '',
            'mega' => $item->is_mega_menu,
        ];

        if ($item->route_name) {
            $mapped['route'] = $item->route_name;
            $mapped['params'] = match ($item->route_name) {
                'page.show' => [$locale, $item->route_params['slug'] ?? 'about-us'],
                'products.index', 'products.show' => [],
                default => [$locale],
            };
            if ($item->route_name === 'page.show') {
                $mapped['page_slug'] = $item->route_params['slug'] ?? 'about-us';
            }
        } elseif ($item->url) {
            $mapped['href'] = $item->url;
        }

        return $mapped;
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapFormRowToItem(array $row, int $index): array
    {
        $linkType = $row['link_type'] ?? 'route';

        $payload = [
            'parent_id' => null,
            'sort_order' => (int) ($row['sort_order'] ?? $index),
            'is_active' => (bool) ($row['is_visible'] ?? true),
            'is_mega_menu' => (bool) ($row['is_mega_menu'] ?? false),
            'target' => '_self',
            'url' => null,
            'route_name' => null,
            'route_params' => null,
        ];

        return match ($linkType) {
            'url' => array_merge($payload, ['url' => $row['url'] ?? null]),
            'anchor' => array_merge($payload, ['url' => $row['url'] ?? '#']),
            default => array_merge($payload, [
                'route_name' => $row['route_name'] ?? null,
                'route_params' => ($row['route_name'] ?? '') === 'page.show'
                    ? ['slug' => $row['page_slug'] ?? 'about-us']
                    : null,
            ]),
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function defaultHeaderItems(string $locale): array
    {
        return [
            ['key' => 'services', 'label' => __('navigation.services', [], $locale), 'route' => 'services.index', 'params' => [$locale], 'mega' => true],
            ['key' => 'about', 'label' => __('navigation.about', [], $locale), 'route' => 'page.show', 'params' => [$locale, 'about-us'], 'page_slug' => 'about-us'],
            ['key' => 'projects', 'label' => __('navigation.projects', [], $locale), 'route' => 'projects.index', 'params' => [$locale]],
            ['key' => 'process', 'label' => __('navigation.process', [], $locale), 'href' => '#process'],
            ['key' => 'contact', 'label' => __('navigation.contact', [], $locale), 'route' => 'contact', 'params' => [$locale]],
        ];
    }
}
