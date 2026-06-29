@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-tabs mb-4">
    <ul class="nav nav-pills nav-fill" id="authTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="email-tab" data-bs-toggle="pill" data-bs-target="#email" type="button" role="tab">
                <i class="bi bi-envelope me-2"></i>Email
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#mobile" type="button" role="tab">
                <i class="bi bi-phone me-2"></i>Mobile
            </button>
        </li>
    </ul>
</div>

<div class="tab-content" id="authTabsContent">
    <!-- Email Login -->
    <div class="tab-pane fade show active" id="email" role="tabpanel">
        <form method="POST" action="/login">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @if ($errors->has('password')) is-invalid @endif" id="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="human-check" required>
                    <label class="form-check-label" for="human-check">
                        I am human
                    </label>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </div>

            <div class="text-center mb-3 position-relative">
                <hr class="my-3">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or continue with</span>
            </div>

            <div class="d-grid">
                <button type="button" class="btn btn-outline-secondary">
                    <i class="fab fa-google me-2"></i>Google
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile Login -->
    <div class="tab-pane fade" id="mobile" role="tabpanel">
        <form method="POST" action="/login/mobile">
            @csrf
            
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <div class="input-group">
                    <span class="input-group-text">+977</span>
                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="98XXXXXXXX" required pattern="[0-9]{10}">
                </div>
            </div>

            <div class="mb-3" id="otp-section" style="display: none;">
                <label for="otp" class="form-label">OTP Code</label>
                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" pattern="[0-9]{6}">
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="human-check-mobile" required>
                    <label class="form-check-label" for="human-check-mobile">
                        I am human
                    </label>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary" id="send-otp-btn">
                    <i class="bi bi-send me-2"></i>Send OTP
                </button>
            </div>

            <div class="text-center mb-3 position-relative">
                <hr class="my-3">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or continue with</span>
            </div>

            <div class="d-grid">
                <button type="button" class="btn btn-outline-secondary">
                    <i class="fab fa-google me-2"></i>Google
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center mt-3">
    <p class="mb-0">Don't have an account? <a href="/register" class="text-primary">Register</a></p>
</div>

@if (session('error'))
    <div class="alert alert-danger mt-3" role="alert">
        {{ session('error') }}
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sendOtpBtn = document.getElementById('send-otp-btn');
        const otpSection = document.getElementById('otp-section');
        let otpSent = false;

        sendOtpBtn.addEventListener('click', function(e) {
            if (!otpSent) {
                e.preventDefault();
                // Simulate sending OTP
                sendOtpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
                setTimeout(function() {
                    otpSection.style.display = 'block';
                    sendOtpBtn.innerHTML = 'Verify OTP';
                    otpSent = true;
                }, 1500);
            }
        });
    });
</script>
@endpush
@endsection
