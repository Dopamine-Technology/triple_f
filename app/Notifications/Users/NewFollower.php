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


class NewFollower extends Notification
{
    use Queueable;

    private $follower;
    private $notificationType = 'new_follower';
    public $user;


    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        new PusherNotifications(['notifiable_id' => $user->id, 'unread_notification_count' => $user->unreadNotifications()->count() + 1]);
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
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'notification_type' => $this->notificationType
        ];
    }


}
