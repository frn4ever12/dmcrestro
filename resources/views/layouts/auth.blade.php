<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login - Nepal Restaurant Management')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        .auth-logo {
            font-size: 42px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        .auth-title {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 4px;
            position: relative;
            z-index: 1;
        }
        .auth-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        .auth-body {
            padding: 32px;
        }
        .auth-tabs .nav-pills .nav-link {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 500;
            font-size: 14px;
            color: #6b7280;
            background: #f3f4f6;
            border: none;
            transition: all 0.3s ease;
        }
        .auth-tabs .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .form-label {
            font-weight: 500;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-outline-secondary {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 500;
            font-size: 14px;
            border: 2px solid #e5e7eb;
            color: #374151;
            transition: all 0.3s ease;
        }
        .btn-outline-secondary:hover {
            border-color: #667eea;
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        .form-check-input {
            border-radius: 6px;
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 2px solid #e5e7eb;
            border-right: none;
            background: #f9fafb;
            font-weight: 500;
            font-size: 13px;
        }
        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-utensils"></i>
            </div>
            <h3 class="auth-title">Nepal Restaurant</h3>
            <p class="auth-subtitle">Management System</p>
        </div>
        <div class="auth-body">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
</body>
</html>
