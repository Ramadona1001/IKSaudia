<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'career_id', 'reference_number', 'first_name', 'last_name', 'email', 'phone',
        'nationality', 'cover_letter', 'resume_path', 'linkedin_url', 'status',
        'locale', 'ip_address', 'user_agent', 'admin_notes', 'reviewed_at', 'reviewed_by',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
