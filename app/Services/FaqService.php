<?php

namespace App\Services;

use Illuminate\Support\Collection;

class FaqService
{
    /**
     * Return FAQ categories with localized question/answer text.
     * Each category is an associative array containing:
     *   key, title, icon, color, items[ { question, answer } ]
     */
    public function categories(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();
        $fallback = config('locales.fallback', 'en');
        $raw = config('faqs.categories', []);

        return collect($raw)->map(function (array $cat) use ($locale, $fallback): array {
            return [
                'key' => $cat['key'] ?? null,
                'icon' => $cat['icon'] ?? 'bi-question-circle-fill',
                'color' => $cat['color'] ?? 'gold',
                'title' => $cat['title'][$locale] ?? $cat['title'][$fallback] ?? '',
                'items' => collect($cat['items'] ?? [])->map(fn (array $item): array => [
                    'question' => $item['question'][$locale] ?? $item['question'][$fallback] ?? '',
                    'answer' => $item['answer'][$locale] ?? $item['answer'][$fallback] ?? '',
                ])->values()->all(),
            ];
        });
    }

    /**
     * Flatten all FAQ items into a single list (useful for homepage previews).
     */
    public function flat(?string $locale = null, int $limit = 6): Collection
    {
        return $this->categories($locale)
            ->flatMap(fn (array $cat) => $cat['items'])
            ->take($limit)
            ->values();
    }
}
