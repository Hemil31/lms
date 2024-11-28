<?php

namespace App\Console\Commands;

use App\Services\BorrowBookServices;
use Illuminate\Console\Command;

class DueDateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:due-date-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send  Due Date Notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending due date notifications...');
        $notificationService = app(BorrowBookServices::class);
        $notificationService->sendDueDateNotifications();
        $this->info('Due date notifications sent.');
    }
}
