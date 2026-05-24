@php
    $locale = app()->getLocale();
    $navService = app(\App\Services\NavigationService::class);
    $dbNav = $navService->headerItems($locale);
    $navConfigFallback = config('front-nav.main', []);

    if (! empty($dbNav)) {
        $mobileNavItems = collect($dbNav)->map(function (array $item) use ($navService, $locale) {
            return [
                'label' => $item['label'] ?? '',
                'href' => $navService->resolveUrl($item, $locale),
                'icon' => null,
            ];
        })->all();
    } else {
        $mobileNavItems = collect($navConfigFallback)->map(function (array $item) use ($locale) {
            return [
                'label' => __($item['label']),
                'href' => isset($item['route']) && Route::has($item['route'])
                    ? (in_array($item['route'], ['products.index'], true)
                        ? route($item['route'])
                        : route($item['route'], $locale))
                    : '#',
                'icon' => $item['icon'] ?? null,
            ];
        })->all();
    }
@endphp
<div class="mobile-overlay" aria-hidden="true"></div>

<nav class="mobile-menu" aria-label="{{ __('navigation.mobile') }}">
    <ul class="mobile-nav-links">
        @foreach ($mobileNavItems as $item)
            <li>
                <a href="{{ $item['href'] }}">
                    @if (! empty($item['icon']))<i class="bi {{ $item['icon'] }}" aria-hidden="true"></i>@endif
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div style="margin-top:32px;display:flex;gap:10px;">
        @foreach (['en', 'ar'] as $code)
            @php
                $currentRouteName = request()->route()?->getName() ?? 'home';
                $isLocalelessRoute = in_array($currentRouteName, ['products.index', 'products.show'], true);
                if ($isLocalelessRoute && Route::has($currentRouteName)) {
                    $url = route($currentRouteName, request()->route()?->parameters() ?? []).'?locale='.$code;
                } else {
                    $params = array_merge(request()->route()?->parameters() ?? [], ['locale' => $code]);
                    $url = Route::has($currentRouteName) ? route($currentRouteName, $params) : route('home', $code);
                }
            @endphp
            <a class="lang-btn {{ $locale === $code ? 'active' : '' }}" data-lang="{{ $code }}" href="{{ $url }}" hreflang="{{ $code }}">
                {{ strtoupper($code) }}
            </a>
        @endforeach
    </div>
</nav>
