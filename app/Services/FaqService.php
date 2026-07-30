<?php

namespace App\Services;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Support\Collection;

class FaqService
{
    /**
     * Return FAQ categories with localized question/answer text.
     *
     * @return Collection<int, array{key: ?string, icon: string, color: string, title: string, items: list<array{question: string, answer: string}>}>
     */
    public function categories(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();
        $fallback = config('locales.fallback', 'en');

        $fromDatabase = FaqCategory::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with([
                'translations',
                'faqs' => fn ($query) => $query
                    ->where('is_published', true)
                    ->orderBy('sort_order')
                    ->with('translations'),
            ])
            ->get()
            ->map(fn (FaqCategory $category): array => $this->mapCategory($category, $locale, $fallback))
            ->filter(fn (array $category): bool => count($category['items']) > 0)
            ->values();

        if ($fromDatabase->isNotEmpty()) {
            return $fromDatabase;
        }

        return $this->categoriesFromConfig($locale, $fallback);
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

    /**
     * @return array{key: ?string, icon: string, color: string, title: string, items: list<array{question: string, answer: string}>}
     */
    private function mapCategory(FaqCategory $category, string $locale, string $fallback): array
    {
        $translation = $category->translate($locale);

        return [
            'key' => $category->key,
            'icon' => $category->icon ?: 'bi-question-circle-fill',
            'color' => $category->color ?: 'gold',
            'title' => $this->localizedValue($translation?->title, $locale, $fallback),
            'items' => $category->faqs
                ->map(fn (Faq $faq): array => $this->mapFaq($faq, $locale, $fallback))
                ->filter(fn (array $item): bool => filled($item['question']) && filled($item['answer']))
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array{question: string, answer: string}
     */
    private function mapFaq(Faq $faq, string $locale, string $fallback): array
    {
        $translation = $faq->translate($locale);

        return [
            'question' => $this->localizedValue($translation?->question, $locale, $fallback),
            'answer' => $this->localizedValue($translation?->answer, $locale, $fallback),
        ];
    }

    private function localizedValue(?string $value, string $locale, string $fallback): string
    {
        return trim((string) ($value ?? ''));
    }

    /**
     * @return Collection<int, array{key: ?string, icon: string, color: string, title: string, items: list<array{question: string, answer: string}>}>
     */
    private function categoriesFromConfig(string $locale, string $fallback): Collection
    {
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
}
