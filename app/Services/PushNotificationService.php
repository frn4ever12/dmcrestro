<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PushNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'));
        
        $this->messaging = $firebase->createMessaging();
    }

    public function sendToUser($userId, $title, $body, $data = [])
    {
        // Get user's FCM token from database
        $user = \App\Models\User::find($userId);
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $user->fcm_token)
            ->withNotification($notification)
            ->withData($data);

        try {
            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            \Log::error('Push notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendToRole($role, $title, $body, $data = [])
    {
        // Get all users with specific role
        $users = \App\Models\User::where('user_type', $role)->get();
        
        foreach ($users as $user) {
            if ($user->fcm_token) {
                $this->sendToUser($user->id, $title, $body, $data);
            }
        }
    }

    public function sendToAll($title, $body, $data = [])
    {
        // Send to all users with FCM tokens
        $users = \App\Models\User::whereNotNull('fcm_token')->get();
        
        foreach ($users as $user) {
            $this->sendToUser($user->id, $title, $body, $data);
        }
    }

    public function sendOrderNotification($orderId, $role)
    {
        $messages = [
            'admin' => [
                'title' => 'New Order Received',
                'body' => "Order #{$orderId} has been placed"
            ],
            'kitchen' => [
                'title' => 'New Kitchen Order',
                'body' => "Order #{$orderId} is ready for preparation"
            ],
            'waiter' => [
                'title' => 'Order Update',
                'body' => "Order #{$orderId} status updated"
            ],
            'cashier' => [
                'title' => 'Payment Required',
                'body' => "Order #{$orderId} is ready for payment"
            ],
            'customer' => [
                'title' => 'Order Status',
                'body' => "Your order #{$orderId} status has been updated"
            ],
            'driver' => [
                'title' => 'New Delivery',
                'body' => "Order #{$orderId} is ready for pickup"
            ]
        ];

        if (isset($messages[$role])) {
            $this->sendToRole($role, $messages[$role]['title'], $messages[$role]['body'], [
                'order_id' => $orderId,
                'type' => 'order_update'
            ]);
        }
    }
}
