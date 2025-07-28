<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\BeneficiaryDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreDashboardController;
use App\Http\Controllers\BeneficiaryProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home.redirect');

// CSRF Token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Contact form routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::view('/help', 'help')->name('help');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Public)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Beneficiary-specific registration routes
Route::get('/beneficiary-register', [RegisterController::class, 'showBeneficiaryRegistrationForm'])->name('beneficiary.register.form');
Route::post('/beneficiary-register', [RegisterController::class, 'registerBeneficiary'])->name('beneficiary.register');
});

/*
|--------------------------------------------------------------------------
| Logout Route (Authenticated Only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Beneficiary Registration Routes (Public)
|--------------------------------------------------------------------------
*/
Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])->name('beneficiaries.create');
Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->name('beneficiaries.store');
Route::get('/beneficiaries/register', fn () => redirect()->route('beneficiaries.create'));
Route::post('/beneficiaries/register', [BeneficiaryController::class, 'store']);



/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by auth + admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('admin.users.updateStatus');
    
    // Contact Messages Management
    Route::get('/contact-messages', [ContactController::class, 'index'])->name('admin.contact-messages.index');
    Route::get('/contact-messages/{id}', [ContactController::class, 'showMessage'])->name('admin.contact-messages.show');
    Route::patch('/contact-messages/{id}/status', [ContactController::class, 'updateStatus'])->name('admin.contact-messages.update-status');
    Route::post('/contact-messages/{id}/replied', [ContactController::class, 'markAsReplied'])->name('admin.contact-messages.mark-replied');
    Route::delete('/contact-messages/{id}', [ContactController::class, 'destroy'])->name('admin.contact-messages.destroy');
    Route::get('/contact-messages/stats', [ContactController::class, 'getStats'])->name('admin.contact-messages.stats');
    
    // Admin Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
    Route::post('/settings/profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.settings.profile');
    Route::post('/settings/password', [AdminDashboardController::class, 'updatePassword'])->name('admin.settings.password');
    Route::post('/settings/system', [AdminDashboardController::class, 'updateSystemSettings'])->name('admin.settings.system');
    Route::post('/settings/notifications', [AdminDashboardController::class, 'updateNotificationSettings'])->name('admin.settings.notifications');
    
    // Admin Static Pages (Protected)
    Route::view('/organizations', 'admin.organizations')->name('admin.organizations');
    Route::view('/stores', 'admin.stores')->name('admin.stores');

    // Organization Management API
    Route::get('/organizations/api', [\App\Http\Controllers\Admin\OrganizationController::class, 'index'])->name('admin.organizations.api.index');
    Route::post('/organizations/api', [\App\Http\Controllers\Admin\OrganizationController::class, 'store'])->name('admin.organizations.api.store');
    Route::get('/organizations/api/{id}', [\App\Http\Controllers\Admin\OrganizationController::class, 'show'])->name('admin.organizations.api.show');
    Route::put('/organizations/api/{id}', [\App\Http\Controllers\Admin\OrganizationController::class, 'update'])->name('admin.organizations.api.update');
    Route::delete('/organizations/api/{id}', [\App\Http\Controllers\Admin\OrganizationController::class, 'destroy'])->name('admin.organizations.api.destroy');
    Route::get('/organizations/stats', [\App\Http\Controllers\Admin\OrganizationController::class, 'stats'])->name('admin.organizations.stats');

    // Store Management API
    Route::get('/stores/api', [\App\Http\Controllers\Admin\StoreController::class, 'index'])->name('admin.stores.api.index');
    Route::post('/stores/api', [\App\Http\Controllers\Admin\StoreController::class, 'store'])->name('admin.stores.api.store');
    Route::get('/stores/api/{id}', [\App\Http\Controllers\Admin\StoreController::class, 'show'])->name('admin.stores.api.show');
    Route::put('/stores/api/{id}', [\App\Http\Controllers\Admin\StoreController::class, 'update'])->name('admin.stores.api.update');
    Route::delete('/stores/api/{id}', [\App\Http\Controllers\Admin\StoreController::class, 'destroy'])->name('admin.stores.api.destroy');
    Route::get('/stores/stats', [\App\Http\Controllers\Admin\StoreController::class, 'stats'])->name('admin.stores.stats');
});

/*
|--------------------------------------------------------------------------
| Store Routes (Protected by auth + store middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\StoreMiddleware::class])->prefix('store')->group(function () {
    // Dashboard
    Route::get('/dashboard', [StoreDashboardController::class, 'index'])->name('store.dashboard');
    
    // Coupon Management
    Route::middleware(\App\Http\Middleware\CheckPermission::class . ':store.coupons.manage')->group(function() {
        Route::get('/coupons', [StoreDashboardController::class, 'coupons'])->name('store.coupons');
        Route::post('/validate-coupon', [StoreController::class, 'validateCoupon'])->name('store.validateCoupon');
        Route::post('/redeem-coupon', [StoreController::class, 'redeemCoupon'])->name('store.redeemCoupon');
    });
    
    // Transactions
    Route::middleware(\App\Http\Middleware\CheckPermission::class . ':store.transactions.view')->group(function() {
        Route::get('/transactions', [StoreDashboardController::class, 'transactions'])->name('store.transactions');
        Route::get('/transactions/{transaction}/details', [StoreDashboardController::class, 'transactionDetails'])->name('store.transaction.details');
    });
    
    // Reports
    Route::get('/reports', [StoreDashboardController::class, 'reports'])
        ->name('store.reports')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':store.reports.view');
    
    // Settings
    Route::middleware(\App\Http\Middleware\CheckPermission::class . ':store.settings.manage')->group(function() {
        Route::get('/settings', [StoreDashboardController::class, 'settings'])->name('store.settings');
        Route::post('/settings/profile', [StoreDashboardController::class, 'updateProfile'])->name('store.settings.profile');
        Route::post('/settings/password', [StoreDashboardController::class, 'updatePassword'])->name('store.settings.password');
        Route::post('/settings/notifications', [StoreDashboardController::class, 'updateNotifications'])->name('store.settings.notifications');
    });
    
    Route::get('/list', [StoreDashboardController::class, 'storeList'])
        ->name('store.list')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':store.list.view');
});

/*
|--------------------------------------------------------------------------
| Beneficiary Profile Routes (Protected by auth only for profile completion)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile completion routes (only for beneficiaries)
    Route::get('/beneficiary/profile/form', [BeneficiaryProfileController::class, 'showProfileForm'])->name('beneficiary.profile.form');
    Route::post('/beneficiary/profile', [BeneficiaryProfileController::class, 'storeProfile'])->name('beneficiary.profile.store');
    Route::get('/beneficiary/profile/check', [BeneficiaryProfileController::class, 'checkProfileCompleteness'])->name('beneficiary.profile.check');
});

/*
|--------------------------------------------------------------------------
| Beneficiary Routes (Limited to Coupons Only)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\BeneficiaryCouponController;

Route::middleware(['auth', 'beneficiary', 'check.beneficiary.profile'])->group(function () {
    // Redirect dashboard to coupons (المستفيدون يصلون مباشرة للكوبونات)
    Route::get('/beneficiary/dashboard', function() {
        return redirect()->route('beneficiary.coupons.index');
    })->name('beneficiary.dashboard');
    
    // Coupons Only (الوظيفة الوحيدة المسموحة للمستفيدين)
    Route::prefix('beneficiary/coupons')->name('beneficiary.coupons.')->group(function() {
        Route::get('/', [BeneficiaryCouponController::class, 'index'])->name('index');
        Route::get('/{coupon}', [BeneficiaryCouponController::class, 'show'])->name('show');
        Route::get('/{coupon}/print', [BeneficiaryCouponController::class, 'print'])->name('print');
    });
});

/*
|--------------------------------------------------------------------------
| Charity Routes (Protected by auth + charity middleware + permissions)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\CharityMiddleware::class])->prefix('charity')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [\App\Http\Controllers\CharityDashboardController::class, 'index'])
        ->name('charity.dashboard')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.dashboard.view');
    
    Route::get('/dashboard/stats', [\App\Http\Controllers\CharityDashboardController::class, 'getStats'])
        ->name('charity.dashboard.stats')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.dashboard.stats');
    
    Route::get('/dashboard/recent-requests', [\App\Http\Controllers\CharityDashboardController::class, 'getRecentRequests'])
        ->name('charity.dashboard.recentRequests')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.requests.view');
    
    Route::get('/dashboard/request-details/{num}', [\App\Http\Controllers\CharityDashboardController::class, 'getRequestDetails'])
        ->name('charity.dashboard.requestDetails')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.requests.details');

    // Campaign routes
    Route::get('/campaigns', [\App\Http\Controllers\CharityDashboardController::class, 'campaigns'])
        ->name('charity.campaigns')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.campaigns.view');
    
    Route::post('/campaigns', [\App\Http\Controllers\CharityDashboardController::class, 'storeCampaignAjax'])
        ->name('charity.campaigns.store')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.campaigns.create');
    
    Route::put('/campaigns/{id}', [\App\Http\Controllers\CharityDashboardController::class, 'updateCampaign'])
        ->name('charity.campaigns.update')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.campaigns.edit');
    
    Route::delete('/campaigns/{id}', [\App\Http\Controllers\CharityDashboardController::class, 'deleteCampaign'])
        ->name('charity.campaigns.delete')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.campaigns.delete');
    
    Route::get('/campaigns/{id}/donations', [\App\Http\Controllers\CharityDashboardController::class, 'getCampaignDonations'])
        ->name('charity.campaigns.donations')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.campaigns.view');
    
    Route::post('/dashboard/campaign', [\App\Http\Controllers\CharityDashboardController::class, 'storeCampaign'])
        ->name('charity.dashboard.storeCampaign')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.campaigns.create');

    // Request management routes
    Route::get('/requests', fn () => view('charity.requests'))
        ->name('charity.requests')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.requests.view');
    
    Route::post('/dashboard/request-approve/{num}', [\App\Http\Controllers\CharityDashboardController::class, 'approveRequest'])
        ->name('charity.dashboard.requestApprove')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.requests.approve');
    
    Route::post('/dashboard/request-reject/{num}', [\App\Http\Controllers\CharityDashboardController::class, 'rejectRequest'])
        ->name('charity.dashboard.requestReject')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.requests.reject');

    // Reports routes
    Route::get('/reports', [\App\Http\Controllers\CharityDashboardController::class, 'reports'])
        ->name('charity.reports')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.reports.view');
    
    Route::post('/reports/generate', [\App\Http\Controllers\CharityDashboardController::class, 'generateReport'])
        ->name('charity.reports.generate')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.reports.generate');
    
    Route::post('/reports/{id}/export', [\App\Http\Controllers\CharityDashboardController::class, 'exportReport'])
        ->name('charity.reports.export')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.reports.export');

    // Settings routes
    Route::get('/settings', fn () => view('charity.settings'))
        ->name('charity.settings')
        ->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.settings.view');

    // Charity Coupons Management
    Route::get('/coupons', [\App\Http\Controllers\CharityDashboardController::class, 'coupons'])->name('charity.coupons')->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.coupons.view');
    Route::get('/coupons/create', [\App\Http\Controllers\CharityDashboardController::class, 'createCoupon'])->name('charity.coupons.create')->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\CharityDashboardController::class, 'storeCoupon'])->name('charity.coupons.store')->middleware(\App\Http\Middleware\CheckPermission::class . ':charity.coupons.create');
});

/*
|--------------------------------------------------------------------------
| Public Static Pages (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::view('/stores', 'store')->name('stores.index');
Route::view('/store', 'store')->name('store');
Route::view('/charity', 'charity')->name('charity');

// Charity login redirect - for users who want to access charity dashboard
Route::get('/charity/login', function () {
    return redirect()->route('login.form')->with('info', 'يرجى تسجيل الدخول للوصول إلى لوحة التحكم الخاصة بالجمعيات الخيرية');
})->name('charity.login');

/*
|--------------------------------------------------------------------------
| Redirect Routes (Public)
|--------------------------------------------------------------------------
*/
Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/beneficiary', '/beneficiary/dashboard');
Route::redirect('/store', '/store/dashboard');

/*
|--------------------------------------------------------------------------
| Development/Testing Routes (Remove in Production)
|--------------------------------------------------------------------------
*/
// Database connection test route
Route::get('/test-db', function () {
    try {
        $userCount = \App\Models\User::count();
        $campaignCount = \App\Models\Campaign::count();
        $couponCount = \App\Models\Coupon::count();
        $transactionCount = \App\Models\Transaction::count();
        
        return response()->json([
            'success' => true,
            'message' => 'Database connection successful!',
            'data' => [
                'users' => $userCount,
                'campaigns' => $campaignCount,
                'coupons' => $couponCount,
                'transactions' => $transactionCount
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database connection failed: ' . $e->getMessage()
        ], 500);
    }
});
if (app()->environment('local', 'development')) {
    Route::get('/force-login', function () {
        $user = \App\Models\User::where('email', 'admin@example.com')->first();
        Auth::login($user);
        return redirect('/admin/dashboard');
    });

    Route::get('/force-login-beneficiary', function () {
        $user = \App\Models\User::where('email', 'beneficiary@example.com')->first();
        Auth::login($user);
        return redirect('/beneficiary/dashboard');
    })->name('force.login.beneficiary');

    Route::get('/force-login-store', function () {
        $user = \App\Models\User::where('email', 'store@example.com')->first();
        Auth::login($user);
        return redirect('/store/dashboard');
    })->name('force.login.store');

    Route::get('/force-login-charity', function () {
        $user = \App\Models\User::where('email', 'charity@example.com')->first();
        Auth::login($user);
        return redirect('/charity/dashboard');
    })->name('force.login.charity');

    Route::get('/test-store-middleware', function () {
        return 'Store middleware works!';
    })->middleware(['auth', \App\Http\Middleware\StoreMiddleware::class]);

    // Test route without middleware to isolate the issue
    Route::get('/test-store-controller', function () {
        return 'StoreDashboardController test: ' . StoreDashboardController::class;
    });

    // Test routes for other middlewares
    Route::get('/test-charity-middleware', function () {
        return 'Charity middleware works!';
    })->middleware(['auth', \App\Http\Middleware\CharityMiddleware::class]);

    Route::get('/test-beneficiary-middleware', function () {
        return 'Beneficiary middleware works!';
    })->middleware(['auth', \App\Http\Middleware\BeneficiaryMiddleware::class]);

    Route::get('/test-admin-middleware', function () {
        return 'Admin middleware works!';
    })->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
}






