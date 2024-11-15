<?php

namespace App\Console\Commands;

use App\Services\BorrowBookServices;
use Illuminate\Console\Command;

class OverDueDateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:over-due-date-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Over Due Date Notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notificationService = app(BorrowBookServices::class);
        $notificationService->sendOverDueDateNotification();
    }
}
