<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        // تطبيق الفلاتر إذا كانت موجودة
        $query = User::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // ترتيب النتائج
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);
        
        // الحصول على المستخدمين مع التقسيم إلى صفحات
        $users = $query->paginate(10);
        
        // إحصائيات المستخدمين
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $adminCount = User::where('role', 'admin')->count();
        $storeCount = User::where('role', 'store')->count();
        $beneficiaryCount = User::where('role', 'beneficiary')->count();
        $charityCount = User::where('role', 'charity')->count();
        
        $stats = [
            'total' => $totalUsers,
            'active' => $activeUsers,
            'admins' => $adminCount,
            'stores' => $storeCount,
            'beneficiaries' => $beneficiaryCount,
            'charities' => $charityCount
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * عرض صفحة إنشاء مستخدم جديد
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,store,beneficiary,charity',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
            'store_description' => 'nullable|string|max:1000',
        ]);
        
        // إنشاء المستخدم
        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'] ?? 'active',
                'store_description' => $validated['role'] == 'store' ? ($validated['store_description'] ?? null) : null,
            ]);
            
            // إضافة إجراءات إضافية حسب نوع المستخدم
            if ($validated['role'] == 'beneficiary') {
                // إنشاء سجل مستفيد
                $user->beneficiary()->create([
                    'user_id' => $user->id,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة تعديل المستخدم
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,store,beneficiary,charity',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
            'store_description' => 'nullable|string|max:1000',
        ]);
        
        // تحديث المستخدم
        try {
            DB::beginTransaction();
            
            // تحديث البيانات الأساسية
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'] ?? 'active',
            ];
            
            // إضافة وصف المتجر إذا كان المستخدم متجر
            if ($validated['role'] == 'store') {
                $userData['store_description'] = $validated['store_description'] ?? null;
            }
            
            // تحديث كلمة المرور إذا تم تغييرها
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $user->update($userData);
            
            // إذا تغير الدور إلى مستفيد وليس لديه سجل مستفيد، قم بإنشاء واحد
            if ($validated['role'] == 'beneficiary' && !$user->beneficiary) {
                $user->beneficiary()->create([
                    'user_id' => $user->id,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * تحديث حالة المستخدم
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        
        $user->status = $request->status;
        $user->save();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة المستخدم بنجاح.',
                'status' => $user->status
            ]);
        }
        
        return back()->with('success', 'تم تحديث حالة المستخدم بنجاح.');
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user)
    {
        try {
            // التحقق من عدم حذف المستخدم الحالي
            if ($user->id == auth()->id()) {
                return back()->with('error', 'لا يمكنك حذف حسابك الحالي.');
            }
            
            // التحقق من عدم وجود سجلات مرتبطة
            $hasCoupons = $user->coupons()->count() > 0;
            $hasTransactions = $user->transactions()->count() > 0 || $user->storeTransactions()->count() > 0;
            $hasRequests = $user->requests()->count() > 0;
            
            if ($hasCoupons || $hasTransactions || $hasRequests) {
                return back()->with('error', 'لا يمكن حذف المستخدم لوجود سجلات مرتبطة به.');
            }
            
            DB::beginTransaction();
            
            // حذف سجل المستفيد إذا كان موجوداً
            if ($user->beneficiary) {
                $user->beneficiary->delete();
            }
            
            // حذف المستخدم
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف المستخدم: ' . $e->getMessage());
        }
    }
}
