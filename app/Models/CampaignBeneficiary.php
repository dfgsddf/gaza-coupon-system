<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'beneficiary_id',
        'status',
        'allocated_amount',
        'notes',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the campaign that owns the beneficiary.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the beneficiary assigned to the campaign.
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    /**
     * Check if the beneficiary is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the beneficiary is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the beneficiary is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the beneficiary is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Scope to get approved beneficiaries.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get pending beneficiaries.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get rejected beneficiaries.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to get completed beneficiaries.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'approved' => 'badge bg-success',
            'pending' => 'badge bg-warning',
            'rejected' => 'badge bg-danger',
            'completed' => 'badge bg-info',
            default => 'badge bg-secondary'
        };
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayNameAttribute(): string
    {
        return match($this->status) {
            'approved' => 'مُوافق عليه',
            'pending' => 'في الانتظار',
            'rejected' => 'مرفوض',
            'completed' => 'مكتمل',
            default => 'غير محدد'
        };
    }
} 