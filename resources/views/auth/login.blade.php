@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 420px;">
            <div class="text-center mb-4">
                <i class="fas fa-ticket-alt fa-2x text-primary mb-2"></i>
                <h4 class="fw-bold">Welcome to Gaza Coupon<br>Management System</h4>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <!-- Submit -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>

                <!-- Extra -->
                <div class="text-center">
                    <a href="javascript:void(0)">Forgot Password?</a> |
                    <a href="{{ route('register') }}">Register as new</a>
                </div>
            </form>
        </div>
    </div>
@endsection
