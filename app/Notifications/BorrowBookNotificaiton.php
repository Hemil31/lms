<?php

namespace App\Notifications;

use App\Notifications\Channels\MongoDBChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        return ['mail', MongoDBChannel::class];
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
     * Customize database channel to use MongoNotification model.
     */
    public function toMongoDB($notifiable)
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
}
