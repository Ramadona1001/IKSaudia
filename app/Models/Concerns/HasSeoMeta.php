<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSeoMeta
{
    public function seoMeta(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'seoable');
    }

    public function seoFor(?string $locale = null): ?SeoMeta
    {
        $locale ??= app()->getLocale();

        $records = $this->relationLoaded('seoMeta')
            ? $this->seoMeta
            : $this->seoMeta()->get();

        return $records->firstWhere('locale', $locale);
    }
}
