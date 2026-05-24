<?php

namespace App\Providers;

use App\Models\Certification;
use App\Models\CertificationTranslation;
use App\Models\Client;
use App\Models\ClientTranslation;
use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Models\Industry;
use App\Models\IndustryTranslation;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemTranslation;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Partner;
use App\Models\PartnerTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\SiteSetting;
use App\Models\SiteSettingTranslation;
use App\Observers\NavigationObserver;
use App\Observers\SiteSettingObserver;
use App\Listeners\AuthSecuritySubscriber;
use App\Observers\ContentCacheObserver;
use App\Observers\HomeSectionObserver;
use Illuminate\Support\Facades\Event;
use App\Contracts\VirusScanner;
use App\Data\WebsiteSettingsBag;
use App\Services\Media\NullVirusScanner;
use App\Services\NavigationService;
use App\Services\ServiceCatalogService;
use App\Services\SettingsService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(VirusScanner::class, NullVirusScanner::class);
    }

    public function boot(): void
    {
        $this->assertProductionSafety();
        Event::subscribe(AuthSecuritySubscriber::class);

        $this->configureTranslationFallback();
        $this->configureProductionUrls();
        $this->configureLocalDevelopmentUrls();
        $this->configureRateLimiting();

        $observer = ContentCacheObserver::class;

        HomeSection::observe(HomeSectionObserver::class);
        HomeSection::observe($observer);
        HomeSectionTranslation::observe($observer);
        Page::observe($observer);
        PageTranslation::observe($observer);
        Service::observe($observer);
        ServiceTranslation::observe($observer);
        Project::observe($observer);
        ProjectTranslation::observe($observer);
        Industry::observe($observer);
        IndustryTranslation::observe($observer);
        Product::observe($observer);
        ProductTranslation::observe($observer);
        Certification::observe($observer);
        CertificationTranslation::observe($observer);
        Client::observe($observer);
        ClientTranslation::observe($observer);
        Partner::observe($observer);
        PartnerTranslation::observe($observer);

        SiteSetting::observe(SiteSettingObserver::class);
        SiteSettingTranslation::observe(SiteSettingObserver::class);

        $navObserver = NavigationObserver::class;
        Menu::observe($navObserver);
        MenuItem::observe($navObserver);
        MenuItemTranslation::observe($navObserver);

        View::composer('*', function ($view): void {
            $name = $view->getName() ?? '';

            // Only inject into front-end views (avoid Filament admin overhead).
            $isFront = str_starts_with($name, 'front.')
                || str_starts_with($name, 'pages.')
                || str_starts_with($name, 'projects.')
                || str_starts_with($name, 'layouts.')
                || str_starts_with($name, 'components.layout.')
                || str_starts_with($name, 'components.front.');

            if (! $isFront) {
                return;
            }

            $locale = app()->getLocale();
            if (! $view->offsetExists('siteSettings')) {
                $view->with('siteSettings', WebsiteSettingsBag::make($locale));
            }
            if (! $view->offsetExists('settingsService')) {
                $view->with('settingsService', app(SettingsService::class));
            }
        });

        View::composer('components.layout.site-header', function ($view): void {
            $locale = app()->getLocale();
            $navService = app(NavigationService::class);
            $view->with('featuredServices', app(ServiceCatalogService::class)->featured($locale, 6));
            $view->with('headerNav', $navService->headerItems($locale));
            $view->with('navService', $navService);
        });

        // Share featured services & industries with the front theme header dropdowns.
        View::composer(['front.partials.header', 'front.partials.footer'], function ($view): void {
            $locale = app()->getLocale();
            $view->with('featuredServices', app(ServiceCatalogService::class)->featured($locale, 6));
            $view->with('featuredIndustries', app(\App\Services\IndustryCatalogService::class)->featured($locale, 6));
        });
    }

    protected function configureTranslationFallback(): void
    {
        Lang::handleMissingKeysUsing(function (string $key, array $replace, ?string $locale): string {
            $fallback = config('app.fallback_locale', 'en');

            if ($locale && $locale !== $fallback) {
                $line = Lang::get($key, $replace, $fallback);

                if ($line !== $key) {
                    return $line;
                }
            }

            if (! $this->app->environment('production')) {
                Log::debug('Missing translation key', ['key' => $key, 'locale' => $locale]);
            }

            return $key;
        });
    }

    protected function configureProductionUrls(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Keep generated storage/asset URLs on the same host as the current request.
     * Prevents CORS when APP_URL is http://localhost but the app is opened at http://127.0.0.1:8000.
     */
    protected function configureLocalDevelopmentUrls(): void
    {
        if (! $this->app->environment('local') || $this->app->runningInConsole()) {
            return;
        }

        $root = request()->getSchemeAndHttpHost();

        if ($root === '') {
            return;
        }

        URL::forceRootUrl($root);
        config(['filesystems.disks.public.url' => $root.'/storage']);
    }

    protected function assertProductionSafety(): void
    {
        if (! $this->app->environment('production') || $this->app->runningInConsole()) {
            return;
        }

        if (blank(config('app.key'))) {
            throw new \RuntimeException('APP_KEY must be set in production.');
        }
    }

    protected function configureRateLimiting(): void
    {
        $contactMinute = config('security.rate_limits.contact_per_minute', 5);
        $contactHour = config('security.rate_limits.contact_per_hour', 20);
        $adminLogin = config('security.rate_limits.admin_login_per_minute', 5);

        RateLimiter::for('contact', function (Request $request) use ($contactMinute, $contactHour) {
            return [
                Limit::perMinute($contactMinute)->by($request->ip()),
                Limit::perHour($contactHour)->by($request->ip()),
            ];
        });

        RateLimiter::for('admin-login', function (Request $request) use ($adminLogin) {
            return Limit::perMinute($adminLogin)->by($request->ip());
        });
    }
}
