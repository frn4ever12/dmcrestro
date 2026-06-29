<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Tenant;
use App\Models\User;

class SubscriptionNotificationService
{
    public function checkAndNotifyExpiringSubscriptions()
    {
        $tenants = Tenant::with('subscriptionPlan', 'users')->get();
        
        foreach ($tenants as $tenant) {
            if (!$tenant->subscription_ends_at) {
                continue;
            }
            
            $daysUntilExpiry = now()->diffInDays($tenant->subscription_ends_at, false);
            
            // Check if subscription is expired
            if ($tenant->isExpired()) {
                $this->notifyExpiredSubscription($tenant);
            }
            // Check if subscription expires within 7 days
            elseif ($daysUntilExpiry <= 7 && $daysUntilExpiry > 0) {
                $this->notifyExpiringSubscription($tenant, $daysUntilExpiry);
            }
        }
    }
    
    private function notifyExpiredSubscription(Tenant $tenant)
    {
        $owners = $tenant->users()->where('user_type', 'owner')->get();
        
        foreach ($owners as $owner) {
            // Check if notification already exists for today
            $existingNotification = Notification::where('user_id', $owner->id)
                ->where('type', 'subscription_expired')
                ->where('created_at', '>=', now()->startOfDay())
                ->first();
            
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $owner->id,
                    'tenant_id' => $tenant->id,
                    'type' => 'subscription_expired',
                    'title' => 'Subscription Expired',
                    'message' => 'Your subscription has expired. You can still access your account, but certain features may be limited. Renew anytime to continue using Nepal Restaurant without interruptions.',
                    'icon' => 'fas fa-exclamation-triangle',
                    'link' => route('subscription.renew'),
                    'data' => [
                        'tenant_id' => $tenant->id,
                        'subscription_ends_at' => $tenant->subscription_ends_at->format('Y-m-d'),
                    ]
                ]);
            }
        }
    }
    
    private function notifyExpiringSubscription(Tenant $tenant, $daysUntilExpiry)
    {
        $owners = $tenant->users()->where('user_type', 'owner')->get();
        
        foreach ($owners as $owner) {
            // Check if notification already exists for today
            $existingNotification = Notification::where('user_id', $owner->id)
                ->where('type', 'subscription_expiring')
                ->where('created_at', '>=', now()->startOfDay())
                ->first();
            
            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $owner->id,
                    'tenant_id' => $tenant->id,
                    'type' => 'subscription_expiring',
                    'title' => 'Subscription Expiring Soon',
                    'message' => "Your subscription will expire in {$daysUntilExpiry} day" . ($daysUntilExpiry > 1 ? 's' : '') . ". Renew now to continue using Nepal Restaurant without interruptions.",
                    'icon' => 'fas fa-clock',
                    'link' => route('subscription.renew'),
                    'data' => [
                        'tenant_id' => $tenant->id,
                        'subscription_ends_at' => $tenant->subscription_ends_at->format('Y-m-d'),
                        'days_until_expiry' => $daysUntilExpiry,
                    ]
                ]);
            }
        }
    }
    
    public function notifyUser(User $user, string $type, string $title, string $message, string $icon = 'fas fa-bell', $link = null, $data = [])
    {
        return Notification::create([
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'link' => $link,
            'data' => $data,
        ]);
    }
}
