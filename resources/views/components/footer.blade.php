<footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 text-center text-md-start mb-4 mb-md-0">
                <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                    <i class="fa-solid fa-ticket-alt me-2 text-warning"></i>
                    نظام قسائم غزة
                </h6>
                <p class="mb-0 text-muted">ربط المجتمعات من خلال المساعدة الرقمية</p>
                <div class="mt-3">
                    <small class="text-muted">© {{ date('Y') }} جميع الحقوق محفوظة</small>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 text-center mb-4 mb-md-0">
                <h6 class="fw-bold mb-3">روابط سريعة</h6>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('contact.show') }}" class="text-muted text-decoration-none hover-lift">
                        <i class="fa-solid fa-envelope me-1"></i>اتصل بنا
                    </a>
                    <a href="{{ route('help') }}" class="text-muted text-decoration-none hover-lift">
                        <i class="fa-solid fa-question-circle me-1"></i>المساعدة
                    </a>
                    <a href="{{ route('login.form') }}" class="text-muted text-decoration-none hover-lift">
                        <i class="fa-solid fa-sign-in-alt me-1"></i>تسجيل الدخول
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12 text-center text-lg-end">
                <h6 class="fw-bold mb-3">تابعنا</h6>
                <div class="d-flex justify-content-center justify-content-lg-end gap-3">
                    <a href="#" class="text-muted text-decoration-none social-link" title="فيسبوك">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="text-muted text-decoration-none social-link" title="تويتر">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-muted text-decoration-none social-link" title="إنستغرام">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="text-muted text-decoration-none social-link" title="لينكد إن">
                        <i class="fab fa-linkedin-in fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <hr class="my-4 border-secondary">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="text-muted">
                    تم تطوير هذا النظام لدعم المجتمعات المحتاجة في غزة
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <small class="text-muted">
                    <i class="fa-solid fa-heart text-danger me-1"></i>
                    صنع بحب للغزة
                </small>
            </div>
        </div>
    </div>
</footer>

<style>
.hover-lift {
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: inline-block;
}

.hover-lift:hover {
    color: var(--white) !important;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    text-decoration: none;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
}

.social-link:hover {
    background: var(--primary-color);
    color: var(--white) !important;
    transform: translateY(-3px);
    text-decoration: none;
}

@media (max-width: 768px) {
    footer {
        text-align: center;
    }
    
    .social-link {
        width: 35px;
        height: 35px;
    }
}
</style> 