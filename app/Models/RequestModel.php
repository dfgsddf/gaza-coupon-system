<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    // اسم الجدول إذا كان مختلفاً (اختياري، Laravel يتعرف عليه تلقائياً إن كان مطابقاً)
    protected $table = 'requests';

    // الحقول القابلة للتعبئة الجماعية
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'description',
    ];

    /**
     * علاقة الطلب بالمستخدم (المستفيد)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
