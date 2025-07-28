<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define admin permissions
        $adminPermissions = [
            // Dashboard permissions
            [
                'name' => 'admin.dashboard.view',
                'display_name' => 'عرض لوحة التحكم',
                'description' => 'إمكانية عرض لوحة تحكم المشرف',
                'module' => 'dashboard',
                'action' => 'view'
            ],
            // User management permissions
            [
                'name' => 'admin.users.manage',
                'display_name' => 'إدارة المستخدمين',
                'description' => 'إمكانية إدارة المستخدمين',
                'module' => 'users',
                'action' => 'manage'
            ],
            // Contact management permissions
            [
                'name' => 'admin.contacts.manage',
                'display_name' => 'إدارة رسائل الاتصال',
                'description' => 'إمكانية إدارة رسائل الاتصال',
                'module' => 'contacts',
                'action' => 'manage'
            ],
            // Settings permissions
            [
                'name' => 'admin.settings.manage',
                'display_name' => 'إدارة الإعدادات',
                'description' => 'إمكانية إدارة إعدادات النظام',
                'module' => 'settings',
                'action' => 'manage'
            ],
            // Organization permissions
            [
                'name' => 'admin.organizations.manage',
                'display_name' => 'إدارة المنظمات',
                'description' => 'إمكانية إدارة المنظمات',
                'module' => 'organizations',
                'action' => 'manage'
            ],
            // Store permissions
            [
                'name' => 'admin.stores.manage',
                'display_name' => 'إدارة المتاجر',
                'description' => 'إمكانية إدارة المتاجر',
                'module' => 'stores',
                'action' => 'manage'
            ],
        ];

        // Define store permissions
        $storePermissions = [
            // Dashboard permissions
            [
                'name' => 'store.dashboard.view',
                'display_name' => 'عرض لوحة التحكم',
                'description' => 'إمكانية عرض لوحة تحكم المتجر',
                'module' => 'dashboard',
                'action' => 'view'
            ],
            // Coupon permissions
            [
                'name' => 'store.coupons.manage',
                'display_name' => 'إدارة القسائم',
                'description' => 'إمكانية إدارة القسائم',
                'module' => 'coupons',
                'action' => 'manage'
            ],
            // Transaction permissions
            [
                'name' => 'store.transactions.view',
                'display_name' => 'عرض المعاملات',
                'description' => 'إمكانية عرض المعاملات',
                'module' => 'transactions',
                'action' => 'view'
            ],
            // Reports permissions
            [
                'name' => 'store.reports.view',
                'display_name' => 'عرض التقارير',
                'description' => 'إمكانية عرض التقارير',
                'module' => 'reports',
                'action' => 'view'
            ],
            // Settings permissions
            [
                'name' => 'store.settings.manage',
                'display_name' => 'إدارة الإعدادات',
                'description' => 'إمكانية إدارة إعدادات المتجر',
                'module' => 'settings',
                'action' => 'manage'
            ],
            // Store list permissions
            [
                'name' => 'store.list.view',
                'display_name' => 'عرض قائمة المتاجر',
                'description' => 'إمكانية عرض قائمة المتاجر',
                'module' => 'stores',
                'action' => 'view'
            ],
        ];

        // Define beneficiary permissions
        $beneficiaryPermissions = [
            // Dashboard permissions
            [
                'name' => 'beneficiary.dashboard.view',
                'display_name' => 'عرض لوحة التحكم',
                'description' => 'إمكانية عرض لوحة تحكم المستفيد',
                'module' => 'dashboard',
                'action' => 'view'
            ],
            // Settings permissions
            [
                'name' => 'beneficiary.settings.manage',
                'display_name' => 'إدارة الإعدادات',
                'description' => 'إمكانية إدارة إعدادات المستفيد',
                'module' => 'settings',
                'action' => 'manage'
            ],
            // Request permissions
            [
                'name' => 'beneficiary.requests.manage',
                'display_name' => 'إدارة الطلبات',
                'description' => 'إمكانية إدارة الطلبات',
                'module' => 'requests',
                'action' => 'manage'
            ],
            // Coupon permissions
            [
                'name' => 'beneficiary.coupons.view',
                'display_name' => 'عرض القسائم',
                'description' => 'إمكانية عرض القسائم',
                'module' => 'coupons',
                'action' => 'view'
            ],
        ];

        // Combine all permissions
        $allPermissions = array_merge($adminPermissions, $storePermissions, $beneficiaryPermissions);

        // Create all permissions
        foreach ($allPermissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                array_merge($permissionData, ['is_active' => true])
            );
        }

        // Assign admin permissions to admin role
        $this->assignPermissions('admin', $adminPermissions);

        // Assign store permissions to store role
        $this->assignPermissions('store', $storePermissions);

        // Assign beneficiary permissions to beneficiary role
        $this->assignPermissions('beneficiary', $beneficiaryPermissions);

        $this->command->info('All permissions created and assigned successfully!');
        $this->command->info('Total permissions created: ' . count($allPermissions));
    }

    /**
     * Assign permissions to a specific role
     */
    private function assignPermissions(string $role, array $permissions): void
    {
        $permissionNames = array_column($permissions, 'name');
        $permissionModels = Permission::whereIn('name', $permissionNames)->get();
        
        foreach ($permissionModels as $permission) {
            RolePermission::firstOrCreate(
                [
                    'role' => $role,
                    'permission_id' => $permission->id
                ],
                [
                    'role' => $role,
                    'permission_id' => $permission->id,
                    'is_granted' => true
                ]
            );
        }

        $this->command->info("Permissions assigned to {$role} role: " . count($permissionModels));
    }
} 