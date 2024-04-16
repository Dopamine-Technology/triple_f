<?php

namespace App\Notifications\Users;

use App\Services\PusherNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPost extends Notification implements ShouldQueue
{
    use Queueable;
    private $post;
    private $notificationType = 'new_post';
    /**
     * Create a new notification instance.
     */
    public function __construct($post)
    {
        $this->post = $post;

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
        new PusherNotifications(['notifiable_id' => $notifiable->id, 'unread_notification_count' => $notifiable->unreadNotifications()->count() + 1]);

        return [
            "sender" => [
                "id" => $this->post->user->id,
                "name" => $this->post->user->name,
                "image" => asset('storage/' .  $this->post->user->image),
            ],
            "notification_type" => $this->notificationType,
            "redirection" => $this->post->id,
            "notification_data" => ""
        ];
    }
}
