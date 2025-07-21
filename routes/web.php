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
    
    // Admin Dashboard AJAX endpoints
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('admin.dashboard.stats');
    Route::get('/dashboard/activity', [AdminDashboardController::class, 'getActivity'])->name('admin.dashboard.activity');
    Route::get('/dashboard/users', [AdminDashboardController::class, 'getUsers'])->name('admin.dashboard.users');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
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
    Route::get('/coupons', [StoreDashboardController::class, 'coupons'])->name('store.coupons');
    Route::post('/validate-coupon', [StoreController::class, 'validateCoupon'])->name('store.validateCoupon');
    Route::post('/redeem-coupon', [StoreController::class, 'redeemCoupon'])->name('store.redeemCoupon');
    
    // Transactions
    Route::get('/transactions', [StoreDashboardController::class, 'transactions'])->name('store.transactions');
    Route::get('/transactions/{transaction}/details', [StoreDashboardController::class, 'transactionDetails'])->name('store.transaction.details');
    
    // Reports
    Route::get('/reports', [StoreDashboardController::class, 'reports'])->name('store.reports');
    
    // Settings
    Route::get('/settings', [StoreDashboardController::class, 'settings'])->name('store.settings');
    Route::post('/settings/profile', [StoreDashboardController::class, 'updateProfile'])->name('store.settings.profile');
    Route::post('/settings/password', [StoreDashboardController::class, 'updatePassword'])->name('store.settings.password');
    Route::post('/settings/notifications', [StoreDashboardController::class, 'updateNotifications'])->name('store.settings.notifications');
    
    // AJAX endpoints
    Route::get('/stats', [StoreController::class, 'getStats'])->name('store.stats');
    Route::get('/recent-transactions', [StoreController::class, 'getRecentTransactions'])->name('store.recentTransactions');
});

/*
|--------------------------------------------------------------------------
| Beneficiary Routes (Protected by auth + beneficiary middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\BeneficiaryMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/beneficiary/dashboard', [BeneficiaryDashboardController::class, 'index'])->name('beneficiary.dashboard');
    
    // Settings
    Route::get('/beneficiary/settings', [BeneficiaryDashboardController::class, 'settings'])->name('beneficiary.settings');
    Route::post('/beneficiary/settings/profile', [BeneficiaryDashboardController::class, 'updateProfile'])->name('beneficiary.settings.profile');
    Route::post('/beneficiary/settings/password', [BeneficiaryDashboardController::class, 'updatePassword'])->name('beneficiary.settings.password');
    Route::post('/beneficiary/settings/notifications', [BeneficiaryDashboardController::class, 'updateNotifications'])->name('beneficiary.settings.notifications');
    
    // Requests
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{request}/details', [RequestController::class, 'details'])->name('requests.details');
    
    // Coupons
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');
    Route::get('/coupons/{coupon}/details', [CouponController::class, 'details'])->name('coupons.details');
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
});

/*
|--------------------------------------------------------------------------
| Public Static Pages (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::view('/stores', 'store')->name('stores.index');
Route::view('/store', 'store')->name('store');
Route::view('/charity', 'charity')->name('charity');

/*
|--------------------------------------------------------------------------
| Redirect Routes (Public)
|--------------------------------------------------------------------------
*/
Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/beneficiary', '/beneficiary/dashboard');
Route::redirect('/store', '/store/dashboard');
Route::redirect('/charity', '/charity/dashboard');

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
