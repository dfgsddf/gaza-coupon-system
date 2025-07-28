<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization_code',
        'type',
        'email',
        'phone',
        'address',
        'status',
        'registration_date',
        'description',
        'organization_type',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدمين
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }

    /**
     * العلاقة مع المؤسسة الخيرية
     */
    public function charity()
    {
        return $this->hasOne(CharityOrganization::class);
    }

    /**
     * العلاقة مع حملات التبرع
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * العلاقة مع التقارير
     */
    public function reports()
    {
        return $this->hasMany(CharityReport::class, 'charity_id');
    }

    /**
     * المستخدم الرئيسي للمنظمة
     */
    public function primaryUser()
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('is_primary')
                    ->wherePivot('is_primary', true)
                    ->first();
    }

    /**
     * المستخدمين بدور محدد
     */
    public function usersWithRole($role)
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('role')
                    ->wherePivot('role', $role);
    }

    /**
     * مديري المنظمة
     */
    public function admins()
    {
        return $this->usersWithRole('admin');
    }

    /**
     * أعضاء المنظمة
     */
    public function members()
    {
        return $this->usersWithRole('member');
    }

    /**
     * متطوعي المنظمة
     */
    public function volunteers()
    {
        return $this->usersWithRole('volunteer');
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('organization_type', $type);
    }

    public function scopeCharities($query)
    {
        return $query->where('organization_type', 'charity');
    }

    public function scopeGovernment($query)
    {
        return $query->where('organization_type', 'government');
    }

    public function scopeNonProfit($query)
    {
        return $query->where('organization_type', 'non_profit');
    }

    public function scopeByAdmin($query, $userId)
    {
        return $query->whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('role', 'admin');
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

    public function scopeWithCharity($query)
    {
        return $query->with('charity');
    }

    public function scopeWithStats($query)
    {
        return $query->withCount(['campaigns', 'users', 'reports']);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('organization_code', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * دوال مساعدة لإدارة المستخدمين
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

    public function addUser($userId, $role = 'member', $isPrimary = false)
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
     * إحصائيات المنظمة
     */
    public function getTotalCampaigns()
    {
        return $this->campaigns()->count();
    }

    public function getActiveCampaigns()
    {
        return $this->campaigns()->where('status', 'active')->count();
    }

    public function getTotalDonations()
    {
        return $this->campaigns()->sum('current_amount');
    }

    public function getTotalMembers()
    {
        return $this->users()->count();
    }

    public function getTotalReports()
    {
        return $this->reports()->count();
    }

    /**
     * دوال التحقق من الحالة
     */
    public function isActive()
    {
        return $this->is_active;
    }

    public function isCharity()
    {
        return $this->organization_type === 'charity';
    }

    public function isGovernment()
    {
        return $this->organization_type === 'government';
    }

    public function isNonProfit()
    {
        return $this->organization_type === 'non_profit';
    }

    /**
     * التحقق من الأذونات
     */
    public function userCanManage($userId)
    {
        $role = $this->getUserRole($userId);
        return $role === 'admin';
    }

    public function userCanView($userId)
    {
        return $this->hasUser($userId);
    }

    public function userCanCreateCampaigns($userId)
    {
        $role = $this->getUserRole($userId);
        return in_array($role, ['admin', 'member']);
    }

    /**
     * دوال للمؤسسات الخيرية
     */
    public function hasValidLicense()
    {
        if (!$this->isCharity() || !$this->charity) {
            return false;
        }
        
        return $this->charity->license_expiry_date && 
               $this->charity->license_expiry_date->isFuture();
    }

    public function getLicenseStatus()
    {
        if (!$this->isCharity() || !$this->charity) {
            return 'not_applicable';
        }
        
        if (!$this->charity->license_expiry_date) {
            return 'no_license';
        }
        
        if ($this->charity->license_expiry_date->isPast()) {
            return 'expired';
        }
        
        if ($this->charity->license_expiry_date->diffInDays(now()) <= 30) {
            return 'expiring_soon';
        }
        
        return 'valid';
    }
} 