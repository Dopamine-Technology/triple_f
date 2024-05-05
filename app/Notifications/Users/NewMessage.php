<?php

namespace App\Notifications\Users;

use App\Services\PusherNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification
{
    use Queueable;

    private $notificationType = 'new_message';
    public $sender;

    /**
     * Create a new notification instance.
     */
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
//    public function toMail(object $notifiable): MailMessage
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        new PusherNotifications(['notifiable_id' => $notifiable->id, 'unread_notification_count' => $notifiable->unreadNotifications()->count() + 1, 'channel' => 'chat_channel', 'event' => 'new_message']);
        return [
            "sender" => [
                "id" => $this->sender->id,
                "name" => $this->sender->name,
                "image" => asset('storage/' . $this->sender->image),
            ],
            "notification_type" => $this->notificationType,
            "redirection" => '',
            "notification_data" => ""
        ];
    }
}
