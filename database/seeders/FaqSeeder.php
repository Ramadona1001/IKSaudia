<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        if (FaqCategory::query()->exists()) {
            return;
        }

        DB::transaction(function (): void {
            $sortOrder = 0;

            foreach (config('faqs.categories', []) as $categoryData) {
                $category = FaqCategory::query()->create([
                    'key' => (string) ($categoryData['key'] ?? 'category-'.$sortOrder),
                    'icon' => (string) ($categoryData['icon'] ?? 'bi-question-circle-fill'),
                    'color' => (string) ($categoryData['color'] ?? 'gold'),
                    'is_published' => true,
                    'sort_order' => $sortOrder,
                ]);

                foreach (['ar', 'en'] as $locale) {
                    $category->translations()->create([
                        'locale' => $locale,
                        'title' => (string) ($categoryData['title'][$locale] ?? $categoryData['title']['en'] ?? $category->key),
                    ]);
                }

                $itemOrder = 0;

                foreach ($categoryData['items'] ?? [] as $itemData) {
                    $faq = Faq::query()->create([
                        'faq_category_id' => $category->id,
                        'is_published' => true,
                        'sort_order' => $itemOrder,
                    ]);

                    foreach (['ar', 'en'] as $locale) {
                        $faq->translations()->create([
                            'locale' => $locale,
                            'question' => (string) ($itemData['question'][$locale] ?? $itemData['question']['en'] ?? ''),
                            'answer' => (string) ($itemData['answer'][$locale] ?? $itemData['answer']['en'] ?? ''),
                        ]);
                    }

                    $itemOrder++;
                }

                $sortOrder++;
            }
        });
    }
}
