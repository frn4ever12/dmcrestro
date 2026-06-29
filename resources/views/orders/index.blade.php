@extends('layouts.app')

@section('title', 'Orders')

@section('page-title', 'Orders')

@section('breadcrumb', 'Orders')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Orders</h3>
        <div class="card-tools">
            <button class="btn btn-primary" data-toggle="modal" data-target="#newOrderModal">
                <i class="fas fa-plus"></i> New Order
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Table</th>
                    <th>Type</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders ?? [] as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                    <td>{{ $order->table->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($order->order_type) }}</td>
                    <td>NPR {{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url('/orders/' . $order->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if ($order->status === 'pending')
                            <a href="{{ url('/orders/' . $order->id . '/complete') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
