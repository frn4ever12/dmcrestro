@extends('layouts.admin')

@section('title', 'Payment Gateway Settings')

@section('page-title', 'Payment Gateway Settings')

@section('breadcrumb', 'Payment Management / Gateway Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Payment Gateway Settings</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Configure payment gateways for subscription payments and restaurant transactions.</p>
        
        <div class="row mt-4">
            @foreach ($gateways as $key => $gateway)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="{{ $gateway['icon'] }} fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">{{ $gateway['name'] }}</h5>
                        <p class="card-text small text-muted">{{ $gateway['description'] }}</p>
                        <div class="mb-3">
                            <span class="badge {{ $gateway['status'] ? 'bg-success' : 'bg-secondary' }}">
                                {{ $gateway['status'] ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <a href="{{ route('admin.payment-gateways.edit', $key) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-cog"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
