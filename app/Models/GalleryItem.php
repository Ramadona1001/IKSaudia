<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'gallery_id',
        'media_type',
        'file_path',
        'thumbnail_path',
        'youtube_url',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(GalleryItemTranslation::class);
    }

    public function isYoutube(): bool
    {
        return $this->media_type === 'video_youtube';
    }

    public function isVideoFile(): bool
    {
        return $this->media_type === 'video_file';
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    public function mediaUrl(): ?string
    {
        if ($this->isYoutube()) {
            return $this->youtubeEmbedUrl();
        }

        if (! $this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function youtubeEmbedUrl(): ?string
    {
        if (! $this->youtube_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }

        return $this->youtube_url;
    }
}
