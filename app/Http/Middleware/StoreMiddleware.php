<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreMiddleware
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

        // التحقق من دور المستخدم
        if (Auth::user()->role !== 'store') {
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
                    return redirect()->route('admin.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة تحكم المتاجر');
                case 'beneficiary':
                    return redirect()->route('beneficiary.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة تحكم المتاجر');
                case 'charity':
                    return redirect()->route('charity.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة تحكم المتاجر');
                default:
                    return redirect()->route('home')->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة');
            }
        }
        
        return $next($request);
    }
} 