<?php

namespace App\Support;

final class BootstrapIcon
{
    /** @var array<string, string> */
    private const ALIASES = [
        'bi-factory' => 'bi-building-fill',
        'factory' => 'bi-building-fill',
    ];

    /**
     * @return array<string, array<string, string>>
     */
    public static function groupedOptions(): array
    {
        return [
            'General' => [
                'bi-gear-fill' => 'Gear',
                'bi-gear-wide-connected' => 'Connected gear',
                'bi-grid-fill' => 'Grid',
                'bi-grid-3x3-gap-fill' => 'Grid (3×3)',
                'bi-box-seam' => 'Box',
                'bi-box-seam-fill' => 'Box (filled)',
                'bi-tools' => 'Tools',
                'bi-wrench-adjustable' => 'Wrench',
                'bi-puzzle' => 'Puzzle',
                'bi-kanban-fill' => 'Kanban',
            ],
            'Industry & energy' => [
                'bi-building-fill' => 'Building',
                'bi-buildings-fill' => 'Buildings',
                'bi-fuel-pump-fill' => 'Fuel pump',
                'bi-lightning-charge-fill' => 'Lightning',
                'bi-lightning-fill' => 'Bolt',
                'bi-droplet-fill' => 'Droplet',
                'bi-water' => 'Water',
                'bi-sun-fill' => 'Sun',
                'bi-thermometer-half' => 'Thermometer',
                'bi-hammer' => 'Hammer',
            ],
            'Business' => [
                'bi-truck' => 'Truck',
                'bi-people-fill' => 'People',
                'bi-handshake-fill' => 'Handshake',
                'bi-graph-up-arrow' => 'Growth chart',
                'bi-patch-check-fill' => 'Certified badge',
                'bi-shield-fill-check' => 'Shield check',
                'bi-award-fill' => 'Award',
            ],
            'Technology' => [
                'bi-cpu-fill' => 'CPU',
                'bi-flask-fill' => 'Flask',
                'bi-diagram-3-fill' => 'Diagram',
                'bi-gem' => 'Gem',
                'bi-wifi' => 'Wi‑Fi',
            ],
            'Communication' => [
                'bi-envelope-fill' => 'Envelope',
                'bi-telephone-fill' => 'Phone',
                'bi-chat-dots-fill' => 'Chat',
                'bi-globe2' => 'Globe',
            ],
            'Help & content' => [
                'bi-question-circle-fill' => 'Question',
                'bi-patch-question-fill' => 'FAQ',
                'bi-file-earmark-text-fill' => 'Document',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $flat = [];

        foreach (self::groupedOptions() as $options) {
            $flat = array_merge($flat, $options);
        }

        return $flat;
    }

    /**
     * @return array<string, string>
     */
    public static function optionsIncluding(?string $current): array
    {
        $options = self::options();
        $normalized = self::normalize($current);

        if ($normalized && ! isset($options[$normalized])) {
            $options = [$normalized => self::label($normalized)] + $options;
        }

        return $options;
    }

    public static function label(string $icon): string
    {
        $normalized = self::normalize($icon) ?? $icon;

        return self::options()[$normalized] ?? str_replace('bi-', '', $normalized);
    }

    public static function normalize(?string $icon, ?string $default = null): ?string
    {
        if (blank($icon)) {
            return $default;
        }

        $icon = trim($icon);

        if (str_starts_with($icon, 'heroicon')) {
            return $default;
        }

        if (str_starts_with($icon, 'bi ')) {
            $icon = 'bi-'.trim(substr($icon, 3));
        } elseif (! str_starts_with($icon, 'bi-')) {
            $icon = 'bi-'.$icon;
        }

        return self::ALIASES[$icon] ?? $icon;
    }

    public static function classes(?string $icon, ?string $default = 'bi-gear-fill'): string
    {
        $name = self::normalize($icon, $default) ?? $default;

        return "bi {$name}";
    }
}
