<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerTranslation extends Model
{
    protected $fillable = ['partner_id', 'locale', 'name', 'description'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
