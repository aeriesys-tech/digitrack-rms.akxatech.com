<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\PHPIMAP\ClientManager;
use Exception;

class UnseenMails extends Command
{
    protected $signature = 'app:unseen-mails';
    protected $description = 'Get unseen emails from the inbox';

    public function handle()
    {
        try 
		{
            $cm = new ClientManager();
            $client = $cm->make([
                'host'          => config('app.host'),
                'port'          => config('app.port'),
                'encryption'    => config('app.encryption'),
                'validate_cert' => true,
                'username'      => config('app.username'),
                'password'      => config('app.password'),
                'protocol'      => config('app.protocol'),
            ]);

            $client->connect();
            $this->info('Connected to mail server...');

            $folder = $client->getFolder('INBOX');
            $this->info('Fetching unseen emails...');

            $unseen_messages = $folder->messages()->unseen()->all()->get();
            $this->info('Unseen messages: ' . $unseen_messages->count());

            foreach ($unseen_messages as $message) {
                $this->info("\n----------------------");
                $this->info('Subject: ' . $message->getSubject());
                $this->info('From: ' . $message->getFrom()[0]->mail);
                $this->info('Body: ' . substr($message->getTextBody(), 0, 500)); // Truncate long emails
                
                // Handling attachments
                if ($message->hasAttachments()) {
                    $this->info('Attachments found!');
                    foreach ($message->getAttachments() as $attachment) {
                        $filename = storage_path('app/email_attachments/' . $attachment->name);
                        $attachment->save(storage_path('app/email_attachments/'));
                        $this->info('Saved attachment: ' . $filename);
                    }
                }
            }

            $client->expunge();
            $client->disconnect();
            $this->info('Finished processing emails.');
        } 
		catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
