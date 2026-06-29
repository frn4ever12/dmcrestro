@extends('layouts.app')

@section('title', 'Billing & Subscription')

@section('page-title', 'Billing & Subscription')

@section('breadcrumb', 'Billing & Subscription')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Subscription Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-crown"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Current Plan</span>
                                <span class="info-box-number">{{ $tenant->subscriptionPlan->name ?? 'No Plan' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-{{ $tenant->isExpired() ? 'danger' : 'success' }}">
                            <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Status</span>
                                <span class="info-box-number">
                                    @if($tenant->isExpired())
                                        Expired
                                    @elseif($tenant->status === 'trial')
                                        Trial
                                    @else
                                        Active
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Subscription Ends</span>
                                <span class="info-box-number">
                                    {{ $tenant->subscription_ends_at ? $tenant->subscription_ends_at->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Users Used</span>
                                <span class="info-box-number">
                                    {{ $tenant->users()->count() }} / {{ $tenant->max_users }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('subscription.renew') }}" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Renew Subscription
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
