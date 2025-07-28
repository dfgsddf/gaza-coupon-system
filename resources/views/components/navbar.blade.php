<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fa-solid fa-ticket-alt"></i>
            <span>نظام قسائم غزة</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="fa-solid fa-home"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact*') ? 'active' : '' }}">
                        <i class="fa-solid fa-envelope"></i>
                        <span>اتصل بنا</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/help') }}" class="nav-link {{ request()->is('help*') ? 'active' : '' }}">
                        <i class="fa-solid fa-question-circle"></i>
                        <span>المساعدة والدعم</span>
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle btn btn-outline-light rounded px-3" href="#" id="registerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>تسجيل جديد</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="registerDropdown">
                            <li><a class="dropdown-item" href="{{ route('beneficiary.register.form') }}">
                                <i class="fa-solid fa-user me-2 text-primary"></i>تسجيل كمستفيد
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">
                                <i class="fa-solid fa-users me-2 text-secondary"></i>جميع أنواع الحسابات
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle btn btn-outline-info rounded px-3" href="#" id="testAccountsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-flask"></i>
                            <span>حسابات تجريبية</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="testAccountsDropdown">
                            <li><a class="dropdown-item" href="{{ route('login.form') }}?email=admin@example.com&password=password">
                                <i class="fa-solid fa-user-shield me-2 text-primary"></i>مشرف
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('login.form') }}?email=beneficiary@example.com&password=password">
                                <i class="fa-solid fa-users me-2 text-success"></i>مستفيد
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('login.form') }}?email=store@example.com&password=password">
                                <i class="fa-solid fa-store me-2 text-info"></i>متجر
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('login.form') }}?email=charity@example.com&password=password">
                                <i class="fa-solid fa-building me-2 text-warning"></i>جمعية خيرية
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login.form') }}" class="nav-link btn btn-light text-primary rounded px-3">
                            <i class="fa-solid fa-sign-in-alt"></i>
                            <span>تسجيل الدخول</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar me-2">
                                <i class="fa-solid fa-user-circle fa-lg"></i>
                            </div>
                            <div class="user-info text-end">
                                <small class="d-block opacity-75">مرحباً</small>
                                <span class="fw-semibold">{{ Auth::user()->name }}</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-user-shield me-2 text-primary"></i>لوحة المشرف
                                </a></li>
                            @elseif(Auth::user()->role === 'charity')
                                <li><a class="dropdown-item" href="{{ route('charity.dashboard') }}">
                                    <i class="fa-solid fa-building me-2 text-success"></i>لوحة الجمعية الخيرية
                                </a></li>
                            @elseif(Auth::user()->role === 'store')
                                <li><a class="dropdown-item" href="{{ route('store.dashboard') }}">
                                    <i class="fa-solid fa-store me-2 text-info"></i>لوحة المتجر
                                </a></li>
                            @elseif(Auth::user()->role === 'beneficiary')
                                <li><a class="dropdown-item" href="{{ route('beneficiary.coupons.index') }}">
                                    <i class="fa-solid fa-ticket-alt me-2 text-warning"></i>كوبوناتي
                                </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar-nav .nav-link {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.navbar-nav .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
}

.navbar-nav .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0.5rem 0;
    margin-top: 0.5rem;
    min-width: 200px;
}

.dropdown-item {
    padding: 0.7rem 1.5rem;
    transition: all 0.3s ease;
    border-radius: 6px;
    margin: 0.1rem 0.5rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.btn.nav-link {
    margin-left: 0.5rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.btn.nav-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

.user-info {
    line-height: 1.2;
}

.user-info small {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* RTL Support */
.navbar-brand {
    direction: rtl;
}

.dropdown-item {
    direction: rtl;
    text-align: right;
}

.dropdown-item i {
    margin-left: 0.5rem;
    margin-right: 0;
}

.user-info {
    direction: rtl;
}

@media (max-width: 991px) {
    .navbar-nav {
        text-align: center;
        margin-top: 1rem;
    }
    
    .navbar-nav .nav-item {
        margin: 0.25rem 0;
    }
    
    .btn.nav-link {
        margin: 0.25rem 0;
        width: 100%;
    }
    
    .user-info {
        text-align: center;
    }
    
    .dropdown-menu {
        width: 100%;
        text-align: center;
    }
    
    .dropdown-item {
        text-align: center;
        transform: none;
    }
    
    .dropdown-item:hover {
        transform: none;
        background-color: #f8f9fa;
    }
}

@media (max-width: 576px) {
    .navbar-brand span {
        font-size: 0.9rem;
    }
    
    .user-info {
        display: none;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
    }
}
</style> 