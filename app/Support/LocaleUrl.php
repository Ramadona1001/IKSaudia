<?php

namespace App\Support;

class LocaleUrl
{
    public static function switchLocale(string $routeName, array $parameters = []): string
    {
        $locale = app()->getLocale() === 'ar' ? 'en' : 'ar';
        $parameters['locale'] = $locale;

        return route($routeName, $parameters);
    }
}
