<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = ['faq_category_id', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }
}
