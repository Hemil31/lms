<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DueDateNotification extends Notification implements ShouldQueue
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
            ->subject('Due Date Book Notification')
            ->view('emails.duedate-notification', ['data' => $this->data]);
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
                'title' => $this->data['bookTitle'],
                'dueDate' => $this->data['dueDate']
            ],
        ];
    }
}
