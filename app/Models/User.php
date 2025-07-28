<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RequestModel;
use App\Models\Coupon;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'address',
        'store_description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //  علاقات
    public function requests()
    {
        return $this->hasMany(RequestModel::class);
    }

    public function coupons()
    {
        return $this->hasMany(\App\Models\Coupon::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'beneficiary_id');
    }

    public function storeTransactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'store_id');
    }

    // إضافة العلاقات الجديدة بين المستخدم والمتاجر والمنظمات
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }

    public function beneficiaryProfile()
    {
        return $this->hasOne(BeneficiaryProfile::class);
    }

    // استبدال الدالة الحالية
    public function beneficiary()
    {
        return $this->hasOne(\App\Models\BeneficiaryProfile::class);
    }

    // دوال مساعدة جديدة للعلاقات
    public function primaryStore()
    {
        return $this->stores()->wherePivot('is_primary', true)->first();
    }

    public function primaryOrganization()
    {
        return $this->organizations()->wherePivot('is_primary', true)->first();
    }

    public function isStoreOwner()
    {
        return $this->stores()->wherePivot('role', 'owner')->exists();
    }

    public function isOrganizationAdmin()
    {
        return $this->organizations()->wherePivot('role', 'admin')->exists();
    }

    public function getStoresByRole($role)
    {
        return $this->stores()->wherePivot('role', $role)->get();
    }

    public function getOrganizationsByRole($role)
    {
        return $this->organizations()->wherePivot('role', $role)->get();
    }

    // نطاقات (Scopes) جديدة
    public function scopeWithStores($query)
    {
        return $query->with('stores');
    }

    public function scopeWithOrganizations($query)
    {
        return $query->with('organizations');
    }

    public function scopeStoreOwners($query)
    {
        return $query->whereHas('stores', function($q) {
            $q->wherePivot('role', 'owner');
        });
    }

    public function scopeOrganizationAdmins($query)
    {
        return $query->whereHas('organizations', function($q) {
            $q->wherePivot('role', 'admin');
        });
    }

    public function scopeHasStores($query)
    {
        return $query->whereHas('stores');
    }

    public function scopeHasOrganizations($query)
    {
        return $query->whereHas('organizations');
    }

    public function scopeVerifiedBeneficiaries($query)
    {
        return $query->where('role', 'beneficiary')
                     ->whereHas('beneficiaryProfile', function($q) {
                         $q->where('verification_status', 'verified');
                     });
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // دوال مساعدة لإدارة الحالات
    public function isVerifiedBeneficiary()
    {
        return $this->role === 'beneficiary' && 
               $this->beneficiaryProfile && 
               $this->beneficiaryProfile->verification_status === 'verified';
    }

    public function canAccessStore($storeId)
    {
        return $this->stores()->where('stores.id', $storeId)->exists();
    }

    public function canAccessOrganization($organizationId)
    {
        return $this->organizations()->where('organizations.id', $organizationId)->exists();
    }

    public function getRoleInStore($storeId)
    {
        $store = $this->stores()->where('stores.id', $storeId)->first();
        return $store ? $store->pivot->role : null;
    }

    public function getRoleInOrganization($organizationId)
    {
        $organization = $this->organizations()->where('organizations.id', $organizationId)->first();
        return $organization ? $organization->pivot->role : null;
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permissionName)
    {
        // المشرفون لديهم جميع الصلاحيات افتراضياً
        if ($this->isAdmin()) {
            return true;
        }

        return RolePermission::forRole($this->role)
            ->granted()
            ->whereHas('permission', function($query) use ($permissionName) {
                $query->where('name', $permissionName)->active();
            })
            ->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        return RolePermission::forRole($this->role)
            ->granted()
            ->whereHas('permission', function($query) use ($permissions) {
                $query->whereIn('name', $permissions)->active();
            })
            ->exists();
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $userPermissions = RolePermission::forRole($this->role)
            ->granted()
            ->whereHas('permission', function($query) use ($permissions) {
                $query->whereIn('name', $permissions)->active();
            })
            ->count();

        return $userPermissions === count($permissions);
    }

    /**
     * Get all permissions for the user's role
     */
    public function getPermissions()
    {
        return Permission::whereHas('rolePermissions', function($query) {
            $query->forRole($this->role)->granted();
        })->active()->get();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is charity
     */
    public function isCharity()
    {
        return $this->role === 'charity';
    }

    /**
     * Check if user is store
     */
    public function isStore()
    {
        return $this->role === 'store';
    }

    /**
     * Check if user is beneficiary
     */
    public function isBeneficiary()
    {
        return $this->role === 'beneficiary';
    }
}
