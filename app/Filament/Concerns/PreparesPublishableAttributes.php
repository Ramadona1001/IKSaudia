<?php

namespace App\Filament\Concerns;

trait PreparesPublishableAttributes
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function preparePublishableAttributes(array $data): array
    {
        if (empty($data['is_published'])) {
            return $data;
        }

        $publishedAt = $data['published_at'] ?? null;

        if ($publishedAt === null || $publishedAt === '') {
            $data['published_at'] = now();

            return $data;
        }

        try {
            if (now()->lt(\Illuminate\Support\Carbon::parse($publishedAt))) {
                $data['published_at'] = now();
            }
        } catch (\Throwable) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
