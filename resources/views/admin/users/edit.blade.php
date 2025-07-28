@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">تعديل المستخدم</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf 
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="beneficiary" {{ (old('role', $user->role) == 'beneficiary') ? 'selected' : '' }}>مستفيد</option>
                                    <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>مشرف</option>
                                    <option value="store" {{ (old('role', $user->role) == 'store') ? 'selected' : '' }}>متجر</option>
                                    <option value="charity" {{ (old('role', $user->role) == 'charity') ? 'selected' : '' }}>مؤسسة خيرية</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @if($user->role == 'store')
                            <div class="col-md-12 mb-3" id="store_fields">
                                <label for="store_description" class="form-label">وصف المتجر</label>
                                <textarea id="store_description" name="store_description" class="form-control @error('store_description') is-invalid @enderror" rows="3">{{ old('store_description', $user->store_description) }}</textarea>
                                @error('store_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label">الحالة</label>
                                <div class="form-check">
                                    <input type="radio" id="status_active" name="status" value="active" class="form-check-input" {{ (old('status', $user->status) == 'active') ? 'checked' : '' }}>
                                    <label for="status_active" class="form-check-label">نشط</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="status_inactive" name="status" value="inactive" class="form-check-input" {{ (old('status', $user->status) == 'inactive') ? 'checked' : '' }}>
                                    <label for="status_inactive" class="form-check-label">غير نشط</label>
                                </div>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">تغيير كلمة المرور</h5>
                                        <p class="text-muted">اترك الحقول فارغة إذا كنت لا تريد تغيير كلمة المرور</p>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-2">
                                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // عرض/إخفاء الحقول الإضافية حسب نوع المستخدم
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const storeFields = document.getElementById('store_fields');
        
        if (role === 'store' && !storeFields) {
            // إنشاء حقول المتجر إذا تم اختيار دور "متجر"
            const storeFieldsDiv = document.createElement('div');
            storeFieldsDiv.id = 'store_fields';
            storeFieldsDiv.className = 'col-md-12 mb-3';
            storeFieldsDiv.innerHTML = `
                <label for="store_description" class="form-label">وصف المتجر</label>
                <textarea id="store_description" name="store_description" class="form-control" rows="3">{{ old('store_description', $user->store_description) }}</textarea>
            `;
            
            // إضافة الحقول قبل قسم الحالة
            const statusField = document.querySelector('.col-md-12.mb-3:has(label.form-label:contains("الحالة"))');
            statusField.parentNode.insertBefore(storeFieldsDiv, statusField);
        } else if (role !== 'store' && storeFields) {
            // إزالة حقول المتجر إذا تم اختيار دور آخر
            storeFields.remove();
        }
    });
</script>
@endpush
