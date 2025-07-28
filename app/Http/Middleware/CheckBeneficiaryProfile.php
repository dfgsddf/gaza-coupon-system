<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\BeneficiaryProfile;

class CheckBeneficiaryProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم مسجل دخول
        if (!$user) {
            return redirect()->route('login.form');
        }
        
        // إذا كان المستخدم مستفيد، يجب التحقق من اكتمال الملف الشخصي
        if ($user->role === 'beneficiary') {
            $profile = BeneficiaryProfile::where('user_id', $user->id)->first();
            
            // السماح بالوصول لصفحات إكمال الملف الشخصي
            $allowedRoutes = [
                'beneficiary.profile.form',
                'beneficiary.profile.store',
                'beneficiary.profile.check'
            ];
            
            if (in_array($request->route()->getName(), $allowedRoutes)) {
                return $next($request);
            }
            
            // إذا لم يكن الملف الشخصي مكتملاً، توجيه للفورم
            if (!$profile || !$profile->isProfileComplete()) {
                return redirect()->route('beneficiary.profile.form')
                    ->with('warning', 'يرجى إكمال ملفك الشخصي أولاً للوصول لجميع خدمات النظام.');
            }
        }
        
        return $next($request);
    }
} 