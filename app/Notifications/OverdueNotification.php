<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $data)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Overdue Book Notification')
            ->view('emails.overdue-notification', ['data' => $this->data]);
    }

    /**
     * Customize database channel to use MongoNotification model.
     */
    public function toMongoDB($notifiable)
    {
        return [
            'user_id' => $notifiable->id,
            'type' => 'overdue_book',
            'data' => [
                'name' => $notifiable->name,
                'title' => $this->data['title'],
                'penalty' => $this->data['penalty']
            ],
        ];
    }
}
