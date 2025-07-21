@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Messages
            </a>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success" onclick="markAsReplied({{ $message->id }})">
                <i class="fa-solid fa-reply"></i> Mark as Replied
            </button>
            <button class="btn btn-outline-danger" onclick="deleteMessage({{ $message->id }})">
                <i class="fa-solid fa-trash"></i> Delete
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Message Details -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Message Details</h5>
                        <div>
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
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>From:</strong> {{ $message->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> 
                            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong> {{ $message->subject }}
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong> {{ $message->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Message:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Admin Notes</h6>
                </div>
                <div class="card-body">
                    <form id="notes-form">
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" 
                                      placeholder="Add internal notes about this message...">{{ $message->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Notes</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Message Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Message Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Message ID:</strong><br>
                        <span class="text-muted">#{{ $message->id }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong><br>
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
                    </div>
                    
                    <div class="mb-3">
                        <strong>Read At:</strong><br>
                        <span class="text-muted">
                            {{ $message->read_at ? $message->read_at->format('F j, Y \a\t g:i A') : 'Not read yet' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Replied At:</strong><br>
                        <span class="text-muted">
                            {{ $message->replied_at ? $message->replied_at->format('F j, Y \a\t g:i A') : 'Not replied yet' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>IP Address:</strong><br>
                        <span class="text-muted">{{ $message->ip_address ?? 'Unknown' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>User Agent:</strong><br>
                        <small class="text-muted">{{ Str::limit($message->user_agent, 100) }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" 
                           class="btn btn-primary">
                            <i class="fa-solid fa-envelope"></i> Reply via Email
                        </a>
                        
                        <button type="button" class="btn btn-outline-success" onclick="markAsReplied({{ $message->id }})">
                            <i class="fa-solid fa-check"></i> Mark as Replied
                        </button>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-cog"></i> Change Status
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('unread', {{ $message->id }})">Mark as Unread</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('read', {{ $message->id }})">Mark as Read</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('replied', {{ $message->id }})">Mark as Replied</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('archived', {{ $message->id }})">Archive</a></li>
                            </ul>
                        </div>
                        
                        <button type="button" class="btn btn-outline-danger" onclick="deleteMessage({{ $message->id }})">
                            <i class="fa-solid fa-trash"></i> Delete Message
                        </button>
                    </div>
                </div>
            </div>
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

function updateStatus(status, messageId) {
    $.ajax({
        url: `/admin/contact-messages/${messageId}/status`,
        method: 'PATCH',
        data: { status: status },
        headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
        success: function(res) {
            showContactToast('Status updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        },
        error: function(xhr) {
            showContactToast(xhr.responseJSON?.message || 'Error updating status', 'danger');
        }
    });
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/contact-messages/${messageId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                showContactToast('Message deleted successfully', 'success');
                setTimeout(() => window.location.href = "{{ route('admin.contact-messages.index') }}", 1000);
            },
            error: function(xhr) {
                showContactToast(xhr.responseJSON?.message || 'Error deleting message', 'danger');
            }
        });
    }
}

// Save admin notes
$('#notes-form').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: `/admin/contact-messages/{{ $message->id }}/status`,
        method: 'PATCH',
        data: { 
            admin_notes: $('#admin_notes').val(),
            status: '{{ $message->status }}' // Keep current status
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
        success: function(res) {
            showContactToast('Notes saved successfully', 'success');
        },
        error: function(xhr) {
            showContactToast(xhr.responseJSON?.message || 'Error saving notes', 'danger');
        }
    });
});
</script> 