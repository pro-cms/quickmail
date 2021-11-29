<?php

namespace App\Jobs;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail_data;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $users = $this->mail_data['emails'];
        $message = $this->mail_data['body'];
        $fromUser = $this->mail_data['fromUser'];


        foreach ($users as $value) {
            // $input['email'] = $value;
            $subject = $this->mail_data['subject'];
            Mail::bcc($value)->send(new SendMail($message, $subject,$fromUser));


        }

    }
}
