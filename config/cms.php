<?php

return [

    /*
    |--------------------------------------------------------------------------
    | IK Saudi CMS — Admin architecture reference
    |--------------------------------------------------------------------------
    */

    'locales' => ['ar', 'en'],

    'default_locale' => 'ar',

    'admin_path' => 'ik-admin',

    'navigation' => [
        \App\Filament\Navigation\NavigationGroup::HOMEPAGE => [
            \App\Filament\Resources\HomeSections\HomeSectionResource::class,
        ],
        \App\Filament\Navigation\NavigationGroup::CONTENT => [
            \App\Filament\Resources\Services\ServiceResource::class,
            \App\Filament\Resources\Industries\IndustryResource::class,
            \App\Filament\Resources\Projects\ProjectResource::class,
            \App\Filament\Resources\Certifications\CertificationResource::class,
            \App\Filament\Resources\Clients\ClientResource::class,
            \App\Filament\Resources\Partners\PartnerResource::class,
            \App\Filament\Resources\FaqCategories\FaqCategoryResource::class,
            \App\Filament\Resources\Faqs\FaqResource::class,
            \App\Filament\Resources\NewsPosts\NewsPostResource::class,
            \App\Filament\Resources\Careers\CareerResource::class,
            \App\Filament\Resources\Pages\PageResource::class,
        ],
        \App\Filament\Navigation\NavigationGroup::ENGAGEMENT => [
            \App\Filament\Resources\CareerApplications\CareerApplicationResource::class,
            \App\Filament\Resources\ContactSubmissions\ContactSubmissionResource::class,
        ],
        \App\Filament\Navigation\NavigationGroup::STRUCTURE => [
            \App\Filament\Resources\Redirects\RedirectResource::class,
        ],
    ],

    'roles' => [
        'super_admin' => 'Full access',
        'admin' => 'All CMS modules',
        'editor' => 'Content create/edit/publish',
        'hr' => 'Careers & applications',
    ],

];
