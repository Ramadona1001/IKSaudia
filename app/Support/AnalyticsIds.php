<?php

namespace App\Support;

final class AnalyticsIds
{
    public static function gtmId(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        if (preg_match('/\b(GTM-[A-Z0-9]+)\b/i', $value, $matches)) {
            return strtoupper($matches[1]);
        }

        return null;
    }

    public static function gaId(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        if (preg_match('/\b(G-[A-Z0-9]+|UA-\d+-\d+)\b/i', $value, $matches)) {
            return strtoupper($matches[1]);
        }

        return null;
    }

    public static function metaPixelId(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        if (preg_match('/\b(\d{10,20})\b/', $value, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
