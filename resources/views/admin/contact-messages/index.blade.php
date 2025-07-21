@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contact Messages</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="refreshStats()">
                <i class="fa-solid fa-refresh"></i> Refresh Stats
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Messages</h6>
                            <h3 class="mb-0" id="total-messages">{{ $messages->total() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Unread Messages</h6>
                            <h3 class="mb-0" id="unread-messages">{{ $messages->where('status', 'unread')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-eye-slash fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Replied Messages</h6>
                            <h3 class="mb-0" id="replied-messages">{{ $messages->where('status', 'replied')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-reply fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Recent (7 days)</h6>
                            <h3 class="mb-0" id="recent-messages">{{ $messages->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Messages</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="status-filter" style="width: auto;">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                        <option value="replied">Replied</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr class="{{ $message->status === 'unread' ? 'table-warning' : '' }}">
                            <td>{{ $message->id }}</td>
                            <td>
                                <strong>{{ $message->name }}</strong>
                                @if($message->status === 'unread')
                                    <span class="badge bg-danger ms-1">New</span>
                                @endif
                            </td>
                            <td>
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                            </td>
                            <td>{{ Str::limit($message->subject, 50) }}</td>
                            <td>
                                @switch($message->status)
                                    @case('unread')
                                        <span class="badge bg-warning">Unread</span>
                                        @break
                                    @case('read')
                                        <span class="badge bg-info">Read</span>
                                        @break
                                    @case('replied')
                                        <span class="badge bg-success">Replied</span>
                                        @break
                                    @case('archived')
                                        <span class="badge bg-secondary">Archived</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($message->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.contact-messages.show', $message->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if($message->status !== 'replied')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success"
                                            onclick="markAsReplied({{ $message->id }})">
                                        <i class="fa-solid fa-reply"></i>
                                    </button>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteMessage({{ $message->id }})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No contact messages found</h5>
                                <p class="text-muted">Contact form submissions will appear here.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($messages->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $messages->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Toasts -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="contact-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="contact-toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

<script>
function showContactToast(msg, type = 'success') {
    const toast = $('#contact-toast');
    toast.removeClass('text-bg-success text-bg-danger text-bg-info').addClass('text-bg-' + type);
    $('#contact-toast-body').text(msg);
    toast.toast('show');
}

function markAsReplied(messageId) {
    if (confirm('Mark this message as replied?')) {
        $.ajax({
            url: `/admin/contact-messages/${messageId}/replied`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                showContactToast('Message marked as replied successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                showContactToast(xhr.responseJSON?.message || 'Error updating message', 'danger');
            }
        });
    }
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/contact-messages/${messageId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                showContactToast('Message deleted successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                showContactToast(xhr.responseJSON?.message || 'Error deleting message', 'danger');
            }
        });
    }
}

function refreshStats() {
    $.ajax({
        url: "{{ route('admin.contact-messages.stats') }}",
        method: 'GET',
        success: function(res) {
            $('#total-messages').text(res.total_messages);
            $('#unread-messages').text(res.unread_messages);
            $('#replied-messages').text(res.messages_by_status.replied);
            $('#recent-messages').text(res.recent_messages);
            showContactToast('Statistics updated successfully', 'info');
        },
        error: function() {
            showContactToast('Error updating statistics', 'danger');
        }
    });
}

// Status filter
$('#status-filter').on('change', function() {
    const status = $(this).val();
    if (status) {
        window.location.href = "{{ route('admin.contact-messages.index') }}?status=" + status;
    } else {
        window.location.href = "{{ route('admin.contact-messages.index') }}";
    }
});

// Set current filter value
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status) {
        $('#status-filter').val(status);
    }
});
</script> 