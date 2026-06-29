@extends('layouts.admin')

@section('title', 'Create Subscription Plan')

@section('page-title', 'Create Subscription Plan')

@section('breadcrumb', 'Subscription Plans / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Create Subscription Plan</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.subscription-plans.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name">Plan Name *</label>
                        <input type="text" class="form-control @error('name', 'is-invalid')" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="slug">Slug *</label>
                        <input type="text" class="form-control @error('slug', 'is-invalid')" id="slug" name="slug" value="{{ old('slug') }}" required>
                        @error('slug')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="monthly_price">Monthly Price (Rs.) *</label>
                        <input type="number" class="form-control @error('monthly_price', 'is-invalid')" id="monthly_price" name="monthly_price" value="{{ old('monthly_price') }}" required>
                        @error('monthly_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="yearly_price">Yearly Price (Rs.) *</label>
                        <input type="number" class="form-control @error('yearly_price', 'is-invalid')" id="yearly_price" name="yearly_price" value="{{ old('yearly_price') }}" required>
                        @error('yearly_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="trial_days">Trial Days *</label>
                        <input type="number" class="form-control @error('trial_days', 'is-invalid')" id="trial_days" name="trial_days" value="{{ old('trial_days', 14) }}" required>
                        @error('trial_days')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="max_restaurants">Max Restaurants *</label>
                        <input type="number" class="form-control" id="max_restaurants" name="max_restaurants" value="{{ old('max_restaurants', 1) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="max_branches">Max Branches *</label>
                        <input type="number" class="form-control" id="max_branches" name="max_branches" value="{{ old('max_branches', 5) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="max_users">Max Users *</label>
                        <input type="number" class="form-control" id="max_users" name="max_users" value="{{ old('max_users', 10) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="storage_limit_mb">Storage Limit (MB) *</label>
                <input type="number" class="form-control" id="storage_limit_mb" name="storage_limit_mb" value="{{ old('storage_limit_mb', 1024) }}" required>
            </div>

            <hr>
            <h5>Module Access (Restaurant Modules)</h5>
            <p class="text-muted small">Enable modules that this plan can access. These determine what the vendor/tenant can see in their dashboard.</p>
            
            <div class="row">
                <div class="col-md-4">
                    <h6 class="mb-2">Core Modules</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="pos_module" name="pos_module" value="1" {{ old('pos_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="pos_module">POS Module</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="inventory_module" name="inventory_module" value="1" {{ old('inventory_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="inventory_module">Inventory Module</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="accounting_module" name="accounting_module" value="1" {{ old('accounting_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="accounting_module">Accounting Module</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="hrm_module" name="hrm_module" value="1" {{ old('hrm_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="hrm_module">HRM Module</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="crm_module" name="crm_module" value="1" {{ old('crm_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="crm_module">CRM Module</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-2">Operations</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="kitchen_display_module" name="kitchen_display_module" value="1" {{ old('kitchen_display_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="kitchen_display_module">Kitchen Display (KDS)</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="online_ordering_module" name="online_ordering_module" value="1" {{ old('online_ordering_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="online_ordering_module">Online Ordering</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="delivery_module" name="delivery_module" value="1" {{ old('delivery_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="delivery_module">Delivery Module</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="reservation_module" name="reservation_module" value="1" {{ old('reservation_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="reservation_module">Reservation Module</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="marketing_module" name="marketing_module" value="1" {{ old('marketing_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="marketing_module">Marketing Module</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-2">Advanced Features</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="api_access" name="api_access" value="1" {{ old('api_access') ? 'checked' : '' }}>
                        <label class="form-check-label" for="api_access">API Access</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="white_label" name="white_label" value="1" {{ old('white_label') ? 'checked' : '' }}>
                        <label class="form-check-label" for="white_label">White Label</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="custom_domain" name="custom_domain" value="1" {{ old('custom_domain') ? 'checked' : '' }}>
                        <label class="form-check-label" for="custom_domain">Custom Domain</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="priority_support" name="priority_support" value="1" {{ old('priority_support') ? 'checked' : '' }}>
                        <label class="form-check-label" for="priority_support">Priority Support</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="qr_menu_module" name="qr_menu_module" value="1" {{ old('qr_menu_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="qr_menu_module">QR Menu Module</label>
                    </div>
                </div>
            </div>

            <hr>

            <h5>Advanced Features</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="mobile_app_module" name="mobile_app_module" value="1" {{ old('mobile_app_module') ? 'checked' : '' }}>
                        <label class="form-check-label" for="mobile_app_module">Mobile App Module</label>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Plan
                </button>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
