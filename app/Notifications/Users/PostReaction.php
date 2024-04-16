<?php

namespace App\Notifications\Users;

use App\Services\PusherNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostReaction extends Notification implements ShouldQueue
{
    use Queueable;

    private string $notificationType = 'post_reaction';
    public $post;
    public $sender;
    public string $medal_type = '';

    /**
     * Create a new notification instance.
     */
    public function __construct($post, $medal_type, $sender)
    {
        $this->post = $post;
        $this->medal_type = $medal_type;
        $this->sender = $sender;
        new PusherNotifications(['notifiable_id' => $this->post->user->id, 'unread_notification_count' => $this->post->user->unreadNotifications()->count() + 1]);
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
//            ->line('The introduction to the notification.')
//            ->action('Notification Action', url('/'))
//            ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "sender" => [
                "id" => $this->sender->id,
                "name" => $this->sender->name,
                "image" => asset('storage/' . $this->sender->image),
            ],
            "notification_type" => $this->notificationType,
            "redirection" => $this->post->id,
            "notification_data" => $this->medal_type
        ];
    }
}
