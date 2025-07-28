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
                        <i class="fas fa-users me-2"></i>
                        مستفيدين الحملة: {{ $campaign->name }}
                    </h4>
                    <div>
                        <a href="{{ route('charity.campaigns.beneficiaries.create', $campaign->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            إضافة مستفيدين
                        </a>
                        <a href="{{ route('charity.campaigns') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            العودة للحملات
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- إحصائيات سريعة -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['total'] }}</h5>
                                    <small>إجمالي المستفيدين</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['approved'] }}</h5>
                                    <small>مُوافق عليهم</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['pending'] }}</h5>
                                    <small>في الانتظار</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['completed'] }}</h5>
                                    <small>مكتملين</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- البحث والتصفية -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form action="{{ route('charity.campaigns.beneficiaries.search', $campaign->id) }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="البحث بالاسم..." value="{{ request('search') }}">
                                <select name="status" class="form-select me-2" style="width: auto;">
                                    <option value="">جميع الحالات</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>مُوافق عليه</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                </select>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-outline-success" onclick="exportBeneficiaries()">
                                <i class="fas fa-download me-1"></i>
                                تصدير البيانات
                            </button>
                        </div>
                    </div>

                    <!-- جدول المستفيدين -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>اسم المستفيد</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>المبلغ المخصص</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($beneficiaries as $index => $campaignBeneficiary)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $campaignBeneficiary->beneficiary->name }}</strong>
                                                @if($campaignBeneficiary->beneficiary->beneficiaryProfile)
                                                    <br><small class="text-muted">{{ $campaignBeneficiary->beneficiary->beneficiaryProfile->id_number }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $campaignBeneficiary->beneficiary->email }}</td>
                                    <td>
                                        @if($campaignBeneficiary->allocated_amount)
                                            <span class="badge bg-success">{{ number_format($campaignBeneficiary->allocated_amount, 2) }} $</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="{{ $campaignBeneficiary->status_badge_class }}">
                                            {{ $campaignBeneficiary->status_display_name }}
                                        </span>
                                    </td>
                                    <td>{{ $campaignBeneficiary->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('charity.campaigns.beneficiaries.show', [$campaign->id, $campaignBeneficiary->beneficiary_id]) }}" 
                                               class="btn btn-sm btn-outline-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="updateStatus({{ $campaignBeneficiary->beneficiary_id }})" title="تحديث الحالة">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteBeneficiary({{ $campaignBeneficiary->beneficiary_id }})" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <p>لا يوجد مستفيدين في هذه الحملة</p>
                                            <a href="{{ route('charity.campaigns.beneficiaries.create', $campaign->id) }}" class="btn btn-primary">
                                                إضافة مستفيدين الآن
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- الترقيم -->
                    @if($beneficiaries->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $beneficiaries->links() }}
                    </div>
                    @endif
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
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">في الانتظار</option>
                            <option value="approved">مُوافق عليه</option>
                            <option value="rejected">مرفوض</option>
                            <option value="completed">مكتمل</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المبلغ المخصص</label>
                        <input type="number" name="allocated_amount" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
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
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(beneficiaryId) {
    const form = document.getElementById('updateStatusForm');
    form.action = `/charity/campaigns/{{ $campaign->id }}/beneficiaries/${beneficiaryId}/status`;
    
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function deleteBeneficiary(beneficiaryId) {
    const form = document.getElementById('deleteForm');
    form.action = `/charity/campaigns/{{ $campaign->id }}/beneficiaries/${beneficiaryId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function exportBeneficiaries() {
    // يمكن إضافة وظيفة التصدير هنا
    alert('سيتم إضافة وظيفة التصدير قريباً');
}
</script>
@endsection 