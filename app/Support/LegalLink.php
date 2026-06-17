<?php

namespace App\Support;

final class LegalLink
{
  public static function url(string $url, ?string $locale = null): string
  {
    $locale ??= app()->getLocale();
    $url = trim($url);

    if ($url === '' || $url === '#') {
      return '#';
    }

    if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
      return $url;
    }

    if (str_starts_with($url, '/')) {
      return $url;
    }

    return route('page.show', [$locale, ltrim($url, '/')]);
  }

  public static function label(array $link, ?string $locale = null): string
  {
    $locale ??= app()->getLocale();

    if ($locale === 'ar') {
      return (string) ($link['label_ar'] ?? $link['label_en'] ?? '');
    }

    return (string) ($link['label_en'] ?? $link['label_ar'] ?? '');
  }

  /**
   * @return list<array<string, mixed>>
   */
  public static function visibleLinks(?string $locale = null): array
  {
    $locale ??= app()->getLocale();

    $links = collect(setting('footer.legal_links', []))
      ->filter(fn (array $link) => $link['is_visible'] ?? true)
      ->sortBy(fn (array $link) => (int) ($link['sort_order'] ?? 0))
      ->values();

    if ($links->isNotEmpty()) {
      return $links->all();
    }

    return [
      [
        'label_en' => __('common.privacy', [], 'en'),
        'label_ar' => __('common.privacy', [], 'ar'),
        'url' => 'privacy-policy',
        'is_visible' => true,
        'sort_order' => 1,
      ],
      [
        'label_en' => __('common.terms', [], 'en'),
        'label_ar' => __('common.terms', [], 'ar'),
        'url' => 'terms-of-use',
        'is_visible' => true,
        'sort_order' => 2,
      ],
    ];
  }
}
