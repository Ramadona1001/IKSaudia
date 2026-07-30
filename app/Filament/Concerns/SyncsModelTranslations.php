<?php

namespace App\Filament\Concerns;

use App\Services\Security\HtmlSanitizer;
use Illuminate\Database\Eloquent\Model;

trait SyncsModelTranslations
{
    /**
     * @param  class-string<Model>  $translationModel
     * @param  array<string, array<string, mixed>>  $translations
     * @param  list<string>  $fields
     */
    protected function syncTranslations(
        Model $parent,
        string $translationModel,
        string $foreignKey,
        array $translations,
        array $fields,
    ): void {
        foreach ($translations as $locale => $data) {
            if (! is_array($data)) {
                continue;
            }

            $payload = collect($fields)
                ->mapWithKeys(fn (string $field) => [$field => $data[$field] ?? null])
                ->filter(fn ($value) => $value !== null && $value !== '')
                ->all();

            if (
                in_array('slug', $fields, true)
                && in_array('title', $fields, true)
                && empty($payload['slug'])
                && ! empty($payload['title'])
            ) {
                $payload['slug'] = \Illuminate\Support\Str::slug((string) $payload['title']);
            }

            $payload = app(HtmlSanitizer::class)->cleanFields(
                $payload,
                array_values(array_intersect($fields, [
                    'body', 'content', 'description', 'requirements', 'responsibilities', 'benefits', 'excerpt', 'answer',
                ]))
            );

            if ($payload === []) {
                continue;
            }

            $translationModel::query()->updateOrCreate(
                [$foreignKey => $parent->getKey(), 'locale' => $locale],
                $payload,
            );
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function extractTranslations(array $data, array $locales = ['ar', 'en']): array
    {
        $translations = [];

        foreach ($locales as $locale) {
            if (isset($data['translations'][$locale]) && is_array($data['translations'][$locale])) {
                $translations[$locale] = $data['translations'][$locale];
            }
        }

        unset($data['translations']);

        return [$translations, $data];
    }
}
