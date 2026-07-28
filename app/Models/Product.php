<?php

namespace App\Models;

use App\Models\Concerns\HasFeaturedImage;
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFeaturedImage;
    use HasSeoMeta;
    use HasTranslations;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'parent_id',
        'legacy_page_id',
        'legacy_path',
        'featured_image',
        'pdf_path',
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
        static::creating(function (Product $product): void {
            $product->uuid ??= (string) Str::uuid();
        });
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function isCategory(): bool
    {
        if ($this->relationLoaded('children')) {
            return $this->children->isNotEmpty();
        }

        return $this->children()->exists();
    }

    public function pdfUrl(): ?string
    {
        if (! $this->pdf_path) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->pdf_path);
    }

    public function hasSpecificationPdf(): bool
    {
        return filled($this->pdf_path);
    }

    public function specificationPdfDownloadName(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $slug = $this->translate($locale)?->slug
            ?? $this->translate('en')?->slug
            ?? 'product';

        return $slug.'-technical-specifications.pdf';
    }
}
