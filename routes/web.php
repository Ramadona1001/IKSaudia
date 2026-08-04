<?php

use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\FaqController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\IndustryController;
use App\Http\Controllers\Web\LegacyRedirectController;
use App\Http\Controllers\Web\LocaleRedirectController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\PartnerController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Middleware\EnsureNavigationRouteVisible;
use App\Http\Middleware\ResolveWebLocale;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', LocaleRedirectController::class)->name('root');

Route::middleware([ResolveWebLocale::class, EnsureNavigationRouteVisible::class])->group(function (): void {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])
        ->where('slug', '[A-Za-z0-9-_]+')
        ->name('products.show');
});

Route::get('{legacyPath}', LegacyRedirectController::class)
    ->where('legacyPath', '.+\.html$');

Route::prefix('{locale}')
    ->where(['locale' => 'ar|en'])
    ->middleware([SetLocale::class, EnsureNavigationRouteVisible::class])
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');

        Route::get('/about', AboutController::class)->name('about');

        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
        Route::get('/industries/{slug}', [IndustryController::class, 'show'])->name('industries.show');

        Route::get('/products', fn (string $locale) => redirect('/products?locale='.$locale, 301));
        Route::get('/products/{slug}', fn (string $locale, string $slug) => redirect('/products/'.$slug.'?locale='.$locale, 301))
            ->where('slug', '[A-Za-z0-9-_]+');

        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

        Route::get('/search', SearchController::class)
            ->middleware('throttle:60,1')
            ->name('search');

        Route::get('/partners', PartnerController::class)->name('partners');
        Route::get('/clients', ClientController::class)->name('clients');
        Route::get('/faq', FaqController::class)->name('faq');

        Route::get('/contact', [ContactController::class, 'show'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])
            ->middleware('throttle:contact')
            ->name('contact.store');

        Route::get('/{slug}', [PageController::class, 'show'])
            ->where('slug', '[A-Za-z0-9-_]+')
            ->name('page.show');
    });
