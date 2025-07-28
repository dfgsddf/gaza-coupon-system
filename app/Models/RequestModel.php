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
        'organization_id',
        'type',
        'category',
        'status',
        'description',
        'amount_requested',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
    ];

    /**
     * علاقة الطلب بالمستخدم (المستفيد)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة الطلب بالمنظمة
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'organization']);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('description', 'like', "%{$term}%")
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'like', "%{$term}%")
                           ->orWhere('email', 'like', "%{$term}%");
              })
              ->orWhereHas('organization', function($orgQuery) use ($term) {
                  $orgQuery->where('name', 'like', "%{$term}%");
              });
        });
    }

    /**
     * دوال التحقق من الحالة
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isUrgent()
    {
        return $this->type === 'urgent';
    }

    public function isEmergency()
    {
        return $this->type === 'emergency';
    }

    /**
     * دوال التحديث
     */
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
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
            case 'pending':
                return 'bg-warning';
            case 'approved':
                return 'bg-success';
            case 'rejected':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    public function getTypeBadgeClass()
    {
        switch ($this->type) {
            case 'monthly':
                return 'bg-info';
            case 'urgent':
                return 'bg-warning';
            case 'emergency':
                return 'bg-danger';
            case 'special':
                return 'bg-primary';
            default:
                return 'bg-secondary';
        }
    }

    public function getFormattedAmount()
    {
        return $this->amount_requested ? '$' . number_format($this->amount_requested, 2) : 'غير محدد';
    }
}
