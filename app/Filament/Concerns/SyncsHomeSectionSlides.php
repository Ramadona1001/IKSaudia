<?php

namespace App\Filament\Concerns;

use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use App\Models\HomeSectionItemTranslation;

trait SyncsHomeSectionSlides
{
    /** @var list<array<string, mixed>> */
    protected array $cachedSlides = [];

    /**
     * @return list<array<string, mixed>>
     */
    protected function mapSlidesForForm(HomeSection $section): array
    {
        return $section->items()
            ->with('translations')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (HomeSectionItem $item) => [
                'id' => $item->id,
                'image' => $item->image,
                'is_active' => $item->is_active,
                'sort_order' => $item->sort_order,
                'translations' => [
                    'ar' => $this->mapSlideTranslation($item, 'ar'),
                    'en' => $this->mapSlideTranslation($item, 'en'),
                ],
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapSlideTranslation(HomeSectionItem $item, string $locale): array
    {
        $translation = $item->translationFor($locale);

        return [
            'title' => $translation?->title,
            'description' => $translation?->description,
            'button_text' => $translation?->button_text,
            'button_url' => $translation?->button_url,
            'secondary_button_text' => $translation?->secondary_button_text,
            'secondary_button_url' => $translation?->secondary_button_url,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $slides
     */
    protected function syncSlides(HomeSection $section, array $slides): void
    {
        if ($section->type !== 'hero') {
            return;
        }

        $keptIds = [];

        foreach ($slides as $index => $slide) {
            if (! is_array($slide)) {
                continue;
            }

            $translations = $slide['translations'] ?? [];
            $itemId = $slide['id'] ?? null;

            $payload = [
                'image' => $this->normalizeUploadedPath($slide['image'] ?? null),
                'is_active' => (bool) ($slide['is_active'] ?? true),
                'sort_order' => (int) ($slide['sort_order'] ?? $index),
            ];

            if ($itemId) {
                $item = HomeSectionItem::query()
                    ->where('home_section_id', $section->id)
                    ->find($itemId);
                $item?->update($payload);
            } else {
                $item = $section->items()->create($payload);
            }

            if (! $item) {
                continue;
            }

            $keptIds[] = $item->id;

            foreach (['ar', 'en'] as $locale) {
                if (! isset($translations[$locale]) || ! is_array($translations[$locale])) {
                    continue;
                }

                $fields = collect($translations[$locale])
                    ->only([
                        'title',
                        'description',
                        'button_text',
                        'button_url',
                        'secondary_button_text',
                        'secondary_button_url',
                    ])
                    ->all();

                if ($fields === []) {
                    continue;
                }

                HomeSectionItemTranslation::query()->updateOrCreate(
                    ['home_section_item_id' => $item->id, 'locale' => $locale],
                    $fields,
                );
            }
        }

        $section->items()->whereNotIn('id', $keptIds)->delete();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function extractSlides(array $data): array
    {
        $slides = $data['slides'] ?? [];
        unset($data['slides']);

        if (! is_array($slides)) {
            $slides = [];
        }

        return [$slides, $data];
    }

    protected function normalizeUploadedPath(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = $value[array_key_first($value)] ?? null;
        }

        return filled($value) ? (string) $value : null;
    }
}
