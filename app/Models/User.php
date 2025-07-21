<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RequestModel;
use App\Models\Coupon;


class User extends Authenticatable
{
    use Notifiable;

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

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permissionName)
    {
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
