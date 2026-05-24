<?php

namespace App\Data;

/**
 * Typed accessors over the nested settings array for views/controllers.
 */
final readonly class WebsiteSettingsBag
{
  public function __construct(
    private array $data,
    private string $locale,
  ) {}

  public static function make(?string $locale = null): self
  {
    $locale ??= app()->getLocale();
    $all = settings(null, $locale);

    return new self($all, $locale);
  }

  public function locale(): string
  {
    return $this->locale;
  }

  public function get(string $group, string $key, mixed $default = null): mixed
  {
    return $this->data[$group][$key] ?? $default;
  }

  public function siteName(): ?string
  {
    return $this->get('general', 'site_name');
  }

  public function siteTagline(): ?string
  {
    return $this->get('general', 'site_tagline');
  }

  /** @return list<array{label?: string, number: string, is_primary?: bool}> */
  public function phones(): array
  {
    return $this->get('contact', 'phones', []) ?: [];
  }

  public function primaryPhone(): ?array
  {
    foreach ($this->phones() as $phone) {
      if (! empty($phone['is_primary'])) {
        return $phone;
      }
    }

    return $this->phones()[0] ?? null;
  }

  /** @return list<array{label?: string, address: string, is_primary?: bool}> */
  public function emails(): array
  {
    return $this->get('contact', 'emails', []) ?: [];
  }

  public function primaryEmail(): ?string
  {
    foreach ($this->emails() as $email) {
      if (! empty($email['is_primary'])) {
        return $email['address'] ?? null;
      }
    }

    return $this->emails()[0]['address'] ?? null;
  }

  /** @return list<array{platform: string, url?: string, enabled?: bool, label?: string}> */
  public function socialLinks(): array
  {
    return collect($this->get('social', 'links', []) ?: [])
      ->filter(fn (array $link) => ($link['enabled'] ?? true) && filled($link['url'] ?? null))
      ->values()
      ->all();
  }

  public function copyrightText(): ?string
  {
    return $this->get('footer', 'copyright');
  }

  public function certificationBadges(): array
  {
    return $this->get('footer', 'certification_badges', []) ?: [];
  }

  /**
   * Build a wa.me link using the WhatsApp number from settings, falling back to the provided default
   * (digits only, no plus sign). Returns null when nothing is available.
   */
  public function whatsappFormatted(?string $fallback = null): ?string
  {
    $raw = (string) ($this->get('contact', 'whatsapp_number') ?? $fallback ?? '');
    $digits = preg_replace('/\D+/', '', $raw) ?? '';

    if ($digits === '') {
      return null;
    }

    return 'https://wa.me/'.$digits;
  }

  /**
   * Localised, single-line address. Falls back to the primary office address.
   */
  public function localizedAddress(?string $locale = null): ?string
  {
    $locale ??= $this->locale;
    $explicit = $this->get('contact', 'address_'.$locale);

    if (filled($explicit)) {
      return (string) $explicit;
    }

    $offices = $this->get('contact', 'offices', []) ?: [];
    $first = is_array($offices) ? ($offices[0] ?? null) : null;

    return is_array($first) ? ($first['address'] ?? null) : null;
  }

  /**
   * @return list<string> Pretty business-hour lines for the contact card.
   */
  public function businessHours(?string $locale = null): array
  {
    $locale ??= $this->locale;
    $lines = $this->get('contact', 'business_hours', []);

    if (is_array($lines) && $lines !== []) {
      return array_values(array_filter(array_map(fn ($l) => is_string($l) ? trim($l) : null, $lines)));
    }

    return [
      __('front.contact.cards.hours_value', [], $locale),
      __('front.contact.cards.hours_emergency', [], $locale),
      __('front.contact.cards.hours_zone', [], $locale),
    ];
  }
}
