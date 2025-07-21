<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    // اسم الجدول (اختياري إذا Laravel قرأه بشكل صحيح)
    protected $table = 'beneficiaries';

    // الحقول المسموح تعبئتها
    protected $fillable = [
        'full_name',
        'id_number',
        'phone',
        'address',
        'family_size',
        'monthly_income',
    ];
}
