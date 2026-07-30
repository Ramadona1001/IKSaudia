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
        $categories = config('faqs.categories', []);

        if ($categories === []) {
            $this->command?->warn('No FAQ categories found in config/faqs.php.');

            return;
        }

        DB::transaction(function () use ($categories): void {
            Faq::query()->each(fn (Faq $faq) => $faq->forceDelete());
            FaqCategory::query()->each(fn (FaqCategory $category) => $category->forceDelete());

            $categoryCount = 0;
            $faqCount = 0;

            foreach ($categories as $index => $categoryData) {
                $category = FaqCategory::query()->create([
                    'key' => (string) ($categoryData['key'] ?? 'category-'.$index),
                    'icon' => (string) ($categoryData['icon'] ?? 'bi-question-circle-fill'),
                    'color' => (string) ($categoryData['color'] ?? 'gold'),
                    'is_published' => true,
                    'sort_order' => $index,
                ]);

                foreach (['ar', 'en'] as $locale) {
                    $category->translations()->create([
                        'locale' => $locale,
                        'title' => (string) (
                            $categoryData['title'][$locale]
                            ?? $categoryData['title']['en']
                            ?? $category->key
                        ),
                    ]);
                }

                $categoryCount++;

                foreach ($categoryData['items'] ?? [] as $itemIndex => $itemData) {
                    $faq = Faq::query()->create([
                        'faq_category_id' => $category->id,
                        'is_published' => true,
                        'sort_order' => $itemIndex,
                    ]);

                    foreach (['ar', 'en'] as $locale) {
                        $faq->translations()->create([
                            'locale' => $locale,
                            'question' => (string) (
                                $itemData['question'][$locale]
                                ?? $itemData['question']['en']
                                ?? ''
                            ),
                            'answer' => (string) (
                                $itemData['answer'][$locale]
                                ?? $itemData['answer']['en']
                                ?? ''
                            ),
                        ]);
                    }

                    $faqCount++;
                }
            }

            $this->command?->info("Seeded {$categoryCount} FAQ categories and {$faqCount} questions from config/faqs.php.");
        });
    }
}
