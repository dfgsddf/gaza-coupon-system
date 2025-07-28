@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4" style="min-height: 100vh;">
            <h5 class="px-3 mb-4">Gaza<br />Faders</h5>
            <a href="{{ route('admin.dashboard') }}" class="d-block text-white mb-3"><i class="fa fa-home me-2"></i> Admin</a>
            <a href="/admin/users" class="d-block text-white mb-3"><i class="fa fa-users me-2"></i> User Management</a>
            <a href="/admin/organizations" class="d-block text-white mb-3 active"><i class="fa fa-building me-2"></i> Organization</a>
            <a href="/admin/stores" class="d-block text-white mb-3"><i class="fa fa-store me-2"></i> Stores</a>
            <a href="/admin/settings" class="d-block text-white mb-3"><i class="fa fa-gear me-2"></i> Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-link text-white p-0"><i class="fa fa-sign-out-alt me-2"></i> Logout</button>
            </form>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-10 col-12 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-white mb-0">Organizations Management</h3>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOrganizationModal">
                    <i class="fa fa-plus me-2"></i>Add Organization
                </button>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white" id="org-total-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Organizations</h5>
                            <h3 class="mb-0" id="org-total">0</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white" id="org-active-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Active Organizations</h5>
                            <h3 class="mb-0" id="org-active">0</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white" id="org-pending-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pending Approval</h5>
                            <h3 class="mb-0" id="org-pending">0</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white" id="org-suspended-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Suspended</h5>
                            <h3 class="mb-0" id="org-suspended">0</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="card bg-dark text-white mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" class="form-control" id="search-organizations" placeholder="Search organizations...">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" id="filter-status">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" id="filter-type">
                                <option value="">All Types</option>
                                <option value="charity">Charity</option>
                                <option value="ngo">NGO</option>
                                <option value="foundation">Foundation</option>
                                <option value="association">Association</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-primary w-100" onclick="filterOrganizations()">
                                <i class="fa fa-search me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizations Table -->
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Organizations List</h5>
                    <button class="btn btn-sm btn-outline-light" onclick="refreshOrganizations()">
                        <i class="fa fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped" id="organizations-table">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Type</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="organizations-tbody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Organization Modal -->
<div class="modal fade" id="addOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Add New Organization</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-organization-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="org-name" class="form-label">Organization Name</label>
                            <input type="text" class="form-control" id="org-name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="org-type" class="form-label">Organization Type</label>
                            <select class="form-select" id="org-type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="charity">Charity</option>
                                <option value="ngo">NGO</option>
                                <option value="foundation">Foundation</option>
                                <option value="association">Association</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="org-email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="org-email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="org-phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="org-phone" name="phone" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="org-address" class="form-label">Address</label>
                        <textarea class="form-control" id="org-address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="org-status" class="form-label">Status</label>
                            <select class="form-select" id="org-status" name="status" required>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="org-registration-date" class="form-label">Registration Date</label>
                            <input type="date" class="form-control" id="org-registration-date" name="registration_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="org-description" class="form-label">Description</label>
                        <textarea class="form-control" id="org-description" name="description" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="saveOrganization()">Save Organization</button>
            </div>
        </div>
    </div>
</div>

<!-- View/Edit Organization Modal -->
<div class="modal fade" id="viewEditOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEditOrganizationModalTitle">Organization Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-organization-form">
                    <input type="hidden" id="edit-org-id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-name" class="form-label">Organization Name</label>
                            <input type="text" class="form-control" id="edit-org-name" name="name" required readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-type" class="form-label">Organization Type</label>
                            <input type="text" class="form-control" id="edit-org-type" name="type" required readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-org-email" name="email" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit-org-phone" name="phone" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-org-address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="edit-org-address" name="address" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="edit-org-status" name="status" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-registration-date" class="form-label">Registration Date</label>
                            <input type="text" class="form-control" id="edit-org-registration-date" name="registration_date" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-org-description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit-org-description" name="description" rows="3" readonly></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="edit-org-btn" style="display:none;" onclick="enableEditOrganization()">Edit</button>
                <button type="button" class="btn btn-success" id="save-org-btn" style="display:none;" onclick="saveEditOrganization()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast for notifications -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="organizations-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="organizations-toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
function showOrganizationsToast(message, type = 'success') {
    const toast = document.getElementById('organizations-toast');
    const toastBody = document.getElementById('organizations-toast-body');
    
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

function refreshOrganizations() {
    // In a real application, this would fetch fresh data from the server
    showOrganizationsToast('Organizations list refreshed!');
}

function filterOrganizations() {
    const search = document.getElementById('search-organizations').value;
    const status = document.getElementById('filter-status').value;
    const type = document.getElementById('filter-type').value;
    
    // In a real application, this would send AJAX request to filter data
    showOrganizationsToast('Organizations filtered!');
}

function viewOrganization(id) {
    fetch(`/admin/organizations/api/${id}`)
        .then(response => response.json())
        .then(org => {
            document.getElementById('viewEditOrganizationModalTitle').textContent = 'Organization Details';
            document.getElementById('edit-org-id').value = org.id;
            document.getElementById('edit-org-name').value = org.name;
            document.getElementById('edit-org-type').value = org.type || '';
            document.getElementById('edit-org-email').value = org.email || '';
            document.getElementById('edit-org-phone').value = org.phone || '';
            document.getElementById('edit-org-address').value = org.address || '';
            document.getElementById('edit-org-status').value = org.status || '';
            document.getElementById('edit-org-registration-date').value = org.registration_date || '';
            document.getElementById('edit-org-description').value = org.description || '';
            document.getElementById('edit-org-name').readOnly = true;
            document.getElementById('edit-org-type').readOnly = true;
            document.getElementById('edit-org-email').readOnly = true;
            document.getElementById('edit-org-phone').readOnly = true;
            document.getElementById('edit-org-address').readOnly = true;
            document.getElementById('edit-org-status').readOnly = true;
            document.getElementById('edit-org-registration-date').readOnly = true;
            document.getElementById('edit-org-description').readOnly = true;
            document.getElementById('edit-org-btn').style.display = 'inline-block';
            document.getElementById('save-org-btn').style.display = 'none';
            var modal = new bootstrap.Modal(document.getElementById('viewEditOrganizationModal'));
            modal.show();
        });
}
function editOrganization(id) {
    fetch(`/admin/organizations/api/${id}`)
        .then(response => response.json())
        .then(org => {
            document.getElementById('viewEditOrganizationModalTitle').textContent = 'Edit Organization';
            document.getElementById('edit-org-id').value = org.id;
            document.getElementById('edit-org-name').value = org.name || '';
            document.getElementById('edit-org-type').value = org.type || '';
            document.getElementById('edit-org-email').value = org.email || '';
            document.getElementById('edit-org-phone').value = org.phone || '';
            document.getElementById('edit-org-address').value = org.address || '';
            document.getElementById('edit-org-status').value = org.status || '';
            document.getElementById('edit-org-registration-date').value = org.registration_date || (org.created_at ? org.created_at.split('T')[0] : '');
            document.getElementById('edit-org-description').value = org.description || '';
            document.getElementById('edit-org-name').readOnly = false;
            document.getElementById('edit-org-type').readOnly = false;
            document.getElementById('edit-org-email').readOnly = false;
            document.getElementById('edit-org-phone').readOnly = false;
            document.getElementById('edit-org-address').readOnly = false;
            document.getElementById('edit-org-status').readOnly = false;
            document.getElementById('edit-org-registration-date').readOnly = false;
            document.getElementById('edit-org-description').readOnly = false;
            document.getElementById('edit-org-btn').style.display = 'none';
            document.getElementById('save-org-btn').style.display = 'inline-block';
            var modal = new bootstrap.Modal(document.getElementById('viewEditOrganizationModal'));
            modal.show();
        });
}
function enableEditOrganization() {
    document.getElementById('edit-org-name').readOnly = false;
    document.getElementById('edit-org-type').readOnly = false;
    document.getElementById('edit-org-btn').style.display = 'none';
    document.getElementById('save-org-btn').style.display = 'inline-block';
}
function saveEditOrganization() {
    const id = document.getElementById('edit-org-id').value;
    const formData = new FormData(document.getElementById('edit-organization-form'));
    fetch(`/admin/organizations/api/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showOrganizationsToast('Organization updated successfully!', 'success');
        var modal = bootstrap.Modal.getInstance(document.getElementById('viewEditOrganizationModal'));
        modal.hide();
        loadOrganizations();
    })
    .catch(() => showOrganizationsToast('Error updating organization', 'danger'));
}

// جلب المنظمات من API وعرضها في الجدول
function loadOrganizations() {
    fetch('/admin/organizations/api')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('organizations-tbody');
            tbody.innerHTML = '';
            data.forEach(org => {
                tbody.innerHTML += `
                    <tr>
                        <td><strong>${org.name}</strong><br><small class='text-muted'>${org.email || ''}</small></td>
                        <td>${org.type ? `<span class='badge bg-info'>${org.type.charAt(0).toUpperCase() + org.type.slice(1)}</span>` : ''}</td>
                        <td>${org.phone || ''}</td>
                        <td>${org.status ? `<span class='badge bg-${org.status === 'active' ? 'success' : org.status === 'pending' ? 'warning' : org.status === 'suspended' ? 'danger' : 'secondary'}'>${org.status.charAt(0).toUpperCase() + org.status.slice(1)}</span>` : ''}</td>
                        <td>${org.registration_date || (org.created_at ? new Date(org.created_at).toLocaleDateString() : '')}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewOrganization(${org.id})"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editOrganization(${org.id})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteOrganization(${org.id})"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        });
}

// إضافة منظمة جديدة عبر API
function saveOrganization() {
    const form = document.getElementById('add-organization-form');
    const formData = new FormData(form);
    fetch('/admin/organizations/api', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showOrganizationsToast('Organization added successfully!', 'success');
        const modal = bootstrap.Modal.getInstance(document.getElementById('addOrganizationModal'));
        modal.hide();
        form.reset();
        loadOrganizations();
    })
    .catch(() => showOrganizationsToast('Error adding organization', 'danger'));
}

// حذف منظمة عبر API
function deleteOrganization(id) {
    if (confirm('Are you sure you want to delete this organization?')) {
        fetch(`/admin/organizations/api/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(() => {
            showOrganizationsToast('Organization deleted successfully!', 'success');
            loadOrganizations();
        })
        .catch(() => showOrganizationsToast('Error deleting organization', 'danger'));
    }
}

// Make stats cards clickable for refresh
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', function() {
        if (this.querySelector('small')) {
            showOrganizationsToast('Statistics refreshed!');
        }
    });
});

// Auto-refresh every 30 seconds
setInterval(function() {
    refreshOrganizations();
}, 30000);

function loadOrganizationStats() {
    fetch('/admin/organizations/stats')
        .then(response => response.json())
        .then(stats => {
            document.getElementById('org-total').textContent = stats.total;
            document.getElementById('org-active').textContent = stats.active;
            document.getElementById('org-pending').textContent = stats.pending;
            document.getElementById('org-suspended').textContent = stats.suspended;
        });
}

// تحميل المنظمات عند تحميل الصفحة
window.addEventListener('DOMContentLoaded', function() {
    loadOrganizationStats();
    loadOrganizations();
    // تحديث الإحصائيات عند الضغط على أي بطاقة
    document.getElementById('org-total-card').onclick = loadOrganizationStats;
    document.getElementById('org-active-card').onclick = loadOrganizationStats;
    document.getElementById('org-pending-card').onclick = loadOrganizationStats;
    document.getElementById('org-suspended-card').onclick = loadOrganizationStats;
});
</script>
@endsection 