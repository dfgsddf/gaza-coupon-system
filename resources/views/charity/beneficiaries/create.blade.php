@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4 text-center">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass me-2"></i> Campaigns</a>
        <a href="{{ route('charity.beneficiaries') }}" class="d-block text-white mb-3 active-link"><i class="fa-solid fa-users me-2"></i> Beneficiaries</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request me-2"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3"><i class="fa-solid fa-book-open me-2"></i> Reports</a>
        <a href="{{ route('charity.settings') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gear me-2"></i> Settings</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="button" class="btn btn-link text-white text-start p-0 w-100" onclick="if(confirm('Are you sure?')){$('#logout-form').submit();}">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-white mb-2">إضافة مستفيد جديد</h2>
                    <p class="text-light mb-0">إضافة مستفيد جديد للجمعية الخيرية</p>
                </div>
                <div>
                    <a href="{{ route('charity.beneficiaries.index') }}" class="btn btn-outline-light">
                        <i class="fa-solid fa-arrow-left me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-user-plus me-2"></i>
                        نموذج إضافة مستفيد جديد
                    </h5>
                </div>
                <div class="card-body">
                    <form id="addBeneficiaryForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fa-solid fa-user me-2"></i>
                                    المعلومات الشخصية
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <div class="invalid-feedback" id="phone-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="id_number" class="form-label">رقم الهوية *</label>
                                <input type="text" class="form-control" id="id_number" name="id_number" required>
                                <div class="invalid-feedback" id="id_number-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">تاريخ الميلاد *</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                <div class="invalid-feedback" id="date_of_birth-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">الجنس *</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">اختر الجنس</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                                <div class="invalid-feedback" id="gender-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="marital_status" class="form-label">الحالة الاجتماعية *</label>
                                <select class="form-select" id="marital_status" name="marital_status" required>
                                    <option value="">اختر الحالة</option>
                                    <option value="single">أعزب</option>
                                    <option value="married">متزوج</option>
                                    <option value="divorced">مطلق</option>
                                    <option value="widowed">أرمل</option>
                                </select>
                                <div class="invalid-feedback" id="marital_status-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="family_members" class="form-label">عدد أفراد الأسرة *</label>
                                <input type="number" class="form-control" id="family_members" name="family_members" min="1" max="20" required>
                                <div class="invalid-feedback" id="family_members-error"></div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fa-solid fa-money-bill me-2"></i>
                                    المعلومات المالية
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="income_level" class="form-label">مستوى الدخل *</label>
                                <select class="form-select" id="income_level" name="income_level" required>
                                    <option value="">اختر مستوى الدخل</option>
                                    <option value="no_income">بدون دخل</option>
                                    <option value="very_low">دخل منخفض جداً</option>
                                    <option value="low">دخل منخفض</option>
                                    <option value="below_average">دخل أقل من المتوسط</option>
                                    <option value="average">دخل متوسط</option>
                                </select>
                                <div class="invalid-feedback" id="income_level-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="employment_status" class="form-label">حالة العمل *</label>
                                <select class="form-select" id="employment_status" name="employment_status" required>
                                    <option value="">اختر حالة العمل</option>
                                    <option value="employed">موظف</option>
                                    <option value="unemployed">عاطل عن العمل</option>
                                    <option value="part_time">عمل جزئي</option>
                                    <option value="student">طالب</option>
                                    <option value="retired">متقاعد</option>
                                </select>
                                <div class="invalid-feedback" id="employment_status-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="housing_type" class="form-label">نوع السكن *</label>
                                <select class="form-select" id="housing_type" name="housing_type" required>
                                    <option value="">اختر نوع السكن</option>
                                    <option value="owned">ملك</option>
                                    <option value="rented">مستأجر</option>
                                    <option value="shared">مشترك</option>
                                    <option value="homeless">بدون مأوى</option>
                                </select>
                                <div class="invalid-feedback" id="housing_type-error"></div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fa-solid fa-map-marker-alt me-2"></i>
                                    معلومات العنوان
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">العنوان التفصيلي *</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                <div class="invalid-feedback" id="address-error"></div>
                            </div>
                        </div>

                        <!-- Special Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fa-solid fa-heart me-2"></i>
                                    معلومات خاصة
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="medical_condition" class="form-label">الحالة الطبية</label>
                                <textarea class="form-control" id="medical_condition" name="medical_condition" rows="3"></textarea>
                                <div class="invalid-feedback" id="medical_condition-error"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="special_needs" class="form-label">الاحتياجات الخاصة</label>
                                <textarea class="form-control" id="special_needs" name="special_needs" rows="3"></textarea>
                                <div class="invalid-feedback" id="special_needs-error"></div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">ملاحظات إضافية</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                <div class="invalid-feedback" id="notes-error"></div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                        <i class="fa-solid fa-undo me-2"></i>إعادة تعيين
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-outline-light me-2" onclick="saveAsDraft()">
                                            <i class="fa-solid fa-save me-2"></i>حفظ كمسودة
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-check me-2"></i>إضافة المستفيد
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content bg-dark text-white">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <h6>جاري إضافة المستفيد...</h6>
                <p class="text-muted">يرجى الانتظار</p>
            </div>
        </div>
    </div>
</div>

<script>
// إرسال النموذج
document.getElementById('addBeneficiaryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // إظهار شاشة التحميل
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();
    
    // جمع البيانات
    const formData = new FormData(this);
    
    // إرسال الطلب
    fetch('{{ route("charity.beneficiaries.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loadingModal.hide();
        
        if (data.success) {
            showAlert('success', data.message);
            
            // إعادة التوجيه بعد ثانيتين
            setTimeout(() => {
                window.location.href = '{{ route("charity.beneficiaries.index") }}';
            }, 2000);
        } else {
            showAlert('error', data.message);
            
            // عرض الأخطاء في الحقول
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById(field + '-error');
                    
                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = data.errors[field][0];
                    }
                });
            }
        }
    })
    .catch(error => {
        loadingModal.hide();
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ أثناء إضافة المستفيد');
    });
});

// إعادة تعيين النموذج
function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟')) {
        document.getElementById('addBeneficiaryForm').reset();
        
        // إزالة رسائل الخطأ
        const invalidInputs = document.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    }
}

// حفظ كمسودة
function saveAsDraft() {
    showAlert('info', 'سيتم إضافة ميزة حفظ المسودات قريباً');
}

// إظهار التنبيهات
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // إزالة التنبيه تلقائياً بعد 5 ثوان
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// التحقق من صحة التاريخ
document.getElementById('date_of_birth').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    
    if (selectedDate >= today) {
        this.classList.add('is-invalid');
        document.getElementById('date_of_birth-error').textContent = 'تاريخ الميلاد يجب أن يكون في الماضي';
    } else {
        this.classList.remove('is-invalid');
        document.getElementById('date_of_birth-error').textContent = '';
    }
});

// إزالة رسائل الخطأ عند التعديل
document.querySelectorAll('input, select, textarea').forEach(element => {
    element.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
            const errorDiv = document.getElementById(this.id + '-error');
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        }
    });
});
</script>
@endsection 