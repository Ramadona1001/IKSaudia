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
     * Detail routes allowed only when their listing route is visible in the menu.
     *
     * @var array<string, string>
     */
    private const SHOW_ROUTE_PARENTS = [
        'services.show' => 'services.index',
        'industries.show' => 'industries.index',
        'projects.show' => 'projects.index',
        'news.show' => 'news.index',
        'products.show' => 'products.index',
    ];

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
     * @return list<array<string, mixed>>
     */
    public function reindexFormItems(array $items): array
    {
        return collect($items)
            ->filter(fn ($row) => is_array($row))
            ->values()
            ->map(fn (array $row, int $index) => array_merge($row, ['sort_order' => $index]))
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    public function syncFromForm(array $items): void
    {
        $items = $this->reindexFormItems($items);

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

        Cache::forget('navigation.searchable_types');
        Cache::forget('navigation.accessible_targets');
    }

    public function isRouteAccessible(string $routeName, array $parameters = []): bool
    {
        if ($routeName === 'page.show') {
            $slug = $parameters['slug'] ?? null;

            return is_string($slug)
                && $slug !== ''
                && in_array($slug, $this->accessiblePageSlugs(), true);
        }

        $requiredRoute = self::SHOW_ROUTE_PARENTS[$routeName] ?? $routeName;

        return in_array($requiredRoute, $this->accessibleRouteNames(), true);
    }

    /**
     * @return list<string>
     */
    public function accessibleRouteNames(): array
    {
        return $this->accessibleNavigationTargets()['routes'];
    }

    /**
     * @return list<string>
     */
    public function accessiblePageSlugs(): array
    {
        return $this->accessibleNavigationTargets()['page_slugs'];
    }

    /**
     * @return array{routes: list<string>, page_slugs: list<string>}
     */
    protected function accessibleNavigationTargets(): array
    {
        return Cache::remember('navigation.accessible_targets', 3600, function (): array {
            $menu = Menu::query()
                ->where('location', self::HEADER_LOCATION)
                ->where('is_active', true)
                ->first();

            if ($menu) {
                $items = $menu->rootItems()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                if ($items->isNotEmpty()) {
                    return $this->targetsFromMenuItems($items);
                }
            }

            return $this->defaultAccessibleTargets();
        });
    }

    /**
     * Content types enabled for site search based on visible header menu links.
     *
     * @return list<string> service|industry|project|news
     */
    public function searchableContentTypes(): array
    {
        return Cache::remember('navigation.searchable_types', 3600, function (): array {
            $routeToType = [
                'services.index' => 'service',
                'industries.index' => 'industry',
                'projects.index' => 'project',
                'news.index' => 'news',
            ];

            $types = [];

            foreach ($this->accessibleRouteNames() as $routeName) {
                if (isset($routeToType[$routeName])) {
                    $types[] = $routeToType[$routeName];
                }
            }

            return array_values(array_unique($types));
        });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, MenuItem>|\Illuminate\Database\Eloquent\Collection<int, MenuItem>  $items
     * @return array{routes: list<string>, page_slugs: list<string>}
     */
    protected function targetsFromMenuItems($items): array
    {
        $routes = [];
        $pageSlugs = [];

        foreach ($items as $item) {
            if (! $item->route_name) {
                continue;
            }

            if ($item->route_name === 'page.show') {
                $slug = is_array($item->route_params) ? ($item->route_params['slug'] ?? null) : null;

                if (is_string($slug) && $slug !== '') {
                    $pageSlugs[] = $slug;
                }

                continue;
            }

            $routes[] = $item->route_name;
        }

        return [
            'routes' => array_values(array_unique($routes)),
            'page_slugs' => array_values(array_unique($pageSlugs)),
        ];
    }

    /**
     * @return array{routes: list<string>, page_slugs: list<string>}
     */
    protected function defaultAccessibleTargets(): array
    {
        $routes = [];
        $pageSlugs = [];

        foreach ($this->defaultHeaderItems(app()->getLocale()) as $item) {
            $route = $item['route'] ?? null;

            if (! $route) {
                continue;
            }

            if ($route === 'page.show') {
                $slug = $item['page_slug'] ?? ($item['params'][1] ?? null);

                if (is_string($slug) && $slug !== '') {
                    $pageSlugs[] = $slug;
                }

                continue;
            }

            $routes[] = $route;
        }

        return [
            'routes' => array_values(array_unique($routes)),
            'page_slugs' => array_values(array_unique($pageSlugs)),
        ];
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
            'sort_order' => $index,
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
