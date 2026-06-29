@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('page-title', 'Subscription Plans')

@section('breadcrumb', 'Subscription Plans')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Subscription Plans</h3>
        <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Plan
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Monthly Price</th>
                    <th>Yearly Price</th>
                    <th>Modules</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                <tr>
                    <td>
                        <strong>{{ $plan->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $plan->description }}</small>
                    </td>
                    <td>Rs. {{ number_format($plan->monthly_price) }}</td>
                    <td>Rs. {{ number_format($plan->yearly_price) }}</td>
                    <td>
                        <small>
                            @if($plan->pos_module) POS @endif
                            @if($plan->inventory_module) Inventory @endif
                            @if($plan->accounting_module) Accounting @endif
                            @if($plan->hrm_module) HRM @endif
                            @if($plan->kitchen_display_module) KDS @endif
                        </small>
                    </td>
                    <td>
                        <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.subscription-plans.destroy', $plan->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
