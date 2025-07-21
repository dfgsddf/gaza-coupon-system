<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // AJAX: Validate coupon code
    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
        ], [
            'code.required' => 'رمز القسيمة مطلوب',
            'code.string' => 'رمز القسيمة يجب أن يكون نص',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::where('code', $request->code)
                ->with('user')
                ->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'القسيمة غير موجودة'
                ], 404);
            }

            // Check if coupon is already redeemed
            if ($coupon->redeemed) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم استرداد هذه القسيمة مسبقاً'
                ], 400);
            }

            // Check if coupon is expired
            if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'انتهت صلاحية القسيمة'
                ], 400);
            }

            // Check if coupon is for this store
            $currentStore = Auth::user();
            if ($coupon->store_name && $coupon->store_name !== $currentStore->name) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذه القسيمة غير صالحة لهذا المتجر'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم التحقق من القسيمة بنجاح',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'beneficiary_name' => $coupon->user ? $coupon->user->name : 'غير محدد',
                    'beneficiary_id' => $coupon->user_id,
                    'value' => number_format($coupon->value, 2),
                    'expiry_date' => $coupon->expiry_date ? Carbon::parse($coupon->expiry_date)->format('Y-m-d') : 'غير محدد',
                    'store_name' => $coupon->store_name ?: $currentStore->name,
                    'description' => $coupon->description,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من القسيمة'
            ], 500);
        }
    }

    // AJAX: Confirm coupon redemption
    public function redeemCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
        ], [
            'code.required' => 'رمز القسيمة مطلوب',
            'code.string' => 'رمز القسيمة يجب أن يكون نص',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $coupon = Coupon::where('code', $request->code)
                ->with('user')
                ->lockForUpdate()
                ->first();

            if (!$coupon) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'القسيمة غير موجودة'
                ], 404);
            }

            // Check if coupon is already redeemed
            if ($coupon->redeemed) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'تم استرداد هذه القسيمة مسبقاً'
                ], 400);
            }

            // Check if coupon is expired
            if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->isPast()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'انتهت صلاحية القسيمة'
                ], 400);
            }

            $currentStore = Auth::user();

            // Check if coupon is for this store
            if ($coupon->store_name && $coupon->store_name !== $currentStore->name) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'هذه القسيمة غير صالحة لهذا المتجر'
                ], 400);
            }

            // Mark coupon as redeemed
            $coupon->update([
                'redeemed' => true,
                'redeemed_at' => now(),
            ]);

            // Create transaction record
            $transaction = Transaction::create([
                'store_id' => $currentStore->id,
                'beneficiary_id' => $coupon->user_id,
                'coupon_id' => $coupon->id,
                'coupon_value' => $coupon->value,
                'beneficiary_name' => $coupon->user ? $coupon->user->name : 'غير محدد',
                'store_name' => $currentStore->name,
                'status' => 'completed',
                'redeemed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استرداد القسيمة بنجاح',
                'transaction' => [
                    'id' => $transaction->id,
                    'beneficiary_name' => $transaction->beneficiary_name,
                    'coupon_value' => number_format($transaction->coupon_value, 2),
                    'redeemed_at' => $transaction->redeemed_at->format('Y-m-d H:i:s'),
                    'status' => $transaction->status,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استرداد القسيمة'
            ], 500);
        }
    }

    // Get store statistics
    public function getStats()
    {
        try {
            $store = Auth::user();
            $storeId = $store->id;

            // Today's transactions
            $todayTransactions = Transaction::where('store_id', $storeId)
                ->whereDate('created_at', Carbon::today())
                ->count();

            // Monthly revenue
            $monthlyRevenue = Transaction::where('store_id', $storeId)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('coupon_value');

            // Total coupons redeemed
            $totalCoupons = Transaction::where('store_id', $storeId)->count();

            // Pending coupons (coupons that exist but haven't been redeemed)
            $pendingCoupons = Coupon::where('store_name', $store->name)
                ->where('redeemed', false)
                ->where(function($query) {
                    $query->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', Carbon::now());
                })
                ->count();

            return response()->json([
                'success' => true,
                'stats' => [
                    'today_transactions' => $todayTransactions,
                    'monthly_revenue' => number_format($monthlyRevenue, 2),
                    'total_coupons' => $totalCoupons,
                    'pending_coupons' => $pendingCoupons,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات'
            ], 500);
        }
    }

    // Get recent transactions
    public function getRecentTransactions()
    {
        try {
            $store = Auth::user();
            $storeId = $store->id;

            $transactions = Transaction::where('store_id', $storeId)
                ->with('beneficiary')
                ->latest()
                ->take(10)
                ->get();

            $formattedTransactions = $transactions->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'beneficiary_name' => $transaction->beneficiary_name,
                    'coupon_value' => number_format($transaction->coupon_value, 2),
                    'created_at' => $transaction->created_at->format('M d, Y H:i'),
                    'status' => $transaction->status,
                ];
            });

            return response()->json([
                'success' => true,
                'transactions' => $formattedTransactions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب المعاملات'
            ], 500);
        }
    }
} 