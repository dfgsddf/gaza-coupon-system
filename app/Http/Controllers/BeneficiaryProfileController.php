<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeneficiaryProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BeneficiaryProfileController extends Controller
{
    /**
     * عرض فورم إكمال الملف الشخصي
     */
    public function showProfileForm()
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم مستفيد
        if ($user->role !== 'beneficiary') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة.');
        }
        
        // التحقق من عدم وجود ملف شخصي مكتمل بالفعل
        $profile = BeneficiaryProfile::where('user_id', $user->id)->first();
        if ($profile && $profile->isProfileComplete()) {
            return redirect()->route('beneficiary.dashboard')->with('info', 'تم إكمال ملفك الشخصي بالفعل.');
        }
        
        return view('beneficiary.profile-form', compact('profile'));
    }
    
    /**
     * حفظ بيانات الملف الشخصي
     */
    public function storeProfile(Request $request)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم مستفيد
        if ($user->role !== 'beneficiary') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة.');
        }
        
        // تحديد قواعد التحقق
        $rules = [
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today|after:' . now()->subYears(100)->format('Y-m-d'),
            'id_number' => 'required|string|max:20|unique:beneficiary_profiles,id_number,' . ($user->beneficiaryProfile->id ?? 'NULL'),
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'family_members' => 'required|integer|min:1|max:20',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            // الحقول الاختيارية
            'gender' => 'nullable|in:male,female',
            'employment_status' => 'nullable|in:unemployed,part_time,full_time,retired,student',
            'income_level' => 'nullable|in:no_income,very_low,low,below_average,average,above_average',
            'education_level' => 'nullable|in:none,primary,secondary,diploma,bachelor,master,phd',
            'special_needs' => 'nullable|string|max:1000',
        ];
        
        // رسائل التحقق باللغة العربية
        $messages = [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'full_name.max' => 'الاسم الكامل يجب أن يكون أقل من 255 حرف',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
            'date_of_birth.date' => 'تاريخ الميلاد غير صحيح',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون قبل اليوم',
            'date_of_birth.after' => 'العمر يجب أن يكون أقل من 100 سنة',
            'id_number.required' => 'رقم الهوية/الإقامة مطلوب',
            'id_number.unique' => 'رقم الهوية/الإقامة مستخدم بالفعل',
            'marital_status.required' => 'الحالة الاجتماعية مطلوبة',
            'marital_status.in' => 'الحالة الاجتماعية غير صحيحة',
            'family_members.required' => 'عدد أفراد الأسرة مطلوب',
            'family_members.integer' => 'عدد أفراد الأسرة يجب أن يكون رقم صحيح',
            'family_members.min' => 'عدد أفراد الأسرة يجب أن يكون على الأقل 1',
            'family_members.max' => 'عدد أفراد الأسرة يجب أن يكون أقل من 20',
            'address.required' => 'العنوان مطلوب',
            'address.max' => 'العنوان يجب أن يكون أقل من 500 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.max' => 'رقم الهاتف يجب أن يكون أقل من 20 حرف',
        ];
        
        // تحقق إضافي للحالة الاجتماعية وعدد أفراد الأسرة
        $request->validate($rules, $messages);
        
        // تحقق منطقي: إذا كان أعزب، عدد أفراد الأسرة يجب أن يكون 1 على الأقل
        if ($request->marital_status === 'single' && $request->family_members < 1) {
            return back()->withErrors(['family_members' => 'عدد أفراد الأسرة للأعزب يجب أن يكون 1 على الأقل'])->withInput();
        }
        
        // تحقق منطقي: إذا كان متزوج، عدد أفراد الأسرة يجب أن يكون 2 على الأقل
        if (in_array($request->marital_status, ['married', 'divorced', 'widowed']) && $request->family_members < 2) {
            return back()->withErrors(['family_members' => 'عدد أفراد الأسرة للمتزوج يجب أن يكون 2 على الأقل'])->withInput();
        }
        
        // التحقق من العمر المنطقي (16-100 سنة)
        try {
            // محاولة تحويل التاريخ بتنسيق Y-m-d أولاً
            $birthDate = Carbon::createFromFormat('Y-m-d', $request->date_of_birth);
            if (!$birthDate) {
                // إذا فشل، استخدم الطريقة العامة
                $birthDate = Carbon::parse($request->date_of_birth);
            }
            
            // حساب العمر
            $age = $birthDate->diffInYears(now());
            
            // تسجيل معلومات للتشخيص (يمكن حذفها لاحقاً)
            \Log::info("Age calculation: Birth date = {$birthDate->format('Y-m-d')}, Current date = " . now()->format('Y-m-d') . ", Age = {$age}");
            
            if ($age < 16) {
                return back()->withErrors([
                    'date_of_birth' => "العمر المحسوب هو {$age} سنة. يجب أن يكون العمر 16 سنة على الأقل للتسجيل في النظام."
                ])->withInput();
            }
            
            if ($age > 100) {
                return back()->withErrors([
                    'date_of_birth' => "العمر المحسوب هو {$age} سنة. يرجى التحقق من تاريخ الميلاد المدخل."
                ])->withInput();
            }
            
            // التحقق من أن التاريخ ليس في المستقبل
            if ($birthDate->isFuture()) {
                return back()->withErrors([
                    'date_of_birth' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.'
                ])->withInput();
            }
            
        } catch (\Exception $e) {
            // تسجيل الخطأ للتشخيص
            \Log::error("Date parsing error: " . $e->getMessage() . " for input: " . $request->date_of_birth);
            
            return back()->withErrors([
                'date_of_birth' => 'تنسيق تاريخ الميلاد غير صحيح. يرجى اختيار تاريخ صحيح.'
            ])->withInput();
        }
        
        try {
            DB::transaction(function () use ($request, $user) {
                // تحديث بيانات المستخدم الأساسية إذا لزم الأمر
                $user->update([
                    'name' => $request->full_name,
                    'phone' => $request->phone,
                ]);
                
                // إنشاء أو تحديث الملف الشخصي
                $profileData = [
                    'user_id' => $user->id,
                    'id_number' => $request->id_number,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'marital_status' => $request->marital_status,
                    'family_members' => $request->family_members,
                    'income_level' => $request->income_level,
                    'employment_status' => $request->employment_status,
                    'profession' => null, // يمكن إضافتها لاحقاً
                    'education_level' => $request->education_level,
                    'special_needs' => $request->special_needs,
                    'verification_status' => 'pending',
                ];
                
                // حفظ العنوان في جدول المستخدمين
                $user->update(['address' => $request->address]);
                
                BeneficiaryProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            });
            
            return redirect()->route('beneficiary.dashboard')->with('success', 'تم حفظ بياناتك بنجاح! يمكنك الآن الوصول لجميع خدمات النظام.');
            
        } catch (\Exception $e) {
            \Log::error('خطأ في حفظ الملف الشخصي للمستفيد: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.'])->withInput();
        }
    }
    
    /**
     * التحقق من اكتمال الملف الشخصي
     */
    public function checkProfileCompleteness()
    {
        $user = Auth::user();
        $profile = BeneficiaryProfile::where('user_id', $user->id)->first();
        
        $isComplete = $profile && $profile->isProfileComplete();
        
        return response()->json([
            'is_complete' => $isComplete,
            'profile_exists' => !!$profile,
        ]);
    }
    
    /**
     * عرض الملف الشخصي للمراجعة
     */
    public function showProfile()
    {
        $user = Auth::user();
        $profile = BeneficiaryProfile::where('user_id', $user->id)->first();
        
        if (!$profile) {
            return redirect()->route('beneficiary.profile.form')->with('info', 'يرجى إكمال ملفك الشخصي أولاً.');
        }
        
        return view('beneficiary.profile', compact('profile', 'user'));
    }
    
    /**
     * تعديل الملف الشخصي
     */
    public function editProfile()
    {
        $user = Auth::user();
        $profile = BeneficiaryProfile::where('user_id', $user->id)->first();
        
        if (!$profile) {
            return redirect()->route('beneficiary.profile.form')->with('info', 'يرجى إكمال ملفك الشخصي أولاً.');
        }
        
        return view('beneficiary.profile-edit', compact('profile', 'user'));
    }
    
    /**
     * تحديث الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = BeneficiaryProfile::where('user_id', $user->id)->first();
        
        if (!$profile) {
            return redirect()->route('beneficiary.profile.form')->with('error', 'لم يتم العثور على ملفك الشخصي.');
        }
        
        // نفس قواعد التحقق مع تعديل unique rule
        $rules = [
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today|after:' . now()->subYears(100)->format('Y-m-d'),
            'id_number' => 'required|string|max:20|unique:beneficiary_profiles,id_number,' . $profile->id,
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'family_members' => 'required|integer|min:1|max:20',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'gender' => 'nullable|in:male,female',
            'employment_status' => 'nullable|in:unemployed,part_time,full_time,retired,student',
            'income_level' => 'nullable|in:no_income,very_low,low,below_average,average,above_average',
            'education_level' => 'nullable|in:none,primary,secondary,diploma,bachelor,master,phd',
            'special_needs' => 'nullable|string|max:1000',
        ];
        
        $request->validate($rules);
        
        try {
            DB::transaction(function () use ($request, $user, $profile) {
                // تحديث بيانات المستخدم
                $user->update([
                    'name' => $request->full_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);
                
                // تحديث الملف الشخصي
                $profile->update([
                    'id_number' => $request->id_number,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'marital_status' => $request->marital_status,
                    'family_members' => $request->family_members,
                    'income_level' => $request->income_level,
                    'employment_status' => $request->employment_status,
                    'education_level' => $request->education_level,
                    'special_needs' => $request->special_needs,
                    // إعادة تعيين حالة التحقق إلى pending في حالة التعديل
                    'verification_status' => 'pending',
                ]);
            });
            
            return redirect()->route('beneficiary.profile.show')->with('success', 'تم تحديث ملفك الشخصي بنجاح!');
            
        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث الملف الشخصي للمستفيد: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث البيانات. يرجى المحاولة مرة أخرى.'])->withInput();
        }
    }
} 