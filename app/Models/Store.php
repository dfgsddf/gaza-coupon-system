<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'store_code',
        'email',
        'phone',
        'address',
        'status',
        'has_physical_location',
        'accepts_online_orders',
        'tax_number',
        'commercial_register',
        'description',
        'store_type',
        'location_lat',
        'location_lng',
        'logo',
    ];

    protected $casts = [
        'has_physical_location' => 'boolean',
        'accepts_online_orders' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدمين
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }

    /**
     * العلاقة مع المعاملات
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_id');
    }

    /**
     * العلاقة مع الكوبونات
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'store_name', 'name');
    }

    /**
     * المستخدم الرئيسي للمتجر - كعلاقة
     */
    public function primaryUserRelation()
    {
        return $this->belongsToMany(User::class, 'store_user')
                   ->withPivot('is_primary')
                   ->wherePivot('is_primary', true);
    }

    /**
     * الحصول على المستخدم الرئيسي
     */
    public function primaryUser()
    {
        return $this->primaryUserRelation()->first();
    }

    /**
     * المستخدمين بدور محدد
     */
    public function usersWithRole($role)
    {
        return $this->belongsToMany(User::class, 'store_user')
                   ->withPivot('role')
                   ->wherePivot('role', $role);
    }

    /**
     * أصحاب المتجر
     */
    public function owners()
    {
        return $this->usersWithRole('owner');
    }

    /**
     * مديري المتجر
     */
    public function managers()
    {
        return $this->usersWithRole('manager');
    }

    /**
     * موظفي المتجر
     */
    public function employees()
    {
        return $this->usersWithRole('employee');
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('store_type', $type);
    }

    public function scopeWithPhysicalLocation($query)
    {
        return $query->where('has_physical_location', true);
    }

    public function scopeWithOnlineOrders($query)
    {
        return $query->where('accepts_online_orders', true);
    }

    public function scopeByOwner($query, $userId)
    {
        return $query->whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('role', 'owner');
        });
    }

    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function scopeWithUsers($query)
    {
        return $query->with('users');
    }

    public function scopeWithStats($query)
    {
        return $query->withCount(['transactions', 'coupons']);
    }

    /**
     * دوال مساعدة
     */
    public function hasUser($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }

    public function getUserRole($userId)
    {
        $user = $this->users()->where('user_id', $userId)->first();
        return $user ? $user->pivot->role : null;
    }

    public function addUser($userId, $role = 'employee', $isPrimary = false)
    {
        return $this->users()->attach($userId, [
            'role' => $role,
            'is_primary' => $isPrimary,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function removeUser($userId)
    {
        return $this->users()->detach($userId);
    }

    public function updateUserRole($userId, $role)
    {
        return $this->users()->updateExistingPivot($userId, [
            'role' => $role,
            'updated_at' => now()
        ]);
    }

    public function setPrimaryUser($userId)
    {
        // إزالة الأولوية من جميع المستخدمين
        $this->users()->updateExistingPivot($this->users()->pluck('user_id'), [
            'is_primary' => false,
            'updated_at' => now()
        ]);
        
        // تعيين المستخدم كرئيسي
        return $this->users()->updateExistingPivot($userId, [
            'is_primary' => true,
            'updated_at' => now()
        ]);
    }

    /**
     * إحصائيات المتجر
     */
    public function getTotalTransactions()
    {
        return $this->transactions()->count();
    }

    public function getTotalRevenue()
    {
        return $this->transactions()->where('status', 'completed')->sum('amount');
    }

    public function getActiveCoupons()
    {
        return $this->coupons()->where('redeemed', false)
                               ->where('expiry_date', '>', now())
                               ->count();
    }

    public function getRedeemedCoupons()
    {
        return $this->coupons()->where('redeemed', true)->count();
    }

    /**
     * دوال التحقق من الحالة
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasPhysicalLocation()
    {
        return $this->has_physical_location;
    }

    public function acceptsOnlineOrders()
    {
        return $this->accepts_online_orders;
    }

    /**
     * التحقق من الأذونات
     */
    public function userCanManage($userId)
    {
        $role = $this->getUserRole($userId);
        return in_array($role, ['owner', 'manager']);
    }

    public function userCanView($userId)
    {
        return $this->hasUser($userId);
    }

    /**
     * دالة البحث
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('store_code', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
