
@extends('layouts.app')

@section('title', 'Inventory Items')

@section('page-title', 'Inventory Items')

@section('breadcrumb', 'Inventory / Items')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Inventory Items</h3>
        <div class="card-tools">
            <a href="{{ url('/inventory/items/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Item
            </a>
            <a href="{{ url('/inventory/items/low-stock') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i> Low Stock
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Stock</th>
                    <th>Cost Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items ?? [] as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                    <td>{{ $item->unit->name ?? 'N/A' }}</td>
                    <td>
                        <span class="{{ $item->current_stock <= $item->reorder_level ? 'text-danger font-weight-bold' : '' }}">
                            {{ $item->current_stock }}
                        </span>
                    </td>
                    <td>NPR {{ number_format($item->cost_price, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $item->is_active ? 'success' : 'danger' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url('/inventory/items/' . $item->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ url('/inventory/items/' . $item->id . '/edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
