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
                    <h2 class="text-white mb-2">إدارة المستفيدين</h2>
                    <p class="text-light mb-0">إضافة وإدارة المستفيدين المرتبطين بالجمعية</p>
                </div>
                <div>
                    <a href="{{ route('charity.beneficiaries.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>إضافة مستفيد جديد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">إجمالي المستفيدين</h5>
                    <h3 class="mb-0" id="total-beneficiaries">{{ $beneficiaries->total() }}</h3>
                    <small>مستفيد مسجل</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">المستفيدين المُتحقق منهم</h5>
                    <h3 class="mb-0" id="verified-beneficiaries">0</h3>
                    <small>مستفيد مُتحقق</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">في انتظار التحقق</h5>
                    <h3 class="mb-0" id="pending-beneficiaries">0</h3>
                    <small>مستفيد في الانتظار</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">ذوي الاحتياجات الخاصة</h5>
                    <h3 class="mb-0" id="special-needs-beneficiaries">0</h3>
                    <small>مستفيد</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-beneficiaries" placeholder="البحث في المستفيدين...">
                                <button class="btn btn-outline-light" type="button" onclick="searchBeneficiaries()">
                                    <i class="fa-solid fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <select class="form-select" id="filter-status">
                                    <option value="">جميع الحالات</option>
                                    <option value="verified">مُتحقق</option>
                                    <option value="pending">في الانتظار</option>
                                    <option value="rejected">مرفوض</option>
                                </select>
                                <button class="btn btn-outline-secondary" onclick="refreshStats()">
                                    <i class="fa-solid fa-sync-alt"></i> تحديث
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Beneficiaries List -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">قائمة المستفيدين</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-light" onclick="exportBeneficiaries()">
                            <i class="fa-solid fa-download me-1"></i>تصدير
                        </button>
                        <button class="btn btn-sm btn-outline-light" onclick="refreshList()">
                            <i class="fa-solid fa-sync-alt me-1"></i>تحديث
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="beneficiaries-list">
                        @if($beneficiaries->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>رقم الهوية</th>
                                            <th>رقم الهاتف</th>
                                            <th>العمر</th>
                                            <th>عدد أفراد الأسرة</th>
                                            <th>الحالة</th>
                                            <th>تاريخ التسجيل</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($beneficiaries as $beneficiary)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <i class="fa-solid fa-user fa-2x text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $beneficiary->user->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $beneficiary->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $beneficiary->id_number }}</td>
                                                <td>{{ $beneficiary->user->phone }}</td>
                                                <td>
                                                    @if($beneficiary->date_of_birth)
                                                        {{ $beneficiary->getAge() }} سنة
                                                    @else
                                                        <span class="text-muted">غير محدد</span>
                                                    @endif
                                                </td>
                                                <td>{{ $beneficiary->family_members }} فرد</td>
                                                <td>
                                                    @if($beneficiary->verification_status === 'verified')
                                                        <span class="badge bg-success">مُتحقق</span>
                                                    @elseif($beneficiary->verification_status === 'pending')
                                                        <span class="badge bg-warning">في الانتظار</span>
                                                    @else
                                                        <span class="badge bg-danger">مرفوض</span>
                                                    @endif
                                                </td>
                                                <td>{{ $beneficiary->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('charity.beneficiaries.show', $beneficiary->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="عرض التفاصيل">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('charity.beneficiaries.edit', $beneficiary->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" 
                                                           title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteBeneficiary({{ $beneficiary->id }})"
                                                                title="حذف">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $beneficiaries->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa-solid fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">لا يوجد مستفيدين مسجلين</h5>
                                <p class="text-muted">ابدأ بإضافة مستفيدين جدد للجمعية</p>
                                <a href="{{ route('charity.beneficiaries.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-2"></i>إضافة مستفيد جديد
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBeneficiaryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا المستفيد؟</p>
                <p class="text-warning">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentBeneficiaryId = null;

// البحث في المستفيدين
function searchBeneficiaries() {
    const searchTerm = document.getElementById('search-beneficiaries').value;
    const filterStatus = document.getElementById('filter-status').value;
    
    // إعادة تحميل الصفحة مع معاملات البحث
    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (filterStatus) params.append('status', filterStatus);
    
    window.location.href = '{{ route("charity.beneficiaries.index") }}?' + params.toString();
}

// تحديث الإحصائيات
function refreshStats() {
    fetch('{{ route("charity.beneficiaries.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-beneficiaries').textContent = data.stats.total;
                document.getElementById('verified-beneficiaries').textContent = data.stats.verified;
                document.getElementById('pending-beneficiaries').textContent = data.stats.pending;
                document.getElementById('special-needs-beneficiaries').textContent = data.stats.with_special_needs;
            }
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
        });
}

// حذف مستفيد
function deleteBeneficiary(id) {
    currentBeneficiaryId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteBeneficiaryModal'));
    modal.show();
}

// تأكيد الحذف
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (currentBeneficiaryId) {
        fetch(`{{ route('charity.beneficiaries.index') }}/${currentBeneficiaryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إخفاء Modal
                bootstrap.Modal.getInstance(document.getElementById('deleteBeneficiaryModal')).hide();
                
                // إظهار رسالة نجاح
                showAlert('success', data.message);
                
                // إعادة تحميل الصفحة
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'حدث خطأ أثناء حذف المستفيد');
        });
    }
});

// تصدير قائمة المستفيدين
function exportBeneficiaries() {
    // يمكن إضافة منطق التصدير هنا
    showAlert('info', 'سيتم إضافة ميزة التصدير قريباً');
}

// تحديث القائمة
function refreshList() {
    window.location.reload();
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

// تحميل الإحصائيات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    refreshStats();
});
</script>
@endsection 