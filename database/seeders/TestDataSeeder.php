<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Store;
use App\Models\Organization;
use App\Models\CharityOrganization;
use App\Models\BeneficiaryProfile;
use App\Models\Beneficiary;
use App\Models\RequestModel;
use App\Models\Coupon;
use App\Models\Transaction;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Permission;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 بدء إنشاء البيانات الاختبارية...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. إنشاء المستخدمين الأساسيين
        $this->createBasicUsers();
        
        // 2. إنشاء الأذونات
        $this->createPermissions();
        
        // 3. إنشاء المنظمات والمؤسسات الخيرية
        $this->createOrganizations();
        
        // 4. إنشاء المتاجر
        $this->createStores();
        
        // 5. إنشاء الحملات
        $this->createCampaigns();
        
        // 6. إنشاء المستفيدين وملفاتهم
        $this->createBeneficiaries();
        
        // 7. إنشاء الطلبات
        $this->createRequests();
        
        // 8. إنشاء الكوبونات
        $this->createCoupons();
        
        // 9. إنشاء المعاملات
        $this->createTransactions();
        
        // 10. إنشاء رسائل الاتصال
        $this->createContactMessages();
        
        // 11. إنشاء العلاقات
        $this->createRelationships();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ تم إنشاء جميع البيانات الاختبارية بنجاح!');
    }

    private function createBasicUsers(): void
    {
        $this->command->info('📝 إنشاء المستخدمين الأساسيين...');

        // مستخدم الإدارة الرئيسي
        User::updateOrCreate(
            ['email' => 'admin@gaza-coupon.com'],
            [
                'name' => 'مدير النظام',
                'role' => 'admin',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        // مديرين إضافيين - فقط إذا لم يكن لديهم أدمن كافي
        $existingAdmins = User::where('role', 'admin')->count();
        if ($existingAdmins < 4) {
            $neededAdmins = 4 - $existingAdmins;
            User::factory($neededAdmins)->create([
                'role' => 'admin',
                'status' => 'active',
            ]);
        }

        $this->command->info('✓ تم إنشاء ' . User::where('role', 'admin')->count() . ' مديرين');
    }

    private function createPermissions(): void
    {
        $this->command->info('🔐 إنشاء الأذونات...');

        $permissions = [
            'admin.dashboard.view',
            'admin.users.manage',
            'admin.stores.manage',
            'admin.organizations.manage',
            'admin.beneficiaries.manage',
            'admin.coupons.manage',
            'admin.transactions.view',
            'admin.reports.view',
            'admin.settings.manage',
            'store.dashboard.view',
            'store.transactions.manage',
            'store.coupons.redeem',
            'beneficiary.dashboard.view',
            'beneficiary.requests.create',
            'beneficiary.coupons.view',
            'charity.dashboard.view',
            'charity.campaigns.manage',
            'charity.requests.manage',
            'charity.reports.create',
        ];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $module = $parts[0]; // admin, store, beneficiary, charity
            $action = isset($parts[2]) ? $parts[2] : 'general'; // view, manage, create, etc.
            
            Permission::firstOrCreate([
                'name' => $permission,
                'display_name' => $permission,
                'module' => $module,
                'action' => $action,
                'description' => "إذن $permission",
            ]);
        }

        $this->command->info('✓ تم إنشاء ' . count($permissions) . ' إذن');
    }

    private function createOrganizations(): void
    {
        $this->command->info('🏢 إنشاء المنظمات والمؤسسات الخيرية...');

        // منظمات خيرية
        $charityOrgs = Organization::factory(8)->charity()->active()->create();
        
        // مؤسسات حكومية
        $govOrgs = Organization::factory(3)->government()->active()->create();
        
        // منظمات غير ربحية
        $nonProfitOrgs = Organization::factory(4)->nonProfit()->active()->create();
        
        // منظمات دولية
        $intlOrgs = Organization::factory(2)->international()->active()->create();

        // إنشاء تفاصيل المؤسسات الخيرية
        foreach ($charityOrgs as $org) {
            CharityOrganization::factory()->create([
                'organization_id' => $org->id,
            ]);
        }

        // إنشاء بعض المؤسسات الخيرية مع تراخيص منتهية أو قريبة الانتهاء
        $expiredCharities = Organization::factory(2)->charity()->create();
        foreach ($expiredCharities as $org) {
            CharityOrganization::factory()->withExpiredLicense()->create([
                'organization_id' => $org->id,
            ]);
        }

        $expiringSoonCharities = Organization::factory(3)->charity()->create();
        foreach ($expiringSoonCharities as $org) {
            CharityOrganization::factory()->withExpiringSoonLicense()->create([
                'organization_id' => $org->id,
            ]);
        }

        $this->command->info('✓ تم إنشاء ' . Organization::count() . ' منظمة');
        $this->command->info('✓ تم إنشاء ' . CharityOrganization::count() . ' مؤسسة خيرية');
    }

    private function createStores(): void
    {
        $this->command->info('🏪 إنشاء المتاجر...');

        // متاجر بقالة
        Store::factory(8)->grocery()->active()->withPhysicalLocation()->create();
        
        // صيدليات
        Store::factory(6)->pharmacy()->active()->withPhysicalLocation()->create();
        
        // مطاعم
        Store::factory(5)->restaurant()->active()->withOnlineOrders()->create();
        
        // متاجر متنوعة
        Store::factory(10)->active()->create();
        
        // متاجر غير نشطة
        Store::factory(3)->inactive()->create();

        $this->command->info('✓ تم إنشاء ' . Store::count() . ' متجر');
    }

    private function createBeneficiaries(): void
    {
        $this->command->info('👥 إنشاء المستفيدين وملفاتهم...');

        // مستخدمين مستفيدين
        $beneficiaryUsers = User::factory(50)->asBeneficiary()->create();

        foreach ($beneficiaryUsers as $user) {
            // إنشاء في النظام القديم (للكوبونات والمعاملات)
            Beneficiary::factory()->create([
                'user_id' => $user->id,
            ]);
            
            // إنشاء في النظام الجديد (للتفاصيل المحسنة)
            BeneficiaryProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // مستفيدين بحالات خاصة
        $specialCases = User::factory(15)->asBeneficiary()->create();

        foreach ($specialCases as $user) {
            // النظام القديم
            Beneficiary::factory()->create(['user_id' => $user->id]);
            
            // النظام الجديد مع حالات خاصة
            $profileType = rand(1, 4);
            switch ($profileType) {
                case 1:
                    BeneficiaryProfile::factory()->largeFamily()->verified()->create(['user_id' => $user->id]);
                    break;
                case 2:
                    BeneficiaryProfile::factory()->withSpecialNeeds()->verified()->create(['user_id' => $user->id]);
                    break;
                case 3:
                    BeneficiaryProfile::factory()->elderly()->verified()->create(['user_id' => $user->id]);
                    break;
                case 4:
                    BeneficiaryProfile::factory()->singleMother()->pending()->create(['user_id' => $user->id]);
                    break;
            }
        }

        // مستفيدين في حالة انتظار أو مرفوضين
        $pendingUsers = User::factory(10)->asBeneficiary()->create();
        foreach ($pendingUsers as $user) {
            Beneficiary::factory()->create(['user_id' => $user->id]);
            BeneficiaryProfile::factory()->pending()->create(['user_id' => $user->id]);
        }

        $rejectedUsers = User::factory(5)->asBeneficiary()->create();
        foreach ($rejectedUsers as $user) {
            Beneficiary::factory()->create(['user_id' => $user->id]);
            BeneficiaryProfile::factory()->rejected()->create(['user_id' => $user->id]);
        }

        $this->command->info('✓ تم إنشاء ' . User::where('role', 'beneficiary')->count() . ' مستفيد');
        $this->command->info('✓ تم إنشاء ' . Beneficiary::count() . ' ملف مستفيد (النظام القديم)');
        $this->command->info('✓ تم إنشاء ' . BeneficiaryProfile::count() . ' ملف مستفيد (النظام الجديد)');
    }

    private function createRequests(): void
    {
        $this->command->info('📋 إنشاء الطلبات...');

        $beneficiaries = User::where('role', 'beneficiary')->pluck('id')->toArray();
        $organizations = Organization::pluck('id')->toArray();

        // طلبات متنوعة
        foreach ($beneficiaries as $beneficiaryId) {
            $requestCount = rand(1, 4);
            for ($i = 0; $i < $requestCount; $i++) {
                $requestType = rand(1, 5);
                switch ($requestType) {
                    case 1:
                        RequestModel::factory()->foodAssistance()->create([
                            'user_id' => $beneficiaryId,
                        ]);
                        break;
                    case 2:
                        RequestModel::factory()->medicalAssistance()->create([
                            'user_id' => $beneficiaryId,
                        ]);
                        break;
                    case 3:
                        RequestModel::factory()->housingAssistance()->create([
                            'user_id' => $beneficiaryId,
                        ]);
                        break;
                    case 4:
                        RequestModel::factory()->educationalAssistance()->create([
                            'user_id' => $beneficiaryId,
                        ]);
                        break;
                    case 5:
                        RequestModel::factory()->urgent()->create([
                            'user_id' => $beneficiaryId,
                        ]);
                        break;
                }
            }
        }

        $this->command->info('✓ تم إنشاء ' . RequestModel::count() . ' طلب');
    }

    private function createCoupons(): void
    {
        $this->command->info('🎫 إنشاء الكوبونات...');

        $organizations = Organization::pluck('id')->toArray();
        $campaigns = Campaign::pluck('id')->toArray();

        // كوبونات متنوعة
        Coupon::factory(100)->create([
            'organization_id' => function() use ($organizations) {
                return $this->getRandomElement($organizations);
            },
            'campaign_id' => function() use ($campaigns) {
                return $this->getRandomElement($campaigns);
            },
        ]);

        // كوبونات عالية القيمة
        Coupon::factory(20)->highValue()->create([
            'organization_id' => function() use ($organizations) {
                return $this->getRandomElement($organizations);
            },
            'campaign_id' => function() use ($campaigns) {
                return $this->getRandomElement($campaigns);
            },
        ]);

        // كوبونات منتهية الصلاحية
        Coupon::factory(30)->expired()->create([
            'organization_id' => function() use ($organizations) {
                return $this->getRandomElement($organizations);
            },
            'campaign_id' => function() use ($campaigns) {
                return $this->getRandomElement($campaigns);
            },
        ]);

        $this->command->info('✓ تم إنشاء ' . Coupon::count() . ' كوبون');
    }

    private function createTransactions(): void
    {
        $this->command->info('💳 إنشاء المعاملات...');

        $beneficiaries = User::where('role', 'beneficiary')->pluck('id')->toArray();
        $storeUsers = User::where('role', 'store')->pluck('id')->toArray();
        $organizations = Organization::pluck('id')->toArray();
        $coupons = Coupon::pluck('id')->toArray();

        // معاملات استبدال كوبونات مكتملة
        Transaction::factory(100)->couponRedemption()->completed()->create([
            'beneficiary_id' => function() use ($beneficiaries) {
                return $this->getRandomElement($beneficiaries);
            },
            'store_id' => function() use ($storeUsers) {
                return $this->getRandomElement($storeUsers);
            },
            'organization_id' => function() use ($organizations) {
                return $this->getRandomElement($organizations);
            },
            'coupon_id' => function() use ($coupons) {
                return $this->getRandomElement($coupons);
            },
        ]);

        // معاملات في انتظار
        Transaction::factory(20)->pending()->create([
            'beneficiary_id' => function() use ($beneficiaries) {
                return $this->getRandomElement($beneficiaries);
            },
            'store_id' => function() use ($storeUsers) {
                return $this->getRandomElement($storeUsers);
            },
            'organization_id' => function() use ($organizations) {
                return $this->getRandomElement($organizations);
            },
            'coupon_id' => function() use ($coupons) {
                return $this->getRandomElement($coupons);
            },
        ]);

        // تبرعات
        Transaction::factory(30)->donation()->completed()->create([
            'beneficiary_id' => null,
            'organization_id' => function() use ($organizations) {
                return $this->getRandomElement($organizations);
            },
        ]);

        $this->command->info('✓ تم إنشاء ' . Transaction::count() . ' معاملة');
    }

    private function createCampaigns(): void
    {
        $this->command->info('📢 إنشاء الحملات...');

        $charityUsers = User::where('role', 'charity')->pluck('id')->toArray();

        // التأكد من وجود مستخدمين خيريين
        if (empty($charityUsers)) {
            $charityUsers = User::factory(5)->asCharity()->create()->pluck('id')->toArray();
        }

        // حملات نشطة
        Campaign::factory(15)->active()->create([
            'charity_id' => function() use ($charityUsers) {
                return $this->getRandomElement($charityUsers);
            },
        ]);

        // حملات مكتملة
        Campaign::factory(10)->completed()->create([
            'charity_id' => function() use ($charityUsers) {
                return $this->getRandomElement($charityUsers);
            },
        ]);

        // حملات متنوعة
        Campaign::factory(20)->create([
            'charity_id' => function() use ($charityUsers) {
                return $this->getRandomElement($charityUsers);
            },
        ]);

        $this->command->info('✓ تم إنشاء ' . Campaign::count() . ' حملة');
    }

    private function createContactMessages(): void
    {
        $this->command->info('📧 إنشاء رسائل الاتصال...');

        // رسائل غير مقروءة
        ContactMessage::factory(20)->unread()->create();
        
        // رسائل مقروءة
        ContactMessage::factory(30)->read()->create();
        
        // رسائل تم الرد عليها
        ContactMessage::factory(25)->replied()->create();

        $this->command->info('✓ تم إنشاء ' . ContactMessage::count() . ' رسالة اتصال');
    }

    private function createRelationships(): void
    {
        $this->command->info('🔗 إنشاء العلاقات بين الجداول...');

        // ربط المستخدمين بالمتاجر (النظام الجديد)
        $stores = Store::all();
        $storeUsers = User::factory(20)->asStoreUser()->create();

        foreach ($storeUsers as $user) {
            $store = $stores->random();
            try {
                $store->addUser($user->id, 'owner', true);
            } catch (\Exception $e) {
                // في حالة فشل العملية، نتجاهلها لمتابعة إنشاء البيانات
                $this->command->warn("تحذير: فشل في ربط المستخدم {$user->id} بالمتجر {$store->id}");
            }
        }

        // إضافة مستخدمين إضافيين للمتاجر
        foreach ($stores->take(10) as $store) {
            $additionalUsers = User::factory(rand(1, 2))->asStoreUser()->create();
            foreach ($additionalUsers as $user) {
                try {
                    $role = ['manager', 'employee'][rand(0, 1)];
                    $store->addUser($user->id, $role, false);
                } catch (\Exception $e) {
                    // في حالة فشل العملية، نتجاهلها
                }
            }
        }

        // ربط المستخدمين بالمنظمات (النظام الجديد)
        $organizations = Organization::all();
        $orgUsers = User::factory(15)->asCharity()->create();

        foreach ($orgUsers as $user) {
            $organization = $organizations->random();
            try {
                $organization->addUser($user->id, 'admin', true);
            } catch (\Exception $e) {
                // في حالة فشل العملية، نتجاهلها
                $this->command->warn("تحذير: فشل في ربط المستخدم {$user->id} بالمنظمة {$organization->id}");
            }
        }

        // إضافة أعضاء للمنظمات
        foreach ($organizations->take(8) as $organization) {
            $members = User::factory(rand(2, 4))->asCharity()->create();
            foreach ($members as $user) {
                try {
                    $role = ['member', 'volunteer'][rand(0, 1)];
                    $organization->addUser($user->id, $role, false);
                } catch (\Exception $e) {
                    // في حالة فشل العملية، نتجاهلها
                }
            }
        }

        $this->command->info('✓ تم إنشاء العلاقات بين المستخدمين والمتاجر والمنظمات');
    }

    private function getRandomElement(array $array)
    {
        return $array[array_rand($array)];
    }
} 