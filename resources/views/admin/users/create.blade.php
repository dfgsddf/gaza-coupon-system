@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">إضافة مستخدم جديد</h3>
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

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">اختر الدور</option>
                                    <option value="beneficiary" {{ old('role') == 'beneficiary' ? 'selected' : '' }}>مستفيد</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مشرف</option>
                                    <option value="store" {{ old('role') == 'store' ? 'selected' : '' }}>متجر</option>
                                    <option value="charity" {{ old('role') == 'charity' ? 'selected' : '' }}>مؤسسة خيرية</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label">الحالة</label>
                                <div class="form-check">
                                    <input type="radio" id="status_active" name="status" value="active" class="form-check-input" checked>
                                    <label for="status_active" class="form-check-label">نشط</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="status_inactive" name="status" value="inactive" class="form-check-input" {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                    <label for="status_inactive" class="form-check-label">غير نشط</label>
                                </div>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ المستخدم
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
                <textarea id="store_description" name="store_description" class="form-control" rows="3">${old_store_description || ''}</textarea>
            `;
            
            this.closest('.row').appendChild(storeFieldsDiv);
        } else if (role !== 'store' && storeFields) {
            // إزالة حقول المتجر إذا تم اختيار دور آخر
            storeFields.remove();
        }
    });
</script>
@endpush
