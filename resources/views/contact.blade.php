@extends('layouts.app')

@section('content')
    <main class="container py-5">
        <div class="row gy-4 align-items-start">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <h4 class="fw-bold">Contact Us</h4>
                <p class="mb-4">Get in touch with us for any inquiries or support</p>
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" id="contact-form">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               placeholder="Your full name"
                               value="{{ old('name') }}"
                               required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               placeholder="your.email@example.com"
                               value="{{ old('email') }}"
                               required />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <select class="form-select @error('subject') is-invalid @enderror" 
                                id="subject" 
                                name="subject" 
                                required>
                            <option value="" selected disabled>Choose a subject</option>
                            <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                            <option value="Account Issue" {{ old('subject') == 'Account Issue' ? 'selected' : '' }}>Account Issue</option>
                            <option value="Store Inquiry" {{ old('subject') == 'Store Inquiry' ? 'selected' : '' }}>Store Inquiry</option>
                            <option value="Charity Support" {{ old('subject') == 'Charity Support' ? 'selected' : '' }}>Charity Support</option>
                            <option value="General Question" {{ old('subject') == 'General Question' ? 'selected' : '' }}>General Question</option>
                            <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                            <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" 
                                  name="message" 
                                  rows="5" 
                                  placeholder="Please describe your inquiry or issue in detail..."
                                  maxlength="5000"
                                  required>{{ old('message') }}</textarea>
                        <div class="form-text">
                            <span id="char-count">0</span> / 5000 characters
                        </div>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                        <i class="fa-solid fa-paper-plane me-2"></i>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-6">
                <h5 class="fw-bold">Contact Information</h5>
                <ul class="list-unstyled mt-4">
                    <li class="mb-3">
                        <i class="fa-solid fa-location-dot text-primary me-2"></i>
                        <strong>Address:</strong> 122 Gui, Gaza - Palestine
                    </li>
                    <li class="mb-3">
                        <i class="fa-solid fa-envelope text-primary me-2"></i>
                        <strong>Email:</strong> 
                        <a href="mailto:info@gazacoupon.com">info@gazacoupon.com</a>
                    </li>
                    <li class="mb-3">
                        <i class="fa-solid fa-phone text-primary me-2"></i>
                        <strong>Phone:</strong> 
                        <a href="tel:+970595700555">+970 59 5700 555</a>
                    </li>
                    <li class="mb-3">
                        <i class="fa-solid fa-clock text-primary me-2"></i>
                        <strong>Business Hours:</strong> Sunday - Thursday, 9:00 AM - 6:00 PM
                    </li>
                </ul>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fa-solid fa-info-circle text-info me-2"></i>
                            Response Time
                        </h6>
                        <p class="card-text mb-0">
                            We typically respond to inquiries within 24-48 hours during business days.
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d217929.25881461377!2d34.22353897372741!3d31.41013997833182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd844104b258a9%3A0xfddcb14b194be8e7!2sGaza%20Strip!5e0!3m2!1sen!2s!4v1751187039019!5m2!1sen!2s"
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </main>
@endsection

<script>
// Character counter for message textarea
document.getElementById('message').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('char-count').textContent = charCount;
    
    // Change color when approaching limit
    const charCountElement = document.getElementById('char-count');
    if (charCount > 4500) {
        charCountElement.style.color = '#dc3545';
    } else if (charCount > 4000) {
        charCountElement.style.color = '#ffc107';
    } else {
        charCountElement.style.color = '#6c757d';
    }
});

// Form submission handling with CSRF token refresh
document.getElementById('contact-form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Sending...';
    
    // Re-enable button after 10 seconds if there's an error
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 10000);
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Refresh CSRF token every 30 minutes to prevent expiration
    setInterval(function() {
        fetch('/csrf-token')
            .then(response => response.json())
            .then(data => {
                document.querySelector('input[name="_token"]').value = data.token;
            })
            .catch(error => {
                console.log('CSRF token refresh failed:', error);
            });
    }, 30 * 60 * 1000); // 30 minutes
});

// Handle 419 errors by refreshing the page
if (window.location.href.includes('419') || document.title.includes('419')) {
    setTimeout(() => {
        window.location.reload();
    }, 2000);
}
</script>
