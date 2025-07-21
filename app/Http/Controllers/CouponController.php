<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::where('user_id', Auth::id())->get();
        return view('coupons.index', compact('coupons'));
    }

    public function show($id)
    {
        $coupon = Coupon::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('coupons.show', compact('coupon'));
    }

    public function details($id)
    {
        $coupon = Coupon::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('beneficiary.coupons.details', compact('coupon'));
    }
}
