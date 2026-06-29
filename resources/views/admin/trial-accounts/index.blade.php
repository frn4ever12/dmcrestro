@extends('layouts.admin')

@section('title', 'Trial Accounts')

@section('page-title', 'Trial Accounts')

@section('breadcrumb', 'Subscription Management / Trial Accounts')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Trial Accounts</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Restaurant Name</th>
                    <th>Email</th>
                    <th>Trial Ends</th>
                    <th>Days Remaining</th>
                    <th>Subscription Plan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trialTenants as $tenant)
                <tr>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td>{{ $tenant->trial_ends_at ? $tenant->trial_ends_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        @if($tenant->trial_ends_at)
                            {{ $tenant->trial_ends_at->diffInDays(now()) }} days
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $tenant->subscriptionPlan->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($trialTenants->hasPages())
            <div class="p-3">
                {{ $trialTenants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
