@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-user-plus fa-2x"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-2">Register as Beneficiary</h2>
                        <p class="text-muted">Create your account to receive assistance</p>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('beneficiaries.store') }}" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-semibold">
                                    <i class="fa-solid fa-user me-2"></i>First Name
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name') }}" 
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-semibold">
                                    <i class="fa-solid fa-user me-2"></i>Last Name
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name') }}" 
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fa-solid fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fa-solid fa-phone me-2"></i>Phone Number
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">
                                <i class="fa-solid fa-map-marker-alt me-2"></i>Address
                            </label>
                            <textarea class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter your full address..." 
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Family Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="family_size" class="form-label fw-semibold">
                                    <i class="fa-solid fa-users me-2"></i>Family Size
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('family_size') is-invalid @enderror" 
                                       id="family_size" 
                                       name="family_size" 
                                       value="{{ old('family_size') }}" 
                                       min="1" 
                                       max="20" 
                                       required>
                                @error('family_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="income" class="form-label fw-semibold">
                                    <i class="fa-solid fa-money-bill me-2"></i>Monthly Income
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('income') is-invalid @enderror" 
                                       id="income" 
                                       name="income" 
                                       value="{{ old('income') }}" 
                                       min="0" 
                                       step="0.01" 
                                       required>
                                @error('income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mb-3">
                            <label for="emergency_contact" class="form-label fw-semibold">
                                <i class="fa-solid fa-phone-alt me-2"></i>Emergency Contact
                            </label>
                            <input type="tel" 
                                   class="form-control form-control-lg @error('emergency_contact') is-invalid @enderror" 
                                   id="emergency_contact" 
                                   name="emergency_contact" 
                                   value="{{ old('emergency_contact') }}" 
                                   placeholder="Emergency contact phone number" 
                                   required>
                            @error('emergency_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Documents Upload -->
                        <div class="mb-3">
                            <label for="documents" class="form-label fw-semibold">
                                <i class="fa-solid fa-file-upload me-2"></i>Supporting Documents
                            </label>
                            <input type="file" 
                                   class="form-control form-control-lg @error('documents') is-invalid @enderror" 
                                   id="documents" 
                                   name="documents[]" 
                                   multiple 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <div class="form-text">Upload ID, income proof, or other supporting documents (PDF, JPG, PNG, DOC)</div>
                            @error('documents')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4 form-check">
                            <input type="checkbox" 
                                   class="form-check-input @error('terms') is-invalid @enderror" 
                                   id="terms" 
                                   name="terms" 
                                   required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-primary">Terms and Conditions</a> and <a href="#" class="text-primary">Privacy Policy</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-user-plus me-2"></i>Register as Beneficiary
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <span class="text-muted">Already have an account?</span>
                            <a href="{{ route('login.form') }}" class="text-decoration-none text-primary fw-semibold ms-1">
                                <i class="fa-solid fa-sign-in-alt me-1"></i>Sign In
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card styling */
    .card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* Form control styling */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus {
        border-color: #2A61CC;
        box-shadow: 0 0 0 0.2rem rgba(42, 97, 204, 0.25);
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    /* File input styling */
    .form-control[type="file"] {
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control[type="file"]:focus {
        transform: none;
    }
    
    /* Button styling */
    .btn {
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2A61CC 0%, #1e4ba3 100%);
        border: none;
        box-shadow: 0 4px 15px rgba(42, 97, 204, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(42, 97, 204, 0.4);
        background: linear-gradient(135deg, #1e4ba3 0%, #2A61CC 100%);
    }
    
    /* Form check styling */
    .form-check-input:checked {
        background-color: #2A61CC;
        border-color: #2A61CC;
    }
    
    .form-check-input:focus {
        border-color: #2A61CC;
        box-shadow: 0 0 0 0.2rem rgba(42, 97, 204, 0.25);
    }
    
    /* Form text styling */
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem !important;
        }
        
        .form-control {
            font-size: 1rem;
        }
        
        .btn {
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .form-control {
            font-size: 0.9rem;
        }
        
        .btn {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
        }
        
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        
        .col-md-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
</style>

<script>
    // Bootstrap form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    
    // Auto-resize textarea
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('address');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }
        
        // Auto-focus on first name field
        document.getElementById('first_name').focus();
        
        // Phone number formatting
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.startsWith('970')) {
                        value = value.replace(/(\d{3})(\d{2})(\d{3})(\d{4})/, '+$1 $2 $3 $4');
                    } else if (value.startsWith('0')) {
                        value = value.replace(/(\d{1})(\d{2})(\d{3})(\d{4})/, '+970 $2 $3 $4');
                    } else {
                        value = value.replace(/(\d{2})(\d{3})(\d{4})/, '+970 $1 $2 $3');
                    }
                }
                e.target.value = value;
            });
        });
        
        // File upload preview
        const fileInput = document.getElementById('documents');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    console.log(`${files.length} file(s) selected`);
                }
            });
        }
    });
</script>
@endsection 