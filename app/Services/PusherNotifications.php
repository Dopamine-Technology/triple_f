<?php

namespace App\Services;

use Pusher\Pusher;

class PusherNotifications
{
    private string $channel = 'notification-channel';
    private string $event = 'new-notification-event';
    private $notification_data;

    public function __construct($data)
    {
        $this->notification_data = $data;
        if (isset($data['channel'])) {
            $this->channel = $data['channel'];
        }
        if (isset($data['event'])) {
            $this->event = $data['event'];
        }
        $this->sendPusherMessage();

    }

    public function sendPusherMessage(): void
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            array('cluster' => 'ap2')
        );
        $pusher->trigger($this->channel, $this->event, $this->notification_data);
    }


}
