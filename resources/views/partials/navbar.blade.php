<!-- resources/views/partials/navbar.blade.php -->
<nav class="navbar">
    <div class="logo">
        <a href="{{ url('/') }}">GAZA COUPON</a>
    </div>
    <ul class="nav-links">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('contact') }}">Contact Us</a></li>
        <li><a href="{{ url('/help') }}">Help & Support</a></li>
        <li><a href="{{ url('/store') }}">Store</a></li>
        <li><a href="{{ url('/beneficiary') }}">Beneficiary</a></li>
        <li><a href="{{ url('/charity') }}">Charity</a></li>
        <li><a href="{{ url('/admin') }}">Admin</a></li>
        @guest
        <li><a href="{{ route('login.form') }}" class="btn btn-primary ms-2">Login</a></li>
        @endguest

    </ul>
</nav>
