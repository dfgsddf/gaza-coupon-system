@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-primary text-center">
                        <i class="fa-solid fa-plus-circle me-2"></i>
                        إنشاء طلب جديد
                    </h3>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <strong>خطأ في البيانات:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('requests.store') }}" id="requestForm">
                        @csrf

                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">
                                <i class="fa-solid fa-tag me-2"></i>نوع الطلب
                            </label>
                            <select class="form-select form-select-lg @error('type') is-invalid @enderror" 
                                    name="type" id="type" required>
                                <option value="">اختر نوع الطلب</option>
                                <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>طارئ</option>
                                <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>عاجل</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fa-solid fa-align-left me-2"></i>تفاصيل الطلب
                            </label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                      name="description" 
                                      id="description" 
                                      rows="5" 
                                      placeholder="اكتب تفاصيل طلبك هنا..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                إرسال الطلب
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-right me-2"></i>
                            العودة لقائمة الطلبات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('requestForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        // إظهار حالة التحميل
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>جاري الإرسال...';
        submitBtn.disabled = true;
        
        // إزالة حالة التحميل بعد 5 ثوانٍ إذا لم يتم التحويل
        setTimeout(function() {
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>إرسال الطلب';
            submitBtn.disabled = false;
        }, 5000);
    });
});
</script>

<style>
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

.card {
    border-radius: 15px;
}
</style>
@endsection
