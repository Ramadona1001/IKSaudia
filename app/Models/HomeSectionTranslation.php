<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSectionTranslation extends Model
{
    protected $fillable = [
        'home_section_id',
        'locale',
        'title',
        'subtitle',
        'content',
        'cta_label',
        'cta_url',
    ];

    /**
     * Plain text body (legacy rows may still decode from JSON shape in DB).
     */
    public function bodyText(): ?string
    {
        $content = $this->attributes['content'] ?? null;

        if ($content === null || $content === '') {
            return null;
        }

        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded['text'] ?? $decoded['body'] ?? null;
            }

            return $content;
        }

        if (is_array($content)) {
            return $content['text'] ?? $content['body'] ?? null;
        }

        return null;
    }

    public function homeSection(): BelongsTo
    {
        return $this->belongsTo(HomeSection::class);
    }
}
