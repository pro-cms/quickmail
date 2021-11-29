<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $messagedata;
    public $emailsubject;
    public $fromUser;

    public function __construct($message,$emailsubject,$fromUser)
    {
        $this->messagedata = $message;
        $this->emailsubject = $emailsubject;
        $this->fromUser = $fromUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this->from($this->fromUser)
        ->view('mails.mail')
        ->subject($this->emailsubject);

        return $this->view('view.name');
    }
}
