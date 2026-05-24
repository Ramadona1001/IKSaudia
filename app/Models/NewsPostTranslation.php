<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsPostTranslation extends Model
{
    protected $fillable = ['news_post_id', 'locale', 'title', 'slug', 'excerpt', 'body'];

    public function newsPost(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class);
    }
}
