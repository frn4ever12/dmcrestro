<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nepal Restaurant Management')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
            overflow-y: auto;
            max-height: 100vh;
        }
        .sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        .sidebar .brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            padding: 0 20px 20px;
            display: block;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link i {
            width: 25px;
        }
        .sidebar .dropdown-toggle .fa-chevron-down {
            transition: transform 0.3s;
            font-size: 12px;
        }
        .sidebar .dropdown-toggle[aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }
        .sidebar .collapse {
            display: none;
        }
        .sidebar .collapse.show {
            display: block;
        }
        .sidebar .nav-link.ms-3 {
            padding-left: 40px;
            font-size: 14px;
        }
        .main-content {
            flex: 1;
            margin-left: 250px;
            background: #f8f9fa;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <i class="fas fa-utensils me-2"></i>Nepal SaaS
        </a>
        <nav class="nav flex-column">
            <!-- Dashboard -->
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <!-- Restaurant Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#restaurantMenu" aria-expanded="false">
                    <i class="fas fa-store"></i> Restaurant Management <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->is('admin/restaurants*') || request()->is('admin/tenants*') ? 'show' : '' }}" id="restaurantMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('admin/tenants*') ? 'active' : '' }}" href="{{ route('admin.tenants.index') }}">All Restaurants</a>
                        <a class="nav-link {{ request()->is('admin/tenants/create') ? 'active' : '' }}" href="{{ route('admin.tenants.create') }}">Add Restaurant</a>
                        <a class="nav-link" href="#">Pending Approval</a>
                        <a class="nav-link" href="#">Branch Management</a>
                    </nav>
                </div>
            </div>

            <!-- Subscription Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#subscriptionMenu" aria-expanded="false">
                    <i class="fas fa-tags"></i> Subscription Management <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->is('admin/subscription*') ? 'show' : '' }}" id="subscriptionMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('admin/subscription-plans*') ? 'active' : '' }}" href="{{ route('admin.subscription-plans.index') }}">Subscription Plans</a>
                        <a class="nav-link" href="#">Active Subscriptions</a>
                        <a class="nav-link {{ request()->is('admin/trial-accounts*') ? 'active' : '' }}" href="{{ route('admin.trial-accounts.index') }}">Trial Accounts</a>
                        <a class="nav-link" href="#">Billing History</a>
                    </nav>
                </div>
            </div>

            <!-- Payment Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#paymentMenu" aria-expanded="false">
                    <i class="fas fa-credit-card"></i> Payment Management <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->is('admin/payment-gateways*') ? 'show' : '' }}" id="paymentMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('admin/payment-gateways*') ? 'active' : '' }}" href="{{ route('admin.payment-gateways.index') }}">Payment Gateway Settings</a>
                        <a class="nav-link" href="#">Transactions</a>
                        <a class="nav-link" href="#">Refund Requests</a>
                    </nav>
                </div>
            </div>

            <!-- Package & Feature Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#packageMenu" aria-expanded="false">
                    <i class="fas fa-box"></i> Package & Features <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="packageMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Packages</a>
                        <a class="nav-link" href="#">Feature Control</a>
                        <a class="nav-link" href="#">Storage Limit</a>
                        <a class="nav-link" href="#">API Access</a>
                    </nav>
                </div>
            </div>

            <!-- Sales & Revenue -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#revenueMenu" aria-expanded="false">
                    <i class="fas fa-chart-line"></i> Sales & Revenue <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="revenueMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">SaaS Revenue</a>
                        <a class="nav-link" href="#">Monthly Income</a>
                        <a class="nav-link" href="#">Commission Report</a>
                        <a class="nav-link" href="#">Profit Report</a>
                    </nav>
                </div>
            </div>

            <!-- Coupon & Promotion -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#couponMenu" aria-expanded="false">
                    <i class="fas fa-ticket-alt"></i> Coupons & Promotions <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="couponMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Coupon Management</a>
                        <a class="nav-link" href="#">Promo Codes</a>
                        <a class="nav-link" href="#">Referral Program</a>
                    </nav>
                </div>
            </div>

            <!-- Mobile App Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-expanded="false">
                    <i class="fas fa-mobile-alt"></i> Mobile App <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="mobileMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">App Version</a>
                        <a class="nav-link" href="#">Push Notifications</a>
                        <a class="nav-link" href="#">App Settings</a>
                    </nav>
                </div>
            </div>

            <!-- Notification Center -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#notificationMenu" aria-expanded="false">
                    <i class="fas fa-bell"></i> Notification Center <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="notificationMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">SMS</a>
                        <a class="nav-link" href="#">Email</a>
                        <a class="nav-link" href="#">Push Notification</a>
                        <a class="nav-link" href="#">Templates</a>
                    </nav>
                </div>
            </div>

            <!-- Support Center -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#supportMenu" aria-expanded="false">
                    <i class="fas fa-headset"></i> Support Center <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="supportMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Support Tickets</a>
                        <a class="nav-link" href="#">Live Chat</a>
                        <a class="nav-link" href="#">Feedback</a>
                        <a class="nav-link" href="#">FAQ</a>
                    </nav>
                </div>
            </div>

            <!-- Reports -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#reportMenu" aria-expanded="false">
                    <i class="fas fa-chart-bar"></i> Reports <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="reportMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Restaurant Report</a>
                        <a class="nav-link" href="#">Subscription Report</a>
                        <a class="nav-link" href="#">Revenue Report</a>
                        <a class="nav-link" href="#">Payment Report</a>
                        <a class="nav-link" href="#">Activity Report</a>
                    </nav>
                </div>
            </div>

            <!-- CMS -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#cmsMenu" aria-expanded="false">
                    <i class="fas fa-file-alt"></i> CMS <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="cmsMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Homepage</a>
                        <a class="nav-link" href="#">About</a>
                        <a class="nav-link" href="#">Pricing</a>
                        <a class="nav-link" href="#">Blog</a>
                        <a class="nav-link" href="#">FAQ</a>
                    </nav>
                </div>
            </div>

            <!-- Location Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#locationMenu" aria-expanded="false">
                    <i class="fas fa-map-marker-alt"></i> Location Management <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="locationMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Countries</a>
                        <a class="nav-link" href="#">Provinces</a>
                        <a class="nav-link" href="#">Districts</a>
                        <a class="nav-link" href="#">Cities</a>
                    </nav>
                </div>
            </div>

            <!-- Language Management -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#languageMenu" aria-expanded="false">
                    <i class="fas fa-language"></i> Language Management <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="languageMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Nepali</a>
                        <a class="nav-link" href="#">English</a>
                        <a class="nav-link" href="#">Translation Manager</a>
                    </nav>
                </div>
            </div>

            <!-- Tax & Fiscal Settings -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#taxMenu" aria-expanded="false">
                    <i class="fas fa-receipt"></i> Tax & Fiscal Settings <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="taxMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">VAT Settings</a>
                        <a class="nav-link" href="#">PAN Settings</a>
                        <a class="nav-link" href="#">Fiscal Year</a>
                        <a class="nav-link" href="#">Currency</a>
                    </nav>
                </div>
            </div>

            <!-- Access Control -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#accessMenu" aria-expanded="false">
                    <i class="fas fa-shield-alt"></i> Access Control <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="accessMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Roles</a>
                        <a class="nav-link" href="#">Permissions</a>
                        <a class="nav-link" href="#">Admin Users</a>
                        <a class="nav-link" href="#">Audit Log</a>
                    </nav>
                </div>
            </div>

            <!-- System Settings -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#settingsMenu" aria-expanded="false">
                    <i class="fas fa-cog"></i> System Settings <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="settingsMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">General Settings</a>
                        <a class="nav-link" href="#">Company Information</a>
                        <a class="nav-link" href="#">Email Settings</a>
                        <a class="nav-link" href="#">SMS Gateway</a>
                        <a class="nav-link" href="#">Backup & Restore</a>
                    </nav>
                </div>
            </div>

            <!-- Database Tools -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#dbMenu" aria-expanded="false">
                    <i class="fas fa-database"></i> Database Tools <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="dbMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">Backup</a>
                        <a class="nav-link" href="#">Restore</a>
                        <a class="nav-link" href="#">Optimize Database</a>
                        <a class="nav-link" href="#">Storage Manager</a>
                    </nav>
                </div>
            </div>

            <!-- Logs -->
            <div class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#logMenu" aria-expanded="false">
                    <i class="fas fa-file-code"></i> Logs <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="logMenu">
                    <nav class="nav flex-column ms-3">
                        <a class="nav-link" href="#">System Logs</a>
                        <a class="nav-link" href="#">Error Logs</a>
                        <a class="nav-link" href="#">Activity Logs</a>
                        <a class="nav-link" href="#">API Logs</a>
                    </nav>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom px-4">
            <div class="container-fluid">
                <button class="btn btn-light d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto d-flex">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
                </ol>
            </nav>
            
            <h1 class="h3 mb-4">@yield('page-title', 'Dashboard')</h1>
            
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
    
    <script>
        // CSRF token for AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.csrfToken = token.getAttribute('content');
            }
        });
    </script>
</body>
</html>
