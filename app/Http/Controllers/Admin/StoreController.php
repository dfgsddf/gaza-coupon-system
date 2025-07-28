<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        // إذا كان الطلب يريد JSON (API)
        if ($request->wantsJson() || $request->is('*/api*')) {
            return $this->getStoresApi($request);
        }
        
        // عرض الصفحة
        return view('admin.stores');
    }
    
    private function getStoresApi(Request $request)
    {
        try {
            Log::info('StoreController@getStoresApi called', ['user_id' => auth()->id()]);
            
            // استعلام محسن مع تحميل العلاقات المطلوبة
            $query = Store::query()
                ->with(['users'])
                ->withCount(['transactions', 'coupons']);
            
            Log::info('Basic query created, checking total stores count', [
                'total_stores_in_db' => Store::count()
            ]);

            // فلترة بناءً على معايير البحث
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $term = $request->search;
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('store_code', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%")
                      ->orWhere('phone', 'like', "%{$term}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('store_type')) {
                $query->where('store_type', $request->store_type);
            }

            if ($request->filled('has_physical_location')) {
                $query->where('has_physical_location', $request->has_physical_location == '1');
            }

            if ($request->filled('accepts_online_orders')) {
                $query->where('accepts_online_orders', $request->accepts_online_orders == '1');
            }

            $storesData = $query->get();
            
            Log::info('Raw stores count before mapping:', [
                'count' => $storesData->count()
            ]);
            
            $stores = $storesData->map(function ($store) {
                try {
                    $primaryUser = $store->primaryUser();
                    return [
                        'id' => $store->id,
                        'name' => $store->name,
                        'store_code' => $store->store_code,
                        'email' => $primaryUser ? $primaryUser->email : $store->email,
                        'phone' => $primaryUser ? $primaryUser->phone : $store->phone,
                        'address' => $primaryUser ? $primaryUser->address : $store->address,
                        'status' => $store->status ?? 'active',
                        'store_type' => $store->store_type,
                        'description' => $store->description,
                        'has_physical_location' => $store->has_physical_location,
                        'accepts_online_orders' => $store->accepts_online_orders,
                        'tax_number' => $store->tax_number,
                        'commercial_register' => $store->commercial_register,
                        'primary_user' => $primaryUser ? [
                            'id' => $primaryUser->id,
                            'name' => $primaryUser->name,
                            'email' => $primaryUser->email,
                            'phone' => $primaryUser->phone,
                        ] : null,
                        'users_count' => $store->users_count ?? 0,
                        'transactions_count' => $store->transactions_count ?? 0,
                        'coupons_count' => $store->coupons_count ?? 0,
                        'total_revenue' => $store->getTotalRevenue(),
                        'active_coupons' => $store->getActiveCoupons(),
                        'created_at' => $store->created_at,
                        'updated_at' => $store->updated_at
                    ];
                } catch (\Exception $e) {
                    Log::error('Error processing store: ' . $e->getMessage(), [
                        'store_id' => $store->id ?? 'unknown',
                        'store_name' => $store->name ?? 'unknown'
                    ]);
                    // بدلاً من إرجاع null، نرجع بيانات المتجر الأساسية
                    return [
                        'id' => $store->id,
                        'name' => $store->name,
                        'store_code' => $store->store_code,
                        'email' => $store->email,
                        'phone' => $store->phone,
                        'address' => $store->address,
                        'status' => $store->status ?? 'active',
                        'store_type' => $store->store_type,
                        'description' => $store->description,
                        'has_physical_location' => $store->has_physical_location,
                        'accepts_online_orders' => $store->accepts_online_orders,
                        'tax_number' => $store->tax_number,
                        'commercial_register' => $store->commercial_register,
                        'primary_user' => null,
                        'users_count' => 0,
                        'transactions_count' => 0,
                        'coupons_count' => 0,
                        'total_revenue' => 0,
                        'active_coupons' => 0,
                        'created_at' => $store->created_at,
                        'updated_at' => $store->updated_at
                    ];
                }
            });
            
            Log::info('Fetched stores data:', [
                'count' => count($stores),
                'sample_store' => $stores->first() ? $stores->first() : null
            ]);
            
            return response()->json($stores);
        } catch (\Exception $e) {
            Log::error('Error fetching stores: ' . $e->getMessage(), [
                'exception' => $e,
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات المتاجر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'store_code' => 'nullable|string|max:50|unique:stores,store_code',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
            'store_type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'has_physical_location' => 'boolean',
            'accepts_online_orders' => 'boolean',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء المتجر
            $store = Store::create([
                'name' => $validated['name'],
                'store_code' => $validated['store_code'] ?? 'STR' . str_pad(Store::count() + 1, 4, '0', STR_PAD_LEFT),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => $validated['status'] ?? 'active',
                'store_type' => $validated['store_type'],
                'description' => $validated['description'],
                'has_physical_location' => $validated['has_physical_location'] ?? true,
                'accepts_online_orders' => $validated['accepts_online_orders'] ?? false,
                'tax_number' => $validated['tax_number'],
                'commercial_register' => $validated['commercial_register'],
            ]);

            // إنشاء المستخدم المالك
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['user_password']),
                'role' => 'store',
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'status' => 'active',
            ]);

            // ربط المستخدم بالمتجر كمالك رئيسي
            $store->addUser($user->id, 'owner', true);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المتجر بنجاح',
                'store' => $store->load('users')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating store: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المتجر: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'store_code' => ['nullable', 'string', 'max:50', Rule::unique('stores')->ignore($id)],
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
            'store_type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'has_physical_location' => 'boolean',
            'accepts_online_orders' => 'boolean',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $store->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات المتجر بنجاح',
                'store' => $store->load('users')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating store: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المتجر: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $store = Store::findOrFail($id);

            // التحقق من وجود معاملات أو كوبونات مرتبطة
            if ($store->transactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف المتجر لوجود معاملات مرتبطة به'
                ], 400);
            }

            if ($store->coupons()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف المتجر لوجود كوبونات مرتبطة به'
                ], 400);
            }

            // حذف العلاقات مع المستخدمين
            $store->users()->detach();

            // حذف المتجر
            $store->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المتجر بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting store: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المتجر: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $store = Store::with(['users', 'transactions', 'coupons'])
                         ->withStats()
                         ->findOrFail($id);

            $storeData = [
                'id' => $store->id,
                'name' => $store->name,
                'store_code' => $store->store_code,
                'email' => $store->email,
                'phone' => $store->phone,
                'address' => $store->address,
                'status' => $store->status,
                'store_type' => $store->store_type,
                'description' => $store->description,
                'has_physical_location' => $store->has_physical_location,
                'accepts_online_orders' => $store->accepts_online_orders,
                'tax_number' => $store->tax_number,
                'commercial_register' => $store->commercial_register,
                'users' => $store->users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role_in_store' => $user->pivot->role,
                        'is_primary' => $user->pivot->is_primary,
                    ];
                }),
                'stats' => [
                    'total_transactions' => $store->getTotalTransactions(),
                    'total_revenue' => $store->getTotalRevenue(),
                    'active_coupons' => $store->getActiveCoupons(),
                    'redeemed_coupons' => $store->getRedeemedCoupons(),
                    'users_count' => $store->users->count(),
                ],
                'created_at' => $store->created_at,
                'updated_at' => $store->updated_at
            ];

            return response()->json([
                'success' => true,
                'store' => $storeData
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching store details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب تفاصيل المتجر'
            ], 500);
        }
    }

    public function stats()
    {
        try {
            $stats = [
                'total' => Store::count(),
                'active' => Store::active()->count(),
                'inactive' => Store::inactive()->count(),
                'with_physical_location' => Store::withPhysicalLocation()->count(),
                'with_online_orders' => Store::withOnlineOrders()->count(),
                'store_types' => Store::select('store_type', DB::raw('count(*) as count'))
                                   ->whereNotNull('store_type')
                                   ->groupBy('store_type')
                                   ->pluck('count', 'store_type'),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error fetching store stats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب إحصائيات المتاجر'
            ], 500);
        }
    }

    public function addUser(Request $request, $storeId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:owner,manager,employee',
            'is_primary' => 'boolean'
        ]);

        try {
            $store = Store::findOrFail($storeId);
            
            if ($store->hasUser($validated['user_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم مرتبط بالمتجر بالفعل'
                ], 400);
            }

            $store->addUser(
                $validated['user_id'], 
                $validated['role'], 
                $validated['is_primary'] ?? false
            );

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المستخدم للمتجر بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المستخدم: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeUser(Request $request, $storeId, $userId)
    {
        try {
            $store = Store::findOrFail($storeId);
            
            if (!$store->hasUser($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مرتبط بالمتجر'
                ], 400);
            }

            $store->removeUser($userId);

            return response()->json([
                'success' => true,
                'message' => 'تم إزالة المستخدم من المتجر بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إزالة المستخدم: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUserRole(Request $request, $storeId, $userId)
    {
        $validated = $request->validate([
            'role' => 'required|in:owner,manager,employee',
            'is_primary' => 'boolean'
        ]);

        try {
            $store = Store::findOrFail($storeId);
            
            if (!$store->hasUser($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مرتبط بالمتجر'
                ], 400);
            }

            $store->updateUserRole($userId, $validated['role']);

            if ($validated['is_primary'] ?? false) {
                $store->setPrimaryUser($userId);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث دور المستخدم بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث دور المستخدم: ' . $e->getMessage()
            ], 500);
        }
    }
} 