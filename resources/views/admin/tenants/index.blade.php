@extends('layouts.admin')

@section('title', 'Tenants')

@section('page-title', 'Tenants Management')

@section('breadcrumb', 'Tenants')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tenants</h3>
        <div class="card-tools">
            <a href="{{ url('/admin/tenants/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Tenant
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Subscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants ?? [] as $tenant)
                <tr>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td>
                        <span class="badge badge-{{ $tenant->status === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </td>
                    <td>{{ $tenant->subscriptionPlan->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ url('/admin/tenants/' . $tenant->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ url('/admin/tenants/' . $tenant->id . '/edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.tenants.login-as', $tenant->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Login as Restaurant">
                                <i class="fas fa-sign-in-alt"></i>
                            </button>
                        </form>
                        @if ($tenant->status === 'active')
                            <form action="{{ route('admin.tenants.suspend', $tenant->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pause"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.tenants.activate', $tenant->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-play"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
