<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClientCatalogService
{
    /** @return Collection<int, Client> */
    public function publishedList(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return Cache::remember("clients.list.{$locale}", 3600, function () use ($locale) {
            return Client::query()
                ->where('is_published', true)
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function clearCache(): void
    {
        foreach (config('locales.supported', ['ar', 'en']) as $locale) {
            Cache::forget("clients.list.{$locale}");
        }
    }
}
