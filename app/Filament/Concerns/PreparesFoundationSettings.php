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

        if (is_array($data['settings'] ?? null)) {
            $data['settings'] = FoundationSection::normalizeSettings($data['settings']);
        }

        return $data;
    }
}
