<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <title>Login</title>
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
    <!-- Login Box -->
     <main>
      <section class="py-5">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
              <div class="shadow-sm rounded-4 p-4">
                <div class="text-center mb-3">
                  <div class="mb-2">
                    <i class="bi bi-ticket-fill fs-1 text-primary"></i> <!-- Bootstrap icon -->
                  </div>
                  <h5 class="fw-bold">Welcome to Gaza Coupon Management System</h5>
                </div>
                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="mb-3">
                    <input type="text" class="form-control" name="email" placeholder="Username or Email" required />
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required />
                  </div>
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="beneficiary" value="beneficiary">
                        <label class="form-check-label" for="beneficiary">Beneficiary</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="store" value="store" checked>
                        <label class="form-check-label" for="store">Store</label>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="charity" value="charity">
                        <label class="form-check-label" for="charity">Charity Org</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="admin" value="admin">
                        <label class="form-check-label" for="admin">Admin</label>
                      </div>
                    </div>
                  </div>
                  <div class="d-grid mb-3">
                    <button class="btn btn-primary" type="submit">Log In</button>
                  </div>
                  <div class="d-flex justify-content-between text-muted small">
                    <a href="#" class="text-secondary text-decoration-none">Forgot Password?</a>
                    <a href="/register" class="text-primary">Register as new</a>
                  </div>
                </form>
              </div>
              <div class="mt-4">
                <h6 class="fw-bold">Test Accounts</h6>
                <table class="table table-sm table-bordered bg-white">
                  <thead><tr><th>Role</th><th>Email</th><th>Password</th></tr></thead>
                  <tbody>
                    <tr><td>Admin</td><td>admin@example.com</td><td>password</td></tr>
                    <tr><td>Store</td><td>store@example.com</td><td>password</td></tr>
                    <tr><td>Beneficiary</td><td>beneficiary@example.com</td><td>password</td></tr>
                    <tr><td>Charity</td><td>charity@example.com</td><td>password</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
     </main>
    <!-- Footer -->
    <footer class="text-center text-muted py-3">
      Gaza Coupon Management System - 2025 &copy;
    </footer>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  </body>
</html>