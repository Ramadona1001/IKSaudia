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

class Project extends Model
{
    use HasFeaturedImage;
    use HasSeoMeta;
    use HasTranslations;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'client_name', 'location', 'featured_image', 'year', 'completed_at',
        'is_featured', 'is_published', 'published_at', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'completed_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(fn (Project $p) => $p->uuid ??= (string) Str::uuid());
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class, 'industry_project')->withPivot('sort_order');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'project_service')->withPivot('sort_order');
    }

}
