<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class MongoDBChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toMongoDB($notifiable);

        \App\Models\Notification::create($data);
    }
}
