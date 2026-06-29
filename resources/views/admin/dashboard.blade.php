@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('page-title', 'Super Admin Dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Super Admin Dashboard</h3>
                </div>
                <div class="card-body">
                    <p>Welcome to the Super Admin Dashboard. Manage tenants, subscriptions, billing, and platform reports.</p>
                    <p class="text-muted small">Modules shown to tenants are controlled via their subscription plan settings.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ \App\Models\Tenant::count() }}</h3>
                                    <p class="card-text">Total Tenants</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ \App\Models\Tenant::where('status', 'active')->count() }}</h3>
                                    <p class="card-text">Active Tenants</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ \App\Models\Tenant::where('status', 'trial')->count() }}</h3>
                                    <p class="card-text">Trial Tenants</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ \App\Models\SubscriptionPlan::count() }}</h3>
                                    <p class="card-text">Subscription Plans</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">Tenant Management</h5>
                                </div>
                                <div class="card-body">
                                    <p>Create and manage restaurant tenants. Assign subscription plans to control which modules they can access.</p>
                                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-users"></i> Manage Tenants
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0">Subscription Plans</h5>
                                </div>
                                <div class="card-body">
                                    <p>Configure subscription plans with module access (POS, Inventory, Accounting, HRM, etc.) and pricing.</p>
                                    <button class="btn btn-success btn-block">
                                        <i class="fas fa-tags"></i> Manage Plans
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title mb-0">Billing & Invoices</h5>
                                </div>
                                <div class="card-body">
                                    <p>View and manage tenant billing, invoices, and payments.</p>
                                    <button class="btn btn-warning btn-block">
                                        <i class="fas fa-file-invoice"></i> View Billing
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">Platform Reports</h5>
                                </div>
                                <div class="card-body">
                                    <p>View platform-wide analytics, revenue reports, and tenant performance metrics.</p>
                                    <button class="btn btn-info btn-block">
                                        <i class="fas fa-chart-bar"></i> View Reports
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
