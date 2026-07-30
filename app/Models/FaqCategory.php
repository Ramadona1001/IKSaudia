<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqCategory extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = ['key', 'icon', 'color', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FaqCategoryTranslation::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order');
    }

    public function publishedFaqs(): HasMany
    {
        return $this->faqs()->where('is_published', true);
    }
}
