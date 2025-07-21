<div class="modal-header">
    <h5 class="modal-title">Request #{{ $details['num'] }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <p><strong>Name:</strong> {{ $details['name'] }}</p>
    <p><strong>Category:</strong> {{ $details['category'] }}</p>
    <p><strong>Date:</strong> {{ $details['date'] }}</p>
    <p><strong>Status:</strong> {{ ucfirst($details['status']) }}</p>
    <p><strong>Description:</strong> {{ $details['description'] }}</p>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-success" id="approve-request-btn" data-num="{{ $details['num'] }}">Approve</button>
    <button type="button" class="btn btn-danger" id="reject-request-btn" data-num="{{ $details['num'] }}">Reject</button>
</div>
<script>
    $('#approve-request-btn').off('click').on('click', function() {
        var num = $(this).data('num');
        $(this).prop('disabled', true);
        $.ajax({
            url: `/charity/dashboard/request-approve/${num}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                showToast(res.message, 'success');
                $('#requestDetailsModal').modal('hide');
                loadRecentRequests();
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || 'Error', 'danger');
            },
            complete: function() {
                $('#approve-request-btn').prop('disabled', false);
            }
        });
    });
    $('#reject-request-btn').off('click').on('click', function() {
        var num = $(this).data('num');
        $(this).prop('disabled', true);
        $.ajax({
            url: `/charity/dashboard/request-reject/${num}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                showToast(res.message, 'success');
                $('#requestDetailsModal').modal('hide');
                loadRecentRequests();
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || 'Error', 'danger');
            },
            complete: function() {
                $('#reject-request-btn').prop('disabled', false);
            }
        });
    });
</script> 