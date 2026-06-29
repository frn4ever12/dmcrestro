@extends('layouts.admin')

@section('title', 'Tenant Details')

@section('page-title', 'Tenant Details')

@section('breadcrumb', 'Tenants / Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">{{ $tenant->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Restaurant Information</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{ $tenant->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $tenant->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $tenant->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $tenant->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $tenant->city ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>PAN Number</th>
                        <td>{{ $tenant->pan_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>VAT Number</th>
                        <td>{{ $tenant->vat_number ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Subscription Information</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $tenant->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Plan</th>
                        <td>{{ $tenant->subscriptionPlan->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Trial Ends</th>
                        <td>{{ $tenant->trial_ends_at ? $tenant->trial_ends_at->format('Y-m-d') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Max Users</th>
                        <td>{{ $tenant->max_users }}</td>
                    </tr>
                    <tr>
                        <th>Max Branches</th>
                        <td>{{ $tenant->max_branches }}</td>
                    </tr>
                    <tr>
                        <th>Storage Limit</th>
                        <td>{{ $tenant->storage_limit_mb }} MB</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <h5>Actions</h5>
        @if ($tenant->status === 'active')
            <form action="{{ route('admin.tenants.suspend', $tenant->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-pause"></i> Suspend
                </button>
            </form>
        @else
            <form action="{{ route('admin.tenants.activate', $tenant->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-play"></i> Activate
                </button>
            </form>
        @endif

        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this tenant?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>
@endsection
