<?php

namespace App\Filament\Concerns;

use App\Support\FoundationSection;

trait PreparesFoundationSettings
{
    use ResolvesHomeSectionRecordType;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function prepareFoundationSettings(array $data): array
    {
        $type = $data['type'] ?? $this->resolveRecordType();

        if ($type !== 'foundation') {
            return $data;
        }

        $data['settings'] = FoundationSection::normalizeSettings(
            is_array($data['settings'] ?? null) ? $data['settings'] : [],
        );

        return $data;
    }
}
