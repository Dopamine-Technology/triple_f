<?php

namespace App\Notifications\Users;

use App\Services\PusherNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostReactionByCount extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private string $notificationType = 'post_reaction_by_count';
    public $post;
    public int $count = 10;

    public function __construct($post, $count)
    {
        $this->post = $post;
        $this->count = $count;
        new PusherNotifications(['notifiable_id' => $this->post->user->id, 'unread_notification_count' => $this->post->user->unreadNotifications()->count() + 1]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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
                "id" => 0,
                "name" => 0,
                "image" => '',
            ],
            "notification_type" => $this->notificationType,
            "redirection" => $this->post->id,
            "notification_text" => $this->count
        ];
    }
}
