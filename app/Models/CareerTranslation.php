<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerTranslation extends Model
{
    protected $fillable = [
        'career_id', 'locale', 'title', 'slug', 'summary',
        'requirements', 'responsibilities', 'benefits',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }
}
