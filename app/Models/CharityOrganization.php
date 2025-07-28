<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CharityOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'registration_number',
        'license_number',
        'license_expiry_date',
        'mission_statement',
        'vision_statement',
        'services',
        'contact_person',
        'website',
        'bank_account',
        'bank_name',
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
    ];

    /**
     * العلاقة مع المنظمة
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * الوصول المباشر للمستخدمين من خلال المنظمة
     */
    public function users()
    {
        return $this->organization->users();
    }

    /**
     * العلاقة مع حملات التبرع
     */
    public function campaigns()
    {
        return $this->organization->campaigns();
    }

    /**
     * العلاقة مع التقارير
     */
    public function reports()
    {
        return $this->hasMany(CharityReport::class, 'charity_id');
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopeWithValidLicense($query)
    {
        return $query->where('license_expiry_date', '>', now());
    }

    public function scopeWithExpiredLicense($query)
    {
        return $query->where('license_expiry_date', '<=', now());
    }

    public function scopeWithExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('license_expiry_date', [
            now(),
            now()->addDays($days)
        ]);
    }

    public function scopeWithoutLicense($query)
    {
        return $query->whereNull('license_expiry_date');
    }

    public function scopeByContactPerson($query, $person)
    {
        return $query->where('contact_person', 'like', "%{$person}%");
    }

    public function scopeByService($query, $service)
    {
        return $query->where('services', 'like', "%{$service}%");
    }

    public function scopeWithOrganization($query)
    {
        return $query->with('organization');
    }

    public function scopeWithStats($query)
    {
        return $query->withCount(['campaigns', 'reports']);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('registration_number', 'like', "%{$term}%")
              ->orWhere('license_number', 'like', "%{$term}%")
              ->orWhere('contact_person', 'like', "%{$term}%")
              ->orWhere('mission_statement', 'like', "%{$term}%")
              ->orWhere('services', 'like', "%{$term}%")
              ->orWhereHas('organization', function($orgQuery) use ($term) {
                  $orgQuery->where('name', 'like', "%{$term}%");
              });
        });
    }

    /**
     * دوال التحقق من حالة الترخيص
     */
    public function hasValidLicense()
    {
        return $this->license_expiry_date && 
               $this->license_expiry_date->isFuture();
    }

    public function isLicenseExpired()
    {
        return $this->license_expiry_date && 
               $this->license_expiry_date->isPast();
    }

    public function isLicenseExpiringSoon($days = 30)
    {
        if (!$this->license_expiry_date) {
            return false;
        }
        
        return $this->license_expiry_date->isFuture() && 
               $this->license_expiry_date->diffInDays(now()) <= $days;
    }

    public function hasNoLicense()
    {
        return empty($this->license_expiry_date);
    }

    public function getLicenseStatus()
    {
        if ($this->hasNoLicense()) {
            return 'no_license';
        }
        
        if ($this->isLicenseExpired()) {
            return 'expired';
        }
        
        if ($this->isLicenseExpiringSoon()) {
            return 'expiring_soon';
        }
        
        return 'valid';
    }

    public function getLicenseStatusBadge()
    {
        $status = $this->getLicenseStatus();
        
        return match($status) {
            'valid' => '<span class="badge bg-success">ساري المفعول</span>',
            'expiring_soon' => '<span class="badge bg-warning">ينتهي قريباً</span>',
            'expired' => '<span class="badge bg-danger">منتهي الصلاحية</span>',
            'no_license' => '<span class="badge bg-secondary">بدون ترخيص</span>',
            default => '<span class="badge bg-secondary">غير محدد</span>'
        };
    }

    public function getDaysUntilExpiry()
    {
        if (!$this->license_expiry_date) {
            return null;
        }
        
        return $this->license_expiry_date->diffInDays(now(), false);
    }

    /**
     * دوال الخدمات
     */
    public function getServicesArray()
    {
        if (empty($this->services)) {
            return [];
        }
        
        return explode(',', $this->services);
    }

    public function addService($service)
    {
        $services = $this->getServicesArray();
        
        if (!in_array($service, $services)) {
            $services[] = $service;
            $this->update(['services' => implode(',', $services)]);
        }
    }

    public function removeService($service)
    {
        $services = $this->getServicesArray();
        $services = array_filter($services, fn($s) => $s !== $service);
        
        $this->update(['services' => implode(',', $services)]);
    }

    public function hasService($service)
    {
        return in_array($service, $this->getServicesArray());
    }

    /**
     * إحصائيات المؤسسة الخيرية
     */
    public function getTotalCampaigns()
    {
        return $this->campaigns()->count();
    }

    public function getActiveCampaigns()
    {
        return $this->campaigns()->where('status', 'active')->count();
    }

    public function getCompletedCampaigns()
    {
        return $this->campaigns()->where('status', 'completed')->count();
    }

    public function getTotalDonations()
    {
        return $this->campaigns()->sum('current_amount');
    }

    public function getTargetAmount()
    {
        return $this->campaigns()->sum('target_amount');
    }

    public function getDonationProgress()
    {
        $target = $this->getTargetAmount();
        $current = $this->getTotalDonations();
        
        return $target > 0 ? ($current / $target) * 100 : 0;
    }

    public function getTotalReports()
    {
        return $this->reports()->count();
    }

    public function getRecentReports($limit = 5)
    {
        return $this->reports()
                   ->orderBy('report_date', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public function getMonthlyReports($year = null)
    {
        $year = $year ?? now()->year;
        
        return $this->reports()
                   ->whereYear('report_date', $year)
                   ->orderBy('report_date', 'desc')
                   ->get()
                   ->groupBy(function($report) {
                       return $report->report_date->format('m');
                   });
    }

    /**
     * دوال التحقق من المعلومات
     */
    public function isProfileComplete()
    {
        $requiredFields = [
            'registration_number',
            'license_number',
            'license_expiry_date',
            'mission_statement',
            'contact_person',
            'bank_account',
            'bank_name'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        
        return true;
    }

    public function getMissingFields()
    {
        $requiredFields = [
            'registration_number' => 'رقم التسجيل',
            'license_number' => 'رقم الترخيص',
            'license_expiry_date' => 'تاريخ انتهاء الترخيص',
            'mission_statement' => 'رسالة المؤسسة',
            'contact_person' => 'الشخص المسؤول',
            'bank_account' => 'رقم الحساب البنكي',
            'bank_name' => 'اسم البنك'
        ];
        
        $missing = [];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($this->$field)) {
                $missing[] = $label;
            }
        }
        
        return $missing;
    }

    public function getCompletionPercentage()
    {
        $totalFields = 7; // عدد الحقول المطلوبة
        $missingFields = count($this->getMissingFields());
        
        return ((7 - $missingFields) / $totalFields) * 100;
    }

    /**
     * دوال للتحديث والإدارة
     */
    public function renewLicense($newExpiryDate)
    {
        $this->update(['license_expiry_date' => $newExpiryDate]);
    }

    public function updateContactPerson($person)
    {
        $this->update(['contact_person' => $person]);
    }

    public function updateBankDetails($accountNumber, $bankName)
    {
        $this->update([
            'bank_account' => $accountNumber,
            'bank_name' => $bankName
        ]);
    }

    public function updateMissionAndVision($mission, $vision = null)
    {
        $this->update([
            'mission_statement' => $mission,
            'vision_statement' => $vision
        ]);
    }

    /**
     * دوال للتقارير والتحليلات
     */
    public function generateMonthlyReport($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $campaigns = $this->campaigns()
                         ->whereBetween('created_at', [$startDate, $endDate])
                         ->get();
        
        $donations = $campaigns->sum('current_amount');
        $newCampaigns = $campaigns->count();
        
        return [
            'period' => $startDate->format('Y-m'),
            'new_campaigns' => $newCampaigns,
            'total_donations' => $donations,
            'average_donation' => $newCampaigns > 0 ? $donations / $newCampaigns : 0,
            'campaigns' => $campaigns
        ];
    }
}
