<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;

class CharityPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all charity permissions
        $charityPermissions = [
            // Dashboard permissions
            [
                'name' => 'charity.dashboard.view',
                'display_name' => 'عرض لوحة التحكم',
                'description' => 'إمكانية عرض لوحة التحكم الخاصة بالجمعية الخيرية',
                'module' => 'dashboard',
                'action' => 'view'
            ],
            [
                'name' => 'charity.dashboard.stats',
                'display_name' => 'عرض الإحصائيات',
                'description' => 'إمكانية عرض إحصائيات لوحة التحكم',
                'module' => 'dashboard',
                'action' => 'stats'
            ],

            // Campaign permissions
            [
                'name' => 'charity.campaigns.view',
                'display_name' => 'عرض الحملات',
                'description' => 'إمكانية عرض قائمة الحملات',
                'module' => 'campaigns',
                'action' => 'view'
            ],
            [
                'name' => 'charity.campaigns.create',
                'display_name' => 'إنشاء حملة',
                'description' => 'إمكانية إنشاء حملة جديدة',
                'module' => 'campaigns',
                'action' => 'create'
            ],
            [
                'name' => 'charity.campaigns.edit',
                'display_name' => 'تعديل الحملات',
                'description' => 'إمكانية تعديل الحملات الموجودة',
                'module' => 'campaigns',
                'action' => 'edit'
            ],
            [
                'name' => 'charity.campaigns.delete',
                'display_name' => 'حذف الحملات',
                'description' => 'إمكانية حذف الحملات',
                'module' => 'campaigns',
                'action' => 'delete'
            ],
            [
                'name' => 'charity.campaigns.manage',
                'display_name' => 'إدارة الحملات',
                'description' => 'إمكانية إدارة جميع جوانب الحملات',
                'module' => 'campaigns',
                'action' => 'manage'
            ],

            // Request permissions
            [
                'name' => 'charity.requests.view',
                'display_name' => 'عرض الطلبات',
                'description' => 'إمكانية عرض قائمة الطلبات',
                'module' => 'requests',
                'action' => 'view'
            ],
            [
                'name' => 'charity.requests.details',
                'display_name' => 'تفاصيل الطلبات',
                'description' => 'إمكانية عرض تفاصيل الطلبات',
                'module' => 'requests',
                'action' => 'details'
            ],
            [
                'name' => 'charity.requests.approve',
                'display_name' => 'الموافقة على الطلبات',
                'description' => 'إمكانية الموافقة على الطلبات',
                'module' => 'requests',
                'action' => 'approve'
            ],
            [
                'name' => 'charity.requests.reject',
                'display_name' => 'رفض الطلبات',
                'description' => 'إمكانية رفض الطلبات',
                'module' => 'requests',
                'action' => 'reject'
            ],
            [
                'name' => 'charity.requests.manage',
                'display_name' => 'إدارة الطلبات',
                'description' => 'إمكانية إدارة جميع جوانب الطلبات',
                'module' => 'requests',
                'action' => 'manage'
            ],

            // Reports permissions
            [
                'name' => 'charity.reports.view',
                'display_name' => 'عرض التقارير',
                'description' => 'إمكانية عرض التقارير',
                'module' => 'reports',
                'action' => 'view'
            ],
            [
                'name' => 'charity.reports.generate',
                'display_name' => 'إنشاء التقارير',
                'description' => 'إمكانية إنشاء تقارير جديدة',
                'module' => 'reports',
                'action' => 'generate'
            ],
            [
                'name' => 'charity.reports.export',
                'display_name' => 'تصدير التقارير',
                'description' => 'إمكانية تصدير التقارير',
                'module' => 'reports',
                'action' => 'export'
            ],

            // Settings permissions
            [
                'name' => 'charity.settings.view',
                'display_name' => 'عرض الإعدادات',
                'description' => 'إمكانية عرض إعدادات الجمعية الخيرية',
                'module' => 'settings',
                'action' => 'view'
            ],
            [
                'name' => 'charity.settings.edit',
                'display_name' => 'تعديل الإعدادات',
                'description' => 'إمكانية تعديل إعدادات الجمعية الخيرية',
                'module' => 'settings',
                'action' => 'edit'
            ],

            // User management permissions
            [
                'name' => 'charity.users.view',
                'display_name' => 'عرض المستخدمين',
                'description' => 'إمكانية عرض قائمة المستخدمين المرتبطين بالجمعية',
                'module' => 'users',
                'action' => 'view'
            ],
            [
                'name' => 'charity.users.manage',
                'display_name' => 'إدارة المستخدمين',
                'description' => 'إمكانية إدارة المستخدمين المرتبطين بالجمعية',
                'module' => 'users',
                'action' => 'manage'
            ],

            // Financial permissions
            [
                'name' => 'charity.financial.view',
                'display_name' => 'عرض البيانات المالية',
                'description' => 'إمكانية عرض البيانات المالية للجمعية',
                'module' => 'financial',
                'action' => 'view'
            ],
            [
                'name' => 'charity.financial.manage',
                'display_name' => 'إدارة البيانات المالية',
                'description' => 'إمكانية إدارة البيانات المالية للجمعية',
                'module' => 'financial',
                'action' => 'manage'
            ],

            // Analytics permissions
            [
                'name' => 'charity.analytics.view',
                'display_name' => 'عرض التحليلات',
                'description' => 'إمكانية عرض تحليلات الأداء',
                'module' => 'analytics',
                'action' => 'view'
            ],
            [
                'name' => 'charity.analytics.export',
                'display_name' => 'تصدير التحليلات',
                'description' => 'إمكانية تصدير بيانات التحليلات',
                'module' => 'analytics',
                'action' => 'export'
            ],
        ];

        // Create permissions
        foreach ($charityPermissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Assign all permissions to charity role
        $permissions = Permission::whereIn('name', array_column($charityPermissions, 'name'))->get();
        
        foreach ($permissions as $permission) {
            RolePermission::firstOrCreate(
                [
                    'role' => 'charity',
                    'permission_id' => $permission->id
                ],
                [
                    'role' => 'charity',
                    'permission_id' => $permission->id,
                    'is_granted' => true
                ]
            );
        }

        $this->command->info('Charity permissions created and assigned successfully!');
        $this->command->info('Total permissions created: ' . count($charityPermissions));
    }
}
