<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckSubscriptionExpiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            // Find tenants expiring in next 7 days
            $expiringSoon = Tenant::where('subscription_ends_at', '>', now())
                ->where('subscription_ends_at', '<', now()->addDays(7))
                ->where('status', 'active')
                ->get();

            foreach ($expiringSoon as $tenant) {
                $daysRemaining = now()->diffInDays($tenant->subscription_ends_at);
                
                Log::info('Tenant subscription expiring soon', [
                    'tenant_id' => $tenant->id,
                    'name' => $tenant->name,
                    'days_remaining' => $daysRemaining,
                ]);

                // Send notification to owner
                $owner = $tenant->users()->wherePivot('role', 'owner')->first();
                if ($owner) {
                    // Send email notification
                    // Mail::to($owner->email)->send(new SubscriptionExpiringSoon($tenant, $daysRemaining));
                }
            }

            // Find expired tenants
            $expired = Tenant::where('subscription_ends_at', '<', now())
                ->where('status', 'active')
                ->get();

            foreach ($expired as $tenant) {
                $tenant->update([
                    'status' => 'expired',
                    'is_active' => false,
                ]);

                Log::info('Tenant subscription expired', [
                    'tenant_id' => $tenant->id,
                    'name' => $tenant->name,
                ]);

                // Send notification
                $owner = $tenant->users()->wherePivot('role', 'owner')->first();
                if ($owner) {
                    // Mail::to($owner->email)->send(new SubscriptionExpired($tenant));
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to check subscription expiry', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
