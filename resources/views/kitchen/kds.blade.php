@extends('layouts.app')

@section('title', 'Kitchen Display System')

@section('page-title', 'Kitchen Display System')

@section('breadcrumb', 'Kitchen / KDS')

@section('content')
<div class="row" id="kds-container">
    <!-- KDS Orders Grid -->
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Kitchen Orders</h3>
            <div class="btn-group">
                <button class="btn btn-primary active" onclick="filterOrders('all')">All</button>
                <button class="btn btn-outline-primary" onclick="filterOrders('pending')">Pending</button>
                <button class="btn btn-outline-primary" onclick="filterOrders('preparing')">Preparing</button>
                <button class="btn btn-outline-primary" onclick="filterOrders('ready')">Ready</button>
            </div>
        </div>

        <div class="row" id="kds-orders">
            <!-- Order Card 1 -->
            <div class="col-md-4 mb-3" data-status="pending">
                <div class="card kds-order-card border-warning">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">ORD-001</h5>
                            <small>Table 5 • Dine-in</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-danger">15 mins ago</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-items">
                            <div class="order-item mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Margherita Pizza x2</strong>
                                    <span class="badge badge-warning">Pending</span>
                                </div>
                                <small class="text-muted">Extra cheese</small>
                            </div>
                            <div class="order-item mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Chicken Momo x1</strong>
                                    <span class="badge badge-warning">Pending</span>
                                </div>
                                <small class="text-muted">Spicy</small>
                            </div>
                            <div class="order-item">
                                <div class="d-flex justify-content-between">
                                    <strong>Coca Cola x2</strong>
                                    <span class="badge badge-warning">Pending</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-actions mt-3">
                            <button class="btn btn-success btn-sm w-100" onclick="updateOrderStatus('ORD-001', 'preparing')">
                                <i class="fas fa-fire"></i> Start Preparing
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        Waiter: John Doe • 15:30
                    </div>
                </div>
            </div>

            <!-- Order Card 2 -->
            <div class="col-md-4 mb-3" data-status="preparing">
                <div class="card kds-order-card border-info">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">ORD-002</h5>
                            <small>Table 3 • Takeaway</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-warning">25 mins ago</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-items">
                            <div class="order-item mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Chicken Burger x2</strong>
                                    <span class="badge badge-info">Preparing</span>
                                </div>
                            </div>
                            <div class="order-item mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Fries x2</strong>
                                    <span class="badge badge-info">Preparing</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-actions mt-3">
                            <button class="btn btn-success btn-sm w-100" onclick="updateOrderStatus('ORD-002', 'ready')">
                                <i class="fas fa-check"></i> Mark Ready
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        Waiter: Jane Smith • 15:20
                    </div>
                </div>
            </div>

            <!-- Order Card 3 -->
            <div class="col-md-4 mb-3" data-status="ready">
                <div class="card kds-order-card border-success">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">ORD-003</h5>
                            <small>Table 7 • Dine-in</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success">Ready</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-items">
                            <div class="order-item mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Veg Chowmein x1</strong>
                                    <span class="badge badge-success">Ready</span>
                                </div>
                            </div>
                            <div class="order-item">
                                <div class="d-flex justify-content-between">
                                    <strong>Spring Roll x4</strong>
                                    <span class="badge badge-success">Ready</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-actions mt-3">
                            <button class="btn btn-primary btn-sm w-100" onclick="updateOrderStatus('ORD-003', 'served')">
                                <i class="fas fa-utensils"></i> Mark Served
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        Waiter: Mike Johnson • 15:10
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.kds-order-card {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.kds-order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.kds-order-card.border-warning {
    border-left: 5px solid #ffc107;
}

.kds-order-card.border-info {
    border-left: 5px solid #17a2b8;
}

.kds-order-card.border-success {
    border-left: 5px solid #28a745;
}

.order-item {
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

.order-actions button {
    font-weight: 600;
}

@media (max-width: 768px) {
    .kds-order-card {
        margin-bottom: 15px;
    }
}
</style>

@push('scripts')
<script>
function filterOrders(status) {
    const orders = document.querySelectorAll('#kds-orders > div');
    
    orders.forEach(order => {
        if (status === 'all' || order.dataset.status === status) {
            order.style.display = 'block';
        } else {
            order.style.display = 'none';
        }
    });

    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active', 'btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    event.target.classList.remove('btn-outline-primary');
    event.target.classList.add('active', 'btn-primary');
}

function updateOrderStatus(orderId, status) {
    // Update order status via AJAX
    fetch(`/kitchen/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the KDS display
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error updating order status:', error);
    });
}

// Auto-refresh KDS every 30 seconds
setInterval(() => {
    // In production, this would fetch new orders via AJAX
    console.log('Refreshing KDS...');
}, 30000);
</script>
@endpush
@endsection
