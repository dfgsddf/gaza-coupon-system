@php $user = auth()->user(); @endphp
@if($user && $user->role === 'store')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
    <title>Homepage | GSMS</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- Navbar -->
    <header>
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="/">Gaza Coupon</a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/contact">Contact Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/help">Help & Support</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/store">Store</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/beneficiary">Beneficiary</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/charity">Charity</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin">Admin</a>
              </li>
          </div>
        </div>
      </nav>
    </header>
<main>
    <div class="store-card shadow-sm">
        <!-- Header -->
        <div class="store-header">
          <span><i class="fas fa-store"></i> Gaza Coupon <small class="d-block fw-normal">Management System</small></span>
          <span>Azalea Store <i class="fas fa-chevron-right ms-2"></i></span>
        </div>
    
        <!-- Body -->
        <div class="store-body">
          <h5 class="fw-bold mb-3">Store Interface</h5>
          <div id="store-alert"></div>
          <label for="coupon-code" class="form-label fw-semibold">Coupon Code</label>
          <div class="input-group mb-4">
            <input type="text" class="form-control" placeholder="Coupon Code" id="coupon-code">
            <button class="btn scan-btn" type="button" id="scan-btn"><i class="fas fa-qrcode"></i> Scan</button>
          </div>
    
          <div class="mb-3" id="coupon-details" style="display:none;">
            <p class="mb-1 fw-bold">Coupon Details</p>
            <div class="d-flex justify-content-between"><span>Beneficiary Name:</span> <strong id="beneficiary-name"></strong></div>
            <div class="d-flex justify-content-between"><span>Coupon Value:</span> <strong id="coupon-value"></strong></div>
            <div class="d-flex justify-content-between"><span>Expiry Date:</span> <strong id="expiry-date"></strong></div>
          </div>
    
          <button class="btn btn-confirm" id="redeem-btn" style="display:none;">Confirm Redemption</button>
        </div>
    
        <div class="transaction-history">
          <h6 class="fw-bold mb-3">Transaction History</h6>
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-light mb-0">
              <thead class="table-light">
                <tr>
                  <th>Beneficiary Name</th>
                  <th>Coupon Value</th>
                  <th>Redemption Date</th>
                </tr>
              </thead>
              <tbody id="transaction-history-body">
                <tr>
                  <td>Alice Smith</td>
                  <td>$25.00</td>
                  <td>Jun 15, 2025</td>
                </tr>
                <tr>
                  <td>Bob Johnson</td>
                  <td>$10.00</td>
                  <td>Jun 25, 2025</td>
                </tr>
                <tr>
                  <td>Eve Williams</td>
                  <td>$15.00</td>
                  <td>May 31, 2025</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
</main>
    <!-- Footer -->
    <footer class="text-center text-muted py-3">
      Gaza Coupon Management System - 2025 &copy;
    </footer>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
    $(function() {
        let couponCode = '';
        $('#scan-btn').on('click', function() {
            couponCode = $('#coupon-code').val();
            if (!couponCode) {
                $('#store-alert').html('<div class="alert alert-warning">Please enter a coupon code.</div>');
                return;
            }
            $('#store-alert').html('');
            $.post({
                url: '{{ route('store.validateCoupon') }}',
                data: { code: couponCode, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        $('#coupon-details').show();
                        $('#beneficiary-name').text(res.coupon.beneficiary_name);
                        $('#coupon-value').text('$' + res.coupon.value);
                        $('#expiry-date').text(res.coupon.expiry_date);
                        $('#redeem-btn').show();
                    } else {
                        $('#store-alert').html('<div class="alert alert-danger">' + res.message + '</div>');
                        $('#coupon-details').hide();
                        $('#redeem-btn').hide();
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error validating coupon.';
                    $('#store-alert').html('<div class="alert alert-danger">' + msg + '</div>');
                    $('#coupon-details').hide();
                    $('#redeem-btn').hide();
                }
            });
        });
        $('#redeem-btn').on('click', function() {
            if (!couponCode) return;
            $.post({
                url: '{{ route('store.redeemCoupon') }}',
                data: { code: couponCode, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        $('#store-alert').html('<div class="alert alert-success">' + res.message + '</div>');
                        // Optionally add to transaction history
                        let row = `<tr><td>${$('#beneficiary-name').text()}</td><td>${$('#coupon-value').text()}</td><td>${(new Date()).toLocaleDateString()}</td></tr>`;
                        $('#transaction-history-body').prepend(row);
                        $('#coupon-details').hide();
                        $('#redeem-btn').hide();
                        $('#coupon-code').val('');
                    } else {
                        $('#store-alert').html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error redeeming coupon.';
                    $('#store-alert').html('<div class="alert alert-danger">' + msg + '</div>');
                }
            });
        });
    });
    </script>
  </body>
</html>
@else
<div class="container py-5">
    <div class="alert alert-danger text-center">You do not have permission to access this page.</div>
</div>
@endif