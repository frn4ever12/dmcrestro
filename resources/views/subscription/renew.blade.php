@extends('layouts.app')

@section('title', 'Renew Subscription')

@section('page-title', 'Renew Subscription')

@section('breadcrumb', 'Renew Subscription')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Choose a Plan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($plans as $plan)
                    <div class="col-md-4">
                        <div class="card plan-card">
                            <div class="card-body text-center">
                                <h4 class="plan-name">{{ $plan->name }}</h4>
                                <div class="plan-price">
                                    <span class="price-amount">${{ number_format($plan->monthly_price, 2) }}</span>
                                    <span class="price-period">/month</span>
                                </div>
                                @if($plan->yearly_price)
                                <div class="plan-yearly-price">
                                    <span class="price-amount">${{ number_format($plan->yearly_price, 2) }}</span>
                                    <span class="price-period">/year</span>
                                </div>
                                @endif
                                
                                <ul class="plan-features">
                                    <li><i class="fas fa-check text-success"></i> {{ $plan->max_restaurants }} Restaurant{{ $plan->max_restaurants > 1 ? 's' : '' }}</li>
                                    <li><i class="fas fa-check text-success"></i> {{ $plan->max_branches }} Branch{{ $plan->max_branches > 1 ? 'es' : '' }}</li>
                                    <li><i class="fas fa-check text-success"></i> {{ $plan->max_users }} User{{ $plan->max_users > 1 ? 's' : '' }}</li>
                                    <li><i class="fas fa-check text-success"></i> {{ $plan->storage_limit_mb }} MB Storage</li>
                                    @if($plan->pos_module)
                                    <li><i class="fas fa-check text-success"></i> POS Module</li>
                                    @endif
                                    @if($plan->inventory_module)
                                    <li><i class="fas fa-check text-success"></i> Inventory Module</li>
                                    @endif
                                    @if($plan->accounting_module)
                                    <li><i class="fas fa-check text-success"></i> Accounting Module</li>
                                    @endif
                                    @if($plan->hrm_module)
                                    <li><i class="fas fa-check text-success"></i> HRM Module</li>
                                    @endif
                                    @if($plan->crm_module)
                                    <li><i class="fas fa-check text-success"></i> CRM Module</li>
                                    @endif
                                </ul>
                                
                                <button class="btn btn-primary btn-block select-plan-btn" data-plan-id="{{ $plan->id }}">
                                    Select Plan
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .plan-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .plan-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    }
    
    .plan-name {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
    }
    
    .plan-price {
        margin-bottom: 8px;
    }
    
    .plan-yearly-price {
        margin-bottom: 24px;
        color: #6b7280;
        font-size: 14px;
    }
    
    .price-amount {
        font-size: 32px;
        font-weight: 700;
        color: #667eea;
    }
    
    .price-period {
        font-size: 14px;
        color: #6b7280;
    }
    
    .plan-features {
        list-style: none;
        padding: 0;
        margin-bottom: 24px;
        text-align: left;
    }
    
    .plan-features li {
        padding: 8px 0;
        color: #6b7280;
    }
    
    .plan-features li i {
        margin-right: 8px;
    }
    
    .select-plan-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .select-plan-btn:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
    }
</style>

<script>
    document.querySelectorAll('.select-plan-btn').forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            // TODO: Implement plan selection logic
            alert('Plan ' + planId + ' selected. Payment integration coming soon.');
        });
    });
</script>
@endsection
