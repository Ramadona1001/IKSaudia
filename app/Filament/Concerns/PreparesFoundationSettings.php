<?php

namespace App\Filament\Concerns;

use App\Support\FoundationSection;

trait PreparesFoundationSettings
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function prepareFoundationSettings(array $data): array
    {
        if (($data['type'] ?? null) !== 'foundation') {
            return $data;
        }

        $data['settings'] = FoundationSection::normalizeSettings(
            is_array($data['settings'] ?? null) ? $data['settings'] : [],
        );

        return $data;
    }
}
