@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'ar' ? 'en' : 'ar';

    $currentRouteName = request()->route()?->getName() ?? 'home';
    $isLocalelessRoute = in_array($currentRouteName, ['products.index', 'products.show'], true);
    $switchParams = array_merge(request()->route()?->parameters() ?? [], ['locale' => $otherLocale]);
    $canSwitchLocale = Route::has($currentRouteName) && ! $isLocalelessRoute;

    $brand = setting('general.site_name') ?: __('common.app_name_short');
    $brandSub = setting('general.site_tagline') ?: __('common.app_tagline');
    $logo = setting_url('general.logo');

    $siteSettings = $siteSettings ?? \App\Data\WebsiteSettingsBag::make($locale);
    $featuredServices = $featuredServices ?? collect();
    $featuredIndustries = $featuredIndustries ?? collect();

    // Build menu from admin-managed Navigation (DB) with a config fallback so the
    // header always renders even before the menu has been published.
    $navService = app(\App\Services\NavigationService::class);
    $dbNav = $navService->headerItems($locale);
    $navConfigFallback = config('front-nav.main', []);

    if (! empty($dbNav)) {
        $navItems = collect($dbNav)->map(function (array $item) use ($navService, $locale) {
            $href = $navService->resolveUrl($item, $locale);
            $routeName = $item['route'] ?? null;
            $isActive = false;
            if ($routeName) {
                $isActive = request()->routeIs($routeName) || request()->routeIs(str_replace('.index', '.*', $routeName));
            } elseif (($item['href'] ?? null) && str_starts_with($item['href'], '#')) {
                $isActive = false;
            }
            $dropdown = null;
            if (! empty($item['mega'])) {
                $dropdown = match ($routeName) {
                    'services.index' => 'services',
                    'industries.index' => 'industries',
                    'products.index' => null,
                    default => null,
                };
            }
            return [
                'label' => $item['label'] ?? '',
                'href' => $href,
                'route' => $routeName,
                'is_active' => $isActive,
                'dropdown' => $dropdown,
                'icon' => null,
            ];
        })->all();
    } else {
        $navItems = collect($navConfigFallback)->map(function (array $item) use ($locale) {
            $routeName = $item['route'] ?? null;
            $isActive = $routeName && (request()->routeIs($routeName) || request()->routeIs(str_replace('.index', '.*', $routeName)));
            return [
                'label' => __($item['label']),
                'href' => $routeName && Route::has($routeName)
                    ? (in_array($routeName, ['products.index', 'products.show'], true)
                        ? route($routeName)
                        : route($routeName, $locale))
                    : '#',
                'route' => $routeName,
                'is_active' => $isActive,
                'dropdown' => $item['dropdown'] ?? null,
                'icon' => $item['icon'] ?? null,
            ];
        })->all();
    }
@endphp

<header class="main-nav transparent" role="banner">
    <div class="nav-inner">

        {{-- Logo --}}
        <a href="{{ route('home', $locale) }}" class="nav-logo" aria-label="{{ $brand }}">
            @if ($logo)
                <img src="{{ $logo }}" alt="{{ $brand }}" class="nav-logo-icon" width="46" height="46" style="object-fit:contain;">
            @else
                <svg class="nav-logo-icon" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polygon points="23,2 44,12.5 44,33.5 23,44 2,33.5 2,12.5" fill="#c9a227" opacity="0.12"/>
                    <polygon points="23,2 44,12.5 44,33.5 23,44 2,33.5 2,12.5" fill="none" stroke="#c9a227" stroke-width="1.5"/>
                    <text x="50%" y="57%" dominant-baseline="middle" text-anchor="middle" font-family="Poppins,Arial" font-weight="900" font-size="14" fill="#c9a227">IK</text>
                </svg>
            @endif
            <div class="nav-logo-text">
                <span class="nav-logo-main">IK <span>{{ Str::limit(Str::after($brand, 'IK '), 12, '') ?: 'Saudi' }}</span></span>
                <span class="nav-logo-sub">{{ $brandSub }}</span>
            </div>
        </a>

        {{-- Desktop Nav Links --}}
        <ul class="nav-links" role="navigation" aria-label="{{ __('navigation.main') }}">
            @foreach ($navItems as $item)
                @php
                    $hasDropdown = ($item['dropdown'] ?? null) === 'services' && $featuredServices->isNotEmpty()
                        || ($item['dropdown'] ?? null) === 'industries' && $featuredIndustries->isNotEmpty();
                @endphp

                @if ($hasDropdown)
                    <li class="nav-dropdown">
                        <a href="{{ $item['href'] }}" class="{{ $item['is_active'] ? 'active' : '' }}" aria-haspopup="true" aria-expanded="false">
                            {{ $item['label'] }} <span aria-hidden="true">▾</span>
                        </a>
                        <ul class="nav-dropdown-menu">
                            @if ($item['dropdown'] === 'services')
                                @foreach ($featuredServices->take(6) as $service)
                                    @php $st = $service->translate($locale); @endphp
                                    @if ($st)
                                        <li>
                                            <a href="{{ route('services.show', [$locale, $st->slug]) }}">
                                                <i class="bi {{ $service->icon ?? 'bi-gear-fill' }}"></i>
                                                <span>{{ $st->title }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                <li>
                                    <a href="{{ route('services.index', $locale) }}">
                                        <i class="bi bi-grid-fill"></i>
                                        <span>{{ __('navigation.all_services') }} →</span>
                                    </a>
                                </li>
                            @elseif ($item['dropdown'] === 'industries')
                                @foreach ($featuredIndustries->take(6) as $industry)
                                    @php $it = $industry->translate($locale); @endphp
                                    @if ($it)
                                        <li>
                                            <a href="{{ route('industries.show', [$locale, $it->slug]) }}">
                                                <i class="bi {{ $industry->icon ?? 'bi-grid-3x3-gap-fill' }}"></i>
                                                <span>{{ $it->title }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                <li>
                                    <a href="{{ route('industries.index', $locale) }}">
                                        <i class="bi bi-grid-fill"></i>
                                        <span>{{ __('navigation.all_industries') }} →</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ $item['href'] }}" class="{{ $item['is_active'] ? 'active' : '' }}">{{ $item['label'] }}</a>
                    </li>
                @endif
            @endforeach
        </ul>

        {{-- Right side actions --}}
        <div class="nav-actions">
            <div class="lang-switcher" role="group" aria-label="{{ __('common.language') }}">
                @foreach (['en', 'ar'] as $code)
                    @php
                        if ($isLocalelessRoute && Route::has($currentRouteName)) {
                            $url = route($currentRouteName, request()->route()?->parameters() ?? []).'?locale='.$code;
                        } else {
                            $params = array_merge(request()->route()?->parameters() ?? [], ['locale' => $code]);
                            $url = $canSwitchLocale ? route($currentRouteName, $params) : route('home', $code);
                        }
                    @endphp
                    <a class="lang-btn {{ $locale === $code ? 'active' : '' }}"
                       data-lang="{{ $code }}"
                       href="{{ $url }}"
                       hreflang="{{ $code }}"
                       aria-label="{{ $code === 'ar' ? __('common.locale_ar') : __('common.locale_en') }}">
                        {{ strtoupper($code) }}
                    </a>
                @endforeach
            </div>

            <button class="nav-icon-btn search-open-btn" aria-label="{{ __('common.search') }}" type="button">
                <i class="bi bi-search"></i>
            </button>

            <a href="{{ route('contact', $locale) }}" class="btn-gold d-none d-md-inline-flex">
                <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                <span>{{ __('navigation.get_quote') }}</span>
            </a>
        </div>

        <button class="nav-toggle" type="button" aria-label="{{ __('navigation.menu') }}" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
