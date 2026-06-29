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
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    
    <style>
        /* Custom Sidebar Styling */
        .sidebar-dark-primary {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%) !important;
        }
        
        .nav-sidebar > .nav-item > .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-sidebar > .nav-item > .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .nav-sidebar > .nav-item > .nav-link .nav-icon {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .nav-sidebar .nav-treeview > .nav-item > .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 8px 20px 8px 52px;
            margin: 2px 10px;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .nav-sidebar .nav-treeview > .nav-item > .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }
        
        .nav-sidebar .nav-treeview > .nav-item > .nav-link.active {
            background: rgba(102, 126, 234, 0.3);
            color: #fff;
        }
        
        .nav-sidebar .nav-treeview > .nav-item > .nav-link .nav-icon {
            font-size: 10px;
            margin-right: 8px;
            opacity: 0.7;
        }
        
        .brand-link {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .brand-text {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
        }
        
        .user-panel {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-panel .info a {
            color: #fff;
            font-weight: 600;
        }
        
        .user-panel .info small {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .nav-sidebar .nav-item .right {
            transition: transform 0.3s ease;
        }
        
        .nav-sidebar .nav-item.menu-open > .nav-link .right {
            transform: rotate(90deg);
        }
        
        /* Notification Dropdown Styles */
        .notification-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .notification-list .dropdown-item {
            padding: 12px 16px;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        
        .notification-list .dropdown-item:hover {
            background: #f8f9fa;
        }
        
        .notification-list .dropdown-item.unread {
            background: #f0f7ff;
            border-left-color: #667eea;
        }
        
        .notification-list .dropdown-item.read {
            opacity: 0.7;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .notification-content {
            min-width: 0;
        }
        
        .notification-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .notification-message {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        
        .notification-time {
            font-size: 11px;
        }
        
        .notification-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            margin-left: 8px;
            flex-shrink: 0;
        }
        
        .notification-count {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
        }
        
        /* User Avatar Styles */
        .user-avatar-navbar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            margin-right: 8px;
        }
        
        .user-avatar-large {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 36px;
            font-weight: 600;
            margin: 0 auto 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('components.navbar')
        
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="brand-link">
                <span class="brand-text font-weight-light">Nepal Restaurant</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ auth()->user()->name ?? 'User' }}</a>
                        <small class="text-muted">{{ auth()->user()->user_type ?? '' }}</small>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        @include('components.sidebar-menu')
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Nepal Restaurant Management</a>.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
    
    <script>
        // CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Notification interactions
        $(document).ready(function() {
            // Mark notification as read when clicked
            $('.notification-list .dropdown-item').on('click', function(e) {
                const notificationId = $(this).data('notification-id');
                if (notificationId) {
                    $.ajax({
                        url: '/notifications/' + notificationId + '/mark-read',
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Update UI
                            $(e.currentTarget).removeClass('unread').addClass('read');
                            $(e.currentTarget).find('.notification-indicator').remove();
                            
                            // Update count
                            const countEl = $('.notification-count');
                            const currentCount = parseInt(countEl.text());
                            if (currentCount > 0) {
                                countEl.text(currentCount - 1);
                            }
                        }
                    });
                }
            });
            
            // Mark all as read
            $('.mark-all-read').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/notifications/mark-all-read',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Update UI
                        $('.notification-list .dropdown-item').removeClass('unread').addClass('read');
                        $('.notification-indicator').remove();
                        $('.notification-count').text('0');
                    }
                });
            });
        });
    </script>
</body>
</html>
