<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceReminderMarkdownMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $serviceDetails;

    public function __construct($userName, $serviceDetails)
    {
        $this->userName = $userName;
        $this->serviceDetails = $serviceDetails;
    }

    public function build()
    {
        return $this->subject('Service Reminder')
            ->markdown('emails.service_reminder')
            ->with([
                'userName' => $this->userName,
                'services' => $this->serviceDetails,
            ]);
    }
}
