<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Notification as MongoNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowBookNotificaiton extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $borrow)
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
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Book Borrow Notification')
            ->view('emails.borrow-book', ['book' => $this->borrow]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'type' => 'borrow_book',
            'data' => [
                'name' => $notifiable->name,
                'title' => $this->borrow['title'],
                'duedate' => $this->borrow['duedate']
            ],
        ];
    }

    /**
     * Customize database channel to use MongoNotification model.
     */
    public function toDatabase($notifiable)
    {
            return MongoNotification::create($this->toArray($notifiable));
    }
}
