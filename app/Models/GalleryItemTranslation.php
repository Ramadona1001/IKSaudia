<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItemTranslation extends Model
{
    protected $fillable = ['gallery_item_id', 'locale', 'title', 'caption'];

    public function galleryItem(): BelongsTo
    {
        return $this->belongsTo(GalleryItem::class);
    }
}
