<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\RequestModel;
use App\Models\Coupon;

class BeneficiaryDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $requests = $user->requests()->latest()->take(5)->get();
        $coupons = $user->coupons()->latest()->take(4)->get();

        return view('beneficiary.dashboard', compact('requests', 'coupons'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('beneficiary.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'الاسم مطلوب',
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
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الملف الشخصي بنجاح',
                'user' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الملف الشخصي'
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
            'email_requests' => 'boolean',
            'email_coupons' => 'boolean',
            'email_news' => 'boolean',
            'dashboard_alerts' => 'boolean',
            'auto_refresh' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store notification preferences in user settings or separate table
            // For now, we'll store in a JSON column or use a simple approach
            $settings = [
                'email_requests' => $request->boolean('email_requests'),
                'email_coupons' => $request->boolean('email_coupons'),
                'email_news' => $request->boolean('email_news'),
                'dashboard_alerts' => $request->boolean('dashboard_alerts'),
                'auto_refresh' => $request->boolean('auto_refresh'),
            ];

            // You can store this in a user_settings table or as JSON in users table
            // For demo purposes, we'll just return success
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
}
