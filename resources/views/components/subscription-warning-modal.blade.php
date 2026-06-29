@php
    $tenant = null;
    $isExpired = false;
    $daysUntilExpiry = null;
    $showWarning = false;
    
    try {
        if (auth()->check()) {
            $user = auth()->user();
            // Try to get tenant through relationship
            if (method_exists($user, 'tenant')) {
                $tenant = $user->tenant;
            }
            
            // If no tenant through relationship, try session
            if (!$tenant && session('tenant_id')) {
                $tenant = \App\Models\Tenant::find(session('tenant_id'));
            }
            
            if ($tenant) {
                $isExpired = $tenant->isExpired();
                
                if ($tenant->subscription_ends_at) {
                    $daysUntilExpiry = now()->diffInDays($tenant->subscription_ends_at, false);
                }
                
                $showWarning = $isExpired || ($daysUntilExpiry !== null && $daysUntilExpiry <= 7);
            }
        }
    } catch (\Exception $e) {
        // If there's any error, don't show the warning
        $showWarning = false;
    }
@endphp

@if ($showWarning)
<div class="modal fade" id="subscriptionWarningModal" tabindex="-1" aria-labelledby="subscriptionWarningModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content subscription-modal">
            <div class="modal-header border-0">
                <div class="modal-logo">
                    <span class="logo-text">Nepal Restaurant</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($isExpired)
                    <div class="subscription-badge expired">
                        <i class="fas fa-exclamation-triangle"></i>
                        Subscription expired
                    </div>
                @else
                    <div class="subscription-badge warning">
                        <i class="fas fa-clock"></i>
                        Subscription expires in {{ $daysUntilExpiry }} day{{ $daysUntilExpiry > 1 ? 's' : '' }}
                    </div>
                @endif
                
                <div class="subscription-message">
                    <h4 class="greeting">Hi {{ auth()->user()->name }},</h4>
                    
                    @if ($isExpired)
                        <p>Your subscription has expired. You can still access your account, but certain features may be limited. Renew anytime to continue using Nepal Restaurant without interruptions — or go to Settings > Billing & Subscription.</p>
                    @else
                        <p>Your subscription will expire in {{ $daysUntilExpiry }} day{{ $daysUntilExpiry > 1 ? 's' : '' }}. Renew now to continue using Nepal Restaurant without interruptions — or go to Settings > Billing & Subscription.</p>
                    @endif
                    
                    <p class="help-text">If you believe this was a mistake or need assistance, please contact us. We're happy to help!</p>
                </div>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-users"></i>
                        <span>Nepal Restaurant Team</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+977 9802853939</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="{{ route('settings.billing') }}" class="btn btn-outline-primary">
                    <i class="fas fa-cog"></i>
                    Billing & Subscription
                </a>
                <a href="{{ route('subscription.renew') }}" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                    Renew Plan
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking for modal...');
        
        var modalElement = document.getElementById('subscriptionWarningModal');
        console.log('Modal element found:', modalElement);
        
        if (modalElement) {
            try {
                var modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                console.log('Modal created, showing...');
                modal.show();
                
                // Don't show again in this session
                sessionStorage.setItem('subscriptionWarningShown', 'true');
            } catch (e) {
                console.error('Error showing modal:', e);
            }
        } else {
            console.error('Modal element not found');
        }
    });
</script>

<style>
    .subscription-modal {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .subscription-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 24px;
    }
    
    .modal-logo {
        display: flex;
        align-items: center;
    }
    
    .logo-text {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 1px;
    }
    
    .subscription-modal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    
    .subscription-modal .btn-close:hover {
        opacity: 1;
    }
    
    .subscription-modal .modal-body {
        padding: 32px;
    }
    
    .subscription-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 24px;
    }
    
    .subscription-badge.expired {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .subscription-badge.warning {
        background: #fef3c7;
        color: #d97706;
    }
    
    .subscription-message h4 {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
    }
    
    .subscription-message p {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    
    .subscription-message .help-text {
        font-size: 14px;
        color: #9ca3af;
        font-style: italic;
    }
    
    .contact-info {
        display: flex;
        gap: 24px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 14px;
    }
    
    .contact-item i {
        color: #667eea;
    }
    
    .subscription-modal .modal-footer {
        padding: 24px 32px;
        background: #f9fafb;
        gap: 12px;
    }
    
    .subscription-modal .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
    }
    
    .subscription-modal .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .subscription-modal .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
    }
    
    .subscription-modal .btn-outline-primary {
        border-color: #667eea;
        color: #667eea;
    }
    
    .subscription-modal .btn-outline-primary:hover {
        background: #667eea;
        color: #fff;
    }
</style>
@endif
