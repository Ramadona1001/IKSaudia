<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number', 'name', 'email', 'phone', 'company', 'subject', 'message',
        'status', 'locale', 'ip_address', 'user_agent', 'admin_notes', 'read_at', 'read_by',
    ];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function readBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }
}
