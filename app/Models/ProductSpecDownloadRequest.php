<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecDownloadRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number',
        'product_id',
        'name',
        'email',
        'phone',
        'company',
        'status',
        'locale',
        'download_token',
        'download_token_expires_at',
        'approved_at',
        'rejected_at',
        'reviewed_by',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'download_token_expires_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function tokenIsValid(): bool
    {
        return $this->isApproved()
            && filled($this->download_token)
            && $this->download_token_expires_at !== null
            && $this->download_token_expires_at->isFuture();
    }
}
