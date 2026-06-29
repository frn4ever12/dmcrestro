@extends('layouts.admin')

@section('title', 'Edit Subscription Plan')

@section('page-title', 'Edit Subscription Plan')

@section('breadcrumb', 'Subscription Plans / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Edit Subscription Plan</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.subscription-plans.update', $plan->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name">Plan Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="slug">Slug *</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $plan->slug) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $plan->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="monthly_price">Monthly Price (Rs.) *</label>
                        <input type="number" class="form-control" id="monthly_price" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="yearly_price">Yearly Price (Rs.) *</label>
                        <input type="number" class="form-control" id="yearly_price" name="yearly_price" value="{{ old('yearly_price', $plan->yearly_price) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="trial_days">Trial Days *</label>
                        <input type="number" class="form-control" id="trial_days" name="trial_days" value="{{ old('trial_days', $plan->trial_days) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="max_restaurants">Max Restaurants *</label>
                        <input type="number" class="form-control" id="max_restaurants" name="max_restaurants" value="{{ old('max_restaurants', $plan->max_restaurants) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="max_branches">Max Branches *</label>
                        <input type="number" class="form-control" id="max_branches" name="max_branches" value="{{ old('max_branches', $plan->max_branches) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="max_users">Max Users *</label>
                        <input type="number" class="form-control" id="max_users" name="max_users" value="{{ old('max_users', $plan->max_users) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="storage_limit_mb">Storage Limit (MB) *</label>
                <input type="number" class="form-control" id="storage_limit_mb" name="storage_limit_mb" value="{{ old('storage_limit_mb', $plan->storage_limit_mb) }}" required>
            </div>

            <hr>
            <h5>Module Access (Restaurant Modules)</h5>
            <p class="text-muted small">Enable modules that this plan can access. These determine what the vendor/tenant can see in their dashboard.</p>
            
            <div class="row">
                @php
                    $modules = \App\Models\Module::orderBy('sort_order')->get();
                    $enabledModules = is_array($plan->enabled_modules) ? $plan->enabled_modules : json_decode($plan->enabled_modules ?? '[]', true);
                    // Convert to integers for proper comparison and re-index
                    $enabledModules = array_values(array_map('intval', $enabledModules));
                @endphp
                
                @foreach($modules as $module)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header py-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input module-checkbox" type="checkbox" id="module_{{ $module->id }}" name="modules[]" value="{{ $module->id }}" {{ in_array($module->id, $enabledModules) ? 'checked' : '' }} onchange="toggleModuleSubmenus({{ $module->id }})">
                                <label class="form-check-label fw-bold" for="module_{{ $module->id }}">
                                    <i class="{{ $module->icon }} me-2"></i>{{ $module->name }}
                                </label>
                            </div>
                        </div>
                        <div class="card-body py-2" id="submenus_{{ $module->id }}">
                            @if($module->menus->count() > 0)
                                @foreach($module->menus as $menu)
                                    <div class="mb-2 ps-3">
                                        <div class="form-check">
                                            <input class="form-check-input menu-checkbox" type="checkbox" id="menu_{{ $menu->id }}" name="menus[]" value="{{ $menu->id }}" data-module="{{ $module->id }}" {{ in_array($menu->id, $enabledModules) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="menu_{{ $menu->id }}">
                                                <i class="{{ $menu->icon }} me-1"></i>{{ $menu->name }}
                                            </label>
                                        </div>
                                        @if($menu->subMenus->count() > 0)
                                            <div class="ps-3 mt-1">
                                                @foreach($menu->subMenus as $subMenu)
                                                    <div class="form-check">
                                                        <input class="form-check-input submenu-checkbox" type="checkbox" id="submenu_{{ $subMenu->id }}" name="submenus[]" value="{{ $subMenu->id }}" data-menu="{{ $menu->id }}" data-module="{{ $module->id }}" {{ in_array($subMenu->id, $enabledModules) ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="submenu_{{ $subMenu->id }}">{{ $subMenu->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small mb-0">No menus configured</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <hr>

            <h5>Advanced Features</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="mobile_app_module" name="mobile_app_module" value="1" {{ old('mobile_app_module', $plan->mobile_app_module ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="mobile_app_module">Mobile App Module</label>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Plan
                </button>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModuleSubmenus(moduleId) {
    const moduleCheckbox = document.getElementById('module_' + moduleId);
    const menuCheckboxes = document.querySelectorAll('.menu-checkbox[data-module="' + moduleId + '"]');
    const submenuCheckboxes = document.querySelectorAll('.submenu-checkbox[data-module="' + moduleId + '"]');
    
    if (moduleCheckbox.checked) {
        // Check all menus and submenus when module is checked
        menuCheckboxes.forEach(checkbox => checkbox.checked = true);
        submenuCheckboxes.forEach(checkbox => checkbox.checked = true);
    } else {
        // Uncheck all menus and submenus when module is unchecked
        menuCheckboxes.forEach(checkbox => checkbox.checked = false);
        submenuCheckboxes.forEach(checkbox => checkbox.checked = false);
    }
}

// When menu checkbox changes, toggle its submenus
document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const menuId = this.dataset.menu;
        const submenuCheckboxes = document.querySelectorAll('.submenu-checkbox[data-menu="' + menuId + '"]');
        
        if (this.checked) {
            submenuCheckboxes.forEach(sub => sub.checked = true);
        } else {
            submenuCheckboxes.forEach(sub => sub.checked = false);
        }
    });
});

// Ensure empty arrays are submitted when no checkboxes are checked
document.querySelector('form').addEventListener('submit', function(e) {
    const form = this;
    
    // Check if any module, menu, or submenu checkboxes exist
    const hasModules = form.querySelectorAll('input[name="modules[]"]').length > 0;
    const hasMenus = form.querySelectorAll('input[name="menus[]"]').length > 0;
    const hasSubmenus = form.querySelectorAll('input[name="submenus[]"]').length > 0;
    
    // If no checkboxes are checked, add hidden fields with empty arrays
    if (!hasModules || !form.querySelector('input[name="modules[]"]:checked')) {
        const hiddenModules = document.createElement('input');
        hiddenModules.type = 'hidden';
        hiddenModules.name = 'modules[]';
        hiddenModules.value = '';
        form.appendChild(hiddenModules);
    }
    
    if (!hasMenus || !form.querySelector('input[name="menus[]"]:checked')) {
        const hiddenMenus = document.createElement('input');
        hiddenMenus.type = 'hidden';
        hiddenMenus.name = 'menus[]';
        hiddenMenus.value = '';
        form.appendChild(hiddenMenus);
    }
    
    if (!hasSubmenus || !form.querySelector('input[name="submenus[]"]:checked')) {
        const hiddenSubmenus = document.createElement('input');
        hiddenSubmenus.type = 'hidden';
        hiddenSubmenus.name = 'submenus[]';
        hiddenSubmenus.value = '';
        form.appendChild(hiddenSubmenus);
    }
});
</script>
@endsection
