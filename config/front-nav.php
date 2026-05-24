<?php

/*
|--------------------------------------------------------------------------
| Front-end Navigation
|--------------------------------------------------------------------------
|
| Configures the main navigation rendered by the public-facing layout.
| Each item declares a label translation key and the route used to build
| its locale-aware URL. Items can opt into a dropdown menu which is fed
| from a dynamic data source (services, industries) at render time.
|
*/

return [

    'main' => [
        [
            'key' => 'home',
            'label' => 'navigation.home',
            'route' => 'home',
            'icon' => 'bi-house-fill',
        ],
        [
            'key' => 'about',
            'label' => 'navigation.about',
            'route' => 'about',
            'icon' => 'bi-info-circle-fill',
        ],
        [
            'key' => 'services',
            'label' => 'navigation.services',
            'route' => 'services.index',
            'dropdown' => 'services',
            'icon' => 'bi-gear-fill',
        ],
        [
            'key' => 'industries',
            'label' => 'navigation.industries',
            'route' => 'industries.index',
            'dropdown' => 'industries',
            'icon' => 'bi-grid-3x3-gap-fill',
        ],
        [
            'key' => 'products',
            'label' => 'navigation.products',
            'route' => 'products.index',
            'icon' => 'bi-box-seam-fill',
        ],
        [
            'key' => 'clients',
            'label' => 'navigation.clients',
            'route' => 'clients',
            'icon' => 'bi-people-fill',
        ],
        [
            'key' => 'partners',
            'label' => 'navigation.partners',
            'route' => 'partners',
            'icon' => 'bi-handshake-fill',
        ],
        [
            'key' => 'faq',
            'label' => 'navigation.faq',
            'route' => 'faq',
            'icon' => 'bi-question-circle-fill',
        ],
        [
            'key' => 'contact',
            'label' => 'navigation.contact',
            'route' => 'contact',
            'icon' => 'bi-envelope-fill',
        ],
    ],

    'footer' => [
        'company' => [
            ['label' => 'navigation.about', 'route' => 'about'],
            ['label' => 'navigation.services', 'route' => 'services.index'],
            ['label' => 'navigation.industries', 'route' => 'industries.index'],
            ['label' => 'navigation.products', 'route' => 'products.index'],
            ['label' => 'navigation.clients', 'route' => 'clients'],
            ['label' => 'navigation.partners', 'route' => 'partners'],
            ['label' => 'navigation.faq', 'route' => 'faq'],
            ['label' => 'navigation.contact', 'route' => 'contact'],
        ],
    ],

];
