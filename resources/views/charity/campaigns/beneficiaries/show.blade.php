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
                        <i class="fas fa-user me-2"></i>
                        تفاصيل المستفيد في الحملة
                    </h4>
                    <div>
                        <a href="{{ route('charity.campaigns.beneficiaries.index', $campaign->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            العودة لقائمة المستفيدين
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- معلومات الحملة -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-compass me-2"></i>معلومات الحملة</h6>
                                </div>
                                <div class="card-body">
                                    <h5>{{ $campaign->name }}</h5>
                                    <p class="text-muted">{{ $campaign->description }}</p>
                                    
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="text-primary">{{ number_format($campaign->current_amount, 2) }} $</h6>
                                                <small class="text-muted">المبلغ المحقق</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-success">{{ number_format($campaign->goal, 2) }} $</h6>
                                            <small class="text-muted">الهدف</small>
                                        </div>
                                    </div>
                                    
                                    <div class="progress mt-2">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ ($campaign->current_amount / $campaign->goal) * 100 }}%"></div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $campaign->start_date->format('Y-m-d') }} - {{ $campaign->end_date->format('Y-m-d') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المستفيد -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>معلومات المستفيد</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white fa-2x"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">{{ $campaignBeneficiary->beneficiary->name }}</h5>
                                                    <p class="text-muted mb-0">{{ $campaignBeneficiary->beneficiary->email }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <strong>رقم الهاتف:</strong>
                                                <span>{{ $campaignBeneficiary->beneficiary->phone ?? 'غير محدد' }}</span>
                                            </div>
                                            
                                            @if($campaignBeneficiary->beneficiary->beneficiaryProfile)
                                            <div class="mb-3">
                                                <strong>رقم الهوية:</strong>
                                                <span>{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->id_number ?? 'غير محدد' }}</span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <strong>تاريخ الميلاد:</strong>
                                                <span>{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->date_of_birth ? $campaignBeneficiary->beneficiary->beneficiaryProfile->date_of_birth->format('Y-m-d') : 'غير محدد' }}</span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <strong>الجنس:</strong>
                                                <span>{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->gender ?? 'غير محدد' }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title">حالة المستفيد في الحملة</h6>
                                                    
                                                    <div class="mb-3">
                                                        <strong>الحالة:</strong>
                                                        <span class="{{ $campaignBeneficiary->status_badge_class }} ms-2">
                                                            {{ $campaignBeneficiary->status_display_name }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <strong>المبلغ المخصص:</strong>
                                                        <span class="text-success">
                                                            {{ $campaignBeneficiary->allocated_amount ? number_format($campaignBeneficiary->allocated_amount, 2) . ' $' : 'غير محدد' }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <strong>تاريخ الإضافة:</strong>
                                                        <span>{{ $campaignBeneficiary->created_at->format('Y-m-d H:i') }}</span>
                                                    </div>
                                                    
                                                    @if($campaignBeneficiary->approved_at)
                                                    <div class="mb-3">
                                                        <strong>تاريخ الموافقة:</strong>
                                                        <span>{{ $campaignBeneficiary->approved_at->format('Y-m-d H:i') }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($campaignBeneficiary->completed_at)
                                                    <div class="mb-3">
                                                        <strong>تاريخ الإكمال:</strong>
                                                        <span>{{ $campaignBeneficiary->completed_at->format('Y-m-d H:i') }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($campaignBeneficiary->notes)
                                                    <div class="mb-3">
                                                        <strong>ملاحظات:</strong>
                                                        <p class="text-muted">{{ $campaignBeneficiary->notes }}</p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية للمستفيد -->
                    @if($campaignBeneficiary->beneficiary->beneficiaryProfile)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-primary">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->family_members ?? 0 }}</h6>
                                                <small class="text-muted">عدد أفراد الأسرة</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-success">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->income_level ?? 'غير محدد' }}</h6>
                                                <small class="text-muted">مستوى الدخل</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-warning">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->housing_type ?? 'غير محدد' }}</h6>
                                                <small class="text-muted">نوع السكن</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 border rounded">
                                                <h6 class="text-info">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->employment_status ?? 'غير محدد' }}</h6>
                                                <small class="text-muted">حالة العمل</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($campaignBeneficiary->beneficiary->beneficiaryProfile->medical_condition || $campaignBeneficiary->beneficiary->beneficiaryProfile->special_needs)
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            @if($campaignBeneficiary->beneficiary->beneficiaryProfile->medical_condition)
                                            <div class="alert alert-warning">
                                                <strong>الحالة الطبية:</strong>
                                                <p class="mb-0">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->medical_condition }}</p>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($campaignBeneficiary->beneficiary->beneficiaryProfile->special_needs)
                                            <div class="alert alert-info">
                                                <strong>الاحتياجات الخاصة:</strong>
                                                <p class="mb-0">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->special_needs }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- أزرار الإجراءات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>الإجراءات</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-warning" onclick="updateStatus()">
                                            <i class="fas fa-edit me-1"></i>
                                            تحديث الحالة
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="deleteBeneficiary()">
                                            <i class="fas fa-trash me-1"></i>
                                            حذف من الحملة
                                        </button>
                                        <a href="{{ route('charity.beneficiaries.show', $campaignBeneficiary->beneficiary_id) }}" class="btn btn-info">
                                            <i class="fas fa-eye me-1"></i>
                                            عرض ملف المستفيد
                                        </a>
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

<!-- Modal تحديث الحالة -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث حالة المستفيد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('charity.campaigns.beneficiaries.update-status', [$campaign->id, $campaignBeneficiary->beneficiary_id]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $campaignBeneficiary->status == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="approved" {{ $campaignBeneficiary->status == 'approved' ? 'selected' : '' }}>مُوافق عليه</option>
                            <option value="rejected" {{ $campaignBeneficiary->status == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                            <option value="completed" {{ $campaignBeneficiary->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المبلغ المخصص</label>
                        <input type="number" name="allocated_amount" class="form-control" 
                               step="0.01" min="0" 
                               value="{{ $campaignBeneficiary->allocated_amount }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $campaignBeneficiary->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا المستفيد من الحملة؟</p>
                <p class="text-danger"><small>لا يمكن التراجع عن هذا الإجراء</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('charity.campaigns.beneficiaries.destroy', [$campaign->id, $campaignBeneficiary->beneficiary_id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}
</style>

<script>
function updateStatus() {
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function deleteBeneficiary() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection 