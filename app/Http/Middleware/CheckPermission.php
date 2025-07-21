<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
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

        $user = Auth::user();

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Insufficient permissions.'
                ], 403);
            }

            // Redirect based on user role with appropriate error message
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
                case 'charity':
                    return redirect()->route('charity.dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
                case 'store':
                    return redirect()->route('store.dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
                case 'beneficiary':
                    return redirect()->route('beneficiary.dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
                default:
                    return redirect()->route('home')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
            }
        }

        return $next($request);
    }
}
