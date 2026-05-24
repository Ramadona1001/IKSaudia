<?php

namespace App\Filament\Concerns;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\GalleryItemTranslation;

trait SyncsGalleryItems
{
    /** @var list<array<string, mixed>> */
    protected array $cachedItems = [];

    /**
     * @return list<array<string, mixed>>
     */
    protected function mapItemsForForm(Gallery $gallery): array
    {
        return $gallery->items()
            ->with('translations')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (GalleryItem $item) => [
                'id' => $item->id,
                'media_type' => $item->media_type,
                'file_path' => $item->file_path,
                'thumbnail_path' => $item->thumbnail_path,
                'youtube_url' => $item->youtube_url,
                'is_published' => $item->is_published,
                'sort_order' => $item->sort_order,
                'translations' => [
                    'ar' => [
                        'title' => $item->translationFor('ar')?->title,
                        'caption' => $item->translationFor('ar')?->caption,
                    ],
                    'en' => [
                        'title' => $item->translationFor('en')?->title,
                        'caption' => $item->translationFor('en')?->caption,
                    ],
                ],
            ])
            ->values()
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    protected function syncGalleryItems(Gallery $gallery, array $items): void
    {
        $keptIds = [];

        foreach ($items as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $translations = $row['translations'] ?? [];
            $itemId = $row['id'] ?? null;

            $payload = [
                'media_type' => $row['media_type'] ?? 'image',
                'file_path' => $this->normalizeUploadedPath($row['file_path'] ?? null),
                'thumbnail_path' => $this->normalizeUploadedPath($row['thumbnail_path'] ?? null),
                'youtube_url' => filled($row['youtube_url'] ?? null) ? (string) $row['youtube_url'] : null,
                'is_published' => (bool) ($row['is_published'] ?? true),
                'sort_order' => (int) ($row['sort_order'] ?? $index),
            ];

            if (($payload['media_type'] ?? '') === 'video_youtube') {
                $payload['file_path'] = null;
            } elseif (($payload['media_type'] ?? '') === 'image') {
                $payload['youtube_url'] = null;
            }

            if ($itemId) {
                $item = GalleryItem::query()->where('gallery_id', $gallery->id)->find($itemId);
                $item?->update($payload);
            } else {
                $item = $gallery->items()->create($payload);
            }

            if (! $item) {
                continue;
            }

            $keptIds[] = $item->id;

            foreach (['ar', 'en'] as $locale) {
                if (! isset($translations[$locale]) || ! is_array($translations[$locale])) {
                    continue;
                }

                GalleryItemTranslation::query()->updateOrCreate(
                    ['gallery_item_id' => $item->id, 'locale' => $locale],
                    collect($translations[$locale])->only(['title', 'caption'])->all(),
                );
            }
        }

        $gallery->items()->whereNotIn('id', $keptIds)->delete();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{0: list<array<string, mixed>>, 1: array<string, mixed>}
     */
    protected function extractGalleryItems(array $data): array
    {
        $items = $data['items'] ?? [];
        unset($data['items']);

        return [is_array($items) ? $items : [], $data];
    }

    protected function normalizeUploadedPath(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = $value[array_key_first($value)] ?? null;
        }

        return filled($value) ? (string) $value : null;
    }
}
