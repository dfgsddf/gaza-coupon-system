<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'donor_id',
        'amount',
        'donor_name',
        'donor_email',
        'donor_phone',
        'message',
        'payment_method',
        'payment_status',
        'transaction_id',
        'is_anonymous',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    /**
     * Get the campaign that received the donation.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the donor who made the donation.
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    /**
     * Get the display name for the donor.
     */
    public function getDonorDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous Donor';
        }
        
        if ($this->donor) {
            return $this->donor->name;
        }
        
        return $this->donor_name ?: 'Unknown Donor';
    }

    /**
     * Check if the donation is completed.
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if the donation is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if the donation failed.
     */
    public function isFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Scope to get completed donations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope to get donations by payment method.
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope to get donations within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
