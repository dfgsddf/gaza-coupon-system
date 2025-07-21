<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'code',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    // Generate unique coupon code
    public static function generateCode()
    {
        do {
            $code = 'COUPON_' . strtoupper(uniqid()) . '_' . rand(1000, 9999);
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    // Check if coupon is expired
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    // Check if coupon can be redeemed
    public function canBeRedeemed()
    {
        return !$this->redeemed && !$this->isExpired();
    }
}
