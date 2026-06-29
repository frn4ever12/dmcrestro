@php
    $menuService = app(\App\Services\MenuService::class);
    $menuStructure = $menuService->getMenuStructure();
@endphp

@foreach ($menuStructure as $module)
    @foreach ($module['menus'] as $menu)
        @if (empty($menu['sub_menus']))
            {{-- Single menu item without submenus --}}
            <li class="nav-item">
                <a href="{{ $menu['route'] && Route::has($menu['route']) ? route($menu['route']) : '#' }}" class="nav-link {{ request()->is($menu['route']) ? 'active' : '' }}">
                    <i class="nav-icon {{ $menu['icon'] }}"></i>
                    <p>{{ $menu['name'] }}</p>
                </a>
            </li>
        @else
            {{-- Menu item with submenus --}}
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link {{ request()->is($menu['route']) ? 'active' : '' }}">
                    <i class="nav-icon {{ $menu['icon'] }}"></i>
                    <p>
                        {{ $menu['name'] }}
                        <i class="right fas fa-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach ($menu['sub_menus'] as $subMenu)
                        <li class="nav-item">
                            <a href="{{ $subMenu['route'] && Route::has($subMenu['route']) ? route($subMenu['route']) : '#' }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ $subMenu['name'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endforeach
@endforeach

@if (auth()->user()->user_type === 'super_admin')
    <li class="nav-item has-treeview">
        <a href="#" class="nav-link {{ request()->is('admin/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
                Admin
                <i class="right fas fa-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/tenants') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tenants</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/subscription-plans') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Subscription Plans</p>
                </a>
            </li>
        </ul>
    </li>
@endif
