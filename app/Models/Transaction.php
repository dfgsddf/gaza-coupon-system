<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'beneficiary_id',
        'coupon_id',
        'organization_id',
        'coupon_value',
        'amount',
        'transaction_type',
        'beneficiary_name',
        'store_name',
        'status',
        'redeemed_at'
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'coupon_value' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the beneficiary that owns the transaction
     */
    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    /**
     * Get the coupon that was redeemed
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the store where the transaction occurred
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the organization associated with the transaction
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeByBeneficiary($query, $beneficiaryId)
    {
        return $query->where('beneficiary_id', $beneficiaryId);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['beneficiary', 'coupon', 'store', 'organization']);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('beneficiary_name', 'like', "%{$term}%")
              ->orWhere('store_name', 'like', "%{$term}%")
              ->orWhereHas('beneficiary', function($beneficiaryQuery) use ($term) {
                  $beneficiaryQuery->where('name', 'like', "%{$term}%");
              })
              ->orWhereHas('store', function($storeQuery) use ($term) {
                  $storeQuery->where('name', 'like', "%{$term}%");
              })
              ->orWhereHas('organization', function($orgQuery) use ($term) {
                  $orgQuery->where('name', 'like', "%{$term}%");
              });
        });
    }

    /**
     * دوال التحقق من الحالة
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isCouponRedemption()
    {
        return $this->transaction_type === 'coupon_redemption';
    }

    public function isDonation()
    {
        return $this->transaction_type === 'donation';
    }

    public function isRefund()
    {
        return $this->transaction_type === 'refund';
    }

    public function isAdjustment()
    {
        return $this->transaction_type === 'adjustment';
    }

    /**
     * دوال التحديث
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'redeemed_at' => now()
        ]);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    public function fail()
    {
        $this->update(['status' => 'failed']);
    }

    public function reset()
    {
        $this->update(['status' => 'pending']);
    }

    /**
     * دوال مساعدة
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'completed':
                return 'bg-success';
            case 'pending':
                return 'bg-warning';
            case 'cancelled':
                return 'bg-secondary';
            case 'failed':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    public function getTypeBadgeClass()
    {
        switch ($this->transaction_type) {
            case 'coupon_redemption':
                return 'bg-primary';
            case 'donation':
                return 'bg-success';
            case 'refund':
                return 'bg-warning';
            case 'adjustment':
                return 'bg-info';
            default:
                return 'bg-secondary';
        }
    }

    public function getFormattedAmount()
    {
        return '$' . number_format($this->amount ?? $this->coupon_value, 2);
    }

    public function getFormattedCouponValue()
    {
        return '$' . number_format($this->coupon_value, 2);
    }

    /**
     * دوال حسابية
     */
    public function getTotalValue()
    {
        return $this->amount ?? $this->coupon_value;
    }

    public function getCommissionAmount($rate = 0.05)
    {
        return $this->getTotalValue() * $rate;
    }

    public function getNetAmount($rate = 0.05)
    {
        return $this->getTotalValue() * (1 - $rate);
    }
} 