# Charity Permission System Documentation

## Overview

The charity permission system provides granular access control for charity users, ensuring they can only access features and perform actions they are authorized for. The system includes middleware protection, database-level permissions, and view-level directives.

## Permission Structure

### Permission Naming Convention
Permissions follow the pattern: `{role}.{module}.{action}`

Examples:
- `charity.dashboard.view` - View charity dashboard
- `charity.campaigns.create` - Create new campaigns
- `charity.requests.approve` - Approve requests

### Permission Categories

#### 1. Dashboard Permissions
- `charity.dashboard.view` - Access to main dashboard
- `charity.dashboard.stats` - View dashboard statistics

#### 2. Campaign Management
- `charity.campaigns.view` - View campaigns list
- `charity.campaigns.create` - Create new campaigns
- `charity.campaigns.edit` - Edit existing campaigns
- `charity.campaigns.delete` - Delete campaigns
- `charity.campaigns.manage` - Full campaign management

#### 3. Request Management
- `charity.requests.view` - View requests list
- `charity.requests.details` - View request details
- `charity.requests.approve` - Approve requests
- `charity.requests.reject` - Reject requests
- `charity.requests.manage` - Full request management

#### 4. Reports & Analytics
- `charity.reports.view` - View reports
- `charity.reports.generate` - Generate new reports
- `charity.reports.export` - Export reports
- `charity.analytics.view` - View analytics
- `charity.analytics.export` - Export analytics data

#### 5. Settings & Configuration
- `charity.settings.view` - View settings
- `charity.settings.edit` - Edit settings

#### 6. User Management
- `charity.users.view` - View users
- `charity.users.manage` - Manage users

#### 7. Financial Management
- `charity.financial.view` - View financial data
- `charity.financial.manage` - Manage financial data

## Implementation

### 1. Database Structure

#### Permissions Table
```sql
CREATE TABLE permissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    description TEXT,
    module VARCHAR(255) NOT NULL,
    action VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Role Permissions Table
```sql
CREATE TABLE role_permissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    role VARCHAR(255) NOT NULL,
    permission_id BIGINT NOT NULL,
    is_granted BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_role_permission (role, permission_id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
```

### 2. Middleware Protection

#### CharityMiddleware
- Checks if user is authenticated
- Verifies user has 'charity' role
- Redirects unauthorized users to appropriate dashboard

#### CheckPermission Middleware
- Checks specific permissions for routes
- Supports AJAX and regular requests
- Provides appropriate error responses

### 3. Route Protection

```php
// Example protected route
Route::middleware(['auth', 'charity', 'permission:charity.campaigns.create'])
    ->post('/charity/campaigns', [CharityDashboardController::class, 'storeCampaignAjax'])
    ->name('charity.campaigns.store');
```

### 4. Controller-Level Protection

```php
public function storeCampaignAjax(Request $request)
{
    // Check permission
    if (!Auth::user()->hasPermission('charity.campaigns.create')) {
        return response()->json([
            'success' => false,
            'message' => 'ليس لديك صلاحية لإنشاء حملات جديدة'
        ], 403);
    }
    
    // Continue with logic...
}
```

### 5. View-Level Protection

#### Blade Directives
```blade
@permission('charity.campaigns.create')
    <button class="btn btn-primary">Create Campaign</button>
@endpermission

@anyPermission(['charity.requests.approve', 'charity.requests.reject'])
    <div class="action-buttons">
        <!-- Action buttons -->
    </div>
@endanyPermission

@role('charity')
    <!-- Charity-specific content -->
@endrole
```

#### Helper Functions
```php
// In views or other classes
use App\HasPermissions;

if (HasPermissions::userHasPermission('charity.campaigns.create')) {
    // Show create button
}
```

## Usage Examples

### 1. Protecting Routes
```php
Route::middleware(['auth', 'charity'])->prefix('charity')->group(function () {
    Route::get('/dashboard', [CharityDashboardController::class, 'index'])
        ->name('charity.dashboard')
        ->middleware('permission:charity.dashboard.view');
    
    Route::post('/campaigns', [CharityDashboardController::class, 'storeCampaignAjax'])
        ->name('charity.campaigns.store')
        ->middleware('permission:charity.campaigns.create');
});
```

### 2. Checking Permissions in Controllers
```php
public function approveRequest($num)
{
    if (!Auth::user()->hasPermission('charity.requests.approve')) {
        return response()->json([
            'success' => false,
            'message' => 'ليس لديك صلاحية للموافقة على الطلبات'
        ], 403);
    }
    
    // Process approval...
}
```

### 3. Conditional UI Elements
```blade
@permission('charity.campaigns.create')
    <div class="card">
        <div class="card-header">
            <h5>Create New Campaign</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('charity.campaigns.store') }}" method="POST">
                <!-- Form fields -->
            </form>
        </div>
    </div>
@endpermission

@permission('charity.requests.approve')
    <button class="btn btn-success" onclick="approveRequest({{ $request->id }})">
        <i class="fa fa-check"></i> Approve
    </button>
@endpermission
```

### 4. Dynamic Menu Items
```blade
@permission('charity.campaigns.view')
    <a href="{{ route('charity.campaigns') }}" class="nav-link">
        <i class="fa fa-bullhorn"></i> Campaigns
    </a>
@endpermission

@permission('charity.reports.view')
    <a href="{{ route('charity.reports') }}" class="nav-link">
        <i class="fa fa-chart-bar"></i> Reports
    </a>
@endpermission
```

## Security Features

### 1. Multi-Layer Protection
- **Route Level**: Middleware protection
- **Controller Level**: Permission checks in methods
- **View Level**: Conditional rendering based on permissions

### 2. Error Handling
- Proper HTTP status codes (401, 403)
- Localized error messages in Arabic
- Appropriate redirects for unauthorized access

### 3. AJAX Support
- JSON responses for AJAX requests
- Proper error handling for API calls

### 4. Database Integrity
- Foreign key constraints
- Unique role-permission combinations
- Soft deletion support

## Maintenance

### Adding New Permissions
1. Add permission to `CharityPermissionSeeder`
2. Run seeder: `php artisan db:seed --class=CharityPermissionSeeder`
3. Update routes with new permission middleware
4. Add permission checks in controllers
5. Update views with new Blade directives

### Modifying Permissions
1. Update permission in database
2. Clear application cache: `php artisan cache:clear`
3. Test affected routes and views

### Removing Permissions
1. Remove from seeder
2. Update routes and controllers
3. Remove from views
4. Clean up database entries

## Testing

### Manual Testing
1. Login as charity user
2. Test each protected route
3. Verify unauthorized access is blocked
4. Check error messages and redirects

### Automated Testing
```php
public function test_charity_cannot_access_admin_dashboard()
{
    $charity = User::factory()->create(['role' => 'charity']);
    
    $response = $this->actingAs($charity)
        ->get('/admin/dashboard');
    
    $response->assertStatus(403);
}

public function test_charity_can_create_campaign_with_permission()
{
    $charity = User::factory()->create(['role' => 'charity']);
    // Assign permission...
    
    $response = $this->actingAs($charity)
        ->post('/charity/campaigns', [
            'name' => 'Test Campaign',
            'goal' => 1000
        ]);
    
    $response->assertStatus(200);
}
```

## Best Practices

1. **Always check permissions** at multiple levels
2. **Use descriptive permission names** following the convention
3. **Provide clear error messages** in Arabic
4. **Test thoroughly** after permission changes
5. **Document new permissions** in this file
6. **Use Blade directives** for view-level protection
7. **Cache permission checks** for performance
8. **Regular security audits** of permission assignments

## Troubleshooting

### Common Issues

1. **Permission not working**: Check if permission exists in database
2. **Middleware not registered**: Verify middleware is in Kernel.php
3. **Blade directives not working**: Clear view cache
4. **AJAX errors**: Check CSRF token and headers

### Debug Commands
```bash
# Check registered routes
php artisan route:list --name=charity

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check permissions in database
php artisan tinker
>>> App\Models\Permission::where('name', 'like', 'charity%')->get()
``` 