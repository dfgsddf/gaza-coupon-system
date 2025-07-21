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
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Organizations</h5>
                            <h3 class="mb-0" id="total-organizations">12</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Active Organizations</h5>
                            <h3 class="mb-0" id="active-organizations">8</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pending Approval</h5>
                            <h3 class="mb-0" id="pending-organizations">3</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Suspended</h5>
                            <h3 class="mb-0" id="suspended-organizations">1</h3>
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
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Gaza Relief Foundation</strong>
                                            <br>
                                            <small class="text-muted">gaza.relief@example.com</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Charity</span></td>
                                    <td>+970 59 123 4567</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>Jan 15, 2024</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrganization(1)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editOrganization(1)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteOrganization(1)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Palestine Aid Network</strong>
                                            <br>
                                            <small class="text-muted">palestine.aid@example.com</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary">NGO</span></td>
                                    <td>+970 59 987 6543</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>Feb 20, 2024</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrganization(2)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editOrganization(2)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteOrganization(2)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Humanitarian Support Group</strong>
                                            <br>
                                            <small class="text-muted">humanitarian@example.com</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Foundation</span></td>
                                    <td>+970 59 555 1234</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>Mar 10, 2024</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrganization(3)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editOrganization(3)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteOrganization(3)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
    // In a real application, this would open a modal with organization details
    showOrganizationsToast(`Viewing organization ID: ${id}`);
}

function editOrganization(id) {
    // In a real application, this would open edit modal
    showOrganizationsToast(`Editing organization ID: ${id}`);
}

function deleteOrganization(id) {
    if (confirm('Are you sure you want to delete this organization?')) {
        // In a real application, this would send AJAX delete request
        showOrganizationsToast('Organization deleted successfully!', 'success');
    }
}

function saveOrganization() {
    const form = document.getElementById('add-organization-form');
    const formData = new FormData(form);
    
    // In a real application, this would send AJAX request to save organization
    showOrganizationsToast('Organization added successfully!', 'success');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addOrganizationModal'));
    modal.hide();
    
    // Reset form
    form.reset();
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
</script>
@endsection 