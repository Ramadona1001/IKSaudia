<?php

return [

    'palette' => [
        'navy' => [
            950 => '#050d18',
            900 => '#0a1628',
            800 => '#0f2744',
            700 => '#163a5f',
            600 => '#1e4d7b',
        ],
        'steel' => [
            100 => '#e8eef4',
            200 => '#c5d4e0',
            300 => '#8fa3b8',
            400 => '#6b8299',
            500 => '#4a6278',
        ],
        'accent' => [
            'gold' => '#c8922a',
            'gold-light' => '#e0ad4f',
            'copper' => '#b87333',
        ],
        'white' => '#ffffff',
        'surface' => '#f4f7fa',
    ],

    'typography' => [
        'font_ltr' => 'Inter',
        'font_rtl' => 'IBM Plex Sans Arabic',
        'scale' => [
            'display-2xl' => ['size' => '4.5rem', 'line' => '1.05', 'weight' => '700'],
            'display-xl' => ['size' => '3.75rem', 'line' => '1.1', 'weight' => '700'],
            'display-lg' => ['size' => '3rem', 'line' => '1.15', 'weight' => '700'],
            'h1' => ['size' => '2.25rem', 'line' => '1.2', 'weight' => '700'],
            'h2' => ['size' => '1.875rem', 'line' => '1.25', 'weight' => '600'],
            'h3' => ['size' => '1.5rem', 'line' => '1.3', 'weight' => '600'],
            'h4' => ['size' => '1.25rem', 'line' => '1.4', 'weight' => '600'],
            'body-lg' => ['size' => '1.125rem', 'line' => '1.7', 'weight' => '400'],
            'body' => ['size' => '1rem', 'line' => '1.65', 'weight' => '400'],
            'body-sm' => ['size' => '0.875rem', 'line' => '1.6', 'weight' => '400'],
            'caption' => ['size' => '0.75rem', 'line' => '1.5', 'weight' => '500'],
            'overline' => ['size' => '0.6875rem', 'line' => '1.4', 'weight' => '600', 'tracking' => '0.2em'],
        ],
    ],

    'spacing' => [
        'section-y' => '5rem',
        'section-y-lg' => '7rem',
    ],

    'cta' => [
        'primary' => ['label_ar' => 'تواصل معنا', 'label_en' => 'Contact us', 'route' => 'contact'],
        'secondary' => ['label_ar' => 'استكشف الخدمات', 'label_en' => 'Explore services', 'route' => 'services.index'],
    ],

];
