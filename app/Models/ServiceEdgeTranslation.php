<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceEdgeTranslation extends Model
{
    protected $fillable = [
        'service_edge_id',
        'locale',
        'title',
        'description',
    ];

    public function edge(): BelongsTo
    {
        return $this->belongsTo(ServiceEdge::class);
    }
}
