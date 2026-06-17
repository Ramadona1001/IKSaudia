<?php

namespace App\Support;

final class SocialPlatform
{
  public static function icon(string $platform): string
  {
    return match (strtolower($platform)) {
      'linkedin' => 'bi-linkedin',
      'x', 'twitter' => 'bi-twitter-x',
      'facebook' => 'bi-facebook',
      'instagram' => 'bi-instagram',
      'youtube' => 'bi-youtube',
      'tiktok' => 'bi-tiktok',
      'snapchat' => 'bi-snapchat',
      'behance' => 'bi-behance',
      'whatsapp' => 'bi-whatsapp',
      default => 'bi-globe2',
    };
  }

  public static function label(array $link): string
  {
    if (filled($link['label'] ?? null)) {
      return (string) $link['label'];
    }

    return match (strtolower((string) ($link['platform'] ?? ''))) {
      'linkedin' => 'LinkedIn',
      'x', 'twitter' => 'X',
      'facebook' => 'Facebook',
      'instagram' => 'Instagram',
      'youtube' => 'YouTube',
      'tiktok' => 'TikTok',
      'snapchat' => 'Snapchat',
      'behance' => 'Behance',
      'whatsapp' => 'WhatsApp',
      default => ucfirst((string) ($link['platform'] ?? 'Social')),
    };
  }
}
