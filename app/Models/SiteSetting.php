<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'label',
        'type',
        'value',
        'is_translatable',
        'options',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_translatable' => 'boolean',
            'options' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SiteSettingTranslation::class);
    }

    public function translationFor(string $locale): ?SiteSettingTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
