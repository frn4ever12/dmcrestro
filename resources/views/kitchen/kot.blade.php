@extends('layouts.app')

@section('title', 'Kitchen Order Ticket')

@section('page-title', 'Kitchen Order Ticket')

@section('breadcrumb', 'Kitchen / KOT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kitchen Order Ticket</h3>
                <div class="card-tools">
                    <button class="btn btn-primary" onclick="printKOT()">
                        <i class="fas fa-print"></i> Print KOT
                    </button>
                </div>
            </div>
            <div class="card-body" id="kot-content">
                <div class="kot-header">
                    <div class="row">
                        <div class="col-6">
                            <h4>{{ auth()->user()->restaurant->name ?? 'Restaurant Name' }}</h4>
                            <p class="mb-1">Order #: <strong>{{ $orderNumber ?? 'ORD-001' }}</strong></p>
                            <p class="mb-1">Table: <strong>{{ $tableNumber ?? 'Dine-in' }}</strong></p>
                            <p class="mb-1">Waiter: <strong>{{ $waiterName ?? auth()->user()->name }}</strong></p>
                        </div>
                        <div class="col-6 text-right">
                            <p class="mb-1">Date: <strong>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</strong></p>
                            <p class="mb-1">Time: <strong>{{ \Carbon\Carbon::now()->format('H:i') }}</strong></p>
                            <p class="mb-1">Type: <strong>{{ $orderType ?? 'Dine-in' }}</strong></p>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="kot-items">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Notes</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Margherita Pizza</td>
                                <td>2</td>
                                <td>Extra cheese</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Chicken Momo (10 pcs)</td>
                                <td>1</td>
                                <td>Spicy</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Coca Cola (500ml)</td>
                                <td>2</td>
                                <td></td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="kot-footer mt-3">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1">Total Items: <strong>3</strong></p>
                            <p class="mb-1">Total Qty: <strong>5</strong></p>
                        </div>
                        <div class="col-6 text-right">
                            <p class="mb-1">Preparation Time: <strong>~25 mins</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.kot-header {
    font-size: 14px;
}

.kot-items table {
    font-size: 13px;
}

.kot-footer {
    font-size: 13px;
}

@media print {
    .card-tools, .breadcrumb, .main-header, .main-footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
}
</style>

@push('scripts')
<script>
function printKOT() {
    window.print();
}
</script>
@endpush
@endsection
