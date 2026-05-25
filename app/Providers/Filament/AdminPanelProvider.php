<?php

namespace App\Providers\Filament;

use App\Filament\Auth\EditProfile;
use App\Filament\Auth\Login;
use App\Filament\Auth\RequestPasswordReset;
use App\Filament\Auth\ResetPassword;
use App\Filament\Navigation\NavigationGroup;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\CmsOverviewWidget;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RestrictAdminByIp;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(config('cms.admin_path', 'ik-admin'))
            ->login(Login::class)
            ->profile(EditProfile::class, isSimple: false)
            ->passwordReset(RequestPasswordReset::class, ResetPassword::class)
            ->authPasswordBroker('users')
            ->brandName('IK Saudi CMS')
            ->colors([
                'primary' => Color::hex('#c8922a'),
            ])
            ->navigationGroups([
                NavigationGroup::HOMEPAGE,
                NavigationGroup::CONTENT,
                NavigationGroup::ENGAGEMENT,
                NavigationGroup::STRUCTURE,
                NavigationGroup::SYSTEM,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                CmsOverviewWidget::class,
                AccountWidget::class,
            ])
            ->middleware([
                RestrictAdminByIp::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureUserIsActive::class,
            ]);
    }
}
