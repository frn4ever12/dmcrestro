@extends('layouts.admin')

@section('title', 'Edit Tenant')

@section('page-title', 'Edit Tenant')

@section('breadcrumb', 'Tenants / Edit')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Edit Tenant</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tenants.update', $tenant->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name">Restaurant Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $tenant->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="email">Restaurant Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $tenant->email) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $tenant->phone) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $tenant->city) }}">
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $tenant->address) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="pan_number">PAN Number</label>
                        <input type="text" class="form-control" id="pan_number" name="pan_number" value="{{ old('pan_number', $tenant->pan_number) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="vat_number">VAT Number</label>
                        <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ old('vat_number', $tenant->vat_number) }}">
                    </div>
                </div>
            </div>

            <hr>
            <h5>Subscription Plan *</h5>
            
            <div class="form-group mb-3">
                <label for="subscription_plan_id">Select Plan *</label>
                <select class="form-control" id="subscription_plan_id" name="subscription_plan_id" required>
                    <option value="">-- Select Plan --</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('subscription_plan_id', $tenant->subscription_plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - Rs. {{ number_format($plan->monthly_price) }}/month
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $tenant->status === 'active') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>

            <hr>
            <h5>Change Owner Password</h5>
            <p class="text-muted small">Leave blank to keep current password</p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Tenant
                </button>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
