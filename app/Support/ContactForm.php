<?php

namespace App\Support;

final class ContactForm
{
    /** @var list<string> */
    public const CORE_KEYS = ['name', 'email', 'phone', 'company', 'subject', 'message'];

    /**
     * @return array{eyebrow: string, title: string, title_accent: string, intro: string}
     */
    public static function copy(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return [
            'eyebrow' => self::localizedText('contact.form_eyebrow', 'front.contact.form_eyebrow', $locale),
            'title' => self::localizedText('contact.form_title', 'front.contact.form_title1', $locale),
            'title_accent' => self::localizedText('contact.form_title_accent', 'front.contact.form_title2', $locale),
            'intro' => self::localizedText('contact.form_intro', 'front.contact.form_intro', $locale, true),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function fields(): array
    {
        $configured = collect(setting('contact.form_fields', []))
            ->filter(fn ($field) => is_array($field) && ($field['is_visible'] ?? true) && filled($field['key'] ?? null))
            ->sortBy(fn (array $field) => (int) ($field['sort_order'] ?? 0))
            ->values();

        if ($configured->isNotEmpty()) {
            return $configured->all();
        }

        return self::defaultFields();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function defaultFields(): array
    {
        return [
            self::defaultField('name', 'text', 'front.contact.fields.name', 'front.contact.fields.name_ph', true, 'half', 0),
            self::defaultField('company', 'text', 'front.contact.fields.company', 'front.contact.fields.company_ph', false, 'half', 1),
            self::defaultField('email', 'email', 'front.contact.fields.email', 'front.contact.fields.email_ph', true, 'half', 2),
            self::defaultField('phone', 'tel', 'front.contact.fields.phone', 'front.contact.fields.phone_ph', false, 'half', 3),
            self::defaultField('subject', 'text', 'front.contact.fields.subject', 'front.contact.fields.subject_ph', true, 'full', 4),
            self::defaultField('message', 'textarea', 'front.contact.fields.message', 'front.contact.fields.message_ph', true, 'full', 5),
        ];
    }

    public static function label(array $field, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return (string) ($field['label_ar'] ?? $field['label_en'] ?? $field['key'] ?? '');
        }

        return (string) ($field['label_en'] ?? $field['label_ar'] ?? $field['key'] ?? '');
    }

    public static function placeholder(array $field, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return (string) ($field['placeholder_ar'] ?? $field['placeholder_en'] ?? '');
        }

        return (string) ($field['placeholder_en'] ?? $field['placeholder_ar'] ?? '');
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function selectOptions(array $field, ?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return collect($field['options'] ?? [])
            ->filter(fn ($option) => is_array($option) && filled($option['value'] ?? null))
            ->map(function (array $option) use ($locale): array {
                $label = $locale === 'ar'
                    ? ($option['label_ar'] ?? $option['label_en'] ?? $option['value'])
                    : ($option['label_en'] ?? $option['label_ar'] ?? $option['value']);

                return [
                    'value' => (string) $option['value'],
                    'label' => (string) $label,
                ];
            })
            ->values()
            ->all();
    }

    public static function inputType(array $field): string
    {
        return match ($field['type'] ?? 'text') {
            'email' => 'email',
            'tel' => 'tel',
            'number' => 'number',
            'url' => 'url',
            default => 'text',
        };
    }

    /**
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        $rules = [];

        foreach (self::fields() as $field) {
            $key = (string) ($field['key'] ?? '');

            if ($key === '') {
                continue;
            }

            $fieldRules = [];
            $required = (bool) ($field['is_required'] ?? false);

            if ($required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            $fieldRules = array_merge($fieldRules, match ($field['type'] ?? 'text') {
                'email' => ['string', 'email:rfc', 'max:255'],
                'tel' => ['string', 'max:30', 'regex:/^[\d\s\-\+\(\)]+$/'],
                'textarea' => ['string', 'max:5000'],
                'number' => ['numeric'],
                'url' => ['string', 'url', 'max:500'],
                'select' => array_values(array_filter([
                    'string',
                    'max:255',
                    self::selectOptions($field) !== []
                        ? 'in:'.implode(',', array_column(self::selectOptions($field), 'value'))
                        : null,
                ])),
                default => ['string', 'max:500'],
            });

            if ($required && ($field['type'] ?? 'text') === 'textarea') {
                $fieldRules[] = 'min:10';
            }

            $rules[$key] = $fieldRules;
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public static function validationAttributes(): array
    {
        $attributes = [];

        foreach (self::fields() as $field) {
            $key = (string) ($field['key'] ?? '');

            if ($key === '') {
                continue;
            }

            $attributes[$key] = self::label($field);
        }

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function mapToSubmission(array $input): array
    {
        $core = array_fill_keys(self::CORE_KEYS, null);
        $custom = [];

        foreach (self::fields() as $field) {
            $key = (string) ($field['key'] ?? '');
            $value = $input[$key] ?? null;

            if (! filled($value)) {
                continue;
            }

            if (in_array($key, self::CORE_KEYS, true)) {
                $core[$key] = $value;
            } else {
                $custom[$key] = $value;
            }
        }

        if (blank($core['name'])) {
            $core['name'] = (string) (collect($custom)->first(fn ($value) => filled($value)) ?? 'Contact form');
        }

        if (blank($core['message'])) {
            $core['message'] = collect($custom)
                ->map(fn ($value, $label) => self::labelForKey((string) $label).': '.$value)
                ->implode("\n");
        }

        if (blank($core['message'])) {
            $core['message'] = '—';
        }

        return [
            'name' => (string) $core['name'],
            'email' => filled($core['email']) ? (string) $core['email'] : null,
            'phone' => filled($core['phone']) ? (string) $core['phone'] : null,
            'company' => filled($core['company']) ? (string) $core['company'] : null,
            'subject' => filled($core['subject']) ? (string) $core['subject'] : null,
            'message' => (string) $core['message'],
            'custom_fields' => $custom === [] ? null : $custom,
        ];
    }

    private static function labelForKey(string $key): string
    {
        foreach (self::fields() as $field) {
            if (($field['key'] ?? null) === $key) {
                return self::label($field);
            }
        }

        return str($key)->replace('_', ' ')->title()->toString();
    }

    private static function localizedText(string $settingsKey, string $fallbackKey, string $locale, bool $allowMultiline = false): string
    {
        $value = setting($settingsKey);

        if (is_array($value)) {
            $text = (string) ($value[$locale] ?? $value['en'] ?? $value['ar'] ?? '');
        } else {
            $text = is_string($value) ? $value : '';
        }

        if (filled($text)) {
            return $text;
        }

        return (string) __($fallbackKey, [], $locale);
    }

    /**
     * @return array<string, mixed>
     */
    private static function defaultField(
        string $key,
        string $type,
        string $labelKey,
        string $placeholderKey,
        bool $required,
        string $width,
        int $sortOrder,
    ): array {
        return [
            'key' => $key,
            'type' => $type,
            'label_en' => __($labelKey, [], 'en'),
            'label_ar' => __($labelKey, [], 'ar'),
            'placeholder_en' => __($placeholderKey, [], 'en'),
            'placeholder_ar' => __($placeholderKey, [], 'ar'),
            'is_required' => $required,
            'width' => $width,
            'is_visible' => true,
            'sort_order' => $sortOrder,
            'options' => [],
        ];
    }
}
