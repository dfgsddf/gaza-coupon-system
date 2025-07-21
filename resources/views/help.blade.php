@extends('layouts.app')

@section('content')
<main class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-5">
                <i class="fa-solid fa-question-circle text-primary me-3"></i>
                Help & Support
            </h1>

            <!-- Quick Navigation -->
            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-user-plus fa-2x text-primary mb-3"></i>
                            <h5>Getting Started</h5>
                            <p class="text-muted">Learn how to register and use the platform</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-ticket fa-2x text-success mb-3"></i>
                            <h5>Using Coupons</h5>
                            <p class="text-muted">How to request and use digital coupons</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fa-solid fa-headset fa-2x text-info mb-3"></i>
                            <h5>Contact Support</h5>
                            <p class="text-muted">Get help from our support team</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="accordion" id="faqAccordion">
                <h2 class="mb-4">Frequently Asked Questions</h2>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                            How do I register as a beneficiary?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>To register as a beneficiary:</p>
                            <ol>
                                <li>Click on "Register as Beneficiary" on the home page</li>
                                <li>Fill out the registration form with your personal information</li>
                                <li>Provide required documents for verification</li>
                                <li>Submit your application and wait for approval</li>
                                <li>Once approved, you can start requesting assistance</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                            How do I request assistance?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>To request assistance:</p>
                            <ol>
                                <li>Log in to your beneficiary account</li>
                                <li>Go to the "Requests" section</li>
                                <li>Click "Create New Request"</li>
                                <li>Select the type of assistance you need</li>
                                <li>Fill out the request form with details</li>
                                <li>Submit your request for review</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                            How do I use my digital coupons?
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>To use your digital coupons:</p>
                            <ol>
                                <li>Visit a participating store</li>
                                <li>Show your digital coupon code to the store staff</li>
                                <li>The store will validate your coupon</li>
                                <li>Use the coupon value for your purchase</li>
                                <li>The coupon will be automatically redeemed</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                            How long does it take to get approved?
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Approval times vary depending on the type of request:</p>
                            <ul>
                                <li><strong>Registration:</strong> 2-5 business days</li>
                                <li><strong>Assistance Requests:</strong> 1-3 business days</li>
                                <li><strong>Emergency Requests:</strong> 24 hours or less</li>
                            </ul>
                            <p>You will receive notifications about your application status.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                            What if I lose my coupon code?
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>If you lose your coupon code:</p>
                            <ol>
                                <li>Log in to your beneficiary account</li>
                                <li>Go to the "Coupons" section</li>
                                <li>Find your active coupons</li>
                                <li>Copy the coupon code again</li>
                                <li>If the coupon is expired, contact support for assistance</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support Section -->
            <div class="mt-5 p-4 bg-light rounded">
                <h3 class="mb-4">
                    <i class="fa-solid fa-headset text-primary me-2"></i>
                    Need More Help?
                </h3>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Contact Information</h5>
                        <ul class="list-unstyled">
                            <li><i class="fa-solid fa-envelope me-2"></i>Email: support@gazacoupon.com</li>
                            <li><i class="fa-solid fa-phone me-2"></i>Phone: +970 59 5700 555</li>
                            <li><i class="fa-solid fa-clock me-2"></i>Hours: Sunday - Thursday, 9:00 AM - 6:00 PM</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('contact.show') }}" class="btn btn-primary">
                                <i class="fa-solid fa-envelope me-2"></i>Send Message
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-user-plus me-2"></i>Register Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Guides -->
            <div class="mt-5">
                <h3 class="mb-4">
                    <i class="fa-solid fa-book text-primary me-2"></i>
                    User Guides
                </h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fa-solid fa-user text-primary me-2"></i>
                                    Beneficiary Guide
                                </h6>
                                <p class="card-text">Complete guide for beneficiaries on how to use the platform.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">Read Guide</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fa-solid fa-store text-success me-2"></i>
                                    Store Guide
                                </h6>
                                <p class="card-text">Guide for stores on how to accept and validate coupons.</p>
                                <a href="#" class="btn btn-sm btn-outline-success">Read Guide</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fa-solid fa-building text-info me-2"></i>
                                    Charity Guide
                                </h6>
                                <p class="card-text">Guide for charities on managing campaigns and assistance.</p>
                                <a href="#" class="btn btn-sm btn-outline-info">Read Guide</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
