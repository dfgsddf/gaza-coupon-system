<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beneficiary extends Model
{
    use HasFactory;
    
    protected $table = 'beneficiaries';

    protected $fillable = [
        'user_id',
        'national_id',
        'family_members',
        'social_status',
        'address'
    ];

    protected $casts = [
        'family_members' => 'integer',
    ];

    /**
     * Get the user that owns the beneficiary.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name of the beneficiary.
     */
    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? 'غير محدد';
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->social_status) {
            'married' => 'badge bg-success',
            'single' => 'badge bg-primary',
            'divorced' => 'badge bg-warning',
            'widowed' => 'badge bg-secondary',
            default => 'badge bg-secondary'
        };
    }
}
