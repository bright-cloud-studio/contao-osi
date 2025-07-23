<?php

namespace Acme;

use Terminal42\NotificationCenterBundle\NotificationCenter;

class SendFormEmail
{
    public function __construct(private NotificationCenter $notificationCenter) {}
    
    public function sendMessage(): void
    {
        $notificationId = 1;
        $tokens = [
            'firstname' => 'value1',
            'lastname' => 'value2',   
        ];
        
        $receipts = $this->notificationCenter->sendNotification($notificationId, $tokens);
    }
}
