<?php

namespace App\Models;

use App\Models\Concerns\HasFeaturedImage;
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Industry extends Model
{
    use HasFeaturedImage;
    use HasSeoMeta;
    use HasTranslations;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'featured_image',
        'icon',
        'is_featured',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Industry $industry): void {
            $industry->uuid ??= (string) Str::uuid();
        });
    }

    public function translations(): HasMany
    {
        return $this->hasMany(IndustryTranslation::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'industry_service')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}
