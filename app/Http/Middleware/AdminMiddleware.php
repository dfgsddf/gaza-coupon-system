<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            return redirect()->route('login.form')->with('error', 'يرجى تسجيل الدخول للوصول إلى هذه الصفحة');
        }

        // Check if user has admin role
        if (Auth::user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin role required.'
                ], 403);
            }
            
            // Redirect based on user role
            $user = Auth::user();
            switch ($user->role) {
                case 'charity':
                    return redirect()->route('charity.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة التحكم الخاصة بالمشرفين');
                case 'store':
                    return redirect()->route('store.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة التحكم الخاصة بالمشرفين');
                case 'beneficiary':
                    return redirect()->route('beneficiary.dashboard')->with('error', 'غير مسموح لك بالوصول إلى لوحة التحكم الخاصة بالمشرفين');
                default:
                    return redirect()->route('home')->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة');
            }
        }

        return $next($request);
    }
}
