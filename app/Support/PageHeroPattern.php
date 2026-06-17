<?php

namespace App\Support;

final class PageHeroPattern
{
    /**
     * @return array{image: string, size: string}
     */
    public static function cssValue(
        ?string $preset,
        ?string $customImageUrl,
        string $accent = '#c9a227',
        int $size = 60,
    ): array {
        $preset = filled($preset) ? $preset : 'hexagon';
        $size = max(16, min(200, $size));

        if ($preset === 'none') {
            return [
                'image' => 'none',
                'size' => "{$size}px {$size}px",
            ];
        }

        if ($preset === 'custom' && filled($customImageUrl)) {
            return [
                'image' => 'url("'.self::escapeCssUrl($customImageUrl).'")',
                'size' => "{$size}px {$size}px",
            ];
        }

        $tileSize = match ($preset) {
            'grid' => $size > 0 ? $size : 40,
            'dots' => $size > 0 ? $size : 24,
            default => $size > 0 ? $size : 60,
        };

        return [
            'image' => 'url("'.self::dataUri(self::svgForPreset($preset, $accent)).'")',
            'size' => "{$tileSize}px {$tileSize}px",
        ];
    }

    public static function svgForPreset(string $preset, string $accent): string
    {
        $stroke = self::accentRgba($accent, 0.08);
        $strokeLight = self::accentRgba($accent, 0.04);

        return match ($preset) {
            'grid' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40">
  <rect x="0" y="0" width="40" height="40" fill="none" stroke="{$stroke}" stroke-width="1"/>
  <line x1="0" y1="20" x2="40" y2="20" stroke="{$strokeLight}" stroke-width="1"/>
  <line x1="20" y1="0" x2="20" y2="40" stroke="{$strokeLight}" stroke-width="1"/>
</svg>
SVG,
            'dots' => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
  <circle cx="12" cy="12" r="1.6" fill="{$stroke}"/>
</svg>
SVG,
            default => <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60">
  <path d="M 30 5 L 55 17.5 L 55 42.5 L 30 55 L 5 42.5 L 5 17.5 Z" fill="none" stroke="{$stroke}" stroke-width="1"/>
</svg>
SVG,
        };
    }

    private static function dataUri(string $svg): string
    {
        return 'data:image/svg+xml,'.rawurlencode(trim($svg));
    }

    private static function escapeCssUrl(string $url): string
    {
        return str_replace(['\\', '"'], ['\\\\', '\\"'], $url);
    }

    private static function accentRgba(string $accent, float $opacity): string
    {
        $hex = ltrim($accent, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return 'rgba(201,162,39,'.$opacity.')';
        }

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return "rgba({$red},{$green},{$blue},{$opacity})";
    }
}
