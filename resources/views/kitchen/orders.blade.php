@extends('layouts.app')

@section('title', 'Kitchen Orders')

@section('page-title', 'Kitchen Display')

@section('breadcrumb', 'Kitchen / Orders')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Order Stats</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending</span>
                        <span class="info-box-number">{{ $pendingOrders ?? 0 }}</span>
                    </div>
                </div>
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-fire"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Preparing</span>
                        <span class="info-box-number">{{ $preparingOrders ?? 0 }}</span>
                    </div>
                </div>
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ready</span>
                        <span class="info-box-number">{{ $readyOrders ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kitchen Orders</h3>
            </div>
            <div class="card-body p-0">
                <div class="row" style="max-height: 600px; overflow-y: auto;">
                    @foreach ($kitchenOrders ?? [] as $order)
                    <div class="col-md-4 mb-3">
                        <div class="card card-{{ $order->status === 'ready' ? 'success' : ($order->status === 'preparing' ? 'info' : 'warning') }}">
                            <div class="card-header">
                                <h5 class="card-title">Order #{{ $order->order_id }}</h5>
                                <span class="badge badge-{{ $order->status === 'ready' ? 'success' : ($order->status === 'preparing' ? 'info' : 'warning') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="card-body">
                                <p><strong>Table:</strong> {{ $order->order->table->name ?? 'N/A' }}</p>
                                <p><strong>Item:</strong> {{ $order->orderItem->menu_item->name ?? 'N/A' }}</p>
                                <p><strong>Quantity:</strong> {{ $order->orderItem->quantity }}</p>
                                <p><strong>Notes:</strong> {{ $order->notes ?? 'None' }}</p>
                                @if ($order->status === 'pending')
                                    <button onclick="startOrder({{ $order->id }})" class="btn btn-primary btn-sm">Start</button>
                                @elseif ($order->status === 'preparing')
                                    <button onclick="markReady({{ $order->id }})" class="btn btn-success btn-sm">Ready</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function startOrder(id) {
    if (confirm('Start preparing this order?')) {
        fetch('/kitchen/orders/' + id + '/start', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
}

function markReady(id) {
    if (confirm('Mark this order as ready?')) {
        fetch('/kitchen/orders/' + id + '/ready', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
}
</script>
@endpush
