<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpcomingServiceMails extends Command
{
    protected $signature = 'app:sendUpcomingServiceMails';

    protected $description = 'Command description';

    public function handle()
    {
        $controller = app(\App\Http\Controllers\UserServiceController::class);
        $controller->sendUpcomingServiceMails(request());

    }
}
