@extends('layouts.admin')

@section('title', 'Create Tenant')

@section('page-title', 'Create New Tenant')

@section('breadcrumb', 'Tenants / Create')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Tenant</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tenants.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Restaurant Name *</label>
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" id="name" name="name" value="{{ old('name') }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Restaurant Email *</label>
                        <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif" id="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pan_number">PAN Number</label>
                        <input type="text" class="form-control" id="pan_number" name="pan_number" value="{{ old('pan_number') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vat_number">VAT Number</label>
                        <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{ old('vat_number') }}">
                    </div>
                </div>
            </div>

            <hr>
            <h5>Subscription Plan *</h5>
            
            <div class="form-group">
                <label for="subscription_plan_id">Select Plan *</label>
                <select class="form-control @if ($errors->has('subscription_plan_id')) is-invalid @endif" id="subscription_plan_id" name="subscription_plan_id" required>
                    <option value="">-- Select Plan --</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} - Rs. {{ number_format($plan->monthly_price) }}/month
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('subscription_plan_id'))
                    <span class="invalid-feedback">{{ $errors->first('subscription_plan_id') }}</span>
                @endif
            </div>

            <hr>
            <h5>Owner Details</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="owner_name">Owner Name *</label>
                        <input type="text" class="form-control @if ($errors->has('owner_name')) is-invalid @endif" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                        @if ($errors->has('owner_name'))
                            <span class="invalid-feedback">{{ $errors->first('owner_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="owner_email">Owner Email *</label>
                        <input type="email" class="form-control @if ($errors->has('owner_email')) is-invalid @endif" id="owner_email" name="owner_email" value="{{ old('owner_email') }}" required>
                        @if ($errors->has('owner_email'))
                            <span class="invalid-feedback">{{ $errors->first('owner_email') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" class="form-control @if ($errors->has('password')) is-invalid @endif" id="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Tenant
                </button>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
