<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSectionItemTranslation extends Model
{
    protected $fillable = [
        'home_section_item_id',
        'locale',
        'title',
        'description',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
    ];

    public function homeSectionItem(): BelongsTo
    {
        return $this->belongsTo(HomeSectionItem::class);
    }
}
