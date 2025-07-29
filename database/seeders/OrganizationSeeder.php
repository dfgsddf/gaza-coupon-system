<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'جمعية الإغاثة الإسلامية',
                'organization_code' => 'ORG001',
                'type' => 'charity',
                'email' => 'info@islamic-relief.ps',
                'phone' => '+970-59-123-4567',
                'address' => 'شارع الوحدة، غزة، فلسطين',
                'status' => 'active',
                'registration_date' => '2020-01-15',
                'description' => 'جمعية خيرية تقدم المساعدات الإنسانية والتنموية للمحتاجين في قطاع غزة',
                'organization_type' => 'charity',
                'logo' => null,
                'is_active' => true,
            ],
            [
                'name' => 'مؤسسة الأمل للتنمية',
                'organization_code' => 'ORG002',
                'type' => 'foundation',
                'email' => 'contact@hope-foundation.ps',
                'phone' => '+970-59-234-5678',
                'address' => 'شارع الرشيد، غزة، فلسطين',
                'status' => 'active',
                'registration_date' => '2019-06-20',
                'description' => 'مؤسسة تنموية تعمل على تحسين حياة الأسر المحتاجة من خلال برامج التنمية المستدامة',
                'organization_type' => 'foundation',
                'logo' => null,
                'is_active' => true,
            ],
            [
                'name' => 'منظمة العون الإنساني',
                'organization_code' => 'ORG003',
                'type' => 'ngo',
                'email' => 'info@humanitarian-aid.ps',
                'phone' => '+970-59-345-6789',
                'address' => 'شارع صلاح الدين، غزة، فلسطين',
                'status' => 'pending',
                'registration_date' => '2023-03-10',
                'description' => 'منظمة غير حكومية تقدم المساعدات الإنسانية العاجلة للمتضررين من الأزمات',
                'organization_type' => 'ngo',
                'logo' => null,
                'is_active' => true,
            ],
            [
                'name' => 'جمعية الخير للرعاية الاجتماعية',
                'organization_code' => 'ORG004',
                'type' => 'charity',
                'email' => 'info@charity-care.ps',
                'phone' => '+970-59-456-7890',
                'address' => 'شارع النصر، غزة، فلسطين',
                'status' => 'active',
                'registration_date' => '2018-11-05',
                'description' => 'جمعية خيرية متخصصة في الرعاية الاجتماعية للأسر المحتاجة والأيتام',
                'organization_type' => 'charity',
                'logo' => null,
                'is_active' => true,
            ],
            [
                'name' => 'مؤسسة المستقبل للتنمية',
                'organization_code' => 'ORG005',
                'type' => 'foundation',
                'email' => 'contact@future-dev.ps',
                'phone' => '+970-59-567-8901',
                'address' => 'شارع الشهداء، غزة، فلسطين',
                'status' => 'suspended',
                'registration_date' => '2021-09-15',
                'description' => 'مؤسسة تنموية تعمل على بناء مستقبل أفضل للأجيال القادمة',
                'organization_type' => 'foundation',
                'logo' => null,
                'is_active' => false,
            ],
            [
                'name' => 'جمعية التضامن الإنساني',
                'organization_code' => 'ORG006',
                'type' => 'charity',
                'email' => 'info@human-solidarity.ps',
                'phone' => '+970-59-678-9012',
                'address' => 'شارع الوحدة، غزة، فلسطين',
                'status' => 'active',
                'registration_date' => '2022-04-12',
                'description' => 'جمعية خيرية تعمل على تعزيز التضامن الإنساني ومساعدة المحتاجين',
                'organization_type' => 'charity',
                'logo' => null,
                'is_active' => true,
            ],
            [
                'name' => 'منظمة الأمل للتنمية المستدامة',
                'organization_code' => 'ORG007',
                'type' => 'ngo',
                'email' => 'contact@hope-sustainable.ps',
                'phone' => '+970-59-789-0123',
                'address' => 'شارع الرشيد، غزة، فلسطين',
                'status' => 'pending',
                'registration_date' => '2023-07-08',
                'description' => 'منظمة غير حكومية تعمل على تحقيق التنمية المستدامة في المجتمع',
                'organization_type' => 'ngo',
                'logo' => null,
                'is_active' => true,
            ],
            [
                'name' => 'جمعية الرحمة الخيرية',
                'organization_code' => 'ORG008',
                'type' => 'charity',
                'email' => 'info@mercy-charity.ps',
                'phone' => '+970-59-890-1234',
                'address' => 'شارع صلاح الدين، غزة، فلسطين',
                'status' => 'active',
                'registration_date' => '2020-12-03',
                'description' => 'جمعية خيرية تقدم خدمات الرعاية والمساعدات للمحتاجين',
                'organization_type' => 'charity',
                'logo' => null,
                'is_active' => true,
            ],
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
} 