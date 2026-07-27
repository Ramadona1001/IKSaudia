<?php

namespace App\Models;

use App\Models\Concerns\HasFeaturedImage;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Partner extends Model
{
    use HasFeaturedImage;
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = ['uuid', 'featured_image', 'website_url', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(fn (Partner $p) => $p->uuid ??= (string) Str::uuid());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PartnerTranslation::class);
    }
}
