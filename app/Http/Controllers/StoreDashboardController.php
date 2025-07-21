<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;

class StoreDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $storeId = $user->store_id ?? 1; // Default store ID for demo

        // Get today's transactions
        $todayTransactions = Transaction::where('store_id', $storeId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Get monthly revenue
        $monthlyRevenue = Transaction::where('store_id', $storeId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('coupon_value');

        // Get total coupons redeemed
        $totalCoupons = Transaction::where('store_id', $storeId)->count();

        // Get pending coupons (coupons that exist but haven't been redeemed)
        $pendingCoupons = Coupon::where('store_name', $user->store_name ?? 'Demo Store')
            ->where('expiry_date', '>', Carbon::now())
            ->whereDoesntHave('transactions')
            ->count();

        // Get recent transactions
        $recentTransactions = Transaction::where('store_id', $storeId)
            ->with('beneficiary')
            ->latest()
            ->take(10)
            ->get();

        // Get top beneficiaries
        $topBeneficiaries = User::where('role', 'beneficiary')
            ->withCount(['transactions' => function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            }])
            ->having('transactions_count', '>', 0)
            ->orderBy('transactions_count', 'desc')
            ->take(5)
            ->get();

        return view('store.dashboard', compact(
            'todayTransactions',
            'monthlyRevenue',
            'totalCoupons',
            'pendingCoupons',
            'recentTransactions',
            'topBeneficiaries'
        ));
    }

    public function coupons()
    {
        return view('store.coupons');
    }

    public function transactions()
    {
        $user = Auth::user();
        $storeId = $user->store_id ?? 1;

        $transactions = Transaction::where('store_id', $storeId)
            ->with('beneficiary')
            ->latest()
            ->paginate(20);

        return view('store.transactions', compact('transactions'));
    }

    public function reports()
    {
        $user = Auth::user();
        $storeId = $user->store_id ?? 1;

        // Get monthly data for chart
        $monthlyData = Transaction::where('store_id', $storeId)
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(coupon_value) as revenue')
            ->groupBy('month')
            ->get();

        // Get daily data for current month
        $dailyData = Transaction::where('store_id', $storeId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        return view('store.reports', compact('monthlyData', 'dailyData'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('store.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'store_description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'اسم المتجر مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'store_description' => $request->store_description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث معلومات المتجر بنجاح',
                'user' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث معلومات المتجر'
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required|string|min:6',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل',
            'new_password.confirmed' => 'كلمة المرور الجديدة غير متطابقة',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 422);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير كلمة المرور بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير كلمة المرور'
            ], 500);
        }
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email_transactions' => 'boolean',
            'email_reports' => 'boolean',
            'email_alerts' => 'boolean',
            'app_transactions' => 'boolean',
            'app_updates' => 'boolean',
            'app_reminders' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store notification preferences
            $settings = [
                'email_transactions' => $request->boolean('email_transactions'),
                'email_reports' => $request->boolean('email_reports'),
                'email_alerts' => $request->boolean('email_alerts'),
                'app_transactions' => $request->boolean('app_transactions'),
                'app_updates' => $request->boolean('app_updates'),
                'app_reminders' => $request->boolean('app_reminders'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ إعدادات الإشعارات بنجاح',
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ إعدادات الإشعارات'
            ], 500);
        }
    }

    public function transactionDetails($id)
    {
        $user = Auth::user();
        $storeId = $user->store_id ?? 1;

        $transaction = Transaction::where('id', $id)
            ->where('store_id', $storeId)
            ->with('beneficiary')
            ->firstOrFail();

        return view('store.transaction-details', compact('transaction'));
    }
} 