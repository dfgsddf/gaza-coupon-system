# مخطط قاعدة بيانات نظام كوبونات غزة (محسّن)

## نظرة عامة

تم تحسين هيكل قاعدة البيانات لنظام كوبونات غزة لتحقيق فصل أفضل بين الكيانات المختلفة وتقليل التكرار في البيانات. هذا المخطط يوضح الجداول الرئيسية والعلاقات بينها بعد التحسينات الإضافية.

## مخطط العلاقات (Entity-Relationship Diagram)

```mermaid
erDiagram
    USERS {
        id int PK
        name string
        email string
        password string
        role string
        phone string
        status string
        address string
        email_verified_at timestamp
        remember_token string
        created_at timestamp
        updated_at timestamp
    }

    STORES {
        id int PK
        name string
        store_code string
        email string
        phone string
        address string
        status string
        has_physical_location boolean
        accepts_online_orders boolean
        tax_number string
        commercial_register string
        description text
        store_type string
        location_lat string
        location_lng string
        logo string
        created_at timestamp
        updated_at timestamp
    }
    
    STORE_USER {
        id int PK
        user_id int FK
        store_id int FK
        role string
        is_primary boolean
        created_at timestamp
        updated_at timestamp
    }
    
    ORGANIZATIONS {
        id int PK
        name string
        organization_code string
        type string
        email string
        phone string
        address string
        status string
        registration_date date
        description text
        organization_type string
        logo string
        is_active boolean
        created_at timestamp
        updated_at timestamp
    }
    
    ORGANIZATION_USER {
        id int PK
        user_id int FK
        organization_id int FK
        role string
        is_primary boolean
        created_at timestamp
        updated_at timestamp
    }
    
    CHARITY_ORGANIZATIONS {
        id int PK
        organization_id int FK
        registration_number string
        license_number string
        license_expiry_date date
        mission_statement text
        vision_statement text
        services text
        contact_person string
        website string
        bank_account string
        bank_name string
        created_at timestamp
        updated_at timestamp
    }
    
    BENEFICIARY_PROFILES {
        id int PK
        user_id int FK
        id_number string
        date_of_birth date
        gender string
        marital_status string
        family_members int
        income_level string
        housing_type string
        medical_condition string
        special_needs text
        employment_status string
        profession string
        education_level string
        documents text
        notes text
        verification_status string
        verified_by string
        verified_at timestamp
        created_at timestamp
        updated_at timestamp
    }
    
    REQUESTS {
        id int PK
        user_id int FK
        organization_id int FK
        title string
        category string
        status string
        approved_by int
        approved_at timestamp
        description text
        amount_requested decimal
        created_at timestamp
        updated_at timestamp
    }
    
    COUPONS {
        id int PK
        request_id int FK
        user_id int FK
        store_id int FK
        organization_id int FK
        code string
        coupon_type string
        amount decimal
        expiry_date date
        redeemed boolean
        redeemed_at timestamp
        created_at timestamp
        updated_at timestamp
    }
    
    TRANSACTIONS {
        id int PK
        coupon_id int FK
        beneficiary_id int FK
        store_id int FK
        organization_id int FK
        amount decimal
        transaction_type string
        status string
        created_at timestamp
        updated_at timestamp
    }
    
    CAMPAIGNS {
        id int PK
        organization_id int FK
        title string
        description text
        target_amount decimal
        current_amount decimal
        start_date date
        end_date date
        status string
        created_at timestamp
        updated_at timestamp
    }
    
    CAMPAIGN_DONATIONS {
        id int PK
        campaign_id int FK
        donor_name string
        donor_email string
        amount decimal
        transaction_id string
        status string
        created_at timestamp
        updated_at timestamp
    }
    
    PERMISSIONS {
        id int PK
        name string
        display_name string
        description text
        is_active boolean
        created_at timestamp
        updated_at timestamp
    }
    
    ROLE_PERMISSIONS {
        id int PK
        role string
        permission_id int FK
        is_granted boolean
        created_at timestamp
        updated_at timestamp
    }

    CHARITY_REPORTS {
        id int PK
        charity_id int FK
        title string
        content text
        report_date date
        created_at timestamp
        updated_at timestamp
    }

    USERS ||--o{ STORE_USER : "has many"
    USERS ||--o{ ORGANIZATION_USER : "has many"
    USERS ||--o{ REQUESTS : "makes"
    USERS ||--o{ COUPONS : "receives"
    USERS ||--o{ TRANSACTIONS : "as beneficiary"
    USERS ||--o| BENEFICIARY_PROFILES : "has one"
    
    STORES ||--o{ STORE_USER : "has many"
    STORES ||--o{ COUPONS : "provides"
    STORES ||--o{ TRANSACTIONS : "processes"
    
    ORGANIZATIONS ||--o{ ORGANIZATION_USER : "has many"
    ORGANIZATIONS ||--o| CHARITY_ORGANIZATIONS : "may be"
    ORGANIZATIONS ||--o{ CAMPAIGNS : "runs"
    ORGANIZATIONS ||--o{ REQUESTS : "receives"
    ORGANIZATIONS ||--o{ COUPONS : "issues"
    ORGANIZATIONS ||--o{ TRANSACTIONS : "manages"
    
    CHARITY_ORGANIZATIONS ||--o{ CHARITY_REPORTS : "publishes"
    
    PERMISSIONS ||--o{ ROLE_PERMISSIONS : "assigned to"
    
    REQUESTS ||--o{ COUPONS : "generates"
    
    COUPONS ||--o| TRANSACTIONS : "results in"
    
    CAMPAIGNS ||--o{ CAMPAIGN_DONATIONS : "receives"
```

## تفاصيل التحسينات المنفذة لتقليل التكرار في البيانات

### 1. تحسين جدول المتاجر (`stores`)
- إضافة حقل `store_code` كمعرف فريد للمتجر
- إضافة حقول خاصة بالمتجر كمؤسسة:
  - `has_physical_location`: هل المتجر له موقع فعلي
  - `accepts_online_orders`: هل يقبل طلبات أونلاين
  - `tax_number`: الرقم الضريبي للمتجر
  - `commercial_register`: رقم السجل التجاري
- اعتماد علاقة المتجر بالمستخدمين عبر جدول `store_user` حيث يمكن استرجاع بيانات الاتصال من المستخدم الرئيسي

### 2. تحسين العلاقة بين الكوبونات والمعاملات
- ضمان أن لكل معاملة كوبون واحد فقط
- ربط المعاملات بالكوبونات المناسبة
- تحديث حالة الكوبونات المستخدمة

### 3. تحسين جدول المنظمات (`organizations`)
- إضافة حقل `organization_code` كمعرف فريد للمنظمة
- الاعتماد على العلاقات لتقليل تكرار البيانات مع جدول المستخدمين

### 4. تحسين جدول الطلبات (`requests`)
- إضافة حقل `organization_id` لتحديد الجهة المرسل إليها الطلب
- إضافة حقل `category` لتصنيف الطلبات
- إضافة حقل `amount_requested` لتحديد المبلغ المطلوب

### 5. تحسين جدول الكوبونات (`coupons`)
- إضافة حقل `organization_id` للمنظمة المصدرة للكوبون
- إضافة حقل `coupon_type` لتحديد نوع الكوبون (قياسي، خاص، هدية)

### 6. تحسين جدول المعاملات (`transactions`)
- إضافة حقل `transaction_type` لتصنيف المعاملة
- إضافة حقل `organization_id` للمنظمة المرتبطة بالمعاملة

## الفوائد الرئيسية للتحسينات الجديدة

1. **تقليل تكرار البيانات**: فصل واضح بين بيانات المستخدمين وبيانات المؤسسات (متاجر ومنظمات)
2. **علاقات أكثر دقة**: توضيح العلاقات بين الكيانات المختلفة (مثل المنظمات والطلبات والكوبونات)
3. **تحسين استخدام البيانات**: إضافة حقول تصنيفية للطلبات والمعاملات والكوبونات
4. **زيادة المرونة**: دعم أنواع مختلفة من المعاملات والكوبونات
5. **تعزيز سلامة البيانات**: ضمان اتساق العلاقات بين الجداول المختلفة

هذه التحسينات تساهم في جعل قاعدة البيانات أكثر كفاءة وأقل تكرارًا، مما يسهّل عمليات البحث والتحليل والتقارير ويحسن أداء النظام بشكل عام. 