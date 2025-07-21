# Route Security Documentation

## Overview

This document outlines the comprehensive security measures implemented to protect all routes in the Gaza Coupon System. The system uses multiple layers of middleware protection to ensure proper access control and prevent unauthorized access.

## Security Architecture

### 1. Middleware Layers

The application uses a multi-layered middleware approach:

1. **Authentication Middleware** (`auth`) - Ensures user is logged in
2. **Role Middleware** (`admin`, `charity`, `store`, `beneficiary`) - Ensures user has correct role
3. **Permission Middleware** (`permission`) - Ensures user has specific permissions (for charity routes)

### 2. Route Categories

#### Public Routes (No Authentication Required)
- Home page (`/`, `/home`)
- Contact form (`/contact`)
- Help page (`/help`)
- Beneficiary registration (`/beneficiaries/*`)
- Static pages (`/stores`, `/store`, `/charity`)

#### Authentication Routes
- Login (`/login`) - Guest middleware
- Register (`/register`) - Guest middleware
- Logout (`/logout`) - Auth middleware

#### Protected Role-Based Routes

##### Admin Routes (`/admin/*`)
- **Middleware**: `['auth', 'admin']`
- **Access**: Admin users only
- **Routes**:
  - `/admin/dashboard` - Admin dashboard
  - `/admin/users` - User management
  - `/admin/organizations` - Organization management
  - `/admin/stores` - Store management
  - `/admin/settings` - Admin settings

##### Store Routes (`/store/*`)
- **Middleware**: `['auth', 'store']`
- **Access**: Store users only
- **Routes**:
  - `/store/dashboard` - Store dashboard
  - `/store/coupons` - Coupon validation
  - `/store/transactions` - Transaction history
  - `/store/reports` - Reports
  - `/store/settings` - Store settings
  - `/store/validate-coupon` - AJAX coupon validation
  - `/store/redeem-coupon` - AJAX coupon redemption
  - `/store/stats` - AJAX statistics
  - `/store/recent-transactions` - AJAX recent transactions

##### Beneficiary Routes
- **Middleware**: `['auth', 'beneficiary']`
- **Access**: Beneficiary users only
- **Routes**:
  - `/beneficiary/dashboard` - Beneficiary dashboard
  - `/beneficiary/settings` - Beneficiary settings
  - `/requests` - Request management
  - `/coupons` - Coupon viewing

##### Charity Routes (`/charity/*`)
- **Middleware**: `['auth', 'charity']` + specific permissions
- **Access**: Charity users with specific permissions
- **Routes**:
  - `/charity/dashboard` - Charity dashboard (requires `charity.dashboard.view`)
  - `/charity/campaigns` - Campaign management (requires `charity.campaigns.view`)
  - `/charity/requests` - Request management (requires `charity.requests.view`)
  - `/charity/reports` - Reports (requires `charity.reports.view`)
  - `/charity/settings` - Settings (requires `charity.settings.view`)

## Middleware Implementation

### 1. AdminMiddleware

```php
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check authentication
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // Check admin role
        if (Auth::user()->role !== 'admin') {
            return $this->handleUnauthorized($request, 'admin');
        }

        return $next($request);
    }
}
```

### 2. StoreMiddleware

```php
class StoreMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check authentication
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // Check store role
        if (Auth::user()->role !== 'store') {
            return $this->handleUnauthorized($request, 'store');
        }

        return $next($request);
    }
}
```

### 3. BeneficiaryMiddleware

```php
class BeneficiaryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check authentication
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // Check beneficiary role
        if (Auth::user()->role !== 'beneficiary') {
            return $this->handleUnauthorized($request, 'beneficiary');
        }

        return $next($request);
    }
}
```

### 4. CharityMiddleware

```php
class CharityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check authentication
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // Check charity role
        if (Auth::user()->role !== 'charity') {
            return $this->handleUnauthorized($request, 'charity');
        }

        return $next($request);
    }
}
```

### 5. CheckPermission Middleware

```php
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check authentication
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // Check specific permission
        if (!Auth::user()->hasPermission($permission)) {
            return $this->handleInsufficientPermissions($request);
        }

        return $next($request);
    }
}
```

## Error Handling

### 1. Unauthenticated Users

**AJAX Requests:**
```json
{
    "success": false,
    "message": "Authentication required"
}
```
**Status Code:** 401

**Regular Requests:**
- Redirect to login page
- Flash message: "يرجى تسجيل الدخول للوصول إلى هذه الصفحة"

### 2. Unauthorized Users

**AJAX Requests:**
```json
{
    "success": false,
    "message": "Access denied. [Role] role required."
}
```
**Status Code:** 403

**Regular Requests:**
- Redirect to appropriate dashboard based on user role
- Flash message in Arabic explaining the restriction

### 3. Insufficient Permissions

**AJAX Requests:**
```json
{
    "success": false,
    "message": "Access denied. Insufficient permissions."
}
```
**Status Code:** 403

**Regular Requests:**
- Redirect to appropriate dashboard
- Flash message: "ليس لديك صلاحية للوصول إلى هذه الصفحة"

## Security Features

### 1. CSRF Protection

All forms and AJAX requests include CSRF tokens:
```php
// In layout
<meta name="csrf-token" content="{{ csrf_token() }}">

// In AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

### 2. Input Validation

All user inputs are validated using Laravel's validation system:
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
], [
    'name.required' => 'الاسم مطلوب',
    'email.required' => 'البريد الإلكتروني مطلوب',
    // ... Arabic error messages
]);
```

### 3. SQL Injection Prevention

- Uses Eloquent ORM with parameterized queries
- Input sanitization through validation
- No raw SQL queries

### 4. XSS Protection

- Blade templates automatically escape output
- HTML Purifier for rich text content
- Content Security Policy headers

### 5. Session Security

- Secure session configuration
- Session timeout
- Session regeneration on login

## Development vs Production

### Development Routes

The following routes are only available in development/local environment:

```php
if (app()->environment('local', 'development')) {
    Route::get('/force-login', function () {
        // Auto-login for testing
    });
    
    Route::get('/force-login-admin', function () {
        // Auto-login admin for testing
    });
    
    Route::get('/test-store-middleware', function () {
        // Test middleware
    })->middleware(['auth', 'store']);
}
```

### Production Security

In production:
- Development routes are automatically disabled
- Debug mode is disabled
- Error reporting is minimal
- HTTPS is enforced
- Rate limiting is enabled

## Testing Security

### 1. Manual Testing

Test each role's access to different routes:

```bash
# Test admin access
curl -H "Authorization: Bearer [admin-token]" /admin/dashboard

# Test store access
curl -H "Authorization: Bearer [store-token]" /store/dashboard

# Test unauthorized access
curl -H "Authorization: Bearer [beneficiary-token]" /admin/dashboard
```

### 2. Automated Testing

```php
public function test_admin_cannot_access_store_dashboard()
{
    $admin = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($admin)
        ->get('/store/dashboard');
    
    $response->assertStatus(403);
}

public function test_unauthenticated_user_cannot_access_protected_routes()
{
    $response = $this->get('/admin/dashboard');
    
    $response->assertRedirect('/login');
}
```

## Best Practices

### 1. Always Use Middleware

```php
// Good
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Bad
Route::get('/admin/dashboard', [AdminController::class, 'index']);
```

### 2. Validate Permissions in Controllers

```php
public function store(Request $request)
{
    if (!Auth::user()->hasPermission('charity.campaigns.create')) {
        return response()->json([
            'success' => false,
            'message' => 'ليس لديك صلاحية لإنشاء حملات جديدة'
        ], 403);
    }
    
    // Continue with logic...
}
```

### 3. Use Blade Directives for UI

```blade
@permission('charity.campaigns.create')
    <button class="btn btn-primary">Create Campaign</button>
@endpermission
```

### 4. Regular Security Audits

- Review route permissions monthly
- Test unauthorized access scenarios
- Monitor failed login attempts
- Check for suspicious activity

## Monitoring and Logging

### 1. Failed Access Attempts

All failed access attempts are logged:
- Unauthenticated access
- Unauthorized access
- Permission violations

### 2. Security Events

Monitor for:
- Multiple failed login attempts
- Access to restricted routes
- Unusual user behavior
- Suspicious IP addresses

### 3. Error Tracking

- 401 Unauthorized errors
- 403 Forbidden errors
- 404 Not Found errors
- 500 Internal Server errors

## Emergency Procedures

### 1. Security Breach Response

1. **Immediate Actions:**
   - Disable affected accounts
   - Review access logs
   - Identify compromised routes

2. **Investigation:**
   - Analyze security logs
   - Review recent changes
   - Check for data breaches

3. **Recovery:**
   - Reset compromised passwords
   - Update security measures
   - Notify affected users

### 2. Route Lockdown

If a security breach is detected:
```php
// Emergency route lockdown
Route::middleware(['auth', 'emergency'])->group(function () {
    // Only essential routes
});
```

## Compliance

### 1. Data Protection

- GDPR compliance for EU users
- Data encryption at rest and in transit
- Right to be forgotten implementation
- Data retention policies

### 2. Audit Trails

- All user actions are logged
- Access logs are retained for 1 year
- Audit reports are generated monthly

### 3. Privacy

- No sensitive data in URLs
- Secure session handling
- Privacy policy compliance

## Conclusion

The route security system provides comprehensive protection against unauthorized access through multiple layers of middleware, proper error handling, and extensive logging. Regular security audits and monitoring ensure the system remains secure and compliant with best practices. 