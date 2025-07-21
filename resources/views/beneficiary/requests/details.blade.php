<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Request #{{ $request->id }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Type:</strong> {{ ucfirst($request->type) }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge
                                @if($request->status == 'approved') bg-success
                                @elseif($request->status == 'rejected') bg-danger
                                @else bg-warning text-dark @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Created:</strong> {{ $request->created_at->format('M d, Y \a\t h:i A') }}</p>
                        <p><strong>Updated:</strong> {{ $request->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
                
                @if($request->status == 'processing')
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i>
                        Your request is currently being processed. You will be notified once it's reviewed.
                    </div>
                @elseif($request->status == 'approved')
                    <div class="alert alert-success">
                        <i class="fa-solid fa-check-circle"></i>
                        Your request has been approved! You should receive your coupon soon.
                    </div>
                @elseif($request->status == 'rejected')
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-times-circle"></i>
                        Your request has been rejected. Please contact support for more information.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 