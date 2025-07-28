@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        تسجيل حساب جديد
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم الكامل</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required 
                                       minlength="3" maxlength="255">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">نوع الحساب</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-users"></i>
                                </span>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">اختر نوع الحساب</option>
                                    <option value="beneficiary" {{ old('role') == 'beneficiary' ? 'selected' : '' }}>
                                        مستفيد - للحصول على قسائم
                                    </option>
                                    <option value="store" {{ old('role') == 'store' ? 'selected' : '' }}>
                                        متجر - لاستقبال القسائم
                                    </option>
                                    <option value="charity" {{ old('role') == 'charity' ? 'selected' : '' }}>
                                        جمعية خيرية - لإدارة القسائم
                                    </option>
                                </select>
                            </div>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required
                                       placeholder="+970XXXXXXXXX">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">يجب أن تكون 6 أحرف على الأقل</div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    أوافق على <a href="#" class="text-decoration-none">الشروط والأحكام</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="submitText">تسجيل الحساب</span>
                                <span id="loadingText" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    جاري التسجيل...
                                </span>
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">لديك حساب بالفعل؟</p>
                        <a href="{{ route('login.form') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            تسجيل الدخول
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>مستفيد؟</strong> يمكنك استخدام 
                            <a href="{{ route('beneficiary.register.form') }}" class="text-decoration-none fw-bold">
                                صفحة التسجيل المبسطة للمستفيدين
                            </a>
                        </div>
                    </div>

                    <!-- Test Accounts Section -->
                    <div class="mt-4">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    حسابات تجريبية للاختبار
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">المشرف:</small><br>
                                        <code>admin@example.com</code> / <code>password</code>
                                        <a href="/force-login" class="btn btn-sm btn-outline-info ms-2">تسجيل دخول سريع</a>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">المستفيد:</small><br>
                                        <code>beneficiary@example.com</code> / <code>password</code>
                                        <a href="/force-login-beneficiary" class="btn btn-sm btn-outline-info ms-2">تسجيل دخول سريع</a>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">المتجر:</small><br>
                                        <code>store@example.com</code> / <code>password</code>
                                        <a href="/force-login-store" class="btn btn-sm btn-outline-info ms-2">تسجيل دخول سريع</a>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">الجمعية الخيرية:</small><br>
                                        <code>charity@example.com</code> / <code>password</code>
                                        <a href="/force-login-charity" class="btn btn-sm btn-outline-info ms-2">تسجيل دخول سريع</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control {
    border-left: none;
}

.form-control:focus {
    border-color: #dee2e6;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    border-radius: 10px;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Form validation and submission
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');
    
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const role = document.getElementById('role');
        const phone = document.getElementById('phone');
        const terms = document.getElementById('terms');
        
        // Reset previous errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        let isValid = true;
        
        // Name validation
        if (name.value.trim().length < 3) {
            name.classList.add('is-invalid');
            isValid = false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.classList.add('is-invalid');
            isValid = false;
        }
        
        // Role validation
        if (!role.value) {
            role.classList.add('is-invalid');
            isValid = false;
        }
        
        // Phone validation
        if (phone.value.length < 10) {
            phone.classList.add('is-invalid');
            isValid = false;
        }
        
        // Password validation
        if (password.value.length < 6) {
            password.classList.add('is-invalid');
            isValid = false;
        }
        
        if (password.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            isValid = false;
        }
        
        // Terms validation
        if (!terms.checked) {
            terms.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        submitText.style.display = 'none';
        loadingText.style.display = 'inline';
        submitBtn.disabled = true;
    });

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (!value.startsWith('970')) {
                value = '970' + value;
            }
            value = '+' + value;
        }
        e.target.value = value;
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        
        switch(field.name) {
            case 'name':
                if (value.length < 3) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
                break;
            case 'phone':
                if (value.length < 10) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
                break;
            case 'password':
                if (value.length < 6) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
                break;
        }
    }
});
</script>
@endsection
