@extends('layouts.app')

@section('title', 'إكمال معلومات الملف الشخصي')

@section('content')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary mb-2">إكمال الملف الشخصي</h2>
                        <p class="text-muted">يرجى إكمال معلوماتك الشخصية للمتابعة</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('beneficiary.profile.store') }}" method="POST" id="profileForm">
                        @csrf
                        
                        <div class="row">
                            <!-- الاسم الكامل -->
                            <div class="col-12 mb-3">
                                <label for="full_name" class="form-label fw-bold">
                                    <i class="fas fa-user text-primary me-2"></i>الاسم الكامل
                                </label>
                                <input type="text" class="form-control form-control-lg" id="full_name" name="full_name" 
                                       value="{{ old('full_name', auth()->user()->name) }}" required 
                                       style="border-radius: 10px;">
                            </div>

                            <!-- تاريخ الميلاد -->
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label fw-bold">
                                    <i class="fas fa-calendar text-primary me-2"></i>تاريخ الميلاد
                                </label>
                                <input type="date" class="form-control form-control-lg" id="date_of_birth" name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}" required style="border-radius: 10px;">
                                <div id="age_display" class="form-text text-primary fw-bold" style="display: none;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    العمر: <span id="calculated_age"></span> سنة
                                </div>
                                <div id="age_error" class="form-text text-danger" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    يجب أن يكون العمر بين 16 و 100 سنة
                                </div>
                            </div>

                            <!-- رقم الهوية/الإقامة -->
                            <div class="col-md-6 mb-3">
                                <label for="id_number" class="form-label fw-bold">
                                    <i class="fas fa-id-card text-primary me-2"></i>رقم الهوية/الإقامة
                                </label>
                                <input type="text" class="form-control form-control-lg" id="id_number" name="id_number" 
                                       value="{{ old('id_number') }}" required style="border-radius: 10px;"
                                       placeholder="123456789">
                            </div>

                            <!-- الحالة الاجتماعية -->
                            <div class="col-md-6 mb-3">
                                <label for="marital_status" class="form-label fw-bold">
                                    <i class="fas fa-heart text-primary me-2"></i>الحالة الاجتماعية
                                </label>
                                <select class="form-select form-select-lg" id="marital_status" name="marital_status" 
                                        required style="border-radius: 10px;">
                                    <option value="">اختر الحالة الاجتماعية</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>أعزب</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>متزوج</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>مطلق</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>أرمل</option>
                                </select>
                            </div>

                            <!-- عدد أفراد الأسرة -->
                            <div class="col-md-6 mb-3">
                                <label for="family_members" class="form-label fw-bold">
                                    <i class="fas fa-users text-primary me-2"></i>عدد أفراد الأسرة
                                </label>
                                <input type="number" class="form-control form-control-lg" id="family_members" name="family_members" 
                                       value="{{ old('family_members') }}" min="1" max="20" required 
                                       style="border-radius: 10px;" placeholder="5">
                            </div>

                            <!-- العنوان الكامل -->
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>العنوان الكامل
                                </label>
                                <textarea class="form-control form-control-lg" id="address" name="address" rows="3" 
                                          required style="border-radius: 10px;" placeholder="المدينة، الحي، اسم الشارع، رقم المنزل">{{ old('address') }}</textarea>
                            </div>

                            <!-- رقم الهاتف -->
                            <div class="col-12 mb-4">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="fas fa-phone text-primary me-2"></i>رقم الهاتف
                                </label>
                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone" 
                                       value="{{ old('phone', auth()->user()->phone) }}" required 
                                       style="border-radius: 10px;" placeholder="+970599123456">
                            </div>

                            <!-- معلومات إضافية (اختيارية) -->
                            <div class="col-12 mb-4">
                                <div class="card" style="background-color: #f8f9fa; border-radius: 10px;">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-secondary mb-3">
                                            <i class="fas fa-info-circle me-2"></i>معلومات إضافية (اختيارية)
                                        </h6>
                                        
                                        <div class="row">
                                            <!-- الجنس -->
                                            <div class="col-md-6 mb-3">
                                                <label for="gender" class="form-label">الجنس</label>
                                                <select class="form-select" id="gender" name="gender" style="border-radius: 8px;">
                                                    <option value="">اختر الجنس</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                                </select>
                                            </div>

                                            <!-- حالة التوظيف -->
                                            <div class="col-md-6 mb-3">
                                                <label for="employment_status" class="form-label">حالة التوظيف</label>
                                                <select class="form-select" id="employment_status" name="employment_status" style="border-radius: 8px;">
                                                    <option value="">اختر حالة التوظيف</option>
                                                    <option value="unemployed" {{ old('employment_status') == 'unemployed' ? 'selected' : '' }}>عاطل عن العمل</option>
                                                    <option value="part_time" {{ old('employment_status') == 'part_time' ? 'selected' : '' }}>دوام جزئي</option>
                                                    <option value="full_time" {{ old('employment_status') == 'full_time' ? 'selected' : '' }}>دوام كامل</option>
                                                    <option value="retired" {{ old('employment_status') == 'retired' ? 'selected' : '' }}>متقاعد</option>
                                                    <option value="student" {{ old('employment_status') == 'student' ? 'selected' : '' }}>طالب</option>
                                                </select>
                                            </div>

                                            <!-- مستوى الدخل -->
                                            <div class="col-md-6 mb-3">
                                                <label for="income_level" class="form-label">مستوى الدخل الشهري</label>
                                                <select class="form-select" id="income_level" name="income_level" style="border-radius: 8px;">
                                                    <option value="">اختر مستوى الدخل</option>
                                                    <option value="no_income" {{ old('income_level') == 'no_income' ? 'selected' : '' }}>لا يوجد دخل</option>
                                                    <option value="very_low" {{ old('income_level') == 'very_low' ? 'selected' : '' }}>أقل من 500 شيكل</option>
                                                    <option value="low" {{ old('income_level') == 'low' ? 'selected' : '' }}>500 - 1000 شيكل</option>
                                                    <option value="below_average" {{ old('income_level') == 'below_average' ? 'selected' : '' }}>1000 - 2000 شيكل</option>
                                                    <option value="average" {{ old('income_level') == 'average' ? 'selected' : '' }}>2000 - 3000 شيكل</option>
                                                    <option value="above_average" {{ old('income_level') == 'above_average' ? 'selected' : '' }}>أكثر من 3000 شيكل</option>
                                                </select>
                                            </div>

                                            <!-- المستوى التعليمي -->
                                            <div class="col-md-6 mb-3">
                                                <label for="education_level" class="form-label">المستوى التعليمي</label>
                                                <select class="form-select" id="education_level" name="education_level" style="border-radius: 8px;">
                                                    <option value="">اختر المستوى التعليمي</option>
                                                    <option value="none" {{ old('education_level') == 'none' ? 'selected' : '' }}>لا يوجد</option>
                                                    <option value="primary" {{ old('education_level') == 'primary' ? 'selected' : '' }}>ابتدائي</option>
                                                    <option value="secondary" {{ old('education_level') == 'secondary' ? 'selected' : '' }}>ثانوي</option>
                                                    <option value="diploma" {{ old('education_level') == 'diploma' ? 'selected' : '' }}>دبلوم</option>
                                                    <option value="bachelor" {{ old('education_level') == 'bachelor' ? 'selected' : '' }}>بكالوريوس</option>
                                                    <option value="master" {{ old('education_level') == 'master' ? 'selected' : '' }}>ماجستير</option>
                                                    <option value="phd" {{ old('education_level') == 'phd' ? 'selected' : '' }}>دكتوراه</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- ملاحظات خاصة -->
                                        <div class="col-12">
                                            <label for="special_needs" class="form-label">احتياجات خاصة أو ملاحظات</label>
                                            <textarea class="form-control" id="special_needs" name="special_needs" rows="2" 
                                                      style="border-radius: 8px;" placeholder="أي احتياجات خاصة أو ملاحظات تود إضافتها...">{{ old('special_needs') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold" style="border-radius: 10px; background: linear-gradient(45deg, #667eea, #764ba2);">
                                <i class="fas fa-save me-2"></i>حفظ البيانات والمتابعة
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            جميع بياناتك محمية ومشفرة بأعلى معايير الأمان
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تجربة المستخدم
    const form = document.getElementById('profileForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        // التحقق من صحة العمر قبل الإرسال
        if (dateInput.value) {
            const age = calculateAge(dateInput.value);
            if (age === null || age < 16 || age > 100) {
                e.preventDefault();
                alert('يرجى التأكد من صحة تاريخ الميلاد والعمر قبل المتابعة.');
                return false;
            }
        }
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
        submitBtn.disabled = true;
    });

    // تنسيق حقل التاريخ
    const dateInput = document.getElementById('date_of_birth');
    const ageDisplay = document.getElementById('age_display');
    const calculatedAge = document.getElementById('calculated_age');
    const ageError = document.getElementById('age_error');
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    
    dateInput.setAttribute('max', maxDate);
    dateInput.setAttribute('min', minDate);

    // دالة حساب العمر بدقة (متطابقة مع PHP)
    function calculateAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        
        // التأكد من صحة التاريخ
        if (isNaN(birth.getTime())) {
            return null;
        }
        
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        // إذا لم يأت عيد الميلاد بعد هذا العام، نقص سنة
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        return age;
    }

    // عرض العمر عند تغيير تاريخ الميلاد
    dateInput.addEventListener('change', function() {
        console.log('Date input changed:', this.value);
        
        if (this.value) {
            const age = calculateAge(this.value);
            console.log('Calculated age:', age);
            
            if (age === null) {
                ageDisplay.style.display = 'none';
                ageError.style.display = 'block';
                ageError.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>تنسيق التاريخ غير صحيح';
                this.classList.add('is-invalid');
                return;
            }
            
            calculatedAge.textContent = age;
            
            if (age < 16) {
                ageDisplay.style.display = 'none';
                ageError.style.display = 'block';
                ageError.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>العمر المحسوب هو ' + age + ' سنة. يجب أن يكون العمر 16 سنة على الأقل';
                this.classList.add('is-invalid');
            } else if (age > 100) {
                ageDisplay.style.display = 'none';
                ageError.style.display = 'block';
                ageError.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>العمر المحسوب هو ' + age + ' سنة. يرجى التحقق من تاريخ الميلاد';
                this.classList.add('is-invalid');
            } else {
                ageError.style.display = 'none';
                ageDisplay.style.display = 'block';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        } else {
            ageDisplay.style.display = 'none';
            ageError.style.display = 'none';
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    // حساب العمر عند تحميل الصفحة إذا كان هناك تاريخ محفوظ
    if (dateInput.value) {
        dateInput.dispatchEvent(new Event('change'));
    }

    // تحديث عدد أفراد الأسرة عند تغيير الحالة الاجتماعية
    const maritalStatus = document.getElementById('marital_status');
    const familyMembers = document.getElementById('family_members');
    
    maritalStatus.addEventListener('change', function() {
        if (this.value === 'single') {
            familyMembers.value = 1;
            familyMembers.setAttribute('min', '1');
        } else {
            familyMembers.setAttribute('min', '2');
            if (familyMembers.value < 2) {
                familyMembers.value = 2;
            }
        }
    });
});
</script>
@endsection 