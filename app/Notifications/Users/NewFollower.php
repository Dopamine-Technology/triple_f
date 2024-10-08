<?php

namespace App\Notifications\Users;

use App\Services\PusherNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Pusher\Pusher;

//use Pusher\Pusher;


class NewFollower extends Notification implements ShouldQueue
{
    use Queueable;

    private $follower;
    private $notificationType = 'new_follower';
    public $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        new PusherNotifications(['notifiable_id' => $notifiable->id, 'unread_notification_count' => $notifiable->unreadNotifications()->count() + 1]);
        return [
            "sender" => [
                "id" => $this->sender->id,
                "name" => $this->sender->name,
                "image" => asset('storage/' . $this->sender->image),
            ],
            "notification_type" => $this->notificationType,
            "redirection" => $this->sender->id,
            "notification_data" => ""
        ];

    }


}
