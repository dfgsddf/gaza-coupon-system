<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryCouponController extends Controller
{
    /**
     * عرض الكوبونات المتاحة للمستفيد
     */
    public function index()
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم مستفيد
        if ($user->role !== 'beneficiary') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة.');
        }
        
        // جلب الكوبونات المتاحة للمستفيد (غير المستخدمة وغير المنتهية الصلاحية)
        $coupons = Coupon::where('user_id', $user->id)
            ->where('redeemed', false)
            ->where('expiry_date', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();
            
        // جلب الكوبونات المستخدمة للإحصائيات
        $usedCoupons = Coupon::where('user_id', $user->id)
            ->where('redeemed', true)
            ->count();
            
        // جلب الكوبونات المنتهية الصلاحية
        $expiredCoupons = Coupon::where('user_id', $user->id)
            ->where('redeemed', false)
            ->where('expiry_date', '<=', now())
            ->count();
        
        return view('beneficiary.coupons.index', compact('coupons', 'usedCoupons', 'expiredCoupons'));
    }
    
    /**
     * عرض تفاصيل الكوبون
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم مستفيد
        if ($user->role !== 'beneficiary') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة.');
        }
        
        // جلب الكوبون مع التأكد من أنه يخص هذا المستفيد
        $coupon = Coupon::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('beneficiary.coupons.show', compact('coupon'));
    }
    
    /**
     * طباعة الكوبون
     */
    public function print($id)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم مستفيد
        if ($user->role !== 'beneficiary') {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة.');
        }
        
        // جلب الكوبون مع التأكد من أنه يخص هذا المستفيد
        $coupon = Coupon::where('id', $id)
            ->where('user_id', $user->id)
            ->where('redeemed', false)
            ->where('expiry_date', '>', now())
            ->firstOrFail();
        
        return view('beneficiary.coupons.print', compact('coupon'));
    }
} 