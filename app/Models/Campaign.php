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
     * Get the donations for the campaign.
     */
    public function donations(): HasMany
    {
        return $this->hasMany(CampaignDonation::class);
    }

    /**
     * Get the progress percentage of the campaign.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->goal <= 0) {
            return 0;
        }
        return min(100, ($this->current_amount / $this->goal) * 100);
    }

    /**
     * Get the remaining amount needed.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->goal - $this->current_amount);
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
     * Check if the campaign has reached its goal.
     */
    public function hasReachedGoal(): bool
    {
        return $this->current_amount >= $this->goal;
    }

    /**
     * Get the total number of donations.
     */
    public function getTotalDonationsCountAttribute(): int
    {
        return $this->donations()->count();
    }

    /**
     * Get the average donation amount.
     */
    public function getAverageDonationAttribute(): float
    {
        $count = $this->donations()->count();
        if ($count === 0) {
            return 0;
        }
        return $this->current_amount / $count;
    }

    /**
     * Scope to get active campaigns.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get featured campaigns.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get campaigns by charity.
     */
    public function scopeByCharity($query, $charityId)
    {
        return $query->where('charity_id', $charityId);
    }
} 