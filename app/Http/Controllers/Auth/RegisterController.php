<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|in:beneficiary,store,charity',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'role.required' => 'نوع الحساب مطلوب',
            'role.in' => 'نوع الحساب غير صحيح',
            'phone.required' => 'رقم الهاتف مطلوب',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
                'status' => 'active',
            ]);

            // Auto-login the user after registration
            Auth::login($user);

            // Redirect based on role
            $redirectRoute = $this->getRedirectRouteByRole($request->role);
            
            return redirect()->route($redirectRoute)->with('success', 'تم التسجيل بنجاح! مرحباً بك في نظام قسائم غزة.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.']);
        }
    }

    private function getRedirectRouteByRole($role)
    {
        switch ($role) {
            case 'admin':
                return 'admin.dashboard';
            case 'charity':
                return 'charity.dashboard';
            case 'store':
                return 'store.dashboard';
            case 'beneficiary':
                return 'beneficiary.dashboard';
            default:
                return 'home';
        }
    }
}
