<?php

namespace App\Console\Commands;

use App\Services\SubscriptionNotificationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-subscription-expiry')]
#[Description('Check for expiring subscriptions and send notifications')]
class CheckSubscriptionExpiry extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking subscription expiry...');
        
        $service = new SubscriptionNotificationService();
        $service->checkAndNotifyExpiringSubscriptions();
        
        $this->info('Subscription check completed.');
        
        return Command::SUCCESS;
    }
}
