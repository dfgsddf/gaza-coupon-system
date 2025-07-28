<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BeneficiaryProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_number',
        'date_of_birth',
        'gender',
        'marital_status',
        'family_members',
        'income_level',
        'housing_type',
        'medical_condition',
        'special_needs',
        'employment_status',
        'profession',
        'education_level',
        'documents',
        'notes',
        'verification_status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'family_members' => 'integer',
        'documents' => 'array',
        'verified_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع المستخدم الذي تحقق من الملف
     */
    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * العلاقة مع الطلبات
     */
    public function requests()
    {
        return $this->hasMany(RequestModel::class, 'user_id', 'user_id');
    }

    /**
     * العلاقة مع الكوبونات
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'user_id', 'user_id');
    }

    /**
     * العلاقة مع المعاملات
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'beneficiary_id', 'user_id');
    }

    /**
     * نطاقات (Scopes)
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByMaritalStatus($query, $status)
    {
        return $query->where('marital_status', $status);
    }

    public function scopeByIncomeLevel($query, $level)
    {
        return $query->where('income_level', $level);
    }

    public function scopeByEmploymentStatus($query, $status)
    {
        return $query->where('employment_status', $status);
    }

    public function scopeWithSpecialNeeds($query)
    {
        return $query->whereNotNull('special_needs');
    }

    public function scopeWithMedicalCondition($query)
    {
        return $query->whereNotNull('medical_condition');
    }

    public function scopeByAgeRange($query, $minAge, $maxAge)
    {
        $maxDate = Carbon::now()->subYears($minAge);
        $minDate = Carbon::now()->subYears($maxAge + 1);
        
        return $query->whereBetween('date_of_birth', [$minDate, $maxDate]);
    }

    public function scopeByFamilySize($query, $min, $max = null)
    {
        $query = $query->where('family_members', '>=', $min);
        
        if ($max) {
            $query = $query->where('family_members', '<=', $max);
        }
        
        return $query;
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('id_number', 'like', "%{$term}%")
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'like', "%{$term}%")
                           ->orWhere('email', 'like', "%{$term}%");
              });
        });
    }

    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    public function scopeWithStats($query)
    {
        return $query->withCount(['requests', 'coupons', 'transactions']);
    }

    /**
     * دوال التحقق من الحالة
     */
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    public function hasSpecialNeeds()
    {
        return !empty($this->special_needs);
    }

    public function hasMedicalCondition()
    {
        return !empty($this->medical_condition);
    }

    /**
     * دوال حسابية
     */
    public function getAge()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        // استخدام diffInYears مع التأكد من الترتيب الصحيح
        return $this->date_of_birth->diffInYears(now());
    }

    /**
     * حساب العمر بالأشهر
     */
    public function getAgeInMonths()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return $this->date_of_birth->diffInMonths(now());
    }

    /**
     * حساب العمر بالأيام
     */
    public function getAgeInDays()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return $this->date_of_birth->diffInDays(now());
    }

    /**
     * حساب العمر مع التفاصيل
     */
    public function getDetailedAge()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        $now = now();
        $birth = $this->date_of_birth;
        $years = $birth->diffInYears($now);
        
        return [
            'years' => $years,
            'months' => $birth->diffInMonths($now) % 12,
            'days' => $birth->copy()->addYears($years)->diffInDays($now),
            'total_days' => $birth->diffInDays($now),
            'is_adult' => $years >= 18,
            'is_senior' => $years >= 65,
        ];
    }

    public function getAgeGroup()
    {
        $age = $this->getAge();
        
        if (!$age) {
            return 'unknown';
        }
        
        if ($age < 18) {
            return 'minor';
        } elseif ($age < 30) {
            return 'young_adult';
        } elseif ($age < 50) {
            return 'adult';
        } elseif ($age < 65) {
            return 'middle_aged';
        } else {
            return 'senior';
        }
    }

    public function getDependencyRatio()
    {
        if (!$this->family_members || $this->family_members <= 1) {
            return 0;
        }
        
        // نسبة الإعالة = (عدد أفراد الأسرة - 1) / 1
        return $this->family_members - 1;
    }

    public function getPriorityScore()
    {
        $score = 0;
        
        // نقاط بناء على حجم العائلة
        $score += min($this->family_members ?? 0, 10) * 2;
        
        // نقاط للاحتياجات الخاصة
        if ($this->hasSpecialNeeds()) {
            $score += 15;
        }
        
        // نقاط للحالة الطبية
        if ($this->hasMedicalCondition()) {
            $score += 10;
        }
        
        // نقاط لحالة العمل
        if ($this->employment_status === 'unemployed') {
            $score += 20;
        } elseif ($this->employment_status === 'part_time') {
            $score += 10;
        }
        
        // نقاط لمستوى الدخل
        switch ($this->income_level) {
            case 'no_income':
                $score += 25;
                break;
            case 'very_low':
                $score += 20;
                break;
            case 'low':
                $score += 15;
                break;
            case 'below_average':
                $score += 10;
                break;
        }
        
        // نقاط للعمر (كبار السن والأطفال)
        $age = $this->getAge();
        if ($age && ($age > 65 || $age < 18)) {
            $score += 10;
        }
        
        return $score;
    }

    /**
     * إحصائيات المستفيد
     */
    public function getTotalRequests()
    {
        return $this->requests()->count();
    }

    public function getApprovedRequests()
    {
        return $this->requests()->where('status', 'approved')->count();
    }

    public function getTotalCoupons()
    {
        return $this->coupons()->count();
    }

    public function getUsedCoupons()
    {
        return $this->coupons()->where('redeemed', true)->count();
    }

    public function getTotalBenefitValue()
    {
        return $this->transactions()->where('status', 'completed')->sum('amount');
    }

    public function getLastTransactionDate()
    {
        return $this->transactions()
                   ->orderBy('created_at', 'desc')
                   ->first()?->created_at;
    }

    /**
     * دوال التحقق والتحديث
     */
    public function markAsVerified($verifiedBy = null)
    {
        $this->update([
            'verification_status' => 'verified',
            'verified_by' => $verifiedBy,
            'verified_at' => now()
        ]);
    }

    public function markAsRejected($verifiedBy = null)
    {
        $this->update([
            'verification_status' => 'rejected',
            'verified_by' => $verifiedBy,
            'verified_at' => now()
        ]);
    }

    public function resetVerification()
    {
        $this->update([
            'verification_status' => 'pending',
            'verified_by' => null,
            'verified_at' => null
        ]);
    }

    /**
     * دوال للوثائق
     */
    public function addDocument($type, $path)
    {
        $documents = $this->documents ?? [];
        $documents[$type] = $path;
        
        $this->update(['documents' => $documents]);
    }

    public function removeDocument($type)
    {
        $documents = $this->documents ?? [];
        unset($documents[$type]);
        
        $this->update(['documents' => $documents]);
    }

    public function hasDocument($type)
    {
        $documents = $this->documents ?? [];
        return isset($documents[$type]);
    }

    public function getDocument($type)
    {
        $documents = $this->documents ?? [];
        return $documents[$type] ?? null;
    }

    /**
     * التحقق من اكتمال الملف الشخصي
     */
    public function isProfileComplete()
    {
        return !empty($this->id_number) && 
               !empty($this->date_of_birth) && 
               !empty($this->marital_status) && 
               !empty($this->family_members);
    }

    /**
     * التحقق من توفر البيانات الأساسية المطلوبة
     */
    public function hasRequiredData()
    {
        return $this->isProfileComplete() && 
               $this->user && 
               !empty($this->user->name) && 
               !empty($this->user->phone);
    }
}
