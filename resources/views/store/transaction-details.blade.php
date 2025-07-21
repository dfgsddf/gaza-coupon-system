<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted mb-3">Transaction Information</h6>
        <table class="table table-borderless">
            <tr>
                <td><strong>Transaction ID:</strong></td>
                <td>#{{ $transaction->id }}</td>
            </tr>
            <tr>
                <td><strong>Date & Time:</strong></td>
                <td>{{ $transaction->created_at->format('M d, Y H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    @if($transaction->status === 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($transaction->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Coupon Value:</strong></td>
                <td class="text-success fw-bold">${{ number_format($transaction->coupon_value, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Redeemed At:</strong></td>
                <td>{{ $transaction->redeemed_at ? $transaction->redeemed_at->format('M d, Y H:i:s') : 'N/A' }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-3">Beneficiary Information</h6>
        <table class="table table-borderless">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $transaction->beneficiary_name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $transaction->beneficiary->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $transaction->beneficiary->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td>{{ $transaction->beneficiary->address ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h6 class="text-muted mb-3">Coupon Details</h6>
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Coupon Code:</strong><br>
                        <span class="badge bg-primary fs-6">{{ $transaction->coupon->code ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>Store Name:</strong><br>
                        {{ $transaction->store_name }}
                    </div>
                    <div class="col-md-4">
                        <strong>Expiry Date:</strong><br>
                        {{ $transaction->coupon->expiry_date ? \Carbon\Carbon::parse($transaction->coupon->expiry_date)->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h6 class="text-muted mb-3">Transaction Summary</h6>
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-6">
                    <strong>Total Amount:</strong> ${{ number_format($transaction->coupon_value, 2) }}
                </div>
                <div class="col-md-6">
                    <strong>Transaction Fee:</strong> $0.00
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <strong>Net Amount:</strong> ${{ number_format($transaction->coupon_value, 2) }}
                </div>
                <div class="col-md-6">
                    <strong>Payment Method:</strong> Coupon Redemption
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-center">
        <button type="button" class="btn btn-outline-primary me-2" onclick="printReceipt()">
            <i class="fa-solid fa-print"></i> Print Receipt
        </button>
        <button type="button" class="btn btn-outline-success me-2" onclick="downloadReceipt()">
            <i class="fa-solid fa-download"></i> Download PDF
        </button>
        <button type="button" class="btn btn-outline-info" onclick="shareReceipt()">
            <i class="fa-solid fa-share"></i> Share
        </button>
    </div>
</div>

<script>
    function printReceipt() {
        const printContent = document.querySelector('.modal-body').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Transaction Receipt #{{ $transaction->id }}</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        .receipt-header { text-align: center; margin-bottom: 30px; }
                        .receipt-footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="receipt-header">
                        <h3>Transaction Receipt</h3>
                        <p>Store: {{ $transaction->store_name }}</p>
                        <p>Date: {{ $transaction->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    ${printContent}
                    <div class="receipt-footer">
                        <p>Thank you for your business!</p>
                        <p>This is a computer-generated receipt.</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }

    function downloadReceipt() {
        showAlert('PDF download functionality will be implemented soon.', 'info');
    }

    function shareReceipt() {
        showAlert('Share functionality will be implemented soon.', 'info');
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                         type === 'warning' ? 'alert-warning' : 
                         type === 'info' ? 'alert-info' : 'alert-danger';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
</script> 