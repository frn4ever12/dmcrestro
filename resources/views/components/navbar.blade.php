<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#" id="notificationDropdown">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge notification-count">{{ auth()->user()->notifications()->unread()->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown">
                <span class="dropdown-item dropdown-header">
                    {{ auth()->user()->notifications()->count() }} Notifications
                    <span class="float-right">
                        <a href="#" class="mark-all-read text-muted" style="font-size: 12px;">Mark all as read</a>
                    </span>
                </span>
                <div class="dropdown-divider"></div>
                <div class="notification-list">
                    @php
                        $notifications = auth()->user()->notifications()->latest()->limit(10)->get();
                    @endphp
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <a href="{{ $notification->link ?: '#' }}" class="dropdown-item {{ $notification->is_read ? 'read' : 'unread' }}" data-notification-id="{{ $notification->id }}">
                                <div class="d-flex">
                                    <div class="notification-icon">
                                        <i class="{{ $notification->icon }}"></i>
                                    </div>
                                    <div class="notification-content flex-grow-1">
                                        <h6 class="notification-title">{{ $notification->title }}</h6>
                                        <p class="notification-message">{{ Str::limit($notification->message, 80) }}</p>
                                        <small class="text-muted notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if(!$notification->is_read)
                                        <div class="notification-indicator"></div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="dropdown-item text-center text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                            <p>No notifications</p>
                        </div>
                    @endif
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        
        <!-- User Account Menu -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <div class="user-avatar-navbar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'User' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User Image -->
                <li class="user-header bg-primary">
                    <div class="user-avatar-large">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <p>
                        {{ auth()->user()->name ?? 'User' }}
                        <small>{{ auth()->user()->email ?? '' }}</small>
                    </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="#" class="btn-link">Profile</a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="#" class="btn-link">Settings</a>
                        </div>
                    </div>
                </li>
                <!-- Menu Footer -->
                <li class="user-footer">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
