@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4 text-center">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3 active-link"><i class="fa-solid fa-compass me-2"></i> Campaigns</a>
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        إضافة مستفيدين للحملة: {{ $campaign->name }}
                    </h4>
                    <div>
                        <a href="{{ route('charity.campaigns.beneficiaries.index', $campaign->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            العودة لقائمة المستفيدين
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($availableBeneficiaries->count() > 0)
                        <form action="{{ route('charity.campaigns.beneficiaries.store', $campaign->id) }}" method="POST">
                            @csrf
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                تم العثور على <strong>{{ $availableBeneficiaries->count() }}</strong> مستفيد متاح للإضافة للحملة.
                                حدد المستفيدين الذين تريد إضافتهم:
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-primary" onclick="selectAll()">
                                        <i class="fas fa-check-square me-1"></i>
                                        تحديد الكل
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="deselectAll()">
                                        <i class="fas fa-square me-1"></i>
                                        إلغاء التحديد
                                    </button>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="text-muted">المحدد: <span id="selectedCount">0</span></span>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($availableBeneficiaries as $beneficiary)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card beneficiary-card" data-beneficiary-id="{{ $beneficiary->id }}">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input beneficiary-checkbox" type="checkbox" 
                                                       name="beneficiary_ids[]" value="{{ $beneficiary->id }}" 
                                                       id="beneficiary_{{ $beneficiary->id }}"
                                                       onchange="updateSelectedCount()">
                                                <label class="form-check-label" for="beneficiary_{{ $beneficiary->id }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $beneficiary->name }}</strong>
                                                            <br><small class="text-muted">{{ $beneficiary->email }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            
                                            @if($beneficiary->beneficiaryProfile)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-id-card me-1"></i>
                                                    {{ $beneficiary->beneficiaryProfile->id_number }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $beneficiary->phone }}
                                                </small>
                                            </div>
                                            @endif

                                            <div class="mt-3 beneficiary-details" style="display: none;">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="form-label small">المبلغ المخصص</label>
                                                        <input type="number" name="allocated_amounts[]" 
                                                               class="form-control form-control-sm" 
                                                               step="0.01" min="0" placeholder="0.00">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">ملاحظات</label>
                                                        <input type="text" name="notes[]" 
                                                               class="form-control form-control-sm" 
                                                               placeholder="ملاحظات اختيارية">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-save me-1"></i>
                                        إضافة المستفيدين المحددين
                                    </button>
                                    <a href="{{ route('charity.campaigns.beneficiaries.index', $campaign->id) }}" class="btn btn-secondary">
                                        إلغاء
                                    </a>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا يوجد مستفيدين متاحين</h5>
                            <p class="text-muted">جميع المستفيدين مضافين لهذه الحملة بالفعل</p>
                            <a href="{{ route('charity.campaigns.beneficiaries.index', $campaign->id) }}" class="btn btn-primary">
                                العودة لقائمة المستفيدين
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.beneficiary-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.beneficiary-card:hover {
    border-color: #007bff;
    box-shadow: 0 0 10px rgba(0,123,255,0.2);
}

.beneficiary-card.selected {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.avatar-sm {
    width: 40px;
    height: 40px;
}
</style>

<script>
let selectedCount = 0;

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.beneficiary-checkbox:checked');
    selectedCount = checkboxes.length;
    document.getElementById('selectedCount').textContent = selectedCount;
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = selectedCount === 0;
    
    // تحديث مظهر البطاقات المحددة
    checkboxes.forEach(checkbox => {
        const card = checkbox.closest('.beneficiary-card');
        card.classList.add('selected');
        
        // إظهار تفاصيل إضافية للمحدد
        const details = card.querySelector('.beneficiary-details');
        if (details) {
            details.style.display = 'block';
        }
    });
    
    // إزالة التحديد من البطاقات غير المحددة
    document.querySelectorAll('.beneficiary-checkbox:not(:checked)').forEach(checkbox => {
        const card = checkbox.closest('.beneficiary-card');
        card.classList.remove('selected');
        
        const details = card.querySelector('.beneficiary-details');
        if (details) {
            details.style.display = 'none';
        }
    });
}

function selectAll() {
    document.querySelectorAll('.beneficiary-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.beneficiary-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

// تحديث العداد عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
});
</script>
@endsection 