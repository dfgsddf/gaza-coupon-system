<?php

namespace App\Http\Controllers\Charity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\BeneficiaryProfile;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BeneficiaryManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('charity');
    }

    /**
     * عرض قائمة المستفيدين
     */
    public function index()
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.view')) {
            abort(403, 'ليس لديك صلاحية لعرض المستفيدين');
        }

        $charityId = Auth::id();
        
        // الحصول على المستفيدين المرتبطين بالجمعية
        $beneficiaries = BeneficiaryProfile::with(['user'])
            ->whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })
            ->latest()
            ->paginate(15);

        return view('charity.beneficiaries.index', compact('beneficiaries'));
    }

    /**
     * عرض نموذج إضافة مستفيد جديد
     */
    public function create()
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.create')) {
            abort(403, 'ليس لديك صلاحية لإضافة مستفيدين جدد');
        }

        return view('charity.beneficiaries.create');
    }

    /**
     * حفظ مستفيد جديد
     */
    public function store(Request $request)
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.create')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإضافة مستفيدين جدد'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'id_number' => 'required|string|max:20|unique:beneficiary_profiles,id_number',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'family_members' => 'required|integer|min:1|max:20',
            'income_level' => 'required|in:no_income,very_low,low,below_average,average',
            'housing_type' => 'required|in:owned,rented,shared,homeless',
            'employment_status' => 'required|in:employed,unemployed,part_time,student,retired',
            'address' => 'required|string|max:500',
            'medical_condition' => 'nullable|string|max:500',
            'special_needs' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'id_number.required' => 'رقم الهوية مطلوب',
            'id_number.unique' => 'رقم الهوية مستخدم بالفعل',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'gender.required' => 'الجنس مطلوب',
            'marital_status.required' => 'الحالة الاجتماعية مطلوبة',
            'family_members.required' => 'عدد أفراد الأسرة مطلوب',
            'family_members.min' => 'عدد أفراد الأسرة يجب أن يكون 1 على الأقل',
            'family_members.max' => 'عدد أفراد الأسرة يجب أن يكون 20 كحد أقصى',
            'income_level.required' => 'مستوى الدخل مطلوب',
            'housing_type.required' => 'نوع السكن مطلوب',
            'employment_status.required' => 'حالة العمل مطلوبة',
            'address.required' => 'العنوان مطلوب',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تصحيح الأخطاء التالية',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $charityId = Auth::id();
            
            // إنشاء حساب المستخدم
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make(Str::random(12)), // كلمة مرور عشوائية
                'role' => 'beneficiary',
                'status' => 'active'
            ]);

            // إنشاء ملف المستفيد
            $beneficiaryProfile = BeneficiaryProfile::create([
                'user_id' => $user->id,
                'id_number' => $request->id_number,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'family_members' => $request->family_members,
                'income_level' => $request->income_level,
                'housing_type' => $request->housing_type,
                'employment_status' => $request->employment_status,
                'medical_condition' => $request->medical_condition,
                'special_needs' => $request->special_needs,
                'notes' => $request->notes,
                'verification_status' => 'verified', // تلقائياً مُتحقق منه
                'verified_by' => $charityId,
                'verified_at' => now()
            ]);

            // ربط المستفيد بالجمعية
            $organization = Organization::find($charityId);
            if ($organization) {
                $user->organizations()->attach($charityId, [
                    'role' => 'beneficiary',
                    'is_primary' => true
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المستفيد بنجاح!',
                'beneficiary' => $beneficiaryProfile->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المستفيد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل المستفيد
     */
    public function show($id)
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.view')) {
            abort(403, 'ليس لديك صلاحية لعرض تفاصيل المستفيدين');
        }

        $charityId = Auth::id();
        
        $beneficiary = BeneficiaryProfile::with(['user', 'requests', 'coupons', 'transactions'])
            ->whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })
            ->findOrFail($id);

        return view('charity.beneficiaries.show', compact('beneficiary'));
    }

    /**
     * عرض نموذج تعديل المستفيد
     */
    public function edit($id)
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.edit')) {
            abort(403, 'ليس لديك صلاحية لتعديل المستفيدين');
        }

        $charityId = Auth::id();
        
        $beneficiary = BeneficiaryProfile::with('user')
            ->whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })
            ->findOrFail($id);

        return view('charity.beneficiaries.edit', compact('beneficiary'));
    }

    /**
     * تحديث بيانات المستفيد
     */
    public function update(Request $request, $id)
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتعديل المستفيدين'
            ], 403);
        }

        $charityId = Auth::id();
        
        $beneficiary = BeneficiaryProfile::with('user')
            ->whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $beneficiary->user_id,
            'phone' => 'required|string|max:20',
            'id_number' => 'required|string|max:20|unique:beneficiary_profiles,id_number,' . $id,
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'family_members' => 'required|integer|min:1|max:20',
            'income_level' => 'required|in:no_income,very_low,low,below_average,average',
            'housing_type' => 'required|in:owned,rented,shared,homeless',
            'employment_status' => 'required|in:employed,unemployed,part_time,student,retired',
            'address' => 'required|string|max:500',
            'medical_condition' => 'nullable|string|max:500',
            'special_needs' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تصحيح الأخطاء التالية',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // تحديث بيانات المستخدم
            $beneficiary->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // تحديث بيانات المستفيد
            $beneficiary->update([
                'id_number' => $request->id_number,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'family_members' => $request->family_members,
                'income_level' => $request->income_level,
                'housing_type' => $request->housing_type,
                'employment_status' => $request->employment_status,
                'medical_condition' => $request->medical_condition,
                'special_needs' => $request->special_needs,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات المستفيد بنجاح!',
                'beneficiary' => $beneficiary->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بيانات المستفيد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف المستفيد
     */
    public function destroy($id)
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لحذف المستفيدين'
            ], 403);
        }

        $charityId = Auth::id();
        
        $beneficiary = BeneficiaryProfile::with('user')
            ->whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // إزالة المستفيد من الجمعية
            $beneficiary->user->organizations()->detach($charityId);

            // حذف ملف المستفيد
            $beneficiary->delete();

            // حذف حساب المستخدم
            $beneficiary->user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستفيد بنجاح!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المستفيد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث في المستفيدين
     */
    public function search(Request $request)
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.view')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض المستفيدين'
            ], 403);
        }

        $charityId = Auth::id();
        $search = $request->get('search', '');
        
        $beneficiaries = BeneficiaryProfile::with(['user'])
            ->whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })
            ->when($search, function($query) use ($search) {
                $query->search($search);
            })
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'beneficiaries' => $beneficiaries
        ]);
    }

    /**
     * إحصائيات المستفيدين
     */
    public function stats()
    {
        // التحقق من الصلاحيات
        if (!Auth::user()->hasPermission('charity.beneficiaries.view')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض إحصائيات المستفيدين'
            ], 403);
        }

        $charityId = Auth::id();
        
        $stats = [
            'total' => BeneficiaryProfile::whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })->count(),
            
            'verified' => BeneficiaryProfile::whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })->where('verification_status', 'verified')->count(),
            
            'pending' => BeneficiaryProfile::whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })->where('verification_status', 'pending')->count(),
            
            'with_special_needs' => BeneficiaryProfile::whereHas('user', function($query) use ($charityId) {
                $query->whereHas('organizations', function($orgQuery) use ($charityId) {
                    $orgQuery->where('organization_id', $charityId);
                });
            })->whereNotNull('special_needs')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
} 