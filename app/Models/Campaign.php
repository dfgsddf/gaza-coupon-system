<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'goal',
        'current_amount',
        'status',
        'charity_id',
        'start_date',
        'end_date',
        'image_url',
        'is_featured',
        'donors_count',
    ];

    protected $casts = [
        'goal' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the charity that owns the campaign.
     */
    public function charity(): BelongsTo
    {
        return $this->belongsTo(User::class, 'charity_id');
    }

    /**
     * Get the coupons for the campaign.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the donations for the campaign.
     */
    public function donations(): HasMany
    {
        return $this->hasMany(CampaignDonation::class);
    }

    /**
     * Get the beneficiaries for the campaign.
     */
    public function beneficiaries(): HasMany
    {
        return $this->hasMany(CampaignBeneficiary::class);
    }

    /**
     * Get the approved beneficiaries for the campaign.
     */
    public function approvedBeneficiaries(): HasMany
    {
        return $this->hasMany(CampaignBeneficiary::class)->where('status', 'approved');
    }

    /**
     * Get the pending beneficiaries for the campaign.
     */
    public function pendingBeneficiaries(): HasMany
    {
        return $this->hasMany(CampaignBeneficiary::class)->where('status', 'pending');
    }

    /**
     * Check if the campaign is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the campaign is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the campaign is currently running.
     */
    public function isRunning(): bool
    {
        $now = now()->toDateString();
        return $this->start_date <= $now && $this->end_date >= $now && $this->isActive();
    }

    /**
     * Get the total number of coupons.
     */
    public function getTotalCouponsCountAttribute(): int
    {
        return $this->coupons()->count();
    }

    /**
     * Get the total value of coupons issued.
     */
    public function getTotalCouponsValueAttribute(): float
    {
        return $this->coupons()->sum('amount');
    }

    /**
     * Scope to get active campaigns.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get campaigns by organization.
     */
    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope to get running campaigns.
     */
    public function scopeRunning($query)
    {
        $now = now()->toDateString();
        return $query->where('status', 'active')
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }
} 