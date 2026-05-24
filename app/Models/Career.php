<?php

namespace App\Models;

use App\Models\Concerns\HasFeaturedImage;
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Career extends Model
{
    use HasFeaturedImage;
    use HasSeoMeta;
    use HasTranslations;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'featured_image', 'department', 'location', 'employment_type', 'experience_level',
        'is_remote', 'is_published', 'published_at', 'closes_at', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_remote' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'closes_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(fn (Career $c) => $c->uuid ??= (string) Str::uuid());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CareerTranslation::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class);
    }
}
