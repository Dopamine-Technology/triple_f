<?php

namespace App\Notifications\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Pusher\Pusher;

class NewFollower extends Notification
{
    use Queueable;

    private $follower;

    /**
     * Create a new notification instance.
     */
    public function __construct($follower)
    {
        $this->$follower = $follower;
        $pusher = new Pusher(
            "323996d4cfab0016889a",
            "ea95ab6a646732d824d7",
            "1787669",
            array('cluster' => 'ap2')
        );
        $pusher->trigger('notification-channel', 'notification-event', array('message' => 'hello world'));

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
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
            'follower' => [
                'id' => $this->follower,
            ],


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
    public function broadcastType()
    {
        return 'new-notification';
    }
}
