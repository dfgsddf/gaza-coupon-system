<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'organization_id',
        'store_id',
        'charity_id',
        'code',
        'coupon_type',
        'value', 
        'description', 
        'store_name', 
        'expiry_date',
        'redeemed',
        'redeemed_at'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'redeemed_at' => 'datetime',
        'redeemed' => 'boolean',
        'value' => 'decimal:2',
    ];

    /**
     * علاقة الكوبون بالمستخدم (المستفيد)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة الكوبون بالمنظمة
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * علاقة الكوبون بالمتجر
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * علاقة الكوبون بالمعاملات
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopeActive($query)
    {
        return $query->where('redeemed', false)
                     ->where(function($q) {
                         $q->whereNull('expiry_date')
                           ->orWhere('expiry_date', '>', now());
                     });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '<=', now());
    }

    public function scopeRedeemed($query)
    {
        return $query->where('redeemed', true);
    }

    public function scopeNotRedeemed($query)
    {
        return $query->where('redeemed', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('coupon_type', $type);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'organization', 'store']);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('code', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'like', "%{$term}%");
              })
              ->orWhereHas('organization', function($orgQuery) use ($term) {
                  $orgQuery->where('name', 'like', "%{$term}%");
              })
              ->orWhereHas('store', function($storeQuery) use ($term) {
                  $storeQuery->where('name', 'like', "%{$term}%");
              });
        });
    }

    /**
     * Generate unique coupon code
     */
    public static function generateCode()
    {
        do {
            $code = 'COUPON_' . strtoupper(uniqid()) . '_' . rand(1000, 9999);
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    /**
     * Check if coupon is expired
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if coupon can be redeemed
     */
    public function canBeRedeemed()
    {
        return !$this->redeemed && !$this->isExpired();
    }

    /**
     * دوال التحقق من الحالة
     */
    public function isActive()
    {
        return !$this->redeemed && !$this->isExpired();
    }

    public function isRedeemed()
    {
        return $this->redeemed;
    }

    public function isStandard()
    {
        return $this->coupon_type === 'standard';
    }

    public function isSpecial()
    {
        return $this->coupon_type === 'special';
    }

    public function isEmergency()
    {
        return $this->coupon_type === 'emergency';
    }

    public function isGift()
    {
        return $this->coupon_type === 'gift';
    }

    /**
     * دوال التحديث
     */
    public function redeem()
    {
        $this->update([
            'redeemed' => true,
            'redeemed_at' => now()
        ]);
    }

    public function unRedeem()
    {
        $this->update([
            'redeemed' => false,
            'redeemed_at' => null
        ]);
    }

    /**
     * دوال مساعدة
     */
    public function getStatusBadgeClass()
    {
        if ($this->redeemed) {
            return 'bg-success';
        } elseif ($this->isExpired()) {
            return 'bg-danger';
        } else {
            return 'bg-primary';
        }
    }

    public function getTypeBadgeClass()
    {
        switch ($this->coupon_type) {
            case 'standard':
                return 'bg-info';
            case 'special':
                return 'bg-primary';
            case 'emergency':
                return 'bg-danger';
            case 'gift':
                return 'bg-warning';
            default:
                return 'bg-secondary';
        }
    }

    public function getFormattedValue()
    {
        return '$' . number_format($this->value, 2);
    }

    public function getDaysUntilExpiry()
    {
        if (!$this->expiry_date) {
            return null;
        }
        
        return now()->diffInDays($this->expiry_date, false);
    }

    public function getExpiryStatus()
    {
        if (!$this->expiry_date) {
            return 'no_expiry';
        }
        
        $daysUntilExpiry = $this->getDaysUntilExpiry();
        
        if ($daysUntilExpiry < 0) {
            return 'expired';
        } elseif ($daysUntilExpiry <= 7) {
            return 'expiring_soon';
        } else {
            return 'valid';
        }
    }
}
