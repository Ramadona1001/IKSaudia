<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationTranslation extends Model
{
    protected $fillable = ['certification_id', 'locale', 'title', 'slug', 'description'];

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }
}
