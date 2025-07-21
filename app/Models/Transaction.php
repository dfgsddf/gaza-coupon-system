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
        'coupon_value',
        'beneficiary_name',
        'store_name',
        'status',
        'redeemed_at'
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
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
        return $this->belongsTo(User::class, 'store_id');
    }
} 