@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4" style="min-height: 100vh;">
            <h5 class="px-3 mb-4">Gaza<br />Faders</h5>
            <a href="{{ route('admin.dashboard') }}" class="d-block text-white mb-3"><i class="fa fa-home me-2"></i> Admin</a>
            <a href="/admin/users" class="d-block text-white mb-3"><i class="fa fa-users me-2"></i> User Management</a>
            <a href="/admin/organizations" class="d-block text-white mb-3"><i class="fa fa-building me-2"></i> Organization</a>
            <a href="/admin/stores" class="d-block text-white mb-3 active"><i class="fa fa-store me-2"></i> Stores</a>
            <a href="/admin/settings" class="d-block text-white mb-3"><i class="fa fa-gear me-2"></i> Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-link text-white p-0"><i class="fa fa-sign-out-alt me-2"></i> Logout</button>
            </form>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-10 col-12 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-white mb-0">Stores Management</h3>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStoreModal">
                    <i class="fa fa-plus me-2"></i>Add Store
                </button>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Stores</h5>
                            <h3 class="mb-0" id="total-stores">25</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Active Stores</h5>
                            <h3 class="mb-0" id="active-stores">18</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Today's Transactions</h5>
                            <h3 class="mb-0" id="today-transactions">47</h3>
                            <small>Click to refresh</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pending Approval</h5>
                            <h3 class="mb-0" id="pending-stores">7</h3>
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
                            <input type="text" class="form-control" id="search-stores" placeholder="Search stores...">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" id="filter-store-status">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" id="filter-store-type">
                                <option value="">All Types</option>
                                <option value="grocery">Grocery</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="clothing">Clothing</option>
                                <option value="electronics">Electronics</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-primary w-100" onclick="filterStores()">
                                <i class="fa fa-search me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stores Table -->
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Stores List</h5>
                    <button class="btn btn-sm btn-outline-light" onclick="refreshStores()">
                        <i class="fa fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped" id="stores-table">
                            <thead>
                                <tr>
                                    <th>Store</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Transactions Today</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="stores-tbody">
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Gaza Supermarket</strong>
                                            <br>
                                            <small class="text-muted">gaza.super@example.com</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Grocery</span></td>
                                    <td>Gaza City, Palestine</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>12</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewStore(1)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editStore(1)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteStore(1)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Pharmacy Plus</strong>
                                            <br>
                                            <small class="text-muted">pharmacy.plus@example.com</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Pharmacy</span></td>
                                    <td>Khan Yunis, Palestine</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>8</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewStore(2)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editStore(2)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteStore(2)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Fashion Hub</strong>
                                            <br>
                                            <small class="text-muted">fashion.hub@example.com</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Clothing</span></td>
                                    <td>Rafah, Palestine</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>0</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewStore(3)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editStore(3)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteStore(3)">
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

<!-- Add Store Modal -->
<div class="modal fade" id="addStoreModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Add New Store</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-store-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="store-name" class="form-label">Store Name</label>
                            <input type="text" class="form-control" id="store-name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="store-type" class="form-label">Store Type</label>
                            <select class="form-select" id="store-type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="grocery">Grocery</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="clothing">Clothing</option>
                                <option value="electronics">Electronics</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="store-email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="store-email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="store-phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="store-phone" name="phone" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="store-address" class="form-label">Address</label>
                        <textarea class="form-control" id="store-address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="store-status" class="form-label">Status</label>
                            <select class="form-select" id="store-status" name="status" required>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="store-registration-date" class="form-label">Registration Date</label>
                            <input type="date" class="form-control" id="store-registration-date" name="registration_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="store-description" class="form-label">Store Description</label>
                        <textarea class="form-control" id="store-description" name="description" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="saveStore()">Save Store</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast for notifications -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="stores-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="stores-toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
function showStoresToast(message, type = 'success') {
    const toast = document.getElementById('stores-toast');
    const toastBody = document.getElementById('stores-toast-body');
    
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

function refreshStores() {
    // In a real application, this would fetch fresh data from the server
    showStoresToast('Stores list refreshed!');
}

function filterStores() {
    const search = document.getElementById('search-stores').value;
    const status = document.getElementById('filter-store-status').value;
    const type = document.getElementById('filter-store-type').value;
    
    // In a real application, this would send AJAX request to filter data
    showStoresToast('Stores filtered!');
}

function viewStore(id) {
    // In a real application, this would open a modal with store details
    showStoresToast(`Viewing store ID: ${id}`);
}

function editStore(id) {
    // In a real application, this would open edit modal
    showStoresToast(`Editing store ID: ${id}`);
}

function deleteStore(id) {
    if (confirm('Are you sure you want to delete this store?')) {
        // In a real application, this would send AJAX delete request
        showStoresToast('Store deleted successfully!', 'success');
    }
}

function saveStore() {
    const form = document.getElementById('add-store-form');
    const formData = new FormData(form);
    
    // In a real application, this would send AJAX request to save store
    showStoresToast('Store added successfully!', 'success');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addStoreModal'));
    modal.hide();
    
    // Reset form
    form.reset();
}

// Make stats cards clickable for refresh
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', function() {
        if (this.querySelector('small')) {
            showStoresToast('Statistics refreshed!');
        }
    });
});

// Auto-refresh every 30 seconds
setInterval(function() {
    refreshStores();
}, 30000);
</script>
@endsection 