<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientTranslation;
use App\Models\Partner;
use App\Models\PartnerTranslation;
use App\Services\Http\SafeHttpClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ClientPartnerLegacyImporter
{
    private const BASE_URL = 'https://www.iksaudi.com/';

    public function importClients(): int
    {
        $entries = $this->scrapeSection('Our Clients');
        $count = 0;

        foreach ($entries as $index => $entry) {
            $this->importClient($entry, $index);
            $count++;
        }

        return $count;
    }

    public function importPartners(): int
    {
        $entries = $this->scrapeSection('Our Partners');
        $count = 0;

        foreach ($entries as $index => $entry) {
            $this->importPartner($entry, $index);
            $count++;
        }

        return $count;
    }

    /**
     * @return list<array{name: string, image_url: string}>
     */
    private function scrapeSection(string $title): array
    {
        $html = app(SafeHttpClient::class)
            ->get(self::BASE_URL, [])
            ->throw()
            ->body();

        $pattern = $title === 'Our Clients'
            ? '/Our Clients<\/h2>.*?<div class="myclients[^"]*"[^>]*>(.*?)<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/section>/is'
            : '/Our Partners<\/h2>.*?<div class="myclients[^"]*"[^>]*>(.*?)<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/section>/is';

        if (! preg_match($pattern, $html, $section)) {
            return [];
        }

        $assetPath = $title === 'Our Clients' ? '/client_images/' : '/partners/';

        if (! preg_match_all('/<img[^>]+src="([^"]+)"[^>]*alt="([^"]*)"/i', $section[1], $matches, PREG_SET_ORDER)) {
            return [];
        }

        $entries = [];
        foreach ($matches as $match) {
            if (! str_contains($match[1], $assetPath)) {
                continue;
            }

            $name = trim(html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5));
            if ($name === '') {
                continue;
            }

            $entries[] = [
                'name' => $name,
                'image_url' => $this->absoluteUrl($match[1]),
            ];
        }

        return $entries;
    }

    /**
     * @param  array{name: string, image_url: string}  $entry
     */
    private function importClient(array $entry, int $sortOrder): void
    {
        $slug = Str::slug($entry['name']);

        $client = ClientTranslation::query()
            ->where('locale', 'en')
            ->where('name', $entry['name'])
            ->first()
            ?->client ?? new Client(['uuid' => (string) Str::uuid()]);

        $client->fill([
            'featured_image' => $this->downloadImage($entry['image_url'], "clients/{$slug}.jpg"),
            'is_featured' => $sortOrder < 8,
            'is_published' => true,
            'sort_order' => $sortOrder,
        ]);
        $client->save();

        foreach (['en', 'ar'] as $locale) {
            ClientTranslation::query()->updateOrCreate(
                ['client_id' => $client->id, 'locale' => $locale],
                [
                    'name' => $entry['name'],
                    'description' => $locale === 'ar'
                        ? 'عميل معتمد لدى IK Saudi For Industries.'
                        : 'Trusted client of IK Saudi For Industries.',
                ],
            );
        }
    }

    /**
     * @param  array{name: string, image_url: string}  $entry
     */
    private function importPartner(array $entry, int $sortOrder): void
    {
        $slug = Str::slug($entry['name']);

        $partner = PartnerTranslation::query()
            ->where('locale', 'en')
            ->where('name', $entry['name'])
            ->first()
            ?->partner ?? new Partner(['uuid' => (string) Str::uuid()]);

        $partner->fill([
            'featured_image' => $this->downloadImage($entry['image_url'], "partners/{$slug}.jpg"),
            'is_published' => true,
            'sort_order' => $sortOrder,
        ]);
        $partner->save();

        foreach (['en', 'ar'] as $locale) {
            PartnerTranslation::query()->updateOrCreate(
                ['partner_id' => $partner->id, 'locale' => $locale],
                [
                    'name' => $entry['name'],
                    'description' => $locale === 'ar'
                        ? 'شريك استراتيجي لـ IK Saudi For Industries.'
                        : 'Strategic partner of IK Saudi For Industries.',
                ],
            );
        }
    }

    private function downloadImage(string $url, string $storagePath): ?string
    {
        try {
            $client = app(SafeHttpClient::class);
            $client->assertAllowedUrl($url);
            $response = $client->get($url);

            if (! $response->successful()) {
                return null;
            }

            Storage::disk('public')->put($storagePath, $response->body());

            return $storagePath;
        } catch (\Throwable) {
            return null;
        }
    }

    private function absoluteUrl(string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return $url;
        }

        return rtrim(self::BASE_URL, '/').'/'.ltrim($url, '/');
    }
}
