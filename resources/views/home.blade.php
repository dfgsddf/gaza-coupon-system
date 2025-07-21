@extends('layouts.app')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Gaza Coupon System</h1>
                    <p class="lead mb-4">
                        Connecting charities, stores, and beneficiaries through a secure and efficient coupon management platform. 
                        Supporting the community with digital assistance programs.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fa-solid fa-user-plus me-2"></i>Register Now
                        </a>
                        <a href="{{ route('login.form') }}" class="btn btn-outline-light btn-lg">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fa-solid fa-hand-holding-heart fa-8x text-light opacity-75"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">How It Works</h2>
                <p class="lead text-muted">Our platform connects all stakeholders in the assistance ecosystem</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-building fa-2x"></i>
                            </div>
                            <h5 class="card-title">Charities</h5>
                            <p class="card-text">
                                Create and manage assistance campaigns, track donations, and generate reports to help those in need.
                            </p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">Join as Charity</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-store fa-2x"></i>
                            </div>
                            <h5 class="card-title">Stores</h5>
                            <p class="card-text">
                                Accept digital coupons, validate transactions, and manage your participation in assistance programs.
                            </p>
                            <a href="{{ route('register') }}" class="btn btn-outline-success">Join as Store</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-users fa-2x"></i>
                            </div>
                            <h5 class="card-title">Beneficiaries</h5>
                            <p class="card-text">
                                Request assistance, receive digital coupons, and use them at participating stores for essential items.
                            </p>
                            <a href="{{ route('beneficiaries.create') }}" class="btn btn-outline-info">Register as Beneficiary</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="bg-white rounded p-4 shadow-sm">
                        <i class="fa-solid fa-building text-primary fa-2x mb-3"></i>
                        <h3 class="fw-bold text-primary">50+</h3>
                        <p class="text-muted mb-0">Charities</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="bg-white rounded p-4 shadow-sm">
                        <i class="fa-solid fa-store text-success fa-2x mb-3"></i>
                        <h3 class="fw-bold text-success">200+</h3>
                        <p class="text-muted mb-0">Stores</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="bg-white rounded p-4 shadow-sm">
                        <i class="fa-solid fa-users text-info fa-2x mb-3"></i>
                        <h3 class="fw-bold text-info">10,000+</h3>
                        <p class="text-muted mb-0">Beneficiaries</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="bg-white rounded p-4 shadow-sm">
                        <i class="fa-solid fa-ticket text-warning fa-2x mb-3"></i>
                        <h3 class="fw-bold text-warning">50,000+</h3>
                        <p class="text-muted mb-0">Coupons Issued</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Simple Process</h2>
                <p class="lead text-muted">Three easy steps to get assistance</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="position-relative">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold">1</span>
                        </div>
                        <h5>Register</h5>
                        <p class="text-muted">Create your account as a beneficiary and provide necessary information.</p>
                    </div>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="position-relative">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold">2</span>
                        </div>
                        <h5>Request Assistance</h5>
                        <p class="text-muted">Submit your assistance request and wait for approval from charities.</p>
                    </div>
                </div>
                
                <div class="col-md-4 text-center">
                    <div class="position-relative">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold">3</span>
                        </div>
                        <h5>Use Coupons</h5>
                        <p class="text-muted">Receive digital coupons and use them at participating stores.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary text-white py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of people who are already benefiting from our platform</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                    <i class="fa-solid fa-user-plus me-2"></i>Register Now
                </a>
                <a href="{{ route('contact.show') }}" class="btn btn-outline-light btn-lg">
                    <i class="fa-solid fa-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer Info -->
    <section class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6>Gaza Coupon System</h6>
                    <p class="mb-0 text-muted">Connecting communities through digital assistance</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="{{ route('contact.show') }}" class="text-muted text-decoration-none">
                            <i class="fa-solid fa-envelope me-1"></i>Contact
                        </a>
                        <a href="{{ route('help') }}" class="text-muted text-decoration-none">
                            <i class="fa-solid fa-question-circle me-1"></i>Help
                        </a>
                        <a href="{{ route('login.form') }}" class="text-muted text-decoration-none">
                            <i class="fa-solid fa-sign-in-alt me-1"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
