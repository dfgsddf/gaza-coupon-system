<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BeneficiaryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى تسجيل الدخول للوصول إلى هذه الصفحة'
                ], 401);
            }
            return redirect()->route('login.form')->with('error', 'يرجى تسجيل الدخول للوصول إلى هذه الصفحة');
        }

        $user = Auth::user();
        
        // التحقق من دور المستخدم
        if ($user->role !== 'beneficiary') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالدخول إلى هذه الصفحة'
                ], 403);
            }
            
            // التوجيه بناءً على دور المستخدم
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة تحكم المستفيدين');
                case 'store':
                    return redirect()->route('store.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة تحكم المستفيدين');
                case 'charity':
                    return redirect()->route('charity.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة تحكم المستفيدين');
                default:
                    return redirect()->route('home')->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة');
            }
        }
        
        // للمستفيدين: التأكد من أنهم يصلون فقط للمسارات المسموحة
        if ($user->role === 'beneficiary') {
            $allowedRoutes = [
                'beneficiary.dashboard',
                'beneficiary.coupons.index',
                'beneficiary.coupons.show', 
                'beneficiary.coupons.print',
                'beneficiary.profile.form',
                'beneficiary.profile.store',
                'beneficiary.profile.check',
                'logout'
            ];
            
            $currentRoute = $request->route()->getName();
            
            // إذا كان المسار غير مسموح، توجيه للكوبونات
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('beneficiary.coupons.index')
                    ->with('warning', 'تم توجيهك لصفحة الكوبونات - الصفحة الوحيدة المتاحة لك.');
            }
        }
        
        return $next($request);
    }
} 